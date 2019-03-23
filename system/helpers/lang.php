<?php

class Lang
{
    public $lang = null;

    public function __construct($lang = 'en_us', $asArray = false)
    {
        $this->getLang($lang, $asArray);
    }

    public function getLang($lang, $asArray = false)
    {
        if (isset($_COOKIE['lang']) && $_COOKIE['lang'] && strlen($_COOKIE['lang']) === 5) {
            $lang = $_COOKIE['lang'];
        }

        $langPath = __DIR__ . '/../../app/lang';
        $langFile = "{$langPath}/{$lang}.json";

        if (file_exists($langFile)) {
            $file = file_get_contents($langFile);

            if ($file) {
                $langContents = json_decode($file, $asArray);
            } else if (!$file && $lang != 'en_us') {
                return $this->getLang(DEFAULT_LANG, true);
            }
        }

        $this->lang = $langContents ? $langContents : null;
    }
}
