<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba0af8f7008bddf8f2948fcf2f9445ed
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lcobucci\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lcobucci\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/lcobucci/jwt/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Mustache' => 
            array (
                0 => __DIR__ . '/..' . '/mustache/mustache/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitba0af8f7008bddf8f2948fcf2f9445ed::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitba0af8f7008bddf8f2948fcf2f9445ed::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitba0af8f7008bddf8f2948fcf2f9445ed::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}