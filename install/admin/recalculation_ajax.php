<?

use Bitrix\Main\Context;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

$request = Context::getCurrent()->getRequest();
$action = $request->get('action');
switch ($action){
    case 'iblock':
        \Bitrix\Main\Loader::includeModule('recalculation.prices');
        $iblockId = $request->get('value');
        $section = \Vayti\Iblock::getSelectSection($iblockId);
        echo $section;
        break;
}