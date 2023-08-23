<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("Контакты");
$APPLICATION->SetPageProperty("title", "Tower Group - Вторичная недвижимость");
$APPLICATION->SetPageProperty("description", "Вторичная недвижимость.");
?>

<?
$APPLICATION->IncludeComponent(
    "bs-soft:search.apartments.resale",
    "resale",
    Array(
        "CITY_CODE" => "spb",
        "IBLOCK_ID" => RESALE_SPB_IBLOCK_ID,
        "AJAX" => true,
        "OBJECTS_PAGE_COUNT_LIST" => 9,
        "OBJECTS_DETAIL_PAGE_COUNT_LIST" => 3,
        "SORT_DEFAULT" => "SORT",
        "SORT_BY_DEFAULT" => "ASC",
        "OBJECT_PAGE_BROKER_ID" => 23633,
        "SEO_TEMPLATE_CODE" => "resale",
        "NUMBER_OF_OBJECT_PHOTOS" => 8
    )
);
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>