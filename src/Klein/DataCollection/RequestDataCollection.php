<?php
/**
 * Klein (klein.php) - A fast & flexible router for PHP
 *
 * @author      Chris O'Hara <cohara87@gmail.com>
 * @author      Trevor Suarez (Rican7) (contributor and v2 refactorer)
 * @copyright   (c) Chris O'Hara
 * @link        https://github.com/chriso/klein.php
 * @license     MIT
 */

namespace Klein\DataCollection;

/**
 * RequestDataCollection
 *
 * A DataCollection for "$_GET,$_POST,$_REQUEST" like data
 *
 * Look familiar?
 *
 * Inspired by @fabpot's Symfony 2's HttpFoundation
 * @link https://github.com/symfony/HttpFoundation/blob/master/ServerBag.php
 */
class RequestDataCollection extends DataCollection
{
    protected $filters = array(); //Same format as filter_var_array

    public function setFilter(array $filters)
    {
        $this->filters = $filters;
    }

    public function all($mask = null, $fill_with_nulls = true)
    {
        $args = func_get_args();
        $requestData = call_user_func_array('parent::all', $args);

        if (!empty($this->filters)) {
            foreach ($requestData as $key => $value) {
                if (isset($this->filters[$key])) {
                    $requestData[$key] = $this->doFilter($value, $this->filters[$key]);
                }
            }
        }
        return $requestData;
    }

    public function get($key, $default_val = null)
    {
        $args = func_get_args();
        $requestData = call_user_func_array('parent::get', $args);

        if (!empty($this->filters) && isset($this->filters[$key])) {
            return $this->doFilter($requestData, $this->filters[$key]);
        } else {
            return $requestData;
        }
    }

    protected function doFilter($rawData, $filter)
    {
        if (is_array($filter)) {
            if (!key_exists('filter', $filter)) {
                throw new \Exception('Invalid Filter Param.');
            }
            $filterVal = $filter['filter'];
            unset($filter['filter']);
            $options = $filter;

            return filter_var($rawData, $filterVal, $options);
        } else {
            return filter_var($rawData, $filter);
        }
    }
}
