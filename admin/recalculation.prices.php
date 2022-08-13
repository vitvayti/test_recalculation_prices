<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

require_once $_SERVER["DOCUMENT_ROOT"] . '/local/modules/recalculation.prices/include.php';

use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$moduleID = RECALCULATION_PRICES_MODULE;
CUtil::InitJSCore([$moduleID]);

Loc::loadMessages(__FILE__);

\Bitrix\Main\Loader::includeModule($moduleID);


$settings = \Bitrix\Main\Config\Option::getDefaults($moduleID);
$request = $_REQUEST;
foreach ($settings['OPTIONS'] as $code => $prop) {
    if($request[$code]) {
        Option::set($moduleID, $code, $request[$code]);
    }
}
$iblockId = Option::get($moduleID,'IBLOCK');
$sectionId = Option::get($moduleID,'SECTION');
$percent = Option::get($moduleID,'RECALCULATION');
$tabControl = new \CAdminTabControl("tabControl", $settings['TABS']);
$arCatalog = \Vayti\Iblock::getCatalogs();
$arSections = [];
if($iblockId) {
    $arSections = \Vayti\Iblock::getSelectSection($iblockId,$sectionId);
}
if($sectionId && ($request['apply'] == 'Применить' || $request['apply'] == 'Сохранить')){
    \Vayti\Prices::recalculate($percent,$sectionId);
}

?>
<div class="adm-detail-title"><?=Loc::getMessage('RECALCULATION_PRICES_MENU_TEXT')?></div>
<form method="post">
    <?
    $tabControl->Begin();
    foreach ($settings['TABS'] as $tab){
        $tabControl->BeginNextTab();
        $groupsTab = $settings['TAB_OPTION'][$tab['DIV']];
        $options = $settings['OPTIONS'];
        foreach ($groupsTab as $group){?>
            <?$groupTab = $settings['GROUP_OPTION'][$group];?>
            <tr class="heading">
                <td colspan="2">
                    <?=Loc::getMessage('RECALCULATION_PRICES_GROUP_'.$group)?>
                </td>
            </tr>
            <?foreach ($groupTab as $code){?>
                <tr>
                    <td class="adm-detail-content-cell-l">
                        <?=Loc::getMessage('RECALCULATION_PRICES_OPTION_'.$code)?>
                    </td>
                    <td class="adm-detail-content-cell-r">
                        <?
                        $option = $options[$code];
                        $strInput = '';
                        $attrs = '';
                        foreach ($option['ATTR'] as $attr => $value){
                            $attrs .= $attr. '='. $value. ' ';
                        }
                        $value = Option::get($moduleID,$code)?:$option['VALUE'];
                        switch ($option['TYPE']){
                            case 'text':
                                $strInput = "<input type='text' name='{$code}' value='{$value}' {$attrs}>";
                                break;
                            case 'texarea':
                                $strInput = "<textarea name='{$code}' {$attrs}>{$value}</textarea>";
                                break;
                            case 'number':
                                $strInput = "<input type='number' name='{$code}' value='{$value}' {$attrs}>";
                                break;
                            case 'select':
                                if($code == 'IBLOCK') {
                                    $strInput = "<select id='iblock_recalculation' name='{$code}' {$attrs}>";
                                    $strInput .= "<option value=''>Выберите каталог</option>";
                                    foreach ($arCatalog as $catalog) {
                                        $selected = '';
                                        if($catalog['ID'] == $iblockId){
                                            $selected = 'selected="selected"';
                                        }
                                        $strInput .= "<option value='{$catalog["ID"]}' {$selected}>{$catalog["NAME"]}</option>";
                                    }
                                    $strInput .= "</select>";
                                }
                                if($code == 'SECTION') {
                                    $strInput = "<select id='section_recalculation' name='{$code}' {$attrs}>";
                                    $strInput .= $arSections;
                                    $strInput .= "</select>";
                                }
                                break;
                        }
                        ?>
                        <?=$strInput?>
                    </td>
                </tr>
            <?}?>
        <?}?>
    <?}
    $tabControl->Buttons(
        [
            "disabled" => false,
        ]
    );
    $tabControl->End();
    ?>
</form>

<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>
