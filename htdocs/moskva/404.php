<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetPageProperty("title","404 Not Found");
$APPLICATION->SetTitle("404 Not Found");
?>

<section class="section-404">
    <div class="container">
        <div class="page-404" data-scroll-fx><img src="<?= SITE_TEMPLATE_PATH ?>/img/logotype-404.svg">
            <h1 class="h2">Ошибка 404</h1>
            <p>Кажется что-то пошло не так. Страницы, которую вы запрашиваете, не существует.</p><a class="button button--light" href="/">На главную</a>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>