<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости и акции Tower Group");
$APPLICATION->SetPageProperty("title", "Актуальные новости и акции Tower Group");
$APPLICATION->SetPageProperty("description", "➜ Актуальные новости и акции Tower Group");
//$APPLICATION->AddChainItem("Новости и акции", "");
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:news",
    "news",
    array(
        "ADD_ELEMENT_CHAIN" => "Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "BROWSER_TITLE" => "-",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "Y",
        "DETAIL_ACTIVE_DATE_FORMAT" => "j F Y",
        "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
        "DETAIL_DISPLAY_TOP_PAGER" => "N",
        "DETAIL_FIELD_CODE" => array(
            0 => "PREVIEW_PICTURE",
            1 => "DETAIL_TEXT",
            2 => "DETAIL_PICTURE",
            3 => "",
        ),
        "DETAIL_PAGER_SHOW_ALL" => "Y",
        "DETAIL_PAGER_TEMPLATE" => "",
        "DETAIL_PAGER_TITLE" => "Страница",
        "DETAIL_PROPERTY_CODE" => array(
            0 => "OBJECTS_NEW_BUILD_MSK",
            1 => "",
            2 => "",
            3 => "",
            4 => "",
        ),
        "DETAIL_SET_CANONICAL_URL" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FILE_404" => "/404.php",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => NEWS_IBLOCK_ID,
        "IBLOCK_TYPE" => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "LIST_ACTIVE_DATE_FORMAT" => "j F Y",
        "LIST_FIELD_CODE" => array(
            0 => "NAME",
            1 => "PREVIEW_TEXT",
            2 => "PREVIEW_PICTURE",
            3 => "DETAIL_PICTURE",
            4 => "ACTIVE_TO",
        ),
        "LIST_PROPERTY_CODE" => array(
            0 => "HIGHLIGHT_NEWS",
            1 => "IS_ACTION",
        ),
        "MESSAGE_404" => "",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "NEWS_COUNT" => "9",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PREVIEW_TRUNCATE_LEN" => "",
        "SEF_FOLDER" => "/moskva/pressa/",
        "SEF_MODE" => "Y",
        "SET_LAST_MODIFIED" => "Y",
        "SET_STATUS_404" => "Y",
        "SET_TITLE" => "Y",
        "SHOW_404" => "Y",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N",
        "USE_CATEGORIES" => "N",
        "USE_FILTER" => "N",
        "USE_PERMISSIONS" => "N",
        "USE_RATING" => "N",
        "USE_REVIEW" => "N",
        "USE_RSS" => "N",
        "USE_SEARCH" => "N",
        "USE_SHARE" => "N",
        "COMPONENT_TEMPLATE" => ".default",
        "SEF_URL_TEMPLATES" => array(
            "news" => "",
            "section" => "#SECTION_CODE#/",
            "detail" => "#SECTION_CODE#/#ELEMENT_CODE#/",
        )
    ),
    false
);?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
