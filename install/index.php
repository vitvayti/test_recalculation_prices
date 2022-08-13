<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Diag\Debug;

if(class_exists("recalculation_prices")) return;

/**
 * Class recalculation_prices
 */
Class recalculation_prices extends CModule
{

    var $MODULE_ID = "recalculation.prices";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->PARTNER_NAME = Loc::getMessage('RECALCULATION_PRICES_PARTNER_NAME');

        $this->MODULE_NAME = Loc::getMessage('RECALCULATION_PRICES_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('RECALCULATION_PRICES_INSTALL_DESCRIPTION');

    }

    public function DoInstall()
    {
        $this->InstallDB();
        $this->installDefault();
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);
        return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/recalculation.prices/install/admin/recalculation.prices.php",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/recalculation.prices.php", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/recalculation.prices/install/admin/recalculation_ajax.php",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/recalculation_ajax.php", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/recalculation.prices/install/admin/js/admin.js",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/recalculation.prices/js/admin.js", true, true);
        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx("/bitrix/admin/recalculation.prices.php");
        DeleteDirFilesEx("/bitrix/admin/recalculation_ajax.php");
        DeleteDirFilesEx("/bitrix/admin/recalculation.prices/js/admin.js");
        return true;
    }
    public function installDefault(){
        try {
            if (!Loader::includeModule('perfmon')) {
                throw new \Error(Loc::getMessage('RECALCULATION_PRICES_ERROR_PERFOM'));
            }
            $defaultOptions = \Bitrix\Main\Config\Option::getDefaults($this->MODULE_ID);
            foreach ($defaultOptions as $key => $value) {
                Option::set("recalculation.prices", $key, $value);
            }
            global $CACHE_MANAGER, $stackCacheManager;
            $CACHE_MANAGER->CleanAll();
            $stackCacheManager->CleanAll();
        }catch (\Throwable $throwable) {
            Debug::writeToFile($throwable);
        }
    }
}