<?php

class HttpData
{
    /**
     * Clean data value
     *
     * @param mixed $value: value of data as key value
     * @return mixed
     */
    private function cleanDataValue($value)
    {
        return str_replace('javascript:', null, addslashes(strip_tags(trim($value))));
    }

    /**
     * Filter data function
     *
     * @param array $data: target data block
     * @param string $parameter: target parameter
     * @return mixed
     */
    private function cleanData($data, $parameter = null)
    {
        // all data contents
        if ($parameter == null && $data) {
            if (is_array($data)) {
                foreach ($data as $key => $param) {
                    if (is_array($param)) {
                        foreach ($param as $key => $value) {
                            $data[$key][$key] = $this->cleanDataValue($value);
                        }
                    } else {
                        $data[$key] = $this->cleanDataValue($param);
                    }
                }

                return $data;
            }
        } // single target
        else if (!empty($data[$parameter]) && array_key_exists($parameter, $data)) {
            // array
            if (is_array($data[$parameter])) {
                $result = array();

                foreach ($data[$parameter] as $param) {
                    $result[] = $this->cleanDataValue($param);
                }

                return $result;
            }
            
            // else return single item
            return $this->cleanDataValue($parameter);
        }
    }

    /**
     * Clean GET value
     *
     * @param string $value
     * @return string
     */
    private function cleanGetValue($value)
    {
        return htmlentities(strip_tags(trim($value)), ENT_QUOTES | ENT_XHTML, 'UTF-8');
    }

    /**
     * Get $_GET contents in filtered way
     *
     * @param object $parameter: target parameter
     * @return mixed
     */
    public function get($parameter = null)
    {
        if ($parameter == null) {
            if (is_array($_GET)) {
                foreach ($_GET as $key => $param) {
                    $_GET[$key] = $this->cleanGetValue($param);
                }

                return $_GET;
            }
        } else if (array_key_exists($parameter, $_GET)) {
            return $this->cleanGetValue($_GET[$parameter]);
        }
    }

    /**
     * Get $_POST contents in filtered way
     *
     * @param object $parameter: target parameter
     * @return mixed
     */
    public function post($parameter = null)
    {
        return $this->cleanData($_POST, $parameter);
    }

    /**
     * Get PUT contents in filtered way
     *
     * @param object $parameter: target parameter
     * @return mixed
     */
    public function put($parameter = null)
    {
        parse_str(file_get_contents('php://input'), $data);

        return $this->cleanData($data, $parameter);
    }

    /**
     * Get DELETE contents in filtered way
     *
     * @param object $parameter: target parameter
     * @return mixed
     */
    public function delete($parameter = null)
    {
        parse_str(file_get_contents('php://input'), $data);

        return $this->cleanData($data, $parameter);
    }
}
