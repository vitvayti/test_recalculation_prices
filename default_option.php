<?
//Табы(Вкладки)
$arTabs = [
    ["DIV" => "edit1", "TAB" => "Основные", "ICON" => "main_user_edit", "TITLE" => "Основные"],
];

//Поля
$arOption = [
    'RECALCULATION' => [
        'VALUE' => '10',
        'TYPE' => 'text',
        'ATTR' => [
            'size' => 50
        ]
    ],
    'IBLOCK' => [
        'VALUE' => '',
        'TYPE' => 'select',
    ],
    'SECTION' => [
        'VALUE' => '',
        'TYPE' => 'select',
    ],
];

//Группы по табам
$arTabOption = [
    'edit1' => [
        'MAIN'
    ],
];

//Поля по группам
$arGroupOption = [
    'MAIN' => [
        'RECALCULATION',
        'IBLOCK',
        'SECTION'
    ],
];

$recalculation_prices_default_option = [
    'TABS' => $arTabs,
    'OPTIONS' => $arOption,
    'TAB_OPTION' => $arTabOption,
    'GROUP_OPTION' => $arGroupOption
];
?>