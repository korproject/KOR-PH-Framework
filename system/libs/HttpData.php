<?php

class HttpData
{
    /**
     * Filter data function
     * 
     * @param array $data: target data block
     * @param string $parameter: target parameter
     * @return mixed
     */
    private function filterData($data, $parameter = null){
        // all data contents
        if ($parameter == null && $data) {
            if (is_array($data)) {
                $request = array();

                foreach ($data as $key => $param) {
                    if (is_array($param)) {
                        foreach ($param as $k => $v) {
                            $request[$key][$k] = htmlspecialchars(addslashes(trim($v)));
                        }
                    } else {
                        $request[$key] = htmlspecialchars(addslashes(trim($param)));
                    }
                }

                return $request;
            }
        } // single target
        else if (!empty($data[$parameter])) {
            if (is_array($data[$parameter])) {
                $request = array();

                foreach ($data[$parameter] as $param) {
                    $request[] = htmlspecialchars(addslashes(trim($param)));
                }

                return $request;
            }

            return htmlspecialchars(addslashes(trim($data[$parameter])));
        }

        return false;
    }

    /** -------------------------- DEFAULT METHODS START -------------------------- */

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
                $request = array();

                foreach ($_GET as $key => $param) {
                    $request[$key] = strip_tags(trim(addslashes(htmlspecialchars($param))));
                }

                return $request;
            }
        } else if (isset($_GET[$parameter])) {
            return strip_tags(trim(addslashes(htmlspecialchars($_GET[$parameter]))));
        }

        return false;
    }

    /**
     * Get $_POST contents in filtered way
     *
     * @param object $parameter: target parameter
     * @return mixed
     */
    public function post($parameter = null)
    {
        return $this->filterData($_POST, $parameter);
    }

    /** -------------------------- DEFAULT METHODS END ---------------------------- */


    /** -------------------------- ADDITIONAL METHODS START ---------------------------- */

    /**
     * Get ALL METHODS contents in filtered way
     *
     * @param object $parameter: target parameter
     * @return mixed
     */
    public function method($parameter = null)
    {
        parse_str(file_get_contents('php://input'), $_PUT);

        return $this->filterData($_PUT, $parameter);
    }

    /** -------------------------- ADDITIONAL METHODS END ------------------------------ */
}
