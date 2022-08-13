<?php
namespace Vayti;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class Prices
 * @package Vayti
 */
class Prices
{
    protected const MODULE_ID = TELEGRAM_BOT_MODULE;

    /**
     * @param $percent
     * @param $sectionId
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function recalculate($percent,$sectionId){
        $sectionExtra = \Vayti\Iblock::getSection($sectionId);
        $arExtra = \Bitrix\Catalog\ExtraTable::getRow([
            'filter' => ['NAME' => $sectionExtra['NAME']],
            'select' =>  ['ID','NAME','PERCENTAGE']
        ]);

        if(!$arExtra){
            $extraId = \Bitrix\Catalog\ExtraTable::add([
                'NAME' => $sectionExtra['NAME'],
                'PERCENTAGE' => $percent
            ]);
        }else{
            $extraId = $arExtra['ID'];
            if($percent != $arExtra['PERCENTAGE']){
                \Bitrix\Catalog\ExtraTable::update($arExtra['ID'],['PERCENTAGE' => $percent]);
            }
        }
        $rsElement = \Bitrix\Iblock\ElementTable::getList([
            'select' => ['ID','IBLOCK_SECTION_ID'],
            'filter' => ['IBLOCK_SECTION_ID' => $sectionId]
        ]);
        while ($arElement = $rsElement->Fetch()){
            $id = $arElement['ID'];
            $allProductPrices = \Bitrix\Catalog\PriceTable::getRow([
                "select" => ["*"],
                "filter" => [
                    "PRODUCT_ID" => $id,
                    "CATALOG_GROUP_ID" => 1
                ],
            ]);

            $price = $allProductPrices['PRICE'];
            $priceScale = $price + $price*($percent/100);
            \Bitrix\Catalog\PriceTable::add([
                'EXTRA_ID' => $extraId,
                'PRODUCT_ID' => $id,
                'CATALOG_GROUP_ID' => 2,
                'CURRENCY' => 'RUB',
                'PRICE' => $priceScale,
                'PRICE_SCALE' => $price

            ]);
        }
    }
}