<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit782560f878aa32b033592b6bd6e42011
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPO\\IPS\\FatturaPA\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPO\\IPS\\FatturaPA\\' => 
        array (
            0 => __DIR__ . '/../..' . '/ubl',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WPO\\IPS\\FatturaPA\\Handlers\\Body\\DatiBeniServiziHandler' => __DIR__ . '/../..' . '/ubl/Handlers/Body/DatiBeniServiziHandler.php',
        'WPO\\IPS\\FatturaPA\\Handlers\\Body\\DatiGeneraliHandler' => __DIR__ . '/../..' . '/ubl/Handlers/Body/DatiGeneraliHandler.php',
        'WPO\\IPS\\FatturaPA\\Handlers\\Body\\DatiPagamentoHandler' => __DIR__ . '/../..' . '/ubl/Handlers/Body/DatiPagamentoHandler.php',
        'WPO\\IPS\\FatturaPA\\Handlers\\Header\\CedentePrestatoreHandler' => __DIR__ . '/../..' . '/ubl/Handlers/Header/CedentePrestatoreHandler.php',
        'WPO\\IPS\\FatturaPA\\Handlers\\Header\\CessionarioCommittenteHandler' => __DIR__ . '/../..' . '/ubl/Handlers/Header/CessionarioCommittenteHandler.php',
        'WPO\\IPS\\FatturaPA\\Handlers\\Header\\DatiTrasmissioneHandler' => __DIR__ . '/../..' . '/ubl/Handlers/Header/DatiTrasmissioneHandler.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit782560f878aa32b033592b6bd6e42011::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit782560f878aa32b033592b6bd6e42011::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit782560f878aa32b033592b6bd6e42011::$classMap;

        }, null, ClassLoader::class);
    }
}
