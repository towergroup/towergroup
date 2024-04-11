<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отзывы Tower Group");
$APPLICATION->SetPageProperty("title", "Отзывы Tower Group");
$currentDescription = $APPLICATION->GetProperty("description");
$APPLICATION->SetPageProperty("description", "Агентство недвижимости бизнес и премиум класса Tower Group. Отзывы наших клиентов. Услуги по подбору недвижимости и сопровождению сделок в Санкт-Петербурге и Москве.766776");

// Добавляем к текущему описанию необходимое слово или фразу
$updatedDescription = $currentDescription ."esfd";

// Устанавливаем измененное описание обратно в мета-тег description
$APPLICATION->SetPageProperty("description", $updatedDescription);
$APPLICATION->AddChainItem("О нас", "/moskva/about/");
$APPLICATION->AddChainItem("Отзывы", "");
?>

<section class="section-heading section-heading--large" data-scroll-fx="data-scroll-fx">
    <div class="container">
        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","page",Array(
                "START_FROM" => (SITE_ID == "s2") ? 1 : 0,
                "PATH" => "",
            )
        );?>
        <h1>Отзывы</h1>
    </div>
</section>
<section class="section-reviews">
    <div class="container">
        <?
        $APPLICATION->IncludeComponent(
            "bs-soft:reviews",
            "reviews",
            Array(
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "REVIEWS_PAGE_COUNT_LIST"   => 9,
                "AJAX" => true
            )
        );
        ?>
    </div>
</section>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
