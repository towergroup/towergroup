<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$this->setFrameMode(true);
use \Bitrix\Main\Page\Asset;
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/service_detail.min.css");

$url = "https://towergroup.ru/moskva/about/services/".$arResult["CODE"];
if (SITE_ID == "s2") {
    $APPLICATION->AddHeadString('<link href="'.$url.'/" rel="canonical" />',true);
}
?>
<section class="section-common" data-scroll-fx>
	<div class="container"><a class="text-control text-control--back" href="/<?=(SITE_ID == "s2") ? "spb" : "moskva"?>/about/services/"><svg class="icon icon--arrow-back" width="6" height="9" viewbox="0 0 6 9">
                <use xlink:href="#arrow-back" />
            </svg><span>Вернуться назад</span></a>
        <h1><?=$arResult["NAME"]?></h1>
        <div class="common-content">
            <?=$arResult["~DETAIL_TEXT"]?>
            <img class="lazy" data-src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>">
        </div>
    </div>
</section>

<script>
    $(function () {
        var arr = '<?= $arResult["NAME"]; ?>';
        $("#service-form").prepend("<input hidden='' type='text' name='tipService' value='" + arr + "' placeholder=''>");
    })
</script>