<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вакансии Tower Group");
$APPLICATION->SetPageProperty("title", "Вакансии Tower Group");
$APPLICATION->SetPageProperty("description", "➜ Вакансии Tower Group");
$APPLICATION->AddChainItem("О нас", "/moskva/about/");
$APPLICATION->AddChainItem("Вакансии", "");
?>

<section class="section-heading section-heading--large" data-scroll-fx="data-scroll-fx">
    <div class="container">
        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","page",Array(
                "START_FROM" => (SITE_ID == "s2") ? 1 : 0,
                "PATH" => "",
            )
        );?>
        <h1>Вакансии</h1>
    </div>
</section>
<section>
    <?
    $APPLICATION->IncludeComponent(
        "bs-soft:vacancies",
        "vacancies",
        Array(
            "VACANCIES_PAGE_COUNT_LIST"   => 10,
            "AJAX" => true
        )
    );
    ?>
</section>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
