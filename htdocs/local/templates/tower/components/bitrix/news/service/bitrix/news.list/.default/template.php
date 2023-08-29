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
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $component->arParams['AJAX_OPTION_ADDITIONAL']);
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/service.min.css");
$url = "https://towergroup.ru/moskva/about/services/";
if (SITE_ID == "s2") {
    $APPLICATION->AddHeadString('<link href="'.$url.'" rel="canonical" />',true);
}
?>

<section>
    <div class="container">
        <?if (!empty($arResult["ITEMS"])):?>
            <div class="service">
                <? foreach ($arResult["ITEMS"] as $key => $arItem) :?>
                    <div class="service-item" data-scroll-fx>
                        <a class="service-link" href="<?=$arItem["DETAIL_PAGE_URL"];?>" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>');">
                            <span><?=$arItem["NAME"];?></span>
                        </a>
                        </div>
                <?endforeach;?>
            </div>
        <?else:?>
        <div class="inner">
            <div class="subhero-content">
                <?= Loc::getMessage("NOT_FOUND_ELEMENTS");?> <a href="<?=SITE_DIR;?>"><?= Loc::getMessage("MAIN");?></a> <?= Loc::getMessage("PAGE");?>
            </div>
        </div>
        <?endif;?>
    </div>
</section>