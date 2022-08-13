<?defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$aMenu = [
    [
        'parent_menu' => 'global_menu_settings',
        'sort' => 400,
        'text' => Loc::getMessage('RECALCULATION_PRICES_MENU_TEXT'),
        'title' => Loc::getMessage('RECALCULATION_PRICES_MENU_TITLE'),
        'url' => 'recalculation.prices.php'

    ]
];
return $aMenu;
