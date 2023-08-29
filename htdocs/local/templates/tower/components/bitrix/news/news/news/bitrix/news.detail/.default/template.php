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
?>
<?
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/press_detail.min.css");

/**
 * Переход назад
 */

$sectionCodeUrl = $arResult['SECTION']['CODE'] ? $arResult['SECTION']['CODE'] . '/' : null;
if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
    $urlPieces = explode("/", $_SERVER['HTTP_REFERER']);
    if ($urlPieces[0] === $_SERVER['HTTP_HOST']) {

        $backUrl = 'javascript:history.back()';

    } else {
        $backUrl = SITE_ID == s2 ? "/spb/pressa/"  . ($sectionCodeUrl ? $sectionCodeUrl : null) : "/moskva/pressa/"  . ($sectionCodeUrl ? $sectionCodeUrl : null);

    }
} else {
    $backUrl = SITE_ID == s2 ? "/spb/pressa/"  . ($sectionCodeUrl ? $sectionCodeUrl : null) : "/moskva/pressa/"  . ($sectionCodeUrl ? $sectionCodeUrl : null);
}
$url = "https://towergroup.ru/moskva/pressa/".$arResult["CODE"];
if (SITE_ID == "s2") {
    $APPLICATION->AddHeadString('<link href="'.$url.'/" rel="canonical" />',true);
}
//echo "<pre>"; print_r($arResult["COMMERCIAL_OBJECTS"]); echo "</pre>";
?>
<section class="section-common" data-scroll-fx>
	<div class="container"><a class="text-control text-control--back" href="<?=$backUrl;?>"><svg class="icon icon--arrow-back" width="6" height="9" viewbox="0 0 6 9">
                <use xlink:href="#arrow-back" />
            </svg><span>Вернуться назад</span></a>
        <h1><?=$arResult["NAME"]?></h1>
        <div class="common-content"><img class="lazy" data-src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>">
            <?=$arResult["~DETAIL_TEXT"]?>
        </div>
        <?if ($arResult["OBJECT"] || $arResult["BUILDER"] || $arResult["PROPERTIES"]["TAGS"]):?>
            <div class="press-tags">
                <ul class="list">
                    <? if ($arResult["OBJECT"]): ?>
                        <li class="list__item">
                            <a class="button button--inverse"
                               href="<?= $arResult["OBJECT"]["OBJECT_PAGE_URL"] ?>"
                               target="_blank">
                                Посмотреть объект <?= $arResult["OBJECT"]["NAME"] ?>
                            </a>
                        </li>
                    <? endif; ?>
                    <? if ($arResult["BUILDER"]): ?>
                        <li class="list__item">
                            <a class="button button--inverse"
                               href='<?= $arResult["BUILDER"]["OBJECTS_PAGE_URL"] ?>'
                               target="_blank">
                                Посмотреть все объекты застройщика <?= $arResult["BUILDER"]["UF_NAME"] ?>
                            </a>
                        </li>
                    <? endif; ?>
                    <? if ($arResult["PROPERTIES"]["TAGS"]): ?>
                        <? foreach ($arResult["PROPERTIES"]["TAGS"]["~VALUE"] as $keyTag => $tagLink): ?>
                            <li class="list__item">
                                <a class="button button--inverse"
                                   href='<?= $tagLink ?>'
                                   target="_blank">
                                    <?= $arResult["PROPERTIES"]["TAGS"]["~DESCRIPTION"][$keyTag] ?>
                                </a>
                            </li>
                        <? endforeach; ?>
                    <? endif; ?>
                </ul>
            </div>
        <?endif;?>
    </div>
</section>
<?
if (preg_match('/moskva/', $backUrl)) {
    $cityCode = 'moskva';
} else {
    $cityCode = 'spb';
}
?>
<? if (!empty($arResult["ITEMS"])): ?>
    <section class="section-press-other" data-scroll-fx>
        <div class="container">
            <div class="h1">Предыдущие материалы</div>
            <div class="news">
                <div class="news-list" data-scroll-fx>
                    <? foreach ($arResult["ITEMS"] as $key => $arItem) : ?>
                        <? if ($arItem["PROPERTIES"]["HIGHLIGHT_NEWS"]["VALUE"] !== "Y") : ?>
                            <div class="news-item ajax-list-item" data-scroll-fx>
                                <? $arItem["DETAIL_PAGE_URL"] = str_replace("moskva/", "", $arItem["DETAIL_PAGE_URL"]); ?>
                                <a class="news-link" href="/<?= $cityCode; ?><?= $arItem["DETAIL_PAGE_URL"]; ?>">
                                    <div class="news-item-content">
                                        <div class="div-title div-title-h4"><?= $arItem["NAME"]; ?></div>
                                        <span class="news-item-date"><?= ConvertDateTime($arItem["ACTIVE_FROM"], "D.M.Y"); ?></span>
                                        <p class="news-item-introtext">
                                            <?= $arItem["PREVIEW_TEXT"]; ?>
                                        </p>
                                    </div>
                                    <span class="text-control text-control--long-arrow">
                                    <span>Читать полностью</span>
                                    <svg class="icon icon--arrow-long" width="30" height="12" viewbox="0 0 30 12">
                                        <use xlink:href="#arrow-long"/>
                                    </svg>
                                </span>
                                    <span class="news-link-tag"><?= $arResult["SECTION"]["NAME"] ?></span>
                                </a>
                            </div>
                        <? else: ?>
                            <div class="news-item news-item--info ajax-list-item">
                                <a class="news-link" href="<?= $arItem["DETAIL_PAGE_URL"]; ?>">
                                    <div class="news-headnote"><?= $arItem["PREVIEW_TEXT"]; ?></div>
                                    <h4><?= $arItem["NAME"]; ?></h4>
                                    <? if (!empty($arItem["ACTIVE_TO"])) : ?>
                                        <span class="news-date">Действует до <?= ConvertDateTime($arItem["ACTIVE_TO"], "D.M.Y"); ?></span>
                                    <? endif; ?>
                                    <span class="text-control text-control--long-arrow">
                                        <span>Читать полностью</span>
                                        <svg class="icon icon--arrow-long" width="30" height="12" viewBox="0 0 30 12">
                                            <use xlink:href="#arrow-long"></use>
                                        </svg>
                                    </span>
                                    <span class="news-link-tag"><?= $arResult["SECTION"]["NAME"] ?></span>
                                </a>
                            </div>
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>
