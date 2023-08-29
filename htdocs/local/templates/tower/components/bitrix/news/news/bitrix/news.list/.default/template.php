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
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/news.min.css");

$url = "https://towergroup.ru/moskva/pressa/";
if (SITE_ID == "s2") {
    $APPLICATION->AddHeadString('<link href="'.$url.'" rel="canonical" />',true);
}
?>

<section class="section-news">
    <div class="container">
        <div class="news-tabs">
            <ul class="list">
                <li class="list-item<?if(!$arResult["SECTION"]):?> list-item--active<?endif;?>">
                    <a class="list-link" href="<?= $arParams["IBLOCK_URL"] ?>">
                        Вся пресса
                    </a>
                </li>
                <?foreach ($arResult["SECTIONS"] as $arSection):?>
                    <li class="list-item<?if($arSection["IS_CURRENT"]):?> list-item--active<?endif;?>">
                        <a class="list-link"
                           href="<?= $arSection["SECTION_PAGE_URL"] ?>">
                            <?= $arSection["NAME"] ?>
                        </a>
                    </li>
                <?endforeach;?>
            </ul>
        </div>
        <?if (!empty($arResult["ITEMS"])):?>
            <div class="news">
                <div class="news-list" id="comp_<?=$bxajaxid?>">
                    <? foreach ($arResult["ITEMS"] as $key => $arItem) :?>
                        <?if ($arItem["PROPERTIES"]["HIGHLIGHT_NEWS"]["VALUE"] !== "Y") :?>
                        <div class="news-item ajax-list-item" data-scroll-fx>
                            <a class="news-link" href="<?=$arItem["DETAIL_PAGE_URL"];?>">
                                <div class="news-item-content">
                                    <div class="div-title div-title-h4"><?=$arItem["NAME"];?></div><span class="news-item-date"><?=ConvertDateTime($arItem["ACTIVE_FROM"], "D.M.Y");?></span>
                                    <p class="news-item-introtext">
                                        <?=$arItem["PREVIEW_TEXT"];?>
                                    </p>
                                </div>
                                <span class="text-control text-control--long-arrow">
                                    <span>Читать полностью</span>
                                    <svg class="icon icon--arrow-long" width="30" height="12" viewbox="0 0 30 12">
                                        <use xlink:href="#arrow-long" />
                                    </svg>
                                </span>
                                <span class="news-link-tag"><?= $arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["NAME"] ?></span>
                            </a>
                        </div>
                        <?else:?>
                            <div class="news-item news-item--info ajax-list-item">
                                <a class="news-link" href="<?=$arItem["DETAIL_PAGE_URL"];?>">
                                    <div class="news-headnote"><?=$arItem["PREVIEW_TEXT"];?></div>
                                    <h4><?=$arItem["NAME"];?></h4>
                                    <?if (!empty($arItem["ACTIVE_TO"])) :?>
                                        <span class="news-date">Действует до <?=ConvertDateTime($arItem["ACTIVE_TO"], "D.M.Y");?></span>
                                    <?endif;?>
                                    <span class="text-control text-control--long-arrow">
                                        <span>Читать полностью</span>
                                        <svg class="icon icon--arrow-long" width="30" height="12" viewBox="0 0 30 12">
                                            <use xlink:href="#arrow-long"></use>
                                        </svg>
                                    </span>
                                    <span class="news-link-tag"><?= $arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["NAME"] ?></span>
                                </a>
                            </div>
                        <?endif;?>
                    <?endforeach;?>
                </div>
                <?if($arResult["NAV_RESULT"]->nEndPage > 1 && $arResult["NAV_RESULT"]->NavPageNomer<$arResult["NAV_RESULT"]->nEndPage):?>
                    <div class="pagination-ajax">
                        <div class="pagination-more" data-scroll-fx="data-scroll-fx" id="btn_<?= $bxajaxid ?>">
                            <button class="button button--inverse button--refresh" data-ajax-id="<?= $bxajaxid ?>"
                               data-show-more="<?= $arResult["NAV_RESULT"]->NavNum ?>"
                               data-next-page="<?= ($arResult["NAV_RESULT"]->NavPageNomer + 1) ?>"
                               data-max-page="<?= $arResult["NAV_RESULT"]->nEndPage ?>">
                                <svg class="icon icon--refresh" width="18" height="22" viewbox="0 0 18 22">
                                    <use xlink:href="#refresh" />
                                </svg><span>Показать еще</span>
                            </button>
                        </div>
                    </div>
                <?endif?>
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