<?php
/**
 * Klein (klein.php) - A fast & flexible router for PHP
 *
 * @author      Chris O'Hara <cohara87@gmail.com>
 * @author      Trevor Suarez (Rican7) (contributor and v2 refactorer)
 * @copyright   (c) Chris O'Hara
 * @link        https://github.com/klein/klein.php
 * @license     MIT
 */

namespace Klein\DataCollection;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ServerDataCollection
 *
 * A DataCollection for "$_FILES" like data
 *
 * Look familiar?
 *
 * Inspired by @fabpot's Symfony 2's HttpFoundation
 * @link https://github.com/symfony/HttpFoundation/blob/master/ServerBag.php
 */
class FileDataCollection extends DataCollection
{
    public function getObj($name)
    {
        if (!$this->exists($name)) {
            return null;
        }

        $request_file = $this->get($name);

        if (is_array($request_file['tmp_name'])) {
            $objs = [];
            foreach ($request_file['tmp_name'] as $indx => $tmp_name) {
                $objs[$indx] = new UploadedFile(
                    $request_file['tmp_name'][$indx],
                    $request_file['name'][$indx],
                    $request_file['type'][$indx],
                    $request_file['size'][$indx],
                    $request_file['error'][$indx]
                );
            }

            return $objs;
        } else {
            return  new UploadedFile(
                $request_file['tmp_name'],
                $request_file['name'],
                $request_file['type'],
                $request_file['size'],
                $request_file['error']
            );
        }
    }
}
