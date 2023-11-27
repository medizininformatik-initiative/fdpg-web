<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf803dde2590f81c222a1c4e19aa744ae
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'BitCode\\FI\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'BitCode\\FI\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf803dde2590f81c222a1c4e19aa744ae::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf803dde2590f81c222a1c4e19aa744ae::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf803dde2590f81c222a1c4e19aa744ae::$classMap;

        }, null, ClassLoader::class);
    }
}
