<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("Контакты");
$APPLICATION->SetPageProperty("title", "Tower Group - Новостройки");
$APPLICATION->SetPageProperty("description", "Новостройки.");
?>

<?
$APPLICATION->IncludeComponent(
    "bs-soft:search.apartments",
    "new_build",
    Array(
        "CITY_CODE" => "spb",
        "IBLOCK_ID" => NEW_BUILD_SPB_IBLOCK_ID,
        "AJAX" => true,
        "OBJECTS_PAGE_COUNT_LIST" => 25,
        "OBJECTS_DETAIL_PAGE_COUNT_LIST" => 6,
        "SORT_DEFAULT" => "SORT",
        "SORT_BY_DEFAULT" => "ASC",
        "OBJECT_PAGE_BROKER_ID" => 23633,
        "SEO_TEMPLATE_CODE" => "new-build",
        "NUMBER_OF_OBJECT_PHOTOS" => 8
    )
);
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>