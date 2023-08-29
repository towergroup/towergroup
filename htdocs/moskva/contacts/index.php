<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("Контакты");
$APPLICATION->SetPageProperty("title", "Tower Group - Контакты");
$APPLICATION->SetPageProperty("description", "Контактная информация: адреса офисов, телефоны, режим работы.");
$APPLICATION->SetPageProperty("header", 'class="header-white"');
$APPLICATION->AddChainItem("Контакты", "");
$serverUrl = 'https://'.$_SERVER['HTTP_HOST'];
$urlLastElement = explode('?', $_SERVER["REQUEST_URI"]);
$urlLastElement = $urlLastElement[0];
?>
	<script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
            { "@type": "ListItem", "position": 1, "name": "Главная", "item": "<?=$serverUrl;?>" },
			{ "@type": "ListItem", "position": 2, "name": "Контакты", "item": "<?=$serverUrl;?><?=$urlLastElement;?>" }]
        }
    </script>
    <?
    $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "contacts",
        Array(
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "BROWSER_TITLE" => "-",
            "CACHE_GROUPS" => "N",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "CHECK_DATES" => "N",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "ELEMENT_CODE" => $_COOKIE['city'] ? $_COOKIE['city'] : "moscow",
            "FIELD_CODE" => array(""),
            "IBLOCK_ID" => CONTACTS_IBLOCK_ID,
            "IBLOCK_TYPE" => "main",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "MESSAGE_404" => "",
            "META_DESCRIPTION" => "-",
            "META_KEYWORDS" => "-",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_TEMPLATE" => "contacts",
            "PAGER_TITLE" => "",
            "PROPERTY_CODE" => array("ADDRESS", ""),
            "SET_BROWSER_TITLE" => "N",
            "SET_CANONICAL_URL" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "Y",
            "SET_META_KEYWORDS" => "Y",
            "SET_STATUS_404" => "Y",
            "SET_TITLE" => "N",
            "SHOW_404" => "Y",
            "USE_PERMISSIONS" => "N",
            "USE_SHARE" => "N"
        )
    );
    ?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>