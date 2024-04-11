<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/catalog_new_build.min.css");
?> 

 <section class="section-common privacy-rules" data-scroll-fx>
        <div class="container">
			<? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "page", Array(
					"START_FROM" => "0",
					"PATH" => "",
				)
			); ?>
			<div class="section-heading-sort">
				<h1>Карта сайта</h1>
			</div>

<ul>
<li>
 <a href="https://towergroup.ru/">Главная</a>
</li>
<li>
 <a href="https://towergroup.ru/spb/about/">О компании</a>
</li>
<li>
 <a href="https://towergroup.ru/spb/about/services/">Услуги</a>
</li>
<li>
 <a href="https://towergroup.ru/spb/about/reviews/">Отзывы</a>
</li>
<li>
 <a href="https://towergroup.ru/spb/pressa/">Пресса</a>
</li>
<li>
 <a href="https://towergroup.ru/spb/about/vacancy/">Вакансии</a>
</li>
   </section>


</ul><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>