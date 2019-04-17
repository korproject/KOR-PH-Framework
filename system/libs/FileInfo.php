<?php

/**
 * @see source: https://www.php.net/manual/en/class.splfileinfo.php
 * @see source: https://www.php.net/manual/en/class.finfo
 */
class FileInfo extends SplFileInfo
{
    public $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        parent::__construct($filename);
    }

    public function getMimeType()
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        return $finfo->file($this->filename);
    }

    public function getFileMimeEncoding()
    {
        $finfo = new finfo(FILEINFO_MIME_ENCODING);

        return $finfo->file($this->filename);
    }

    public function getImageSize()
    {
        return getimagesize($this->filename);
    }
}
