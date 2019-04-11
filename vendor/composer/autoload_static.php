<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita8c71155acfa7cd527449996f7fd4120
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lebenlabs\\SimpleStorage\\Tests\\' => 30,
            'Lebenlabs\\SimpleStorage\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lebenlabs\\SimpleStorage\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'Lebenlabs\\SimpleStorage\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita8c71155acfa7cd527449996f7fd4120::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita8c71155acfa7cd527449996f7fd4120::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
