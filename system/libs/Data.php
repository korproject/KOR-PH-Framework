<?php

class Data extends Lang
{
    /**
     * Snake-case or kebab-case to ucwords
     * 
     * @param string $string
     * @return string
     */
    public function normalizeString($string)
    {
        $string = preg_replace('/[_\-~|]+/', ' ', $string);
        return ucwords($string);
    }
    
    /**
     * Array to table function
     * 
     * @param array $array
     * @param int $maxLength: max length of every value
     * @return bool|string
     */
    public function arrayToTable(array $array)
    {
        if ($array && count($array) > 0){
            $table = '<table><thead><tr>';

            $firstItem = isset($array[0]) && is_array($array[0]) ? $array[0] : $array;

            foreach ($firstItem as $key => $value) {
                $title = isset($this->lang->$key) ? $this->lang->$key : $this->normalizeString($key).'<br>';

                $table .= "<th>{$title}</th>";
            }

            $table .= '</tr></thead><tbody>';

            $singleRow = null;

            // all arrays
            foreach ($array as $item) {
                // multiple rows
                if (is_array($item)){
                    $row = '<tr>';

                    foreach ($item as $key => $value) {
                        $row .= "<td>{$value}</td>";
                    }

                    $row .= '</tr>';

                    $table .= $row;
                } // single row
                else {
                    if ($singleRow === null){
                        $singleRow = '<tr>';
                    }

                    $singleRow .= "<td>{$item}</td>";
                }
            }

            if ($singleRow){
                $singleRow .= '</tr>';
                $table .= $singleRow;
            }

            $table .= '<tbody></table>';

            return $table;
        }

        return false;
    }

    /**
     * Check is PHP
     * @param string $file: target subject
     */
    public function isPhpFile($file){
        if (is_file($file)){
            $ext = pathinfo($file);

            if ($ext['extension'] === 'php'){
                requireFile($file);

                return true;
            }
        }

        return false;
    }

}
