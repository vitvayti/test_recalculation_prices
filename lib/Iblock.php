<?php
namespace Vayti;

use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;

/**
 * Class Iblock
 * @package Vayti
 */
class Iblock
{
    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getCatalogs(){
        Loader::includeModule('iblock');
        $idCatalog = [];
        $result = [];
        $rsCatalog = \Bitrix\Catalog\CatalogIblockTable::getList([]);
        while($arCatalog = $rsCatalog->Fetch()){
            $idCatalog[] = $arCatalog['IBLOCK_ID'];
        }
        if($idCatalog){
            $rsIblock = IblockTable::getList([
                'select' => ['ID','NAME','CODE'],
                'filter' => ['ID' => $idCatalog]
            ]);
            while($arIblock = $rsIblock->Fetch()){
                $result[] = $arIblock;
            }
        }
        return $result;
    }

    /**
     * @param $iblockId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getIblockSection($iblockId){
        Loader::includeModule('iblock');
        $arSections = [];
        $tree = [];
        $rsSection = SectionTable::getList([
            'order' => ['LEFT_MARGIN' => 'ASC'],
            'select' => ['ID','NAME','IBLOCK_ID','IBLOCK_SECTION_ID','DEPTH_LEVEL','LEFT_MARGIN'],
            'filter' => ['IBLOCK_ID' => $iblockId,'ACTIVE' => 'Y']
        ]);
        while($arSection = $rsSection->Fetch()){
            $arSections[$arSection['ID']] = $arSection;
        }
        return $arSections;
    }

    /**
     * @param $iblockId
     * @param false $sectionId
     * @return string
     */
    public static function getSelectSection($iblockId,$sectionId = false){
        $arSection = static::getIblockSection($iblockId);
        $strInput = '';
        $strInput = "<option value=''>Выберите раздел</option>";;
        foreach ($arSection as $section) {
            $level = '';
            $selected = '';
            for($i=0;$i < $section['DEPTH_LEVEL'];$i++){
                $level .= '-';
            }
            if($section['ID'] == $sectionId){
                $selected = 'selected="selected"';
            }
            $strInput .= "<option value='{$section["ID"]}' {$selected}>{$level}{$section["NAME"]}</option>";
        }
        return $strInput;
    }

    /**
     * @param $id
     * @return array|mixed|null
     */
    public static function getSection($id){
        Loader::includeModule('iblock');
        $arSection = [];
        $arSection = SectionTable::getRow([
            'select' => ['ID','NAME'],
            'filter' => ['ID' => $id,'ACTIVE' => 'Y']
        ]);

        return $arSection;
    }
}