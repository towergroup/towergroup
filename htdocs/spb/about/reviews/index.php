<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отзывы Tower Group");
$APPLICATION->SetPageProperty("title", "Отзывы Tower Group");
$APPLICATION->SetPageProperty("description", "➜ Отзывы Tower Group");
$APPLICATION->AddChainItem("О нас", "/spb/about/");
$APPLICATION->AddChainItem("Отзывы", "");
$APPLICATION->AddHeadString('<link href="https://towergroup.ru/moskva/about/reviews/" rel="canonical" />',true);
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
