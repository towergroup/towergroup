<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty('title','Tower Group');
$APPLICATION->SetTitle('Tower Group');
$serverUrl = 'https://'.$_SERVER['HTTP_HOST'];
?>
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
            { "@type": "ListItem", "position": 1, "name": "Главная", "item": "<?=$serverUrl;?>" }]
        }
    </script>
    <section class="section-hero">
        <?
        $APPLICATION->IncludeComponent(
            "bitrix:news.detail",
            "main_background",
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
                "DETAIL_URL" => "/index.php",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "ELEMENT_CODE" => $_COOKIE['city'] ? "first-screen-on-main-page-".$_COOKIE['city'] : "first-screen-on-main-page-moskva",
                "FIELD_CODE" => array("PREVIEW_PICTURE",""),
                "IBLOCK_ID" => FIRST_SCREEN_MAIN_IBLOCK_ID,
                "IBLOCK_TYPE" => "plug_in_areas",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "MESSAGE_404" => "",
                "META_DESCRIPTION" => "-",
                "META_KEYWORDS" => "-",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_TEMPLATE" => "contacts",
                "PAGER_TITLE" => "",
                "PROPERTY_CODE" => array("", ""),
                "SET_BROWSER_TITLE" => "N",
                "SET_CANONICAL_URL" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "Y",
                "SET_META_KEYWORDS" => "Y",
                "SET_STATUS_404" => "Y",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "USE_PERMISSIONS" => "N",
                "USE_SHARE" => "N"
            )
        );
        ?>
        <div class="container">
            <?
            $APPLICATION->IncludeComponent(
                "bs-soft:search.main",
                "main",
                Array(
                    "CITY_CODE" => "moskva",
                    "AJAX" => true,
                    "SORT_DEFAULT" => "SORT",
                    "SORT_BY_DEFAULT" => "ASC"
                )
            );
            ?>
        </div>
        <div class="scroll-down">Листайте вниз</div>
    </section>
    <section class="section-main-types">
        <div class="container">
            <?global $filterThreeMain;?>
            <?$filterThreeMain = array("PROPERTY_CITY_VALUE" => "Москва");?>
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "three_screen",
                Array(
                    "IBLOCK_ID" => THREE_SCREEN_IBLOCK_ID,
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PROPERTIES" => array(
                        0 => "PREVIEW_PICTURE",
                        1 => "",
                    ),
                    "PROPERTY_CODE" => array(
                        0 => "LINKS",
                        1 => "",
                    ),
                    "NEWS_COUNT" => "5",
                    "SET_TITLE" => "N",
                    "SORT_BY1" => "SORT",
                    "SORT_ORDER1" => "ASC",
                    "FILTER_NAME" => "filterThreeMain",
                    "CACHE_TYPE" => "N",
                    "CACHE_TIME" => "0",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                )
            );
            ?>
        </div>
    </section>
    <section class="section-hero-about">
        <div class="container">
            <div class="hero-about">
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:news.detail",
                    "main_description",
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
                        "DETAIL_URL" => "/index.php",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "DISPLAY_DATE" => "Y",
                        "DISPLAY_NAME" => "Y",
                        "DISPLAY_PICTURE" => "Y",
                        "DISPLAY_PREVIEW_TEXT" => "Y",
                        "DISPLAY_TOP_PAGER" => "N",
                        "ELEMENT_CODE" => $_COOKIE['city'] ? "second-screen-on-main-page-".$_COOKIE['city'] : "second-screen-on-main-page-moskva",
                        "FIELD_CODE" => array("PREVIEW_TEXT",""),
                        "IBLOCK_ID" => SECOND_SCREEN_MAIN_IBLOCK_ID,
                        "IBLOCK_TYPE" => "plug_in_areas",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "MESSAGE_404" => "",
                        "META_DESCRIPTION" => "-",
                        "META_KEYWORDS" => "-",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_TEMPLATE" => "contacts",
                        "PAGER_TITLE" => "",
                        "PROPERTY_CODE" => array("LINK", ""),
                        "SET_BROWSER_TITLE" => "N",
                        "SET_CANONICAL_URL" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "Y",
                        "SET_META_KEYWORDS" => "Y",
                        "SET_STATUS_404" => "Y",
                        "SET_TITLE" => "N",
                        "SHOW_404" => "N",
                        "USE_PERMISSIONS" => "N",
                        "USE_SHARE" => "N"
                    )
                );
                ?>
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
                        "DETAIL_URL" => "/index.php",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "DISPLAY_DATE" => "Y",
                        "DISPLAY_NAME" => "Y",
                        "DISPLAY_PICTURE" => "Y",
                        "DISPLAY_PREVIEW_TEXT" => "Y",
                        "DISPLAY_TOP_PAGER" => "N",
                        "ELEMENT_CODE" => $_COOKIE['city'] ? $_COOKIE['city'] : "moskva",
                        "FIELD_CODE" => array(""),
                        "IBLOCK_ID" => CONTACTS_IBLOCK_ID,
                        "IBLOCK_TYPE" => "content",
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
                        "SHOW_404" => "N",
                        "USE_PERMISSIONS" => "N",
                        "USE_SHARE" => "N",
                        "PAGE_TYPE_AND_POSITION" => "MAIN_TOP"
                    )
                );
                ?>
            </div>
        </div>
    </section>
<?global $filterObjectsMain;?>
<?$filterObjectsMain = array("IBLOCK_ID" => NEW_BUILD_IBLOCK_ID, "UF_SHOW_ON_MAIN" => 1);?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list","objects.main",
    Array(
        "VIEW_MODE" => "TEXT",
        "SHOW_PARENT_NAME" => "Y",
        "IBLOCK_ID" => NEW_BUILD_IBLOCK_ID,
        "COUNT_ELEMENTS" => "Y",
        "TOP_DEPTH" => "2",
        "SECTION_FIELDS" => "",
        "SECTION_USER_FIELDS" => array('UF_*'),
        "FILTER_NAME" => "filterObjectsMain",
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_GROUPS" => "Y",
        "TITLE_H1" => "Предложения, которые могут Вас заинтересовать",
        "CITY_CODE" => "moskva"
    )
);?>
    <section class="section-form-inline section-form-inline--small" data-scroll-fx="data-scroll-fx">
        <div class="container">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => "/local/include/forms/form_help.php",
                    "EDIT_TEMPLATE" => "",
                    "FORM_CODE" => "help-main-form",
                ),
                false
            );?>
        </div>
    </section>
    <section class="section-map">
        <div class="static-map">
            <div class="static-map-image lazy" data-bg="<?= SITE_TEMPLATE_PATH ?>/img/map.jpg">
                <div class="static-map-marker"></div>
            </div>
        </div>
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
                "DETAIL_URL" => "/index.php",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "ELEMENT_CODE" => $_COOKIE['city'] ? $_COOKIE['city'] : "moskva",
                "FIELD_CODE" => array(""),
                "IBLOCK_ID" => CONTACTS_IBLOCK_ID,
                "IBLOCK_TYPE" => "content",
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
                "SHOW_404" => "N",
                "USE_PERMISSIONS" => "N",
                "USE_SHARE" => "N",
                "PAGE_TYPE_AND_POSITION" => "MAIN_BOTTOM"
            )
        );
        ?>
    </section>
    <section class="section-main-description" data-scroll-fx>
        <div class="container">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:news.detail",
                "main_description_bottom",
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
                    "DETAIL_URL" => "/index.php",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "ELEMENT_CODE" => $_COOKIE['city'] ? "bottom_description-on-main-page-".$_COOKIE['city'] : "bottom_description-on-main-page-moskva",
                    "FIELD_CODE" => array("PREVIEW_TEXT",""),
                    "IBLOCK_ID" => BOTTOM_DESCRIPTION_MAIN_IBLOCK_ID,
                    "IBLOCK_TYPE" => "plug_in_areas",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "MESSAGE_404" => "",
                    "META_DESCRIPTION" => "-",
                    "META_KEYWORDS" => "-",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_TEMPLATE" => "contacts",
                    "PAGER_TITLE" => "",
                    "PROPERTY_CODE" => "",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_CANONICAL_URL" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "Y",
                    "SET_META_KEYWORDS" => "Y",
                    "SET_STATUS_404" => "Y",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "USE_PERMISSIONS" => "N",
                    "USE_SHARE" => "N"
                )
            );
            ?>
        </div>
    </section>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>