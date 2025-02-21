<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite88da55715a2f89a0c7ea429e67d500a
{
    public static $files = array (
        '5374218f54ebd3508ef0ee93d0ad6d14' => __DIR__ . '/../..' . '/includes/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WcDPD\\' => 6,
        ),
        'L' => 
        array (
            'League\\ISO3166\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WcDPD\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
        'League\\ISO3166\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/iso3166/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'League\\ISO3166\\Exception\\DomainException' => __DIR__ . '/..' . '/league/iso3166/src/Exception/DomainException.php',
        'League\\ISO3166\\Exception\\ISO3166Exception' => __DIR__ . '/..' . '/league/iso3166/src/Exception/ISO3166Exception.php',
        'League\\ISO3166\\Exception\\OutOfBoundsException' => __DIR__ . '/..' . '/league/iso3166/src/Exception/OutOfBoundsException.php',
        'League\\ISO3166\\Guards' => __DIR__ . '/..' . '/league/iso3166/src/Guards.php',
        'League\\ISO3166\\ISO3166' => __DIR__ . '/..' . '/league/iso3166/src/ISO3166.php',
        'League\\ISO3166\\ISO3166DataProvider' => __DIR__ . '/..' . '/league/iso3166/src/ISO3166DataProvider.php',
        'League\\ISO3166\\ISO3166DataValidator' => __DIR__ . '/..' . '/league/iso3166/src/ISO3166DataValidator.php',
        'League\\ISO3166\\ISO3166WithAliases' => __DIR__ . '/..' . '/league/iso3166/src/ISO3166WithAliases.php',
        'WcDPD\\Ajax' => __DIR__ . '/../..' . '/includes/Ajax.php',
        'WcDPD\\Assets' => __DIR__ . '/../..' . '/includes/Assets.php',
        'WcDPD\\Client' => __DIR__ . '/../..' . '/includes/Client.php',
        'WcDPD\\Core' => __DIR__ . '/../..' . '/includes/Core.php',
        'WcDPD\\DpdExport' => __DIR__ . '/../..' . '/includes/DpdExport.php',
        'WcDPD\\DpdExportSettings' => __DIR__ . '/../..' . '/includes/DpdExportSettings.php',
        'WcDPD\\DpdParcelShopShippingMethod' => __DIR__ . '/../..' . '/includes/DpdParcelShopShippingMethod.php',
        'WcDPD\\Email' => __DIR__ . '/../..' . '/includes/Email.php',
        'WcDPD\\Hooks' => __DIR__ . '/../..' . '/includes/Hooks.php',
        'WcDPD\\Notice' => __DIR__ . '/../..' . '/includes/Notice.php',
        'WcDPD\\Order' => __DIR__ . '/../..' . '/includes/Order.php',
        'WcDPD\\OrderList' => __DIR__ . '/../..' . '/includes/OrderList.php',
        'WcDPD\\OrderMetabox' => __DIR__ . '/../..' . '/includes/OrderMetabox.php',
        'WcDPD\\Shipping' => __DIR__ . '/../..' . '/includes/Shipping.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite88da55715a2f89a0c7ea429e67d500a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite88da55715a2f89a0c7ea429e67d500a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite88da55715a2f89a0c7ea429e67d500a::$classMap;

        }, null, ClassLoader::class);
    }
}
