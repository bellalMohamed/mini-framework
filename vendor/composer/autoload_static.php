<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4d7697495b9dc24292a87a81e6020d25
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4d7697495b9dc24292a87a81e6020d25::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4d7697495b9dc24292a87a81e6020d25::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
