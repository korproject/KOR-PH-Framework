<?php

/**
 * resumable, chunk
 * mime type
 * 72 dpi
 * %55 quality
 * bit rate 24
 * max resolution
 */

class Upload
{
    protected $defaultPermission = 0750;
    protected $file = [];
    protected $fileName = null;
    protected $fileTemp = null;
    protected $allowedMimeTypes = [];


    public $randomStringLength = 30;
    public $targetPath = './';

    /** Helper Functions */

    private function setRandomFileName()
    {
        $randomValues = new RandomValues();

        $this->randomStringLength > 0 ? $this->randomStringLength : 30;
        $this->fileName = $randomValues->getRandomString($this->randomStringLength);
    }

    public function getFileMimeType($filePath)
    {
        if (file_exists($filePath)){
            return \MimeType\MimeType::getType($filePath);
        }
        
        return false;
    }

    public function getFileMimeTypes()
    {
        return \MimeType\Mapping::$types;
    }

    public function getImageSize($filePath)
    {
        if (file_exists($filePath)){
            return getimagesize($filePath);
        }

        return false;
    }

    public function filename(string $fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function hashFilename(string $param = null, bool $type = false)
    {

        return $this;
    }

    public function maxFileSize(int $size, string $type)
    {

        return $this;
    }

    public function file(array $file)
    {
        $this->file = $file;

        return $this;
    }

    public function files(array $files)
    {
        # code...
    }

    public function image(array $image)
    {
        # code...
    }

    public function images(array $images)
    {
        # code...
    }

    public function allowedMimeTypes(array $allowedMimeTypes)
    {
        $this->allowedMimeTypes = $allowedMimeTypes;
        
        return $this;
    }

    public function upload($targetPath = null)
    {
        if (!empty($targetPath) && is_string($targetPath)) {
            $this->targetPath = $targetPath;
        } else if (empty($this->targetPath) || !is_string($this->targetPath)) {
            $this->targetPath = './';
        }

        if (empty($this->fileName)) {
            $this->setRandomFileName();
        }

        $this->file['filename'] = $this->fileName;

    }
}
