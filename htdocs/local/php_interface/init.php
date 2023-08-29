<?
//SMTP
include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/wsrubi.smtp/classes/general/wsrubismtp.php");

// Подключаем константы
require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/init_constants.php");

/**
 * Подключаем необходимые библиотеки
 */
CJSCore::Init(['jquery']);
CModule::IncludeModule("iblock");

// Функции общего назначения
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/functions/common.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/functions/common.php");
}

\Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    'EventHandler' => '/local/php_interface/functions/EventHandler.php',
    'Helper' => '/local/php_interface/functions/Helper.php'
]);
EventHandler::init();

/* Обработчики событий */

AddEventHandler("main", "OnEndBufferContent", "deleteKernelJs"); //Убрать js
AddEventHandler("main", "OnEndBufferContent", "deleteKernelCss"); //Убрать css
//AddEventHandler("main", "OnEndBufferContent", "ChangeMyContent"); //Сжать html
function deleteKernelJs(&$content) {
    global $USER, $APPLICATION;
    if((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;
    if($APPLICATION->GetProperty("save_kernel") == "Y") return;
    $arPatternsToRemove = Array(
        '/<script.+?src=".+?kernel_main\/kernel_main_v1\.js\?\d+"><\/script\>/',
        '/<script.+?src=".+?bitrix\/js\/main\/jquery\/jquery[^"]+"><\/script\>/',
        '/<script.+?>BX\.(setCSSList|setJSList)\(\[.+?\]\).*?<\/script>/',
        '/<script.+?>if\(\!window\.BX\)window\.BX.+?<\/script>/',
        '/<script[^>]+?>\(window\.BX\|\|top\.BX\)\.message[^<]+<\/script>/',
    );
    $content = preg_replace($arPatternsToRemove, "", $content);
    $content = preg_replace("/\n{2,}/", "\n\n", $content);
}
function deleteKernelCss(&$content) {
    global $USER, $APPLICATION;
    if((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;
    if($APPLICATION->GetProperty("save_kernel") == "Y") return;
    $arPatternsToRemove = Array(
        '/<link.+?href=".+?kernel_main\/kernel_main_v1\.css\?\d+"[^>]+>/',
        '/<link.+?href=".+?bitrix\/js\/main\/core\/css\/core[^"]+"[^>]+>/',
        '/<link.+?href=".+?bitrix\/templates\/[\w\d_-]+\/styles.css[^"]+"[^>]+>/',
        '/<link.+?href=".+?bitrix\/templates\/[\w\d_-]+\/template_styles.css[^"]+"[^>]+>/',
    );
    $content = preg_replace($arPatternsToRemove, "", $content);
    $content = preg_replace("/\n{2,}/", "\n\n", $content);
}

//Сжатие HTML
function ChangeMyContent(&$content)
{
    global $USER, $APPLICATION;
    if((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;
    if($APPLICATION->GetProperty("save_kernel") == "Y") return;
    $search = array(
        '/\>[^\S ]+/s',
        '/[^\S ]+\</s',
        '/(\s)+/s'
    );
    $replace = array(
        '>',
        '<',
        '\\1'
    );
    $content = preg_replace($search, $replace, $content);
}


AddEventHandler("solverweb.sitemap", "OnAfterElementParse", "OnAfterElementParse");
function OnAfterElementParse(&$element, &$arElements, $arElement) {
    if ($arElement['IBLOCK_ID'] == 5 || $arElement['IBLOCK_ID'] == 6 || $arElement['IBLOCK_ID'] == 23)
        $element['url'] = str_replace('spb', 'moskva',$arElement["DETAIL_PAGE_URL"]);
}

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "AddNoFollowForLinks");
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "AddNoFollowForLinks");

function AddNoFollowForLinks(&$arFields) {
    if ($arFields["IBLOCK_ID"] == NEWS_IBLOCK_ID) {
		$mainUrl = "https://".$_SERVER['SERVER_NAME'];
        $arFields['DETAIL_TEXT_TYPE'] = "html";
        $arFields['PREVIEW_TEXT_TYPE'] = "html";

        $arFields['PREVIEW_TEXT'] = nofollow($arFields['PREVIEW_TEXT'], $mainUrl);
        $arFields['DETAIL_TEXT'] = nofollow($arFields['DETAIL_TEXT'], $mainUrl);

    }
}