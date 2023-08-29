<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);
?>
<?
/**
 * Параметры стр
 */

$APPLICATION->SetPageProperty("page", 'detail_overseas');
$APPLICATION->SetPageProperty("body-page", "class='-detail-page'");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/detail_overseas.min.css");
$APPLICATION->AddChainItem($arResult["OBJECT"]["FLAT"]["NAME"], '');
$phoneReplaceClass = $APPLICATION->GetPageProperty("PHONE_REPLACE_CLASS");

/**
 * SEO Start
 */

$titleSeo = $arResult["OBJECT"]["FLAT"]["SEO_TITLE"];
$titleDesc = $arResult["OBJECT"]["FLAT"]["SEO_DESCRIPTION"];
$APPLICATION->SetPageProperty("title", $titleSeo);
$APPLICATION->SetPageProperty("description", $titleDesc);

/**
 * SEO end
 */

$GLOBALS['filter_employees'] = array("ID" => $arResult["OBJECT"]["UF_MANAGER"]["ID"]);

$GLOBALS['default_broker'] = array(
    "ID" => $arResult["OBJECT"]["UF_MANAGER"]["ID"],
    "NAME" => $arResult["OBJECT"]["UF_MANAGER"]["NAME"],
    "PICTURE" => $arResult["OBJECT"]["UF_MANAGER"]["PREVIEW_PICTURE"]["SRC"],
    "DEPARTMENT" => $arResult["OBJECT"]["UF_MANAGER"]['PROPERTY_DEPARTMENT_VALUE'],
    "PHONE" => $arResult["OBJECT"]["UF_MANAGER"]['PROPERTY_PHONE_VALUE'],
    "EMAIL" => $arResult["OBJECT"]["UF_MANAGER"]['PROPERTY_EMAIL_VALUE'],
    "WHATSAPP" => $arResult["OBJECT"]["UF_MANAGER"]['PROPERTY_WHATSAPP_VALUE'],
    "TELEGRAM" => $arResult["OBJECT"]["UF_MANAGER"]['PROPERTY_TELEGRAM_VALUE']
);

$arObjectInfo = [
    'object-class' => $arResult["OBJECT"]["FLAT"]["PROPERTY_TYPE_FLATS_VALUE"],
    'object' => $arResult["OBJECT"]["FLAT"]["NAME"],
    'type-of-property' => 'Зарубежная',
    'site-id' => SITE_ID
];

/**
 * Переход назад
 */
if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
    $urlPieces = explode("/", $_SERVER['HTTP_REFERER']);
    if ($urlPieces[0] === $_SERVER['HTTP_HOST']) {

        $backUrl = 'javascript:history.back()';

    } else {
        $backUrl = SITE_ID == s2 ? "/spb/nedvizhimost-za-rubezhom/" : "/moskva/nedvizhimost-za-rubezhom/";

    }
} else {
    $backUrl = SITE_ID == s2 ? "/spb/nedvizhimost-za-rubezhom/" : "/moskva/nedvizhimost-za-rubezhom/";
}

if(!empty($arResult["OBJECT"]["CONTACT"]["PROPERTY_PHONE_OVERSEAS_VALUE"])) {
    $phoneValue = $arResult["OBJECT"]["CONTACT"]["PROPERTY_PHONE_OVERSEAS_VALUE"];
} else {
    $phoneValue = $arResult["OBJECT"]["CONTACT"]["PROPERTY_PHONE_VALUE"];   
}

//xprint($arResult["OBJECT"]["FLAT"]);
$url = "https://towergroup.ru/moskva/nedvizhimost-za-rubezhom/".$arResult["OBJECT"]["FLAT"]["CODE"]."/";
if (SITE_ID == "s2") {
    $APPLICATION->AddHeadString('<link href="'.$url.'" rel="canonical" />',true);
}

$showSchemaOrg = !empty($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_RUB_VALUE"]);
?>

<?php if($showSchemaOrg): ?>
<div itemscope itemtype="http://schema.org/Product">
<? endif; ?>
<section class="section-detail-heading">
    <div class="container container--wide">
        <div class="detail-heading">
            <div class="detail-heading-back"><a href="<?= $backUrl; ?>"><svg class="icon icon--arrow-long" width="30" height="12" viewbox="0 0 30 12">
                        <use xlink:href="#arrow-long" />
                    </svg></a></div>
            <div class="detail-heading-title"><?= $arResult["OBJECT"]["FLAT"]["NAME"]; ?></div>
            <div class="detail-heading-navigation">
                <ul class="list">
                    <li class="list-item list-item--active"><a class="list-link" href="#body" data-scroll-to="data-scroll-to">Характеристики</a></li>
                    <? if (!empty($arResult['OBJECT']["FLAT"]["PROPERTY_MAP_VALUE"])): ?>
                        <li class="list-item"><a class="list-link" href="#infrastructure"
                                                 data-scroll-to="data-scroll-to">Инфраструктура</a></li>
                    <? endif; ?>
                </ul>
            </div>
            <div class="detail-heading-request"><a class="button button--default button--small" href="#broker" data-broker-object-info='<?=json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE)?>' data-modal="data-modal">Записаться на просмотр</a></div>
        </div>
    </div>
</section>
<section class="section-detail-hero-other">
    <div class="container">
        <div class="detail-hero-other">
            <div class="detail-hero-other-info" data-scroll-fx>
                <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","page",Array(
                        "START_FROM" => (SITE_ID == "s2") ? 1 : 0,
                        "PATH" => "",
                    )
                );?>
                <? if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_CITY_VALUE"]) && !empty($arResult["OBJECT"]["FLAT"]["PROPERTY_REGION_VALUE"])): ?>
                    <div class="detail-hero-other-headnote"><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_CITY_VALUE"]; ?>, <?= $arResult["OBJECT"]["FLAT"]["PROPERTY_REGION_VALUE"]; ?></div>
                <? elseif (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_CITY_VALUE"])): ?>
                    <div class="detail-hero-other-headnote">
                        <?= $arResult["OBJECT"]["FLAT"]["PROPERTY_CITY_VALUE"]; ?>
                    </div>
                <? elseif (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_REGION_VALUE"])): ?>
                    <div class="detail-hero-other-headnote">
                        <?= $arResult["OBJECT"]["FLAT"]["PROPERTY_REGION_VALUE"]; ?>
                    </div>
                <? endif; ?>
                <h1<?php if($showSchemaOrg): ?> itemprop="name"<? endif; ?>><?= $arResult["OBJECT"]["FLAT"]["NAME"]; ?></h1>
                <div class="detail-hero-other-params">
                    <?if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"])): ?>
                        <span><?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?>  <?= $arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"]; ?> м²</span>
                    <?endif;?>
                    <?if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_ROOMS_NUMBER_VALUE"])): ?>
                        <span><?= plural_form($arResult["OBJECT"]["FLAT"]["PROPERTY_ROOMS_NUMBER_VALUE"], array("комната","комнаты","комнат")); ?></span>
                    <?endif;?>
                    <?if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_FLOOR_MAX_VALUE"])): ?>
                        <span><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_FLOOR_VALUE"]; ?> этаж из <?= $arResult["OBJECT"]["FLAT"]["PROPERTY_FLOOR_MAX_VALUE"]; ?></span>
                    <?endif;?>
                </div>
                <div class="detail-hero-other-price">
                    <?if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_RUB_VALUE"])): ?>
                        <div class="detail-hero-other-price-value" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                            <meta itemprop="price" content="<?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_RUB_VALUE"], 0, '.', '') ?>">
                            <meta itemprop="priceCurrency" content="RUB">
                            <div class="detail-hero-other-price-large"
                                 data-price-rur="<?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_RUB_VALUE"], 0, '.', ' ') ?> ₽"
                                 data-price-usd="<?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_USD_VALUE"], 0, '.', ' ') ?> USD"
                                 data-price-eur="<?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_EUR_VALUE"], 0, '.', ' ') ?> EUR">
                                <?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_RUB_VALUE"], 0, '.', ' ') ?> ₽
                            </div>
                            <?if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"])):?>
                                <div class="detail-hero-other-price-small"
                                     data-sq-rur="<?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_RUB_VALUE"] / $arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"], 0, '.', ' ') ?> ₽/м²"
                                     data-sq-usd="<?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_USD_VALUE"] / $arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"], 0, '.', ' ') ?> USD/м²"
                                     data-sq-eur="<?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_EUR_VALUE"] / $arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"], 0, '.', ' ') ?> EUR/м²">
                                    <?if($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= number_format($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_RUB_VALUE"] / $arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"], 0, '.', ' ') ?>
                                    ₽/м²
                                </div>
                            <?endif;?>
                        </div>
                        <div class="detail-hero-other-price-currency">
                            <label>
                                <input type="radio" checked name="currency" data-currency="rur"><span>₽</span>
                            </label>
                            <? if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_USD_VALUE"])): ?>
                                <label>
                                    <input type="radio" name="currency" data-currency="usd"><span>$</span>
                                </label>
                            <? endif; ?>
                            <? if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_PRICE_EUR_VALUE"])): ?>
                                <label>
                                    <input type="radio" name="currency" data-currency="eur"><span>€</span>
                                </label>
                            <? endif; ?>
                        </div>
                    <?endif;?>
                </div>
                <div class="detail-hero-other-controls"><a class="button button--light pdf-generate" target="_blank" href="/pdf/generate_pdf.php?id=<?=$arResult["OBJECT"]["FLAT"]["ID"];?>">Скачать презентацию</a><a class="button button--inverse button--phone" href="#backcall-secondary" data-modal><svg class="icon icon--phone" width="17" height="17" viewbox="0 0 17 17">
                            <use xlink:href="#phone" />
                        </svg><span class="<?= $phoneReplaceClass ?>"><?= $phoneValue; ?></span></a></div>
            </div>
            <div class="detail-hero-other-media" data-scroll-fx>
                <? if(count($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_VALUE"]) > 1): ?>
                    <div class="slider slider--thumbs" data-slider-media>
                        <div class="slider-controls">
                            <div class="swiper-button-prev"><svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                    <use xlink:href="#arrow" />
                                </svg></div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"><svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                    <use xlink:href="#arrow" />
                                </svg></div>
                        </div>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <? foreach ($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_RESIZE_VALUE"] as $picture): ?>
                                    <div class="swiper-slide swiper-lazy" data-background="<?= $picture; ?>"><a href="#object" data-modal data-object-modal="gallery"></a></div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                <? elseif(!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_VALUE"][0])): ?>
                    <div class="detail-hero-other-media-image lazy" data-bg="<?= $arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_VALUE"][0]; ?>"><a href="#object" data-modal data-object-modal="image"></a></div>
                <? else: ?>
                    <div class="detail-hero-other-media-no-image">
                        <div>На данный момент фотографии этого объекта отсутствуют</div>
                    </div>
                <? endif; ?>
                <div class="detail-hero-other-media-navigation">
                    <? if(count($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_MINI_VALUE"]) > 1): ?>
                        <div class="detail-hero-other-media-thumbs">
                            <div class="swiper-container" data-slider-media-thumb>
                                <div class="swiper-wrapper">
                                    <?
                                    $i = 1;
                                    foreach ($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_MINI_VALUE"] as $picture):
                                        ?>
                                        <div class="swiper-slide swiper-lazy <?= $i == 1 ? 'slide-thumb-active' : ''?>" data-background="<?= $picture; ?>"></div>
                                        <?
                                        $i++;
                                    endforeach; ?>
                                </div>
                            </div>
                            <div class="swiper-button-prev"><svg class="icon icon--arrow" width="12" height="9" viewbox="0 0 12 9">
                                    <use xlink:href="#arrow" />
                                </svg></div>
                            <div class="swiper-button-next"><svg class="icon icon--arrow" width="12" height="9" viewbox="0 0 12 9">
                                    <use xlink:href="#arrow" />
                                </svg></div>
                        </div>
                    <? endif; ?>
                    <ul class="list">
                        <? if ($arResult["OBJECT"]["FLAT"]["DETAIL_PICTURE"]["SRC"]): ?>
                            <li class="list-item">
                                <a class="list-link" href="#object" data-modal data-object-modal="plan">
                                    <div class="list-item-icon">
                                        <svg class="icon icon--plan" width="16" height="16" viewbox="0 0 16 16">
                                            <use xlink:href="#plan"/>
                                        </svg>
                                    </div>
                                    <span>Планировка</span>
                                </a>
                            </li>
                        <? endif; ?>
                        <? if ($arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_VALUE"]): ?>
                            <li class="list-item">
                                <a class="list-link" href="#object" data-modal data-object-modal="map">
                                    <div class="list-item-icon">
                                        <svg class="icon icon--map-icon" width="16" height="16" viewbox="0 0 16 16">
                                            <use xlink:href="#map-icon"/>
                                        </svg>
                                    </div>
                                    <span>Карта</span>
                                </a>
                            </li>
                        <? endif; ?>
                        <? if ($arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_STREET_VALUE"]): ?>
                            <li class="list-item">
                                <a class="list-link" href="#object" data-modal data-object-modal="street">
                                    <div class="list-item-icon">
                                        <svg class="icon icon--street" width="16" height="16" viewbox="0 0 16 16">
                                            <use xlink:href="#street"/>
                                        </svg>
                                    </div>
                                    <span>Улица</span>
                                </a>
                            </li>
                        <? endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-detail-params">
    <div class="container">
        <div class="detail-params">
            <div class="detail-params-list" data-scroll-fx>
                <h2 class="h1">Характеристики</h2>
                <ul class="list">
                    <? if($arResult["OBJECT"]["FLAT"]["PROPERTY_FEATURE_VALUE"]): ?>
                        <li class="list-item">
                            <div class="list-item-label">Признак</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_FEATURE_VALUE"]; ?></div>
                        </li>
                    <? endif; ?>
                    <? if($arResult["OBJECT"]["FLAT"]["PROPERTY_TYPE_FLATS_VALUE"]): ?>
                        <li class="list-item">
                            <div class="list-item-label">Тип квартиры</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_TYPE_FLATS_VALUE"]; ?></div>
                        </li>
                    <? endif; ?>
                    <? if($arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"]): ?>
                        <li class="list-item">
                            <div class="list-item-label">Площадь</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_SQUARE_VALUE"]; ?> м²</div>
                        </li>
                    <? endif; ?>
                    <? if($arResult["OBJECT"]["FLAT"]["PROPERTY_ROOMS_NUMBER_VALUE"]): ?>
                        <li class="list-item">
                            <div class="list-item-label">Количество комнат</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_ROOMS_NUMBER_VALUE"]; ?></div>
                        </li>
                    <? endif; ?>
                    <? if($arResult["OBJECT"]["FLAT"]["PROPERTY_FLOOR_VALUE"]): ?>
                        <li class="list-item">
                            <div class="list-item-label">Этаж/этажей в доме</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_FLOOR_VALUE"]; ?>/<?= $arResult["OBJECT"]["FLAT"]["PROPERTY_FLOOR_MAX_VALUE"]; ?></div>
                        </li>
                    <? endif; ?>
                    <? if($arResult["OBJECT"]["FLAT"]["PROPERTY_DEADLINE_YEAR_VALUE"]): ?>
                        <li class="list-item">
                            <div class="list-item-label">Готовность дома</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_DEADLINE_YEAR_VALUE"]; ?></div>
                        </li>
                    <? endif; ?>
                    <li class="list-item">
                        <div class="list-item-label">Отделка</div>
                        <div class="list-item-value"><?= $arResult["OBJECT"]["FLAT"]["PROPERTY_DECORATION_VALUE"] ? $arResult["OBJECT"]["FLAT"]["PROPERTY_DECORATION_VALUE"] : 'Без отделки'; ?></div>
                    </li>
                </ul>
            </div>
            <div class="detail-params-text" data-scroll-fx<?php if($showSchemaOrg): ?> itemprop="description"<? endif; ?>>
                <?= $arResult["OBJECT"]["FLAT"]["~DETAIL_TEXT"]; ?>
            </div>
        </div>
    </div>
</section>
<?php if($showSchemaOrg): ?>
</div>
<?php endif; ?>

<section class="section-form-inline" data-scroll-fx="data-scroll-fx">
    <div class="container">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/forms/form_reserve.php",
                "EDIT_TEMPLATE" => "",
                "FORM_CODE" => "reserve-form",
                "OBJECT" => json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE),
            ),
            false
        );?>
    </div>
</section>
<? if (!empty($arResult['OBJECT']["FLAT"]["PROPERTY_MAP_VALUE"])): ?>
    <section class="section-detail-infrastructure" id="infrastructure" data-anchor="data-anchor">
        <div class="infrastructure-map" data-scroll-fx="data-scroll-fx">
            <div class="container">
                <h2 class="h1">Инфраструктура</h2>
            </div>
            <div class="infrastructure-map-holder">
                <div id="map"></div>
                <div class="infrastructure-map-filters">
                    <ul class="list" data-map-filter="data-map-filter"></ul>
                </div>
            </div>
            <div class="infrastructure-map-text" data-scroll-fx="data-scroll-fx">
                <div class="container">
                    <?= $arResult["OBJECT"]["FLAT"]['PROPERTY_INFRASTRUCTURE_DESCRIPTION_VALUE']
                        ? $arResult["OBJECT"]["FLAT"]['~PROPERTY_INFRASTRUCTURE_DESCRIPTION_VALUE']['TEXT']
                        : $arResult["OBJECT"]["FLAT"]['PROPERTY_REGION_DESCRIPTION']; ?>
                </div>
            </div>
            <div class="infrastructure-map-text-mobile">
                <div class="container">
                    <div class="detail-text" data-detail-text="data-detail-text">
                        <div class="detail-text-cols">
                            <?if ($arResult["OBJECT"]["FLAT"]['PROPERTY_INFRASTRUCTURE_DESCRIPTION_VALUE']) : ?>
                                <?= str_replace('<div>','<div class="detail-text-cols-item">',$arResult["OBJECT"]["FLAT"]['~PROPERTY_INFRASTRUCTURE_DESCRIPTION_VALUE']['TEXT']) ?>
                            <?else:?>
                                <?= $arResult["OBJECT"]["FLAT"]['PROPERTY_REGION_DESCRIPTION']; ?>
                            <?endif;?>
                        </div>
                        <button class="button button--inline" data-detail-text-show="data-detail-text-show">Читать далее</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>
<? if(!empty($arResult['SIMILAR_OBJECTS'])): ?>
    <section class="section-detail-other">
        <div class="container" data-scroll-fx>
            <h3 class="h1">Похожие объекты</h3>
            <div class="catalog">
                <div class="catalog-list catalog-list--other">
                    <? foreach ($arResult['SIMILAR_OBJECTS'] as $object):?>
                        <div class="catalog-item">
                            <a class="object-other" href="<?= $object['SECTION_PAGE_URL']; ?>">
                                <? if(empty($object['PROPERTY_SLIDER_MINI_VALUE'])): ?>
                                    <div class="object-other-image-empty">
                                        <div>На данный момент фотографии этого объекта отсутствуют</div>
                                    </div>
                                <? else: ?>
                                    <div class="object-other-image lazy" data-bg="<?= $object['PROPERTY_SLIDER_MINI_VALUE']; ?>" alt="<?= $object['NAME']; ?>"></div>
                                <? endif; ?>
                                <div class="object-other-info">
                                    <h4><?= $object['NAME']; ?></h4>
                                    <div class="object-other-data"><span><?if($object["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?= $object['PROPERTY_SQUARE_VALUE']; ?> м²</span><span><?= $object['PROPERTY_ROOMS_NUMBER_VALUE']; ?> комнаты</span><span><?= $object['PROPERTY_DECORATION_VALUE'] ? $object['PROPERTY_DECORATION_VALUE'] : 'Без отделки'; ?></span></div>
                                    <div class="object-other-price"><?if($object["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?=number_format($object["PROPERTY_PRICE_RUB_VALUE"], 0, '.', ' ')?> ₽</div>
                                </div>
                            </a>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>
<?if (!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_DECORATION_VALUE"]) && !empty($arResult["INFRASTRUCTURE_OBJECTS"]) && !empty($arResult["SIMILAR_OBJECTS"])) :?>
    <section class="section-form-inline" data-scroll-fx="data-scroll-fx">
        <div class="container">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => "/local/include/forms/form_help.php",
                    "EDIT_TEMPLATE" => "",
                    "FORM_CODE" => "help-form",
                    "OBJECT" => json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE),
                ),
                false
            );?>
        </div>
    </section>
<?endif;?>

<div class="modal modal--object-info" id="object">
    <div class="modal-container"><button class="modal-close" data-modal-close="data-modal-close"><svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                <use xlink:href="#cross-light-large" />
            </svg></button>
        <div class="object-other-modal">
            <div class="object-other-modal-heading">
                <ul class="list">
                    <? if ($arResult["OBJECT"]["FLAT"]["PROPERTY_PHOTOS_VALUE"]): ?>
                        <li class="list-item" data-object-tab="<?= count($arResult["OBJECT"]["FLAT"]["PROPERTY_PHOTOS_VALUE"]) > 1 ? 'gallery' : 'image'; ?>">Фото</li>
                    <? endif; ?>
                    <? if ($arResult["OBJECT"]["FLAT"]["DETAIL_PICTURE"]["SRC"]): ?>
                        <li class="list-item" data-object-tab="plan">Планировки</li>
                    <? endif; ?>
                    <? if ($arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_VALUE"]): ?>
                        <li class="list-item" data-object-tab="map">Карта</li>
                    <? endif; ?>
                    <? if ($arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_STREET_VALUE"]): ?>
                        <li class="list-item" data-object-tab="street">Просмотр улицы</li>
                    <? endif; ?>
                </ul>
            </div>
            <div class="object-other-modal-body">
                <? if(count($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_VALUE"]) > 1): ?>
                    <div class="object-other-modal-item">
                        <div class="object-other-modal-gallery">
                            <div class="object-other-modal-gallery-main">
                                <div class="slider slider--thumbs" data-gallery-modal-main="data-gallery-modal-main">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            <? foreach ($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_VALUE"] as $picture): ?>
                                                <div class="swiper-slide"><img class="swiper-lazy" data-src="<?= $picture; ?>"></div>
                                            <? endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="slider-controls">
                                        <div class="swiper-button-prev"><svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                                <use xlink:href="#arrow" />
                                            </svg></div>
                                        <div class="swiper-button-next"><svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                                <use xlink:href="#arrow" />
                                            </svg></div>
                                    </div>
                                </div>
                            </div>
                            <div class="object-other-modal-gallery-thumbs" data-gallery-modal-thumb="data-gallery-modal-thumb">
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                                        <?
                                        $i = 1;
                                        foreach ($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_MINI_VALUE"] as $picture):
                                            ?>
                                            <div class="swiper-slide swiper-lazy <?= $i == 1 ? 'slide-thumb-active' : ''?>" data-background="<?= $picture; ?>"></div>
                                            <?
                                            $i++;
                                        endforeach; ?>
                                    </div>
                                </div><button data-gallery-modal-toggle="data-gallery-modal-toggle">
                                    <div><svg class="icon icon--photo" width="16" height="16" viewbox="0 0 16 16">
                                            <use xlink:href="#photo" />
                                        </svg></div><span>Все фото</span>
                                </button>
                                <div class="swiper-button-prev"><svg class="icon icon--arrow" width="12" height="9" viewbox="0 0 12 9">
                                        <use xlink:href="#arrow" />
                                    </svg></div>
                                <div class="swiper-button-next"><svg class="icon icon--arrow" width="12" height="9" viewbox="0 0 12 9">
                                        <use xlink:href="#arrow" />
                                    </svg></div>
                            </div>
                        </div>
                        <div class="object-other-modal-gallery-list">
                            <ul class="list">
                                <? foreach ($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_VALUE"] as $picture): ?>
                                    <li class="list-item"><a class="list-link" href="#" style="background-image: url('<?= $picture; ?>')"></a></li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <? elseif(!empty($arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_VALUE"][0])): ?>
                    <div class="object-other-modal-item">
                        <div class="object-other-modal-image"><img src="<?= $arResult["OBJECT"]["FLAT"]["PROPERTY_SLIDER_VALUE"][0]; ?>"></div>
                    </div>
                <? endif; ?>
                <? if ($arResult["OBJECT"]["FLAT"]["DETAIL_PICTURE"]["SRC"]): ?>
                    <div class="object-other-modal-item">
                        <div class="object-other-modal-plan"><img src="<?= $arResult["OBJECT"]["FLAT"]["DETAIL_PICTURE"]["SRC"]; ?>"></div>
                    </div>
                <? endif; ?>
                <? if ($arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_VALUE"]): ?>
                    <div class="object-other-modal-item">
                        <div id="map-object"></div>
                    </div>
                <? endif; ?>
                <? if ($arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_STREET_VALUE"]): ?>
                    <div class="object-other-modal-item">
                        <div id="streetobject"></div>
                    </div>
                <? endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=82e46027-5420-42fe-ba21-2755432ad262"></script>
<script>
    var hrefPdf = $('.pdf-generate').attr('href');
    $('[name=currency]').on('click', function() {

        var currency = $(this).data('currency');
        var dataPrice = 'price-' + currency;
        var dataSquare = 'sq-' + currency;
        var price = $('.detail-hero-other-price-large').data(dataPrice);
        var priceSquare = $('.detail-hero-other-price-small').data(dataSquare);


        $('.detail-hero-other-price-large').html(price);
        $('.detail-hero-other-price-small').html(priceSquare);
        $('.pdf-generate').attr('href', hrefPdf + '&currency=' + currency);
    });
</script>
<script>
    window.mapOptions = {
        center: [<?= $arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_VALUE"]; ?>],
        zoom: 17,
        objectArea: [
            <?= $arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_BORDER_VALUE"] ? $arResult["OBJECT"]["FLAT"]["~PROPERTY_MAP_BORDER_VALUE"]["TEXT"] : null; ?>
        ],
        iconsFolder: '<?= SITE_TEMPLATE_PATH; ?>/img/detail/new_markers/',
        searchRadius: 400
    }
    <?if (!empty($arResult['INFRASTRUCTURE_OBJECTS'])):?>
    window.mapGroups = [
        <? foreach ($arResult['INFRASTRUCTURE_OBJECTS']['CATEGORY'] as $item): ?>
        <?if ($item['CODE'] == 'vse') continue; ?>
        {
            groupTitle: '<?= $item['NAME']; ?>',
            key: '<?= $item['CODE']; ?>',
            icon: '<?= $item['UF_CATEGORY_ICON']['SRC']; ?>',
            searchQuery: '<?= $item['UF_CATEGORY_SEARCH_QUERY']; ?>',
            customMarkers: [
                <?if (!empty($item['ITEMS'])):?>
                <?foreach ($item['ITEMS'] as $childItem):?>
                [<?=$childItem['COORDS'];?>],
                <?endforeach;?>
                <?endif;?>
            ]
        },
        <? endforeach; ?>
    ]
    <?else:?>
    window.mapGroups = [
        {
            groupTitle: 'Школы',
            icon: '<svg width="22" height="22" xmlns="http://www.w3.org/2000/svg"><path d="m19.7 9.34-.918-.503-8.25-4.584h-.1a.973.973 0 0 0-.175-.055H9.918a1.073 1.073 0 0 0-.183.055h-.1l-8.25 4.584a.917.917 0 0 0 0 1.595l2.282 1.265v4.345a2.75 2.75 0 0 0 2.75 2.75h7.333a2.75 2.75 0 0 0 2.75-2.75v-4.345l1.833-1.027v2.622a.917.917 0 1 0 1.834 0v-3.154a.916.916 0 0 0-.468-.797Zm-5.033 6.702a.916.916 0 0 1-.917.916H6.417a.917.917 0 0 1-.917-.916v-3.328l4.134 2.292.138.055h.082c.076.01.153.01.23 0 .075.01.152.01.229 0h.082a.433.433 0 0 0 .138-.055l4.134-2.292v3.328Zm-4.584-2.879L3.722 9.625l6.361-3.538 6.362 3.538-6.362 3.538Z"/></svg>',
            key: 'schools',
            searchQuery: 'Общеобразовательная школа',
            customMarkers: []
        },
        {
            groupTitle: 'Детские сады',
            icon: '<svg width="22" height="22" xmlns="http://www.w3.org/2000/svg"><path d="M17.417 14.667a2.75 2.75 0 1 0 0 5.499 2.75 2.75 0 0 0 0-5.5Zm0 3.666a.916.916 0 1 1 0-1.832.916.916 0 0 1 0 1.832ZM8.25 14.667a2.75 2.75 0 1 0 0 5.5 2.75 2.75 0 0 0 0-5.5Zm0 3.666a.916.916 0 1 1 0-1.832.916.916 0 0 1 0 1.832ZM20.167 7.792a5.967 5.967 0 0 0-5.959-5.959h-.458a.916.916 0 0 0-.917.917v4.583h-5.94l-1.145-3.07a.917.917 0 0 0-.862-.596H2.75a.917.917 0 1 0 0 1.833h1.503L5.4 8.59l.468 1.255v.082a5.904 5.904 0 0 0 5.591 3.823h2.75a5.95 5.95 0 0 0 5.959-5.958Zm-3.044 2.915a4.098 4.098 0 0 1-2.915 1.21h-2.75A4.097 4.097 0 0 1 7.645 9.35a.128.128 0 0 1 0-.055l-.073-.128h10.523c-.2.581-.532 1.109-.972 1.54Zm-2.456-3.374V3.667a4.116 4.116 0 0 1 3.666 3.666h-3.666Z"/></svg>',
            key: 'kindergartens',
            searchQuery: 'Детский сад',
            customMarkers: []
        },
        {
            groupTitle: 'Спорт',
            icon: '<svg width="22" height="22" xmlns="http://www.w3.org/2000/svg"><path d="M11 1.833a9.167 9.167 0 0 0-7.7 4.208 9.167 9.167 0 0 0 13.438 12.1A9.167 9.167 0 0 0 11 1.833Zm1.833 2.072a7.333 7.333 0 0 1 5.262 5.262 8.965 8.965 0 0 0-4.345 1.265 14.394 14.394 0 0 0-2.2-2.026 9.222 9.222 0 0 0 1.283-4.501ZM11 3.667a7.242 7.242 0 0 1-1.045 3.73c-.137-.073-.266-.155-.403-.22a14.227 14.227 0 0 0-3.75-1.347A7.333 7.333 0 0 1 11 3.667ZM4.583 7.48c1.438.2 2.83.646 4.116 1.32l.12.073A7.269 7.269 0 0 1 3.666 11a7.333 7.333 0 0 1 .916-3.52Zm4.584 10.615a7.333 7.333 0 0 1-5.262-5.262 9.075 9.075 0 0 0 6.49-2.942c.663.49 1.277 1.042 1.833 1.65a9.168 9.168 0 0 0-3.061 6.554Zm1.833.238a7.334 7.334 0 0 1 2.347-5.362c.055.073.11.137.155.21a12.833 12.833 0 0 1 1.687 3.832A7.27 7.27 0 0 1 11 18.333Zm5.692-2.75a14.67 14.67 0 0 0-1.65-3.437l-.192-.23A7.27 7.27 0 0 1 18.333 11a7.333 7.333 0 0 1-1.64 4.583Z"/></svg>',
            key: 'sport',
            searchQuery: 'Спорт',
            customMarkers: [
            ]
        },
        {
            groupTitle: 'Магазины',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" xml:space="preserve"><path d="M18 21c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM8 21c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm9.1-6h-8c-.6 0-1.2-.2-1.7-.6-.5-.4-.8-.9-.9-1.5L5.1 6.3v-.1L4.5 3H2c-.6 0-1-.4-1-1s.4-1 1-1h3.3c.5 0 .9.3 1 .8L6.9 5H20c.3 0 .6.1.8.4.2.2.2.5.2.8l-1.3 6.7c-.1.6-.5 1.1-.9 1.5-.5.4-1.1.6-1.7.6zM7.3 7l1.1 5.5c0 .1.1.3.2.3.2.2.3.2.5.2h8c.2 0 .3 0 .4-.1.1-.1.2-.2.2-.3L18.8 7H7.3z"/></svg>',
            key: 'shops',
            searchQuery: 'Магазин',
            customMarkers: []
        },
        {
            groupTitle: 'Аптеки',
            icon: '<svg width="22" height="22" xmlns="http://www.w3.org/2000/svg"><path d="M12.833 10.083h-.916v-.916a.917.917 0 0 0-1.834 0v.916h-.916a.917.917 0 0 0 0 1.834h.916v.916a.917.917 0 0 0 1.834 0v-.916h.916a.917.917 0 0 0 0-1.834Zm5.647-5.5a5.766 5.766 0 0 0-7.48-.54 5.747 5.747 0 0 0-7.48 8.69l5.5 5.545a2.75 2.75 0 0 0 3.887 0l5.5-5.545a5.746 5.746 0 0 0 .073-8.15Zm-1.293 6.839-5.5 5.5a.916.916 0 0 1-1.301 0l-5.5-5.5a3.933 3.933 0 0 1 0-5.5 3.914 3.914 0 0 1 5.5 0 .917.917 0 0 0 1.302 0 3.914 3.914 0 0 1 5.5 0 3.932 3.932 0 0 1 0 5.518v-.018Z"/></svg>',
            key: 'pharmacy',
            searchQuery: 'Аптека',
            customMarkers: []
        }
    ]
    <?endif;?>
</script>

<script>
    window.mapObject = {
        coords: [<?= $arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_VALUE"]; ?>],
        zoom: 13,
        markerIcon: '<?= SITE_TEMPLATE_PATH; ?>/img/static-map-marker.svg'
    }
</script>

<script>
    var panoramaInit = false
    window.addEventListener('modalAfterOpen', function(event)
    {
        if (event.detail.id === 'object' && !panoramaInit)
        {
            ymaps.ready(function()
            {
                ymaps.panorama.locate([<?= $arResult["OBJECT"]["FLAT"]["PROPERTY_MAP_STREET_VALUE"]; ?>]).done(function(panoramas)
                {
                    if (panoramas.length > 0)
                    {
                        var player = new ymaps.panorama.Player('streetobject', panoramas[0],
                            {
                                controls: ['zoomControl']
                            })
                    }
                })
            })
            panoramaInit = true
        }
    });
    jQuery(document).on('click', '[data-broker-object-info]', function () {
        var arr = $(this).attr('data-broker-object-info');

        if (!$("#viewing-form input:first").is("[name='tipObject']")){
            $("#viewing-form").prepend("<input hidden='' type='text' name='tipObject' value='"+arr+"' placeholder=''>");
        } else {
            $("#viewing-form input[name='tipObject']").val(arr);
        }
    });
    jQuery(document).on('click', '[href="#backcall"]', function () {
        var arr = '<?=json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE)?>';

        if (!$("#backcall-form input:first").is("[name='tipObject']")){
            $("#backcall-form").prepend("<input hidden='' type='text' name='tipObject' value='"+arr+"' placeholder=''>");
        } else {
            $("#backcall-form input[name='tipObject']").val(arr);
        }
    });
    jQuery(document).on('click', '[href="#backcall-secondary"]', function () {
        var arr = '<?=json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE)?>';

        if (!$("#backcall-form-secondary input:first").is("[name='tipObject']")){
            $("#backcall-form-secondary ").prepend("<input hidden='' type='text' name='tipObject' value='"+arr+"' placeholder=''>");
        } else {
            $("#backcall-form-secondary input[name='tipObject']").val(arr);
        }
    });
</script>