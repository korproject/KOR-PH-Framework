<?php

class FileSize
{
    public $removeComma = true;

    public function byte2KB($bytes, $decimal_places = 0){
        str_replace(array('.', ','), null , $bytes);
        $kb = number_format($bytes / 1024, $decimal_places);
        return $this->removeComma ? str_replace(',', null, $kb) : $kb;
    }

    public function byte2MB($bytes, $decimal_places = 0){
        str_replace(array('.', ','), null , $bytes);
        $mb = number_format($bytes / 1048576, $decimal_places);
        return $this->removeComma ? str_replace(',', null, $mb) : $mb;
    }

    public function byte2GB($bytes, $decimal_places = 0){
        str_replace(array('.', ','), null , $bytes);
        $gb = number_format($bytes / 1073741824, $decimal_places);
        return $this->removeComma ? str_replace(',', null, $gb) : $gb;
    }

    public function parseFileSize($size){
        preg_match('/^([0-9\.?]+).?([A-Z]{2})$/', $size, $matches);

        if (isset($matches[1]) && $matches[1]){
            $matches[1] = is_numeric($matches[1]) ? (int)ceil($matches[1]) : 0;
        }

        return $matches;
    }

    /**
     * Returns recalculated bytes
     *
     * @param string $bytes default byte value
     * @see http://php.net/manual/tr/function.disk-total-space.php
     * @return int|string
     */
    public function getSize($bytes, $split = false, $maxType = 'MB')
    {
        if ($bytes == null || $bytes == '0' || !is_numeric($bytes)) {
            return 0;
        }

        $symbols = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $exp = floor(log($bytes) / log(1024));
        $size = sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));

        if ($split){
            $matches = $this->parseFileSize($size);

            if (isset($matches[2]) && $matches[2]){
                $matches[3] = array_search($matches[2], $symbols) <= array_search($maxType, $symbols) ? true : false;
            } else {
                $matches[3] = false;
            }

            return $matches;
        } else {
            return $size;
        }
    }

    public function getMaxUploadSize(){
        $size = ini_get('upload_max_filesize') . 'B';
        $matches = $this->parseFileSize($size);

        return $matches && $matches[1] ? $matches : false;
    }

    public function getMaxPostSize(){
        $size = ini_get('post_max_size') . 'B';
        $matches = $this->parseFileSize($size);

        return $matches && $matches[1] ? $matches : false;
    }

    /**
     * Max POST size vs Upload size compare
     * POST size has high priority, usually files sends via form POST method
     * 
     * @return bool|array
     */
    public function getMaxPostUpSize(){
        $maxPostSize = $this->getMaxPostSize();
        $maxUpSize = $this->getMaxUploadSize();

        if($maxPostSize && $maxUpSize){
            $symbols = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            // post_max_size x MB == upload_max_filesize y MB
            if (array_search($maxPostSize[2], $symbols) == array_search($maxUpSize, $symbols)){
                // post_max_size 99 MB <= upload_max_filesize 99 || 100 MB
                if ($maxPostSize[1] <= $maxUpSize[1]){
                    return $maxPostSize; // return POST size (as array)
                } // post_max_size 101 MB > upload_max_filesize 100 MB
                else {
                    return $maxUpSize; // return Upload size (as array)
                }
            } // post_max_size 1 MB || GB <= upload_max_filesize 2 GB
            else if (array_search($maxPostSize[2], $symbols) <= array_search($maxUpSize, $symbols)){
                return $maxPostSize;
            } // post_max_size 100 MB || GB ... > upload_max_filesize 2 MB || GB ...
            else {
                return $maxUpSize;
            }
        }

        return false;
    }
}
