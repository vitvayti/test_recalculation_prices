<?php
require_once 'include/const.php';

\Bitrix\Main\Loader::registerAutoloadClasses(
    RECALCULATION_PRICES_MODULE,
    [
        "Vayti\\Prices" => "lib/Prices.php",
        "Vayti\\Iblock" => "lib/Iblock.php",
    ]
);

$arJsConfig = [
    RECALCULATION_PRICES_MODULE => [
        'js' => '/bitrix/admin/'.RECALCULATION_PRICES_MODULE.'/js/admin.js',
        'rel' => [],
    ]
];

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}