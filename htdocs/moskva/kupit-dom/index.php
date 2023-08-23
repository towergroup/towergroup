<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Tower Group - Загородная недвижимость");
$APPLICATION->SetPageProperty("description", "Загородная недвижимость.");
?>

<?
$APPLICATION->IncludeComponent(
    "bs-soft:search.apartments.country",
    "resale",
    Array(
        "CITY_CODE" => "moskva",
        "IBLOCK_ID" => COUNTRY_IBLOCK_ID,
        "AJAX" => true,
        "OBJECTS_PAGE_COUNT_LIST" => 9,
        "OBJECTS_DETAIL_PAGE_COUNT_LIST" => 3,
        "SORT_DEFAULT" => "SORT",
        "SORT_BY_DEFAULT" => "ASC",
        "OBJECT_PAGE_BROKER_ID" => 23633,
        "SEO_TEMPLATE_CODE" => "country",
        "NUMBER_OF_OBJECT_PHOTOS" => 8
    )
);
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>