<? if (!empty($arResult["OBJECT"]["UF_CONST_PROGRESS"])) : ?>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
<? endif; ?>

<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);
?>
<?
/**
 * Параметры стр
 */

$phoneReplaceClass = $APPLICATION->GetPageProperty("PHONE_REPLACE_CLASS");

$arObjectInfo = [
    'object-class' => $arResult["OBJECT"]["UF_CLASS_NAME"],
    'object' => $arResult["OBJECT"]["NAME"],
    'type-of-property' => 'Новостройка',
    'site-id' => SITE_ID,
    'city' => $arParams['CITY_CODE']
];

if ($arResult["OBJECT"]["UF_PROJECT_LOGO"] || $arResult["OBJECT"]["UF_BUILD_LOGO"]) {
    $projectLogo = $arResult["OBJECT"]["UF_PROJECT_LOGO"] ? $arResult["OBJECT"]["UF_PROJECT_LOGO"]["SRC"] : $arResult["OBJECT"]["UF_BUILD_LOGO"]["SRC"];
    $APPLICATION->SetPageProperty("logo-object", '<div class="header-brand"><img src="'.$projectLogo.'" alt></div>');
}


if (SITE_ID == "s1" || SITE_ID == "s2") {
    $APPLICATION->SetPageProperty("page", 'detail_new_build');
    $APPLICATION->SetPageProperty("body-page", "class='-detail-page'");
    $innerMenu = '<div class="header-innermenu"><button class="text-control text-control--dropdown" data-main-main-toggle><span>Разделы сайта</span><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
										<use xlink:href="#dropdown" />
									</svg></button></div>';
    $dataInnerMenu .= '<ul class="list" data-menu-inner>
                    <li class="list-item list-item--active"><a class="list-link" href="#description"
                                                               data-scroll-to="data-scroll-to">Описание объекта</a></li>';
    if (!empty($arResult["OBJECT"]["UF_PHOTOS"])) {
        $dataInnerMenu .= '<li class="list-item"><a class="list-link" href="#gallery" data-scroll-to="data-scroll-to">Фотогалерея</a>
                        </li>';
    }

    if (!empty($arResult["OBJECT"]["UF_PROJECT_DOCS"])) {
        $dataInnerMenu .= '<li class="list-item"><a class="list-link" href="#project" data-scroll-to="data-scroll-to">Проектная документация</a>
                        </li>';
    }

    if ($arResult["FILTER_RANGES"]["ALL_COUNT"] > 0) {
        $dataInnerMenu .= '<li class="list-item"><a class="list-link" href="#flats" data-scroll-to="data-scroll-to">Планировки</a>
                        </li>';
    }
    if (!empty($arResult["OBJECT"]["UF_DECORATION_IMAGE"])) {
        $dataInnerMenu .= '<li class="list-item"><a class="list-link" href="#about" data-scroll-to="data-scroll-to">О жилом
                                комплексе</a></li>';
    }

    if (!empty($arResult["OBJECT"]["UF_COORD"])) {
        $dataInnerMenu .= '<li class="list-item"><a class="list-link" href="#infrastructure"
                                                 data-scroll-to="data-scroll-to">Инфраструктура</a></li>';
    }
    $dataInnerMenu .= '</ul>';
    $APPLICATION->SetPageProperty("menu-inner", $innerMenu);
    $APPLICATION->SetPageProperty("inner-menu", "true");
    $APPLICATION->SetPageProperty("data-inner-menu", $dataInnerMenu);
    $this->addExternalCss(SITE_TEMPLATE_PATH."/css/detail_new_build.min.css");

    if (!empty($arResult["SEO"]["PROPERTY_PHONE_NEW_BUILD_VALUE"])) {
        $phoneValue = $arResult["SEO"]["PROPERTY_PHONE_NEW_BUILD_VALUE"];
    } else {
        $phoneValue = $arResult["SEO"]["PROPERTY_PHONE_VALUE"];
    }
} else {
    $APPLICATION->SetPageProperty("page", 'landing');
    //$APPLICATION->SetPageProperty("logo-object", '<div class="header-brand" style="display: none"><img src="'.SITE_TEMPLATE_PATH.'/img/brand.png" alt></div>');
    $APPLICATION->SetPageProperty("body-page", "class='-landing-page'");
    //$APPLICATION->SetPageProperty("body-page", "class='-detail-page'");
    //$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/detail_new_build.min.css");
    $this->addExternalCss(SITE_TEMPLATE_PATH."/css/landing.min.css");
    $landingMenu .= '<div class="detail-heading-navigation">';
    $landingMenu .= '<ul class="list">
                    <li class="list-item list-item--active"><a class="list-link" href="#description"
                                                               data-scroll-to="data-scroll-to">Описание объекта</a></li>';
    if (!empty($arResult["OBJECT"]["UF_PHOTOS"])) {
        $landingMenu .= '<li class="list-item"><a class="list-link" href="#gallery" data-scroll-to="data-scroll-to">Фотогалерея</a>
                        </li>';
    }

    if ($arResult["FILTER_RANGES"]["ALL_COUNT"] > 0) {
        $landingMenu .= '<li class="list-item"><a class="list-link" href="#flats" data-scroll-to="data-scroll-to">Планировки</a>
                        </li>';
    }
    if (!empty($arResult["OBJECT"]["UF_DECORATION_IMAGE"])) {
        $landingMenu .= '<li class="list-item"><a class="list-link" href="#about" data-scroll-to="data-scroll-to">О жилом
                                комплексе</a></li>';
    }

    if (!empty($arResult["OBJECT"]["UF_COORD"])) {
        $landingMenu .= '<li class="list-item"><a class="list-link" href="#infrastructure"
                                                 data-scroll-to="data-scroll-to">Инфраструктура</a></li>';
    }
    $landingMenu .= '</ul>';
    $landingMenu .= '</div>';
    $landingMenu .= "<div class='detail-heading-request'><a class='button button--default button--small' href='#broker'
                                                   data-broker-object-info='".json_encode($arObjectInfo,
            JSON_UNESCAPED_UNICODE)."' data-modal='data-modal'>Записаться на
                    просмотр</a></div>";
    $APPLICATION->SetPageProperty("landing-menu", $landingMenu);

    $phoneValueLanding = getIDMetrics(SITE_ID);
    if (!empty($phoneValueLanding["PROPERTY_PHONE_LANDING_VALUE"])) {
        $phoneValue = $phoneValueLanding["PROPERTY_PHONE_LANDING_VALUE"];
    } else {
        $phoneValue = $arResult["SEO"]["PROPERTY_PHONE_VALUE"];
    }
    if (!empty($phoneValueLanding["PROPERTY_PHONE_REPLACE_VALUE"])) {
        $phoneReplaceClass = $phoneValueLanding["PROPERTY_PHONE_REPLACE_VALUE"];
    }
}
//$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/detail_new_build.min.css");
//$APPLICATION->AddChainItem("Новостройки", SITE_ID == s2 ? "/spb/novostroyki/" : "/moskva/novostroyki/");
$APPLICATION->AddChainItem($arResult["OBJECT"]["NAME"], "");
/**
 * SEO Start
 */

$titleSeo = $arResult["OBJECT"]["UF_TITLE"];
$titleDesc = $arResult["OBJECT"]["UF_DESCRIPTION"];

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


/**
 * Переход назад
 */
if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
    $urlPieces = explode("/", $_SERVER['HTTP_REFERER']);
    if ($urlPieces[0] === $_SERVER['HTTP_HOST']) {

        $backUrl = 'javascript:history.back()';

    } else {
        $backUrl = SITE_ID == s2 ? "/spb/novostroyki/" : "/moskva/novostroyki/";

    }
} else {
    $backUrl = SITE_ID == s2 ? "/spb/novostroyki/" : "/moskva/novostroyki/";
}
if (!empty($urlPieces[5]) && $backUrl != 'javascript:history.back()') {
    $backUrl .= $urlPieces[5];
}
$countAdvantage = 0;

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/include/room_types.php')) {
    include_once($_SERVER['DOCUMENT_ROOT'].'/local/include/room_types.php');
}

?>

<? if ($APPLICATION->GetPageProperty("page") === "landing"): ?>
    <? $this->SetViewTarget('header_controls_phone'); ?>
    <a class="header-controls-phone" href="#landing-backcall" data-broker-object-info='<?= json_encode($arObjectInfo,
        JSON_UNESCAPED_UNICODE) ?>' data-modal>
        <svg class="icon icon--phone" width="17" height="17" viewbox="0 0 17 17">
            <use xlink:href="#phone"/>
        </svg>
    </a>
    <? $this->EndViewTarget(); ?>
<? endif; ?>
<? if ($APPLICATION->GetPageProperty("page") !== "landing"): ?>
    <section class="section-detail-heading">
        <div class="container container--wide">
            <div class="detail-heading">
                <div class="detail-heading-title"><?= $arResult["OBJECT"]["NAME"]; ?></div>
                <div class="detail-heading-navigation">
                    <ul class="list">
                        <li class="list-item list-item--active"><a class="list-link" href="#description"
                                                                   data-scroll-to="data-scroll-to">Описание объекта</a>
                        </li>
                        <? if (!empty($arResult["OBJECT"]["UF_PHOTOS"])) : ?>
                            <li class="list-item"><a class="list-link" href="#gallery" data-scroll-to="data-scroll-to">Фотогалерея</a>
                            </li>
                        <? endif; ?>
                        <? if ($arResult["FILTER_RANGES"]["ALL_COUNT"] > 0): ?>
                            <li class="list-item"><a class="list-link" href="#flats" data-scroll-to="data-scroll-to">Планировки</a>
                            </li>
                        <? endif; ?>
                        <? if (!empty($arResult["OBJECT"]["UF_DECORATION_IMAGE"])) : ?>
                            <li class="list-item"><a class="list-link" href="#about" data-scroll-to="data-scroll-to">О
                                    жилом
                                    комплексе</a></li>
                        <? endif; ?>
                        <? if (!empty($arResult["OBJECT"]["UF_COORD"])): ?>
                            <li class="list-item"><a class="list-link" href="#infrastructure"
                                                     data-scroll-to="data-scroll-to">Инфраструктура</a></li>
                        <? endif; ?>
                    </ul>
                </div>
                <div class="detail-heading-request"><a class="button button--default button--small" href="#broker"
                                                       data-broker-object-info='<?= json_encode($arObjectInfo,
                                                           JSON_UNESCAPED_UNICODE) ?>' data-modal="data-modal">Записаться
                        на
                        просмотр</a></div>
            </div>
        </div>
    </section>
<? endif; ?>
<section class="section-detail-hero" data-scroll-fx>
    <div class="detail-hero lazy"
         data-bg="<?= $arResult["OBJECT"]["DETAIL_PICTURE"]["SRC"] ? $arResult["OBJECT"]["DETAIL_PICTURE"]["SRC"] : $arResult["OBJECT"]["PICTURE"]["SRC"] ?>">
        <div class="container">
            <div class="detail-hero-crumbs">
                <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "page", array(
                        "START_FROM" => (SITE_ID == "s2") ? 1 : 0,
                        "PATH" => "",
                    )
                ); ?>
            </div>
            <div class="detail-hero-tags">
                <ul class="list">
                    <? if ($arResult["OBJECT"]["UF_DEADLINE"]): ?>
                        <li class="list-item">
                            <?= ($arResult["OBJECT"]["UF_DEADLINE"] != "Сдан") ? "Сдача ".$arResult["OBJECT"]["UF_DEADLINE"] : $arResult["OBJECT"]["UF_DEADLINE"]; ?>
                        </li>
                        <? $countAdvantage++; ?>
                    <? endif; ?>
                    <? if (!empty($arResult["FILTER_RANGES"]["TERRACE"])): ?>
                        <li class="list-item">С террасами</li>
                        <? $countAdvantage++; ?>
                    <? endif; ?>
                    <? if ($arResult["OBJECT"]["UF_ADVANTAGES"]): ?>
                        <? foreach ($arResult["OBJECT"]["UF_ADVANTAGES"] as $advantage) : ?>
                            <? if ($countAdvantage == 6) {
                                continue;
                            } ?>
                            <li class="list-item"><?= $advantage; ?></li>
                            <? $countAdvantage++; ?>
                        <? endforeach; ?>
                    <? endif; ?>
                </ul>
            </div>
            <div class="detail-hero-description">
                <div class="detail-hero-info">
                    <? if ($arResult['OBJECT']['UF_H1']): ?>
                        <? $objectDescription = explode(" – ",
                            str_replace(array(' - ',), " – ", $arResult['OBJECT']['UF_H1'])); ?>
                        <!--<? //echo "<pre>"; print_r($objectDescription); echo "</pre>";?>-->
                        <? //$objectDescription = explode(" – ", $arResult['OBJECT']['UF_H1']);?>
                        <h1><span class="object-info-title"><?= $objectDescription[0]; ?></span><span
                                    class="object-info-delimiter"> - </span><span
                                    class="object-info-description"><?= $objectDescription[1]; ?></span></h1>
                    <? else: ?>
                        <h1><?= $arResult['OBJECT']['NAME']; ?></h1>
                    <? endif; ?>
                    <ul class="list">
                        <? if (!empty($arResult['OBJECT']["UF_METRO_HL"])): ?>
                            <li class="list-item list-item--metro">
                                <svg class="icon icon--metro" width="26" height="20" viewbox="0 0 26 20">
                                    <use xlink:href="#metro"/>
                                </svg>
                                <div class="list-item-label">Метро</div>
                                <div class="list-item-value"><?= $arResult['OBJECT']["UF_METRO_HL"]; ?></div>
                            </li>
                        <? endif; ?>
                        <? if (!empty($arResult['OBJECT']["UF_ADDRESS"])): ?>
                            <li class="list-item list-item--address">
                                <svg class="icon icon--address" width="15" height="21" viewbox="0 0 15 21">
                                    <use xlink:href="#address"/>
                                </svg>
                                <div class="list-item-label">Адрес</div>
                                <div class="list-item-value"><?= $arResult['OBJECT']["UF_ADDRESS"]; ?></div>
                            </li>
                        <? endif; ?>
                    </ul>
                    <a class="button button--default" href="#modal-map" data-modal>Посмотреть на карте</a>
                </div>
                <? if (!empty($arResult["OBJECT"]["UF_PRESENTATION"])) : ?>
                    <div class="detail-hero-form">
                        <div class="div-title-h3">Скачайте <span>PDF-презентацию</span> комплекса в&nbsp;1&nbsp;клик
                        </div>
                        <form class="form" id="presentation-form">
                            <input type="hidden" name="pdf"
                                   value="<?= $arResult["OBJECT"]["UF_PRESENTATION"]["SRC"]; ?>">
                            <input type="hidden" name="tipObject"
                                   value='<?= json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE) ?>'>
                            <input type="hidden" name="site_id" value="<?= SITE_ID; ?>">
                            <input type="hidden" name="form_name" value="Скачать презентацию">
                            <div class="form-item">
                                <div class="field"><input class="field-input" type="tel" data-phone-validate value
                                                          placeholder="Телефон" name="phone"></div>
                            </div>
                            <div class="form-item form-item--success form-item--hide" data-phone-validate-hide>
                                <div class="field"><input class="field-input" type="text" value placeholder="Имя"
                                                          name="name"></div>
                            </div>
                            <div class="form-control">
                                <button class="button button--light">Скачать презентацию</button>
                            </div>
                        </form>
                        <div class="success-form-presentation">Спасибо, скачивание презентации началось.</div>
                    </div>
                <? endif; ?>
            </div>
        </div>
    </div>
    <div class="detail-hero-mobile">
        <div class="container">
            <ul class="list">
                <? if (!empty($arResult['OBJECT']["UF_METRO_HL"])): ?>
                    <li class="list-item list-item--metro">
                        <svg class="icon icon--metro" width="26" height="20" viewbox="0 0 26 20">
                            <use xlink:href="#metro"/>
                        </svg>
                        <div class="list-item-label">Метро</div>
                        <div class="list-item-value"><?= $arResult['OBJECT']["UF_METRO_HL"]; ?></div>
                    </li>
                <? endif; ?>
                <? if (!empty($arResult['OBJECT']["UF_ADDRESS"])): ?>
                    <li class="list-item list-item--address">
                        <svg class="icon icon--address" width="15" height="21" viewbox="0 0 15 21">
                            <use xlink:href="#address"/>
                        </svg>
                        <div class="list-item-label">Адрес</div>
                        <div class="list-item-value"><?= $arResult['OBJECT']["UF_ADDRESS"]; ?></div>
                    </li>
                <? endif; ?>
            </ul>
        </div>
    </div>
</section>
<section class="section-detail-objects" id="description" data-anchor>
    <div class="container" data-scroll-fx>
        <div class="object-characteristics">
            <div class="object-advantages-list">
                <h2 class="h1">Описание объекта</h2>
                <ul class="list detail-params-list">
                    <? if (!empty($arResult["OBJECT"]["UF_CLASS_NAME"])): ?>
                        <li class="list-item">
                            <div class="list-item-label">Класс жилья</div>
                            <div class="list-item-value"><?= $arResult['OBJECT']['UF_CLASS_NAME']; ?></div>
                        </li>
                    <? endif; ?>
                    <? if (!empty($arResult["OBJECT"]["UF_TYPE_NAME"])): ?>
                        <li class="list-item">
                            <div class="list-item-label">Тип недвижимости</div>
                            <div class="list-item-value"><?= $arResult['OBJECT']['UF_TYPE_NAME']; ?></div>
                        </li>
                    <? endif; ?>
                    <? if (!empty($arResult['OBJECT']['UF_BUILD_NAME']) && ($arParams["HIDE_BUILDER"] != "Y")): ?>
                        <li class="list-item">
                            <div class="list-item-label">Застройщик</div>
                            <div class="list-item-value"><?= $arResult['OBJECT']['UF_BUILD_NAME']; ?></div>
                        </li>
                    <? endif; ?>
                    <? if (!empty($arResult["OBJECT"]["UF_DEADLINE"])): ?>
                        <li class="list-item">
                            <div class="list-item-label">Срок сдачи</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["UF_DEADLINE"]; ?></div>
                        </li>
                    <? endif; ?>
                    <? if (!empty($arResult["OBJECT"]["UF_METRO_HL"])): ?>
                        <li class="list-item">
                            <div class="list-item-label">Метро</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["UF_METRO_HL"]; ?></div>
                        </li>
                    <? endif; ?>
                    <? if (!empty($arResult["OBJECT"]["UF_TYPE_BUILD"])): ?>
                        <li class="list-item">
                            <div class="list-item-label">Тип здания</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["UF_TYPE_BUILD"]; ?></div>
                        </li>
                    <? endif; ?>
                    <? if (!empty($arResult["FILTER_RANGES"]["MAX_FLOOR"])): ?>
                        <li class="list-item">
                            <div class="list-item-label">Этажность</div>
                            <div class="list-item-value"><?= $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?></div>
                        </li>
                    <? endif; ?>
                    <? if (!empty($arResult["OBJECT"]["ELEMENT_CNT"])): ?>
                        <li class="list-item">
                            <div class="list-item-label">Осталось квартир</div>
                            <div class="list-item-value"><?= $arResult["OBJECT"]["ELEMENT_CNT"]; ?></div>
                        </li>
                    <? endif; ?>
                </ul>
            </div>
            <div class="object-text">
                <div class="content_block hide">
                    <p><?= $arResult["OBJECT"]["DESCRIPTION"]; ?></p>
                </div>
                <a class="content_toggle" href="#">Читать далее</a>
            </div>
        </div>
    </div>
</section>
<? if (!empty($arResult["OBJECT"]["UF_PHOTOS"])) : ?>
    <section class="section-detail-gallery" id="gallery" data-anchor>
        <div class="container" data-scroll-fx>
            <div class="slider slider--gallery" data-slider-gallery>
                <div class="slider-headnote">
                    <h2 class="h1">Фотогалерея</h2>
                    <? if (count($arResult["OBJECT"]["UF_PHOTOS"]) > 3) : ?>
                        <div class="slider-controls">
                            <div class="swiper-button-prev">
                                <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                    <use xlink:href="#arrow"/>
                                </svg>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next">
                                <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                    <use xlink:href="#arrow"/>
                                </svg>
                            </div>
                        </div>
                    <? endif; ?>
                </div>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <? foreach ($arResult["OBJECT"]["UF_PHOTOS"] as $arObjectPhoto): ?>
                            <div class="swiper-slide swiper-lazy"
                                 data-background="<?= $arObjectPhoto["PHOTO_RESIZE"] ? $arObjectPhoto["PHOTO_RESIZE"] : $arObjectPhoto["SRC"] ?>">
                                <a href="#gallery-modal" data-modal></a></div>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>
<section class="section-flats" id="flats" data-anchor>
    <div class="container">
        <? if ($arResult["FILTER_RANGES"]["ALL_COUNT"] > 0): ?>
            <div class="filter-title" style="margin-top: 4rem;">
                <? if (count($arResult["OBJECT"]["ELEMENT_BUILD_TYPES"]) == 1 && $arResult["OBJECT"]["ELEMENT_BUILD_TYPES"][8]) {
                    $isApartment = true;
                }
                ?>
                <h2 class="div-title div-title-h1 code-pro">
                    ПЛАНИРОВКИ<?= ($arParams['CITY_CODE'] == 'moskva') ? " И ЦЕНЫ" : null; ?> <?= $isApartment ? " АПАРТАМЕНТОВ" : " КВАРТИР"; ?> <?= $arResult['OBJECT']['UF_TYPE_NAME'] ?>
                    "<?= $arResult["OBJECT"]["NAME"]; ?>"</h2>
                <p>Вы можете выбрать и забронировать <?= $isApartment ? "апартаменты" : "квартиру"; ?> в комлексе
                    "<?= $arResult["OBJECT"]["NAME"]; ?>" в нашем разделе с планировками</p>
            </div>
            <div class="details-objects">
                <?
                if ($arResult['IS_AJAX']) {
                    $APPLICATION->RestartBuffer();
                }
                ?>
                <button class="button button--light button--filter" data-filters-open>
                    <span>Фильтры</span>
                    <span class="desc-to-icon--dropdown">раскрыть</span>
                    <div class="dropdown-icon white">
                        <svg class="icon icon--dropdown" width="10" height="6" viewBox="0 0 10 6">
                            <use xlink:href="#dropdown"></use>
                        </svg>
                    </div>
                </button>
                <div class="wrapper-mobile-rooms-filter">
                    <div class="mobile-rooms-filter details-filter-item">
                        <div class="form-label">Комнаты</div>
                        <div class="details-filter-choose">
                            <? foreach ($arResult['ROOMS_TYPES'] as $arRoom): ?>
                                <? $arRoom['XML_ID'] = str_replace('rooms_', '', $arRoom['XML_ID']) ?>
                                <? if ($arRoom['XML_ID'] == 0) {
                                    $arRoom['XML_ID'] = 'С';
                                } ?>
                                <label>
                                    <input type="checkbox" name="rooms_mobile"
                                           value="<?= $arRoom['ID']; ?>" <? if (isset($arResult["FILTER_VALUES"]["ROOMS"]) && in_array($arRoom['ID'],
                                            $arResult["FILTER_VALUES"]["ROOMS"])
                                    ): ?> checked<? endif; ?> <? if (isset($arResult["OBJECT_ROOMS_TYPES"]) && !in_array($arRoom['ID'],
                                            $arResult["OBJECT_ROOMS_TYPES"])
                                    ): ?> disabled<? endif; ?>>
                                    <span><?= $arRoom['XML_ID']; ?></span>
                                </label>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="details-objects-sidebar" data-scroll-fx>
                    <div class="div-title div-title-h3">Фильтр</div>
                    <div class="details-filter">
                        <div class="details-filter-heading">
                            <div class="div-title div-title-h4">Фильтры</div>
                            <button data-filters-close>
                                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                                    <use xlink:href="#cross-light-large"/>
                                </svg>
                            </button>
                        </div>
                        <form class="form">
                            <? if (!$arResult["HIDE_PRICE"] && ($arResult["FILTER_RANGES"]["CURRENCY"]["MIN_PRICE"] != $arResult["FILTER_RANGES"]["CURRENCY"]["MAX_PRICE"])) : ?>
                                <div class="details-filter-item">
                                    <div class="form-label">Цена</div>
                                    <div class="filter-price" data-price-filter="data-price-filter">
                                        <div class="filter-price-values">
                                            <div class="filter-price-from"><input class="field-input"
                                                                                  data-price-from-label="data-price-from-label"
                                                                                  disabled="disabled"><input
                                                        class="field-input" type="number"
                                                        data-price-from="data-price-from"
                                                        value="<?= $arResult["FILTER_VALUES"]["PRICE"][0] ? $arResult["FILTER_VALUES"]["PRICE"][0] : $arResult["FILTER_RANGES"]["CURRENCY"]["MIN_PRICE"]; ?>">
                                            </div>
                                            <div class="filter-price-to"><input class="field-input"
                                                                                data-price-to-label="data-price-to-label"
                                                                                disabled="disabled"><input
                                                        class="field-input" type="number" data-price-to="data-price-to"
                                                        value="<?= $arResult["FILTER_VALUES"]["PRICE"][1] ? $arResult["FILTER_VALUES"]["PRICE"][1] : $arResult["FILTER_RANGES"]["CURRENCY"]["MAX_PRICE"]; ?>">
                                            </div>
                                        </div>
                                        <div class="filter-price-line"
                                             data-price="data-price"
                                             data-price-step="1"
                                             data-price-min="<?= $arResult["FILTER_RANGES"]["CURRENCY"]["MIN_PRICE"] ?>"
                                             data-price-max="<?= $arResult["FILTER_RANGES"]["CURRENCY"]["MAX_PRICE"] ?>"
                                             data-price-min-current="<? if (isset($arResult["FILTER_VALUES"]["PRICE"][0])): ?><?= $arResult["FILTER_VALUES"]["PRICE"][0] ?><? else: ?><?= $arResult["FILTER_RANGES"]["CURRENCY"]["MIN_PRICE"] ?><? endif; ?>"
                                             data-price-max-current="<? if (isset($arResult["FILTER_VALUES"]["PRICE"][1])): ?><?= $arResult["FILTER_VALUES"]["PRICE"][1] ?><? else: ?><?= $arResult["FILTER_RANGES"]["CURRENCY"]["MAX_PRICE"] ?><? endif; ?>"
                                        >
                                        </div>
                                    </div>
                                </div>
                                <div class="details-filter-item">
                                    <div class="form-label">Валюта</div>
                                    <div class="details-filter-choose">
                                        <label>
                                            <input type="radio" <?= (strtolower($arResult['FILTER_VALUES']['CURRENCY']) == 'rub') ? 'checked data-cur' : null ?>
                                                   value="rub" name="currency">
                                            <span>₽</span>
                                        </label>
                                        <label>
                                            <input type="radio" <?= (strtolower($arResult['FILTER_VALUES']['CURRENCY']) == 'usd') ? 'checked data-cur' : null ?>
                                                   name="currency" value="usd">
                                            <span>$</span>
                                        </label>
                                        <label>
                                            <input type="radio" <?= (strtolower($arResult['FILTER_VALUES']['CURRENCY']) == 'eur') ? 'checked data-cur' : null ?>
                                                   name="currency" value="eur">
                                            <span>€</span>
                                        </label>
                                        <label>
                                            <input type="radio" <?= (strtolower($arResult['FILTER_VALUES']['CURRENCY']) == 'gbp') ? 'checked data-cur' : null ?>
                                                   value="gbp" name="currency">
                                            <span>£</span>
                                        </label>
                                    </div>
                                </div>
                            <? endif; ?>
                            <div class="details-filter-item">
                                <div class="form-label">Комнаты</div>
                                <div class="details-filter-choose">
                                    <? foreach ($arResult['ROOMS_TYPES'] as $arRoom): ?>
                                        <? $arRoom['XML_ID'] = str_replace('rooms_', '', $arRoom['XML_ID']) ?>
                                        <? if ($arRoom['XML_ID'] == 0) {
                                            $arRoom['XML_ID'] = 'С';
                                        } ?>
                                        <label>
                                            <input type="checkbox" name="rooms"
                                                   value="<?= $arRoom['ID']; ?>" <? if (isset($arResult["FILTER_VALUES"]["ROOMS"]) && in_array($arRoom['ID'],
                                                    $arResult["FILTER_VALUES"]["ROOMS"])
                                            ): ?> checked="checked"<? endif; ?> <? if (isset($arResult["OBJECT_ROOMS_TYPES"]) && !in_array($arRoom['ID'],
                                                    $arResult["OBJECT_ROOMS_TYPES"])
                                            ): ?> disabled<? endif; ?>>
                                            <span><?= $arRoom['XML_ID']; ?></span>
                                        </label>
                                    <? endforeach; ?>
                                </div>
                            </div>
                            <? if ($arResult["FILTER_RANGES"]["MIN_SQUARE"] != $arResult["FILTER_RANGES"]["MAX_SQUARE"]): ?>
                                <div class="details-filter-item">
                                    <div class="form-label">Площадь, м²</div>
                                    <div class="details-filter-range" data-filter-range
                                         data-filter-range-min="<?= str_replace(".", ",",
                                             $arResult["FILTER_RANGES"]["MIN_SQUARE"]); ?>"
                                         data-filter-range-max="<?= str_replace(".", ",",
                                             $arResult["FILTER_RANGES"]["MAX_SQUARE"]); ?>">
                                        <div class="details-filter-range-item" data-filter-from><input
                                                    class="field-input"
                                                    type="text"
                                                    data-filter-name="square-from"
                                                    value="<?= $arResult["FILTER_VALUES"]["SQUARE"][0] ? str_replace(".",
                                                        ",",
                                                        $arResult["FILTER_VALUES"]["SQUARE"][0]) : null; ?>"
                                                    data-filter-value
                                                    data-mask-number>
                                            <div class="field-input">От <span
                                                        data-filter-label><?= $arResult["FILTER_VALUES"]["SQUARE"][0] ? str_replace(".",
                                                        ",", $arResult["FILTER_VALUES"]["SQUARE"][0]) : str_replace(".",
                                                        ",", $arResult["FILTER_RANGES"]["MIN_SQUARE"]); ?></span>
                                            </div>
                                        </div>
                                        <div class="details-filter-range-item" data-filter-to><input class="field-input"
                                                                                                     type="text"
                                                                                                     data-filter-name="square-to"
                                                                                                     value="<?= $arResult["FILTER_VALUES"]["SQUARE"][1] ? str_replace(".",
                                                                                                         ",",
                                                                                                         $arResult["FILTER_VALUES"]["SQUARE"][1]) : null; ?>"
                                                                                                     data-filter-value
                                                                                                     data-mask-number>
                                            <div class="field-input">До <span
                                                        data-filter-label><?= $arResult["FILTER_VALUES"]["SQUARE"][1] ? str_replace(".",
                                                        ",", $arResult["FILTER_VALUES"]["SQUARE"][1]) : str_replace(".",
                                                        ",", $arResult["FILTER_RANGES"]["MAX_SQUARE"]); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? endif; ?>
                            <? if ($arResult["FILTER_RANGES"]["MIN_FLOOR"] != $arResult["FILTER_RANGES"]["MAX_FLOOR"]): ?>
                                <div class="details-filter-item">
                                    <div class="form-label">Этаж</div>
                                    <div class="details-filter-range" data-filter-range
                                         data-filter-range-min="<?= $arResult["FILTER_RANGES"]["MIN_FLOOR"]; ?>"
                                         data-filter-range-max="<?= $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?>">
                                        <div class="details-filter-range-item" data-filter-from><input
                                                    class="field-input"
                                                    type="text"
                                                    data-filter-name="floor-from"
                                                    value="<?= $arResult["FILTER_VALUES"]["FLOOR"][0] ? $arResult["FILTER_VALUES"]["FLOOR"][0] : null; ?>"
                                                    data-filter-value
                                                    data-mask-number>
                                            <div class="field-input">От <span
                                                        data-filter-label><?= $arResult["FILTER_VALUES"]["FLOOR"][0] ? $arResult["FILTER_VALUES"]["FLOOR"][0] : $arResult["FILTER_RANGES"]["MIN_FLOOR"]; ?></span>
                                            </div>
                                        </div>
                                        <div class="details-filter-range-item" data-filter-to><input class="field-input"
                                                                                                     type="text"
                                                                                                     value="<?= $arResult["FILTER_VALUES"]["FLOOR"][1] ? $arResult["FILTER_VALUES"]["FLOOR"][1] : null; ?>"
                                                                                                     data-filter-name="floor-to"
                                                                                                     data-filter-value
                                                                                                     data-mask-number>
                                            <div class="field-input">До <span
                                                        data-filter-label><?= $arResult["FILTER_VALUES"]["FLOOR"][1] ? $arResult["FILTER_VALUES"]["FLOOR"][1] : $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? endif; ?>
                            <? if ($arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"] != $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]): ?>
                                <div class="details-filter-item">
                                    <div class="form-label">Площадь кухни, м²</div>
                                    <div class="details-filter-range" data-filter-range
                                         data-filter-range-min="<?= str_replace(".", ",",
                                             $arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"]); ?>"
                                         data-filter-range-max="<?= str_replace(".", ",",
                                             $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]); ?>">
                                        <div class="details-filter-range-item" data-filter-from><input
                                                    class="field-input"
                                                    type="text"
                                                    data-filter-name="kitchen-square-from"
                                                    value="<?= $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][0] ? str_replace(".",
                                                        ",",
                                                        $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][0]) : null; ?>"
                                                    data-filter-value
                                                    data-mask-number>
                                            <div class="field-input">От <span
                                                        data-filter-label><?= $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][0] ? str_replace(".",
                                                        ",",
                                                        $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][0]) : str_replace(".",
                                                        ",",
                                                        $arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"]); ?></span>
                                            </div>
                                        </div>
                                        <div class="details-filter-range-item" data-filter-to><input class="field-input"
                                                                                                     type="text"
                                                                                                     data-filter-name="kitchen-square-to"
                                                                                                     value="<?= $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][1] ? str_replace(".",
                                                                                                         ",",
                                                                                                         $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][1]) : null; ?>"
                                                                                                     data-filter-value
                                                                                                     data-mask-number>
                                            <div class="field-input">До <span
                                                        data-filter-label><?= $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][1] ? str_replace(".",
                                                        ",",
                                                        $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][1]) : str_replace(".",
                                                        ",",
                                                        $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? endif; ?>
                            <? if (count($arResult["FILTER_RANGES"]["DECORATIONS"]) > 1) : ?>
                                <div class="details-filter-item">
                                    <div class="form-label">Отделка</div>
                                    <? foreach ($arResult["FILTER_RANGES"]["DECORATIONS"] as $arDecoration) : ?>
                                        <div class="form-item-checkbox">
                                            <label class="checkbox">
                                                <input type="checkbox"
                                                       name="decoration" <? if (isset($arResult["FILTER_VALUES"]["DECORATIONS"]) && in_array($arDecoration["UF_ID"],
                                                        $arResult["FILTER_VALUES"]["DECORATIONS"])
                                                ): ?> checked<? endif; ?>
                                                       value="<?= $arDecoration["UF_ID"]; ?>">
                                                <span><?= $arDecoration["UF_NAME"]; ?></span>
                                            </label>
                                        </div>
                                    <? endforeach; ?>
                                </div>
                            <? endif; ?>
                            <? if (!empty($arResult["FILTER_RANGES"]["HIGH_CEILINGS"])
                                || !empty($arResult["FILTER_RANGES"]["TERRACE"])
                                || !empty($arResult["FILTER_RANGES"]["TWO_TIER"])): ?>
                                <div class="details-filter-item details-filter-item--checkboxes">
                                    <div class="form-label">Особенности</div>
                                    <? if (!empty($arResult["FILTER_RANGES"]["HIGH_CEILINGS"])): ?>
                                        <div class="form-item-checkbox"><label class="checkbox"><input type="checkbox"
                                                                                                       name="additional-options" <? if (isset($arResult["FILTER_VALUES"]["ADDITIONAL_OPTIONS"]) && in_array('highceilings',
                                                        $arResult["FILTER_VALUES"]["ADDITIONAL_OPTIONS"])
                                                ): ?> checked<? endif; ?>
                                                                                                       value="highceilings"><span>Высокие потолки</span></label>
                                        </div>
                                    <? endif; ?>
                                    <? if (!empty($arResult["FILTER_RANGES"]["TERRACE"])): ?>
                                        <div class="form-item-checkbox"><label class="checkbox"><input type="checkbox"
                                                                                                       name="additional-options" <? if (isset($arResult["FILTER_VALUES"]["ADDITIONAL_OPTIONS"]) && in_array('terrace',
                                                        $arResult["FILTER_VALUES"]["ADDITIONAL_OPTIONS"])
                                                ): ?> checked<? endif; ?>
                                                                                                       value="terrace"><span>Терраса</span></label>
                                        </div>
                                    <? endif; ?>
                                    <? if (!empty($arResult["FILTER_RANGES"]["TWO_TIER"])): ?>
                                        <div class="form-item-checkbox"><label class="checkbox"><input type="checkbox"
                                                                                                       name="additional-options" <? if (isset($arResult["FILTER_VALUES"]["ADDITIONAL_OPTIONS"]) && in_array('twotier',
                                                        $arResult["FILTER_VALUES"]["ADDITIONAL_OPTIONS"])
                                                ): ?> checked<? endif; ?>
                                                                                                       value="twotier"><span>Двухуровневая</span></label>
                                        </div>
                                    <? endif; ?>
                                </div>
                            <? endif; ?>
                            <div class="details-filter-control">
                                <button id="apply-filter" disabled="disabled" class="button button--default"
                                        type="button">
                                    Показать (<span><?= $arResult["FILTER_RANGES"]["ALL_COUNT"] ?></span>)
                                </button>
                                <button class="button button--inline" id="filter-clear" type="button">Сбросить всё
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="details-objects-result">
                    <? if ($arResult["FILTER_RANGES"]["ALL_COUNT"] > 0): ?>
                        <div class="details-objects-list">
                            <?
                            if ($arResult['IS_AJAX'] && $arResult["SHOW_MORE"]) {
                                $APPLICATION->RestartBuffer();
                            }
                            ?>
                            <? foreach ($arResult["FLATS"]["ITEMS"] as $keyFlat => $arFlat): ?>
                                <?
                                if ($arFlat["PROPERTY_BUILD_TYPE_VALUE"] == 7) {
                                    $arFlat["NAME"] = "Коммерческое помещение";
                                } elseif ($arFlat["PROPERTY_BUILD_TYPE_VALUE"] == 8) {
                                    $arFlat["NAME"] = $arRoomApartmentTypes[intval($arFlat["PROPERTY_ROOMS_VALUE"])];
                                } else {
                                    $arFlat["NAME"] = $arRoomFlatTypes[intval($arFlat["PROPERTY_ROOMS_VALUE"])];
                                }
                                //echo "<pre hidden>"; print_r($arFlat["NAME"]); echo "</pre>";
                                $arFlatInfo = [
                                    'object-class' => $arResult["OBJECT"]["UF_CLASS_NAME"],
                                    'object' => $arResult["OBJECT"]["NAME"],
                                    'type-of-property' => 'Новостройка',
                                    'apartment-id' => $arFlat["ID"],
                                    'apartment-name' => $arFlat["NAME"],
                                    'apartment-room' => $arFlat["PROPERTY_ROOMS_VALUE"],
                                    'apartment-square' => $arFlat["PROPERTY_SQUARE_VALUE"]." м²",
                                    'apartment-kitchen-square' => ($arFlat["PROPERTY_KITCHEN_SQUARE_VALUE"]) ? $arFlat["PROPERTY_KITCHEN_SQUARE_VALUE"]." м²" : "размер кухни не указан",
                                    'apartment-price' => (!$arResult["HIDE_PRICE"]) ? number_format($arFlat["PROPERTY_PRICE_".$arResult['FILTER_VALUES']['CURRENCY']."_VALUE"],
                                            0, '.',
                                            ' ')." ".$arResult["FILTER_VALUES"]["CURRENCY_SYMBOL"] : "По запросу",
                                    'apartment-decoration' => $arFlat["PROPERTY_DECORATION_VALUE"],
                                    'city' => $arParams['CITY_CODE']
                                ];
                                ?>
                                <div class="details-objects-item" <?= ($arResult["HIDE_PRICE"]) ? "data-objects-on-request" : null; ?>>
                                    <a class="details-objects-link"
                                       href="#object-<?= $arFlat["ID"]; ?>" data-modal>
                                        <div class="details-objects-item-title"><?= $arFlat["NAME"]; ?></div>
                                        <? $imgFlat = CFile::ResizeImageGet($arFlat["DETAIL_PICTURE"],
                                            array('width' => 230, 'height' => 230), BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                                            true); ?>
                                        <? if ($imgFlat): ?>
                                            <div class="details-objects-item-image">
                                                <img class="lazy" data-src="<?= $imgFlat["src"]; ?>"
                                                     style="width:<?= $imgFlat["width"]; ?>px;height:<?= $imgFlat["height"]; ?>px;">
                                            </div>
                                        <? else: ?>
                                            <div class="object-other-image-empty">
                                                <div>Скоро добавим планировку</div>
                                            </div>
                                        <? endif; ?>
                                        <div class="details-objects-item-params">
                                            <div class="details-objects-item-size">
                                                <? if (!empty($arFlat["PROPERTY_SQUARE_VALUE"])) : ?>
                                                    <span><?= str_replace(".", ",",
                                                            $arFlat["PROPERTY_SQUARE_VALUE"]); ?>
                                                        м²</span>
                                                <? endif; ?>
                                                <? /* if (!empty($arFlat["PROPERTY_KITCHEN_SQUARE_VALUE"])) : ?>
                                                <span><?= str_replace(".", ",",
                                                        $arFlat["PROPERTY_KITCHEN_SQUARE_VALUE"]); ?>
                                                    м²</span>
                                            <? endif; */ ?>
                                                <? if (!empty($arFlat["PROPERTY_FLOOR_VALUE"])) : ?>
                                                    <span><?= $arFlat["PROPERTY_FLOOR_VALUE"]; ?> этаж</span>
                                                <? endif; ?>
                                            </div>
                                            <? if (!$arResult["HIDE_PRICE"]) : ?>
                                                <div class="details-objects-item-price"><?= number_format($arFlat["PROPERTY_PRICE_".$arResult['FILTER_VALUES']['CURRENCY']."_VALUE"],
                                                        0, '.',
                                                        ' ') ?> <?= $arResult["FILTER_VALUES"]["CURRENCY_SYMBOL"]; ?></div>
                                                <? if (!empty($arFlat["PROPERTY_PRICE_".$arResult['FILTER_VALUES']['CURRENCY']."_VALUE"]) && !empty($arFlat["PROPERTY_SQUARE_VALUE"])): ?>
                                                    <div class="details-objects-item-price-small"><?= number_format($arFlat["PROPERTY_PRICE_".$arResult['FILTER_VALUES']['CURRENCY']."_VALUE"] / $arFlat["PROPERTY_SQUARE_VALUE"],
                                                            0, '.',
                                                            ' ') ?> <?= $arResult["FILTER_VALUES"]["CURRENCY_SYMBOL"]; ?>
                                                        /м²
                                                    </div>
                                                <? endif; ?>
                                            <? else: ?>
                                                <button class="button button--light details-objects-item-price on-request">
                                                    Узнать цену
                                                </button>
                                            <? endif; ?>
                                        </div>
                                    </a></div>
                                <? if ($arResult["HIDE_PRICE"]) : ?>
                                    <div class="details-objects-item" data-objects-mobile><a
                                                class="details-objects-link"
                                                href="#object-form" data-modal
                                                data-apartment='<?= json_encode($arFlatInfo,
                                                    JSON_UNESCAPED_UNICODE) ?>'>
                                            <div class="details-objects-item-title"><?= $arFlat["NAME"]; ?></div>
                                            <? $imgFlat = CFile::ResizeImageGet($arFlat["DETAIL_PICTURE"],
                                                array('width' => 230, 'height' => 230),
                                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                                                true); ?>
                                            <? if ($imgFlat): ?>
                                                <div class="details-objects-item-image">
                                                    <? if ($arResult["HIDE_PRICE"]) : ?>
                                                        <div data-apartment='<?= json_encode($arFlatInfo,
                                                            JSON_UNESCAPED_UNICODE) ?>'>
                                                            <img class="lazy" data-src="<?= $imgFlat["src"]; ?>">
                                                        </div>
                                                    <? else: ?>
                                                        <img class="lazy" data-src="<?= $imgFlat["src"]; ?>">
                                                    <? endif; ?>
                                                </div>
                                            <? else: ?>
                                                <div class="object-other-image-empty">
                                                    <div>Скоро добавим планировку</div>
                                                </div>
                                            <? endif; ?>
                                            <div class="details-objects-item-params">
                                                <div class="details-objects-item-size">
                                                    <? if (!empty($arFlat["PROPERTY_SQUARE_VALUE"])) : ?>
                                                        <span><?= str_replace(".", ",",
                                                                $arFlat["PROPERTY_SQUARE_VALUE"]); ?>
                                                            м²</span>
                                                    <? endif; ?>
                                                    <? /* if (!empty($arFlat["PROPERTY_KITCHEN_SQUARE_VALUE"])) : ?>
                                                    <span><?= str_replace(".", ",",
                                                            $arFlat["PROPERTY_KITCHEN_SQUARE_VALUE"]); ?>
                                                        м²</span>
                                                <? endif; */ ?>
                                                    <? if (!empty($arFlat["PROPERTY_FLOOR_VALUE"])) : ?>
                                                        <span><?= $arFlat["PROPERTY_FLOOR_VALUE"]; ?> этаж</span>
                                                    <? endif; ?>
                                                </div>
                                                <? if (!$arResult["HIDE_PRICE"]) : ?>
                                                    <div class="details-objects-item-price"><?= number_format($arFlat["PROPERTY_PRICE_".$arResult['FILTER_VALUES']['CURRENCY']."_VALUE"],
                                                            0, '.',
                                                            ' ') ?> <?= $arResult["FILTER_VALUES"]["CURRENCY_SYMBOL"]; ?></div>
                                                    <? if (!empty($arFlat["PROPERTY_PRICE_".$arResult['FILTER_VALUES']['CURRENCY']."_VALUE"]) && !empty($arFlat["PROPERTY_SQUARE_VALUE"])): ?>
                                                        <div class="details-objects-item-price-small"><?= number_format($arFlat["PROPERTY_PRICE_".$arResult['FILTER_VALUES']['CURRENCY']."_VALUE"] / $arFlat["PROPERTY_SQUARE_VALUE"],
                                                                0, '.',
                                                                ' ') ?> <?= $arResult["FILTER_VALUES"]["CURRENCY_SYMBOL"]; ?>
                                                            /м²
                                                        </div>
                                                    <? endif; ?>
                                                <? else: ?>
                                                    <button class="button button--light details-objects-item-price on-request">
                                                        Узнать цену
                                                    </button>
                                                <? endif; ?>
                                            </div>
                                        </a></div>
                                <? endif; ?>
                                <div class="modal modal--object" id="object-<?= $arFlat["ID"]; ?>">
                                    <div class="modal-container">
                                        <button class="modal-close" data-modal-close>
                                            <svg class="icon icon--cross-light-large" width="32" height="32"
                                                 viewbox="0 0 32 32">
                                                <use xlink:href="#cross-light-large"/>
                                            </svg>
                                        </button>
                                        <div class="modal-object">
                                            <div class="modal-object-description">
                                                <div class="h3-title"><?= $arFlat["NAME"]; ?></div>
                                                <ul class="list">
                                                    <? if (!empty($arFlat["PROPERTY_SQUARE_VALUE"])) : ?>
                                                        <li class="list-item">
                                                            <div class="list-item-label">Площадь</div>
                                                            <div class="list-item-value"><?= str_replace(".", ",",
                                                                    $arFlat["PROPERTY_SQUARE_VALUE"]); ?>
                                                                м²
                                                            </div>
                                                        </li>
                                                    <? endif; ?>
                                                    <? if (!empty($arFlat["PROPERTY_KITCHEN_SQUARE_VALUE"])) : ?>
                                                        <li class="list-item">
                                                            <div class="list-item-label">Площадь кухни</div>
                                                            <div class="list-item-value"><?= str_replace(".", ",",
                                                                    $arFlat["PROPERTY_KITCHEN_SQUARE_VALUE"]); ?>
                                                                м²
                                                            </div>
                                                        </li>
                                                    <? endif; ?>
                                                    <? if (!empty($arFlat["PROPERTY_FLOOR_VALUE"])) : ?>
                                                        <li class="list-item">
                                                            <div class="list-item-label">Этаж</div>
                                                            <div class="list-item-value"><?= $arFlat["PROPERTY_FLOOR_VALUE"]; ?>
                                                                из <?= $arFlat["PROPERTY_MAX_FLOOR_VALUE"]; ?></div>
                                                        </li>
                                                    <? endif; ?>
                                                    <? if (!empty($arFlat["PROPERTY_ROOMS_VALUE"])) : ?>
                                                        <li class="list-item">
                                                            <div class="list-item-label">Кол-во комнат</div>
                                                            <div class="list-item-value"><?= $arFlat["PROPERTY_ROOMS_VALUE"]; ?></div>
                                                        </li>
                                                    <? endif; ?>
                                                    <? if (!empty($arFlat["PROPERTY_DECORATION_VALUE"])) : ?>
                                                        <li class="list-item">
                                                            <div class="list-item-label">Отделка</div>
                                                            <div class="list-item-value"><?= $arFlat["PROPERTY_DECORATION_VALUE"]; ?></div>
                                                        </li>
                                                    <? endif; ?>
                                                </ul>
                                                <div class="modal-object-person">
                                                    <div class="modal-object-person-image"
                                                         style="background-image: url('<?= $arResult["OBJECT"]["UF_MANAGER"]["PREVIEW_PICTURE"]["SRC"] ?>');"></div>
                                                    <div class="modal-object-person-info">
                                                        <div class="div-title div-title-h4"><?= $arResult["OBJECT"]["UF_MANAGER"]["NAME"]; ?></div>
                                                        <div>Позвоните для консультации эксперту проекта</div>
                                                        <a class="text-control text-control--phone <?= $phoneReplaceClass ?>"
                                                           href="tel:<?= str_replace(array("(", ")", " ", "-"), "",
                                                               $phoneValue); ?>"><span><?= $phoneValue; ?></span>
                                                            <svg class="icon icon--phone" width="17" height="17"
                                                                 viewbox="0 0 17 17">
                                                                <use xlink:href="#phone"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                                <? if ($arResult["HIDE_PRICE"]) : ?>
                                                    <a class="button button--light" href="#object-form" data-modal-close
                                                       data-modal
                                                       data-apartment='<?= json_encode($arFlatInfo,
                                                           JSON_UNESCAPED_UNICODE) ?>'>Узнать
                                                        цену</a>
                                                <? else: ?>
                                                    <br>
                                                    <div class="list-item-label">Цена:</div>
                                                    <div class="details-objects-item-price"><?= number_format($arFlat["PROPERTY_PRICE_".$arResult['FILTER_VALUES']['CURRENCY']."_VALUE"],
                                                            0, '.',
                                                            ' ') ?> <?= $arResult["FILTER_VALUES"]["CURRENCY_SYMBOL"]; ?></div>
                                                    <a class="button button--light" href="#excursion" data-modal-close
                                                       data-modal
                                                       data-apartment='<?= json_encode($arFlatInfo,
                                                           JSON_UNESCAPED_UNICODE) ?>'>Записаться
                                                        на экскурсию</a>
                                                <? endif; ?>
                                                <a class="button button--light pdf-generate" target="_blank"
                                                   href="/pdf/generate_pdf.php?id=<?= $arFlat["ID"]; ?>">Скачать
                                                    презентацию</a>
                                            </div>
                                            <? $imgPlanFlat = CFile::ResizeImageGet($arFlat["DETAIL_PICTURE"],
                                                array('width' => 480, 'height' => 480),
                                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                                                true); ?>
                                            <div class="modal-object-image">
                                                <? if ($imgPlanFlat): ?>
                                                    <img src="<?= $imgPlanFlat["src"]; ?>" alt>
                                                <? else: ?>
                                                    <div class="object-other-image-empty">
                                                        <div>Скоро добавим планировку</div>
                                                    </div>
                                                <? endif; ?>
                                            </div>
                                            <div class="modal-object-person">
                                                <div class="modal-object-person-image"
                                                     style="background-image: url('<?= $arResult["OBJECT"]["UF_MANAGER"]["PREVIEW_PICTURE"]["SRC"] ?>');"></div>
                                                <div class="modal-object-person-info">
                                                    <div class="div-title div-title-h4"><?= $arResult["OBJECT"]["UF_MANAGER"]["NAME"]; ?></div>
                                                    <div>Позвоните для консультации эксперту проекта</div>
                                                    <a class="text-control text-control--phone <?= $phoneReplaceClass ?>"
                                                       href="tel:<?= str_replace(array("(", ")", " ", "-"), "",
                                                           $phoneValue); ?>"><span><?= $phoneValue; ?></span>
                                                        <svg class="icon icon--phone" width="17" height="17"
                                                             viewbox="0 0 17 17">
                                                            <use xlink:href="#phone"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? endforeach; ?>
                            <?
                            if ($arResult['IS_AJAX'] && $arResult["SHOW_MORE"]) {
                                die();
                            }
                            ?>
                        </div>
                        <? if ($arResult["FLATS"]["SHOW_MORE"]): ?>
                            <div class="pagination-more">
                                <button id="show-more" style="pointer-events: all;"
                                        class="button button--inverse button--refresh" data-page="1">
                                    <svg class="icon icon--refresh" width="18" height="22" viewbox="0 0 18 22">
                                        <use xlink:href="#refresh"/>
                                    </svg>
                                    <span>Показать еще</span>
                                </button>
                            </div>
                        <? endif; ?>
                    <? else: ?>
                        <div class="params-content" data-scroll-fx>
                            <div class="container">
                                <div class="details-empty params-noresult" data-scroll-fx>По вашему запросу не найдено
                                    предложений.
                                </div>
                            </div>
                        </div>
                    <? endif; ?>
                </div>
                <?
                if ($arResult['IS_AJAX']) {
                    die();
                }
                ?>
            </div>
        <? else: ?>
            <div class="wrapper-no-result-landing">
                <div class="title-no-result">
                    <span class="tower-icon"><img src="<?= SITE_TEMPLATE_PATH; ?>/img/tower-icon.svg" alt=""></span>
                    Квартир в продаже нет
                </div>
                <div class="description-no-result">
                    Мы подобрали для вас похожие объекты. Вы можете ознакомиться с ними ниже, либо подобрать подходящий
                    вариант недвижимости с нашим специалистом
                    <a class="text-control text-control--phone <?= $phoneReplaceClass ?>"
                       href="tel:<?= str_replace(array("(", ")", " ", "-"), "",
                           $phoneValue); ?>"><span><?= $phoneValue; ?></span></a>
                </div>
            </div>
        <? endif; ?>
    </div>
</section>
<? if (!($arResult["FILTER_RANGES"]["ALL_COUNT"] > 0)): ?>
    <? if (!empty($arResult["SIMILAR_OBJECTS"])): ?>
        <section class="section-detail-other">
            <div class="container" data-scroll-fx>
                <h3 class="h1">Похожие объекты</h3>
                <div class="catalog">
                    <div class="catalog-list">
                        <? foreach ($arResult["SIMILAR_OBJECTS"] as $arSimilarObject): ?>
                            <? if ($arSimilarObject['ELEMENT_CNT'] == 0) {
                                continue;
                            } ?>
                            <div class="catalog-item"><a
                                        class="object-new" <?= (SITE_ID != 's1' && SITE_ID != 's2') ? 'target="_blank"' : ''; ?>
                                        href="<?= (SITE_ID != 's1' && SITE_ID != 's2') ? 'https://towergroup.ru' : ''; ?><?= $arSimilarObject["SECTION_PAGE_URL"]; ?>">
                                    <div class="object-new-preview lazy"
                                         data-bg="<?= $arSimilarObject["PICTURE_RESIZE"] ? $arSimilarObject["PICTURE_RESIZE"] : $arSimilarObject["PICTURE"]["SRC"]; ?>"></div>
                                    <div class="object-new-info">
                                        <div class="h3 h4-title"><?= $arSimilarObject["NAME"]; ?></div>
                                        <div class="object-new-info-hide">
                                            <div class="text-overflow"><?= $arSimilarObject["UF_AREA"]; ?></div>
                                            <div class="text-overflow"><?= str_replace($arSimilarObject['ELEMENT_CNT'],
                                                    "",
                                                    plural_form($arSimilarObject['ELEMENT_CNT'], array(
                                                        "Осталась",
                                                        "Осталось",
                                                        "Осталось"
                                                    ))); ?> <?= plural_form($arSimilarObject["ELEMENT_CNT"],
                                                    array("квартира", "квартиры", "квартир")); ?></div>
                                            <div class="text-overflow"><?= $arSimilarObject['UF_DEADLINE']; ?></div>
                                        </div>
                                    </div>
                                </a></div>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <? endif; ?>
<? endif; ?>
<? if (!empty($arResult["OBJECT"]["UF_PRESENTATION"])) : ?>
    <section class="section-detail-presentation">
        <div class="section-detail-holder" data-scroll-fx>
            <div class="container">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/local/include/forms/form_presentation_no_modal.php",
                        "EDIT_TEMPLATE" => "",
                        "TITLE" => "Скачать презентацию проекта",
                        "OBJECT" => $arObjectInfo,
                    ),
                    false
                ); ?>
            </div>
        </div>
    </section>
<? endif; ?>
<? if (!empty($arResult["OBJECT"]["UF_DECORATION_IMAGE"])) : ?>
    <section class="section-detail-about" id="about" data-anchor>
        <div class="slider slider--fullwidth" data-slider-about data-scroll-fx>
            <div class="slider-controls">
                <div class="swiper-button-prev">
                    <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                        <use xlink:href="#arrow"/>
                    </svg>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next">
                    <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                        <use xlink:href="#arrow"/>
                    </svg>
                </div>
            </div>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <? foreach ($arResult["OBJECT"]["UF_DECORATION_IMAGE"] as $arSlide): ?>
                        <div class="swiper-slide swiper-lazy" data-background="<?= $arSlide["IMAGE_SLIDE"]["SRC"]; ?>">
                            <div class="slider-about-description">
                                <h2 class="h1"><?= $arSlide["PREVIEW_TEXT"]; ?></h2>
                                <div>
                                    <?= $arSlide["DETAIL_TEXT"]; ?>
                                </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>
<section class="section-form-inline">
    <div class="container" data-scroll-fx="data-scroll-fx">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/forms/form_reserve.php",
                "EDIT_TEMPLATE" => "",
                "FORM_CODE" => "reserve-form",
                "OBJECT" => json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE),
            ),
            false
        ); ?>
    </div>
</section>
<? if (!empty($arResult["OBJECT"]["UF_CONST_PROGRESS"])) : ?>
    <section class="section-detail-progress" id="progress" data-anchor style="margin-top:60px">
        <div class="container">
            <div class="slider--progress">
                <div class="slider-headnote">
                    <h2 class="h1">Ход строительства</h2>
                </div>
                <div class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <? foreach (array_reverse($arResult["OBJECT"]["UF_CONST_PROGRESS"]) as $fileId): ?>
                                <li class="splide__slide"><img src="<?= Cfile::getPath($fileId); ?>" style="width: 100%;"></li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>
<? if (!empty($arResult["OBJECT"]["UF_OBJECT_DOCS"])) : ?>
    <section class="section-detail-зкщоусе" id="project-documentation" data-anchor style="margin-top:60px">
        <div class="container" data-scroll-fx>
            <div class="project-documentation-wrapper">
                <div class="slider-headnote">
                    <h2 class="h1">Проектная документация</h2>
                </div>
                <div class="swiper-container">
                    <div class="project-documentation">
                        <? foreach ($arResult["OBJECT"]["UF_OBJECT_DOCS"] as $fileId): ?>
                            <div class="project-documentation-item" style="margin-bottom: 30px; font-size: 16px;">
                                <a href="<?= CFile::GetPath($fileId) ?>"><?= CFile::GetFileArray($fileId)['ORIGINAL_NAME'] ?></a>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>
<? if (!empty($arResult["OBJECT"]["UF_PHOTOS"][2])) : ?>
<section class="section-detail-credit lazy" data-bg="<?= $arResult["OBJECT"]["UF_PHOTOS"][2]["SRC"] ?>">
    <? else: ?>
    <section class="section-detail-credit lazy" data-bg="<?= SITE_TEMPLATE_PATH ?>/img/catalog/credit.jpg">
        <? endif; ?>
        <div class="container" data-scroll-fx>
            <div class="detail-credit" data-detail-credit>
                <div class="detail-credit-text">
                    <div class="h1">Оставить заявку на&nbsp;ипотеку</div>
                    <span>Оставьте заявку, и&nbsp;наши брокеры перезвонят в&nbsp;ближайшее время</span>
                </div>
                <!--<div class="detail-credit-control"><a class="button button--light" href="#" data-detail-credit-control>Оставить заявку</a></div>-->
                <div class="section-form-inline">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => "/local/include/forms/form_mortgage.php",
                            "EDIT_TEMPLATE" => "",
                            "FORM_CODE" => "mortgage-form",
                            "OBJECT" => json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE),
                        ),
                        false
                    ); ?>
                </div>
            </div>
        </div>
    </section>
    <? if ($arResult["OBJECT"]["UF_FINISH_DESCRIPTION"] || $arResult["OBJECT"]["UF_PLANNING"]): ?>
        <section class="section-detail-text-cols">
            <div class="container">
                <div class="detail-text" data-detail-text>
                    <div class="detail-text-cols">
                        <? if ($arResult["OBJECT"]["UF_FINISH_DESCRIPTION"]): ?>
                            <div class="detail-text-cols-item">
                                <h2>Отделка</h2>
                                <div class="detail-text-cols-desc">
                                    <?= $arResult["OBJECT"]["UF_FINISH_DESCRIPTION"]; ?>
                                </div>
                            </div>
                        <? endif; ?>
                        <? if ($arResult["OBJECT"]["UF_PLANNING"]): ?>
                            <div class="detail-text-cols-item">
                                <h2>Планировка</h2>
                                <div class="detail-text-cols-desc">
                                    <?= $arResult["OBJECT"]["UF_PLANNING"]; ?>
                                </div>
                            </div>
                        <? endif; ?>
                    </div>
                    <button class="button button--inline" data-detail-text-show>Читать далее</button>
                </div>
            </div>
        </section>
    <? endif; ?>
    <? if (!empty($arResult["OBJECT"]["UF_COORD"])): ?>
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
                        <?= $arResult["OBJECT"]["UF_INFRASTRUCTURE_DESCRIPTION"]
                            ? $arResult["OBJECT"]["UF_INFRASTRUCTURE_DESCRIPTION"]
                            : $arResult['OBJECT']['UF_AREA']['DESCRIPTION']; ?>
                    </div>
                </div>
                <div class="infrastructure-map-text-mobile">
                    <div class="container">
                        <div class="detail-text" data-detail-text="data-detail-text">
                            <div class="detail-text-cols">
                                <? if ($arResult["OBJECT"]["UF_INFRASTRUCTURE_DESCRIPTION"]) : ?>
                                    <?= str_replace('<div>', '<div class="detail-text-cols-item">',
                                        $arResult['OBJECT']['UF_INFRASTRUCTURE_DESCRIPTION']) ?>
                                <? else: ?>
                                    <?= $arResult['OBJECT']['UF_AREA']['DESCRIPTION']; ?>
                                <? endif; ?>
                            </div>
                            <button class="button button--inline" data-detail-text-show="data-detail-text-show">Читать
                                далее
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <? endif; ?>
    <? if ($arResult["OBJECT"]["UF_OPINION_TITLE"]): ?>
        <section class="section-detail-opinion">
            <div class="container">
                <div class="detail-opinion">
                    <div class="detail-opinion-content" data-scroll-fx>
                        <h2>Наше мнение</h2>
                        <div class="detail-opinion-blockquote">
                            <div class="detail-opinion-title">
                                <?= $arResult["OBJECT"]["UF_OPINION_TITLE"] ?>
                            </div>
                            <div class="detail-opinion-text">
                                <?= $arResult["OBJECT"]["UF_OPINION_DESCRIPTION"] ?>
                            </div>
                        </div>
                    </div>
                    <div class="detail-opinion-person" data-scroll-fx>
                        <div class="detail-opinion-image"><img class="lazy"
                                                               data-src="<?= $arResult["OBJECT"]["UF_SPECIALIST"]["PREVIEW_PICTURE_RESIZE"] ? $arResult["OBJECT"]["UF_SPECIALIST"]["PREVIEW_PICTURE_RESIZE"] : $arResult["OBJECT"]["UF_SPECIALIST"]["PREVIEW_PICTURE"]["SRC"]; ?>"
                                                               alt></div>
                        <div class="detail-opinion-name">
                            <div>Мнение специалиста</div>
                            <span><?= $arResult["OBJECT"]["UF_SPECIALIST"]["NAME"]; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <? endif; ?>
    <? if ($arResult["OBJECT"]["OBJECT_NEWS"]): ?>
        <? $arNewsSections = array_keys($arResult["OBJECT"]["OBJECT_NEWS"]) ?>
        <section class="section-detail-articles">
            <div class="container">
                <div class="detail-articles">
                    <div class="detail-articles-heading" data-scroll-fx>
                        <h2>Новости</h2>
                        <a href="<?= SITE_ID == "s1" || SITE_ID == "s2" ? "/" : "https://towergroup.ru/" ?><?= $arParams['CITY_CODE'] ?>/pressa/"
                           target="_blank">Все новости</a>
                    </div>
                    <div class="tabs">
                        <div class="tabs-title" data-scroll-fx>
                            <? foreach ($arNewsSections as $keySection => $sectionCode): ?>
                                <div class="tabs-item<? if ($keySection == 0): ?> -active<? endif; ?>"><?= $sectionCode == 'NEWS' ? 'О ЖК' : 'О застройщике' ?></div>
                            <? endforeach; ?>
                        </div>
                        <div class="tabs-content" data-scroll-fx>
                            <? foreach ($arResult["OBJECT"]["OBJECT_NEWS"] as $keySectionNews => $arSectionNews): ?>
                                <div class="tabs-item<? if ($keySectionNews == $arNewsSections[0]): ?> -active<? endif; ?>">
                                    <div class="detail-articles-list">
                                        <? foreach ($arSectionNews as $arNews): ?>
                                            <div class="detail-articles-item">
                                                <? if ($arNews["PREVIEW_PICTURE"]): ?>
                                                    <div class="detail-articles-image lazy"
                                                         data-bg="<?= $arNews["PREVIEW_PICTURE"]["SRC"] ?>"></div>
                                                <? endif; ?>
                                                <div class="detail-articles-description">
                                    <span class="detail-articles-date">
                                        <?= strtolower(FormatDate("d F Y", MakeTimeStamp($arNews['ACTIVE_FROM']))); ?>
                                    </span>
                                                    <div class="detail-articles-title">
                                                        <? $arNews["DETAIL_PAGE_URL"] = str_replace("/moskva/moskva/", '/moskva/', $arNews["DETAIL_PAGE_URL"]); ?>
                                                        <a href="<?= $arNews["DETAIL_PAGE_URL"] ?>">
                                                            <?= $arNews["NAME"] ?>
                                                        </a>
                                                    </div>
                                                    <div class="detail-articles-introtext">
                                                        <?= $arNews["PREVIEW_TEXT"] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <? endforeach; ?>
                                    </div>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="detail-articles-more">
                        <a href="<?= SITE_ID == "s1" || SITE_ID == "s2" ? "/" : "https://towergroup.ru/" ?><?= $arParams['CITY_CODE'] ?>/pressa/"
                           target="_blank">Все новости</a>
                    </div>
                </div>
            </div>
        </section>
    <? endif; ?>
    <? if ($arResult["OBJECT"]["UF_BUILDER"]): ?>
        <section class="section-detail-developer">
            <div class="container">
                <div class="detail-developer">
                    <div class="detail-developer-info">
                        <h2>Застройщик «<?= $arResult["OBJECT"]["UF_BUILDER"]["UF_NAME"] ?>»</h2>
                        <? if ($arResult["OBJECT"]["UF_BUILDER"]["UF_NUMBER_OF_YEARS"]
                            || $arResult["OBJECT"]["UF_BUILDER"]["UF_COUNT_OBJECTS"]
                            || $arResult["OBJECT"]["UF_BUILDER"]["UF_PROJECT_LOGO"]
                        ): ?>
                            <div class="detail-developer-row">
                                <? if ($arResult["OBJECT"]["UF_BUILDER"]["UF_NUMBER_OF_YEARS"]): ?>
                                    <div class="detail-developer-stats detail-developer-stats--year">
                                        <?= plural_form($arResult["OBJECT"]["UF_BUILDER"]["UF_NUMBER_OF_YEARS"],
                                            array("год", "года", "лет")) ?><br>
                                        на рынке
                                    </div>
                                <? endif; ?>
                                <? if ($arResult["OBJECT"]["UF_BUILDER"]["UF_COUNT_OBJECTS"]): ?>
                                    <div class="detail-developer-stats detail-developer-stats--done">
                                        <?= $arResult["OBJECT"]["UF_BUILDER"]["UF_COUNT_OBJECTS"] ?> ЖК<br>
                                        <?= str_replace($arResult["OBJECT"]["UF_BUILDER"]["UF_COUNT_OBJECTS"], "",
                                            plural_form($arResult["OBJECT"]["UF_BUILDER"]["UF_COUNT_OBJECTS"],
                                                array("сдан", "сдано", "сдано"))) ?>
                                    </div>
                                <? endif; ?>
                                <? if ($arResult["OBJECT"]["UF_BUILDER"]["UF_PROJECT_LOGO"]): ?>
                                    <div class="detail-developer-logo"><img
                                                src="<?= $arResult["OBJECT"]["UF_BUILDER"]["UF_PROJECT_LOGO"]["SRC"] ?>"
                                                alt></div>
                                <? endif; ?>
                            </div>
                        <? endif; ?>
                    </div>
                    <? if ($arResult["OBJECT"]["UF_BUILDER"]["UF_DOCS"]): ?>
                        <div class="detail-developer-docs"><span>Разрешительная документация</span>
                            <ul class="list">
                                <? foreach ($arResult["OBJECT"]["UF_BUILDER"]["UF_DOCS"] as $arDoc): ?>
                                    <li class="list-item">
                                        <a class="list-link"
                                           href="<?= $arDoc["SRC"] ?>"><?= $arDoc["ORIGINAL_NAME"] ?></a>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    <? endif; ?>
                </div>
                <? if (!empty($arResult["OBJECT"]["UF_BUILDER"]["OBJECTS"])): ?>
                    <div class="slider slider--objects-new" data-slider-objects data-scroll-fx>
                        <div class="slider-headnote">
                            <h3 class="h1">Другие ЖК застройщика «<?= $arResult["OBJECT"]["UF_BUILDER"]["UF_NAME"] ?>
                                »</h3>
                            <? if (count($arResult["OBJECT"]["UF_BUILDER"]["OBJECTS"]) > 4): ?>
                                <div class="slider-controls">
                                    <div class="swiper-button-prev">
                                        <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                            <use xlink:href="#arrow"/>
                                        </svg>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                    <div class="swiper-button-next">
                                        <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                            <use xlink:href="#arrow"/>
                                        </svg>
                                    </div>
                                </div>
                            <? endif; ?>
                        </div>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <? foreach ($arResult["OBJECT"]["UF_BUILDER"]["OBJECTS"] as $arBuilderObject): ?>
                                    <? if ($arBuilderObject['ELEMENT_CNT'] == 0) {
                                        continue;
                                    } ?>
                                    <div class="swiper-slide">
                                        <a class="object-new" <?= (SITE_ID != 's1' && SITE_ID != 's2') ? 'target="_blank"' : ''; ?>
                                           href="<?= (SITE_ID != 's1' && SITE_ID != 's2') ? 'https://towergroup.ru' : ''; ?><?= $arBuilderObject["SECTION_PAGE_URL"]; ?>">
                                            <div class="object-new-preview swiper-lazy"
                                                 data-background="<?= $arBuilderObject["PICTURE_RESIZE"] ? $arBuilderObject["PICTURE_RESIZE"] : $arBuilderObject["PICTURE"]["SRC"]; ?>">
                                            </div>
                                            <div class="object-new-info">
                                                <div class="h3"><?= $arBuilderObject["NAME"]; ?></div>
                                                <div class="object-new-info-hide">
                                                    <div class="text-overflow"><?= $arResult["OBJECT"]["UF_BUILDER"]["AREAS"][$arBuilderObject["UF_AREA"]]["NAME"]; ?></div>
                                                    <div class="text-overflow"><?= str_replace($arBuilderObject['ELEMENT_CNT'],
                                                            "",
                                                            plural_form($arBuilderObject['ELEMENT_CNT'], array(
                                                                "Осталась",
                                                                "Осталось",
                                                                "Осталось"
                                                            ))); ?> <?= plural_form($arBuilderObject["ELEMENT_CNT"],
                                                            array("квартира", "квартиры", "квартир")); ?></div>
                                                    <div class="text-overflow"><?= $arBuilderObject['UF_DEADLINE']; ?></div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
            </div>
        </section>
    <? endif; ?>
    <? if ($arResult["FILTER_RANGES"]["ALL_COUNT"] > 0): ?>
        <? if (!empty($arResult["SIMILAR_OBJECTS"])): ?>
            <section class="section-detail-other">
                <div class="container" data-scroll-fx>
                    <h3 class="h1">Похожие объекты</h3>
                    <div class="catalog">
                        <div class="catalog-list">
                            <? foreach ($arResult["SIMILAR_OBJECTS"] as $arSimilarObject): ?>
                                <? if ($arSimilarObject['ELEMENT_CNT'] == 0) {
                                    continue;
                                } ?>
                                <div class="catalog-item"><a
                                            class="object-new" <?= (SITE_ID != 's1' && SITE_ID != 's2') ? 'target="_blank"' : ''; ?>
                                            href="<?= (SITE_ID != 's1' && SITE_ID != 's2') ? 'https://towergroup.ru' : ''; ?><?= $arSimilarObject["SECTION_PAGE_URL"]; ?>">
                                        <div class="object-new-preview lazy"
                                             data-bg="<?= $arSimilarObject["PICTURE_RESIZE"] ? $arSimilarObject["PICTURE_RESIZE"] : $arSimilarObject["PICTURE"]["SRC"]; ?>"></div>
                                        <div class="object-new-info">
                                            <div class="h3 h4-title"><?= $arSimilarObject["NAME"]; ?></div>
                                            <div class="object-new-info-hide">
                                                <div class="text-overflow"><?= $arSimilarObject["UF_AREA"]; ?></div>
                                                <div class="text-overflow"><?= str_replace($arSimilarObject['ELEMENT_CNT'],
                                                        "",
                                                        plural_form($arSimilarObject['ELEMENT_CNT'], array(
                                                            "Осталась",
                                                            "Осталось",
                                                            "Осталось"
                                                        ))); ?> <?= plural_form($arSimilarObject["ELEMENT_CNT"],
                                                        array("квартира", "квартиры", "квартир")); ?></div>
                                                <div class="text-overflow"><?= $arSimilarObject['UF_DEADLINE']; ?></div>
                                            </div>
                                        </div>
                                    </a></div>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        <? endif; ?>
    <? endif; ?>
    <? if (!empty($arResult["OBJECT"]["UF_DECORATION_IMAGE"]) && !empty($arResult["INFRASTRUCTURE_OBJECTS"]) && !empty($arResult["SIMILAR_OBJECTS"])) : ?>
        <section class="section-form-inline" data-scroll-fx="data-scroll-fx">
            <div class="container">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/local/include/forms/form_help.php",
                        "EDIT_TEMPLATE" => "",
                        "FORM_CODE" => "help-form",
                        "OBJECT" => json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE),
                    ),
                    false
                ); ?>
            </div>
        </section>
    <? endif; ?>
    <? //Modal//?>
    <? if ($APPLICATION->GetPageProperty("page") === "landing") : ?>
        <div class="modal modal--buklet" id="buklet">
            <div class="modal-container">
                <button class="modal-close" data-modal-close>
                    <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                        <use xlink:href="#cross-light-large"/>
                    </svg>
                </button>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/local/include/forms/form_presentation_modal.php",
                        "EDIT_TEMPLATE" => "",
                        "TITLE" => "Скачать презентацию проекта",
                        "OBJECT" => $arObjectInfo,
                    ),
                    false
                ); ?>
                <? /*\?>
        <div class="detail-presentation">
            <div class="detail-presentation-form">
                <div class="h1">Скачать презентацию проекта</div><span class="detail-presentation-footnote">Оставьте заявку, и&nbsp;наши брокеры вышлют вам презентацию в&nbsp;ближайшее время</span>
                <form class="form" id="detail-buklet">
                    <div class="form-item">
                        <div class="field"><input class="field-input" type="text" name="name" value placeholder="Имя"></div>
                    </div>
                    <div class="form-item">
                        <div class="field"><input class="field-input" type="tel" name="phone" value placeholder="Телефон"></div>
                    </div>
                    <div class="form-control"><button class="button button--light">Оставить заявку</button></div>
                    <div class="form-item form-item--checkbox"><label class="checkbox"><input type="checkbox" checked name="privacy"><span>Согласен на обработку <a href="#">персональных данных</a></span></label></div>
                </form>
            </div>
            <div class="detail-presentation-icon">
                <div><img src="<?= SITE_TEMPLATE_PATH ?>/img/catalog/presentation.png" alt></div><span>PDF-презентация жилого комлпекса «MONT BLANC»</span>
            </div>
        </div>
        <?\*/ ?>
            </div>
        </div>
    <? endif; ?>
    <div class="modal modal--object-form" id="object-form">
        <div class="modal-container">
            <button class="modal-close" data-modal-close>
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large"/>
                </svg>
            </button>
            <div class="section-form-inline">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/local/include/forms/form_object.php",
                        "EDIT_TEMPLATE" => "",
                        "FORM_CODE" => "object-form"
                    ),
                    false
                ); ?>
            </div>
        </div>
    </div>
    <div class="modal modal--map" id="modal-map">
        <div class="modal-container">
            <button class="modal-close" data-modal-close>
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large"/>
                </svg>
            </button>
            <div id="map-object"></div>
        </div>
    </div>
    <? if (!empty($arResult["OBJECT"]["UF_PHOTOS"])) : ?>
        <div class="modal modal--gallery" id="gallery-modal">
            <div class="modal-container">
                <button class="modal-close" data-modal-close>
                    <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                        <use xlink:href="#cross-light-large"/>
                    </svg>
                </button>
                <div class="slider slider--gallery-modal" data-slider-gallery-modal>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <? foreach ($arResult["OBJECT"]["UF_PHOTOS"] as $arObjectPhoto): ?>
                                <div class="swiper-slide"><img src="<?= $arObjectPhoto["SRC"] ?>" loading="lazy" alt>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="slider-controls">
                        <div class="swiper-button-prev">
                            <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                <use xlink:href="#arrow"/>
                            </svg>
                        </div>
                        <div class="swiper-button-next">
                            <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                <use xlink:href="#arrow"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>
    <? if (!empty($arResult["OBJECT"]["UF_CONST_PROGRESS"])) : ?>
        <div class="modal modal--progress" id="progress-modal">
            <div class="modal-container">
                <button class="modal-close" data-modal-close>
                    <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                        <use xlink:href="#cross-light-large"/>
                    </svg>
                </button>
                <div class="slider slider--progress-modal" data-slider-progress-modal>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <? foreach ($arResult["OBJECT"]["UF_CONST_PROGRESS"] as $photoId): ?>
                                <div class="swiper-slide"><img src="<?= CFile::GetPath($photoId) ?>" loading="lazy" alt>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="slider-controls">
                        <div class="swiper-button-prev">
                            <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                <use xlink:href="#arrow"/>
                            </svg>
                        </div>
                        <div class="swiper-button-next">
                            <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                <use xlink:href="#arrow"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>
    <? //Modal//?>
    <? if (!empty($arResult["OBJECT"]["UF_PHOTOS"][3])) : ?>
    <section class="section-detail-excursion lazy" data-bg="<?= $arResult["OBJECT"]["UF_PHOTOS"][3]["SRC"] ?>">
        <? else: ?>
        <section class="section-detail-excursion lazy" data-bg="<?= SITE_TEMPLATE_PATH ?>/img/catalog/excursion.jpg">
            <? endif; ?>
            <div class="container" data-scroll-fx>

                <?
                $arInfo = getFormInfo('excursion-form-no-modal');
                if (SITE_ID == "s1") {
                    $urlPrivacy = "/moskva/privacy/";
                } elseif (SITE_ID == "s2") {
                    $urlPrivacy = "/spb/privacy/";
                } else {
                    $urlPrivacy = "/privacy/";
                }
                ?>
                <div class="detail-excursion">
                    <div class="detail-excursion-title">
                        <div>
                            <? if ($arResult["OBJECT"]["UF_TITLE_EXCUR_NO_MODAL"]): ?>
                                <?= $arResult["OBJECT"]["UF_TITLE_EXCUR_NO_MODAL"]; ?>
                            <? else: ?>
                                <?= htmlspecialcharsBack(str_replace("#OBJECT_NAME#", $arObjectInfo['object'],
                                    $arInfo["TITLE"])); ?>
                            <? endif; ?>
                        </div>
                    </div>
                    <div class="detail-excursion-form">
        <span class="detail-excursion-form-title">
            <?= htmlspecialcharsBack($arInfo["FOOTNOTE"]); ?>
        </span>
                        <form class="form" id="detail-excursion">
                            <input type="hidden" name="site_id" value="<?= SITE_ID; ?>">
                            <input type="hidden" name="tipObject"
                                   value='<?= json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE) ?>'>
                            <input type="hidden" name="form_name" value="Записаться на экскурсию">
                            <div class="form-item">
                                <div class="field"><input class="field-input" type="text" name="name" value
                                                          placeholder="Имя"></div>
                            </div>
                            <div class="form-item">
                                <div class="field"><input class="field-input" type="tel" name="phone" value
                                                          placeholder="Телефон"></div>
                            </div>
                            <div class="form-control">
                                <button class="button button--light">Оставить заявку</button>
                            </div>
                        </form>
                    </div>
                </div>
                <? /* $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/forms/form_excursion_no_modal.php",
                "EDIT_TEMPLATE" => "",
                "FORM_CODE" => "excursion-form-no-modal",
                "OBJECT" => $arObjectInfo,
            ),
            false
        ); */ ?>

            </div>
        </section>
        <script>

            $(function () {
                function checkFilterParams() {

                    var params = {};
                    var buildtype = null;
                    <?if ($_GET["buildtype"] == 7):?>
                    buildtype = 7;
                    <?endif;?>

                    function arrayUnique(arr) {
                        return arr.filter((e, i, a) => a.indexOf(e) == i
                        )
                    }

                    var rooms = jQuery('input[name="rooms"]:checked').map(function (index, element) {
                        return $(element).val();
                    }).get();


                    var additionalOptions = jQuery('input[name="additional-options"]:checked').map(function (index, element) {
                        return $(element).val();
                    }).get();

                    var decorations = jQuery('input[name="decoration"]:checked').map(function (index, element) {
                        return $(element).val();
                    }).get();

                    if (parseFloat(jQuery('[data-price-from]').val()) && (jQuery('[data-price-from]').val().replace(/ /g, '') != jQuery('[data-price-min]').attr('data-price-min'))) {
                        var priceMin = parseFloat(jQuery('[data-price-from]').val().replace(/ /g, '')) / 1000000;
                    }

                    if (parseFloat(jQuery('[data-price-to]').val()) && (jQuery('[data-price-to]').val().replace(/ /g, '') != jQuery('[data-price-max]').attr('data-price-max'))) {
                        var priceMax = parseFloat(jQuery('[data-price-to]').val().replace(/ /g, '')) / 1000000;
                    }

                    var curCurrency = jQuery('input[name="currency"]:checked').val() ? jQuery('input[name="currency"][data-cur]').val() : null;

                    var newCurrency = jQuery('input[name="currency"]:checked').val() ? jQuery('input[name="currency"]:checked').val() : null;

                    if (parseInt(jQuery('[data-filter-name="floor-from"]').val())) {
                        var floorMin = parseInt(jQuery('[data-filter-name="floor-from"]').val());
                    }

                    if (parseInt(jQuery('[data-filter-name="floor-to"]').val())) {
                        var floorMax = parseInt(jQuery('[data-filter-name="floor-to"]').val());
                    }

                    if (parseFloat(jQuery('[data-filter-name="square-from"]').val())) {
                        var squareMin = parseFloat(jQuery('[data-filter-name="square-from"]').val().replace(',', '.'));
                    }

                    if (parseFloat(jQuery('[data-filter-name="square-to"]').val())) {
                        var squareMax = parseFloat(jQuery('[data-filter-name="square-to"]').val().replace(',', '.'));
                    }

                    if (parseFloat(jQuery('[data-filter-name="kitchen-square-from"]').val())) {
                        var kitchenSquareMin = parseFloat(jQuery('[data-filter-name="kitchen-square-from"]').val().replace(',', '.'));
                    }

                    if (parseFloat(jQuery('[data-filter-name="kitchen-square-to"]').val())) {
                        var kitchenSquareMax = parseFloat(jQuery('[data-filter-name="kitchen-square-to"]').val().replace(',', '.'));
                    }


                    if (priceMin) params.price_min = priceMin;
                    if (priceMax) params.price_max = priceMax;
                    if (curCurrency) params.cur_currency = curCurrency;
                    if (newCurrency) params.price_currency = newCurrency;
                    if (floorMin) params.floor_min = floorMin;
                    if (floorMax) params.floor_max = floorMax;
                    if (squareMin) params.square_min = squareMin;
                    if (squareMax) params.square_max = squareMax;
                    if (kitchenSquareMin) params.kitchensizemin = kitchenSquareMin;
                    if (kitchenSquareMax) params.kitchensizemax = kitchenSquareMax;
                    if (rooms.length > 0) params.rooms = rooms;
                    if (additionalOptions.length > 0) params.additional = additionalOptions;
                    if (decorations.length > 0) params.decoration = decorations;
                    if (buildtype) params.buildtype = buildtype;
                    return params;
                }

                function setUrlParamsPageParametrical(params) {
                    var arr = [];
                    for (var key in params) {
                        if (params[key] != null) {
                            arr.push(key + '=' + params[key]);
                        }
                    }

                    return (arr.length > 0) ? '?' + arr.join('&') : window.location.origin + window.location.pathname;
                }

                function getCount(event = null) {
                    var event = event.target || event;
                    var params = checkFilterParams();

                    params.getCount = true;

                    jQuery.ajax({
                        type: "GET",
                        data: params,
                        dataType: 'html',
                        success: function (count) {
                            jQuery('#apply-filter > span').text(count);
                            if (count > 0) {
                                jQuery('#apply-filter').removeAttr('disabled');
                            } else {
                                jQuery('#apply-filter').attr('disabled', 'disabled');
                            }
                        }
                    });
                }

                function getObjects() {

                    <?if($arParams["AJAX"]):?>
                    var params = checkFilterParams();

                    jQuery.ajax({
                        type: "GET",
                        data: params,
                        dataType: 'html',
                        success: function (html) {
                            if (html) {
                                if (jQuery('.details-empty.params-noresult'))
                                    jQuery('.details-empty.params-noresult').fadeOut(300).remove();

                                jQuery('#apply-filter').attr('disabled', 'disabled');
                                //jQuery('.details-objects:not(.-noresult)').html($(html));
                                var flatsHtml = html;

                                var mobileFilter = $(flatsHtml).filter('.wrapper-mobile-rooms-filter').html();
                                
                                jQuery('.wrapper-mobile-rooms-filter').html(mobileFilter);
                                jQuery('.details-filter').html($(html).find('.details-filter').html());
                                jQuery('.details-objects-list').html($(flatsHtml).find('.details-objects-list').html());
                                jQuery('.pagination-more').html($(flatsHtml).find('.pagination-more').html());

                                var queryParams = setUrlParamsPageParametrical(params);
                                history.replaceState(null, null, queryParams);

                                revealNew();
                                lazyload.update();
                                rangeFilter.init();
                                filterMobile.handleClose();
                                sliderPriceInit();

                                $('[data-price]').each(function (indx, element) {
                                    $(element).get(0).noUiSlider.on('change', function (values, handle) {
                                        getCount(this);
                                    });
                                });
                            }
                        }
                    });
                    <?else:?>
                    var params = {
                        'square': [parseFloat(jQuery('.square-filter_0').val()), parseFloat(jQuery('.square-filter_1').val())],
                        <?if(FLATS_NO_PRICE != "Y"):?>
                        'price': [parseFloat(jQuery('.price-filter_0').val()), parseFloat(jQuery('.price-filter_1').val())],
                        <?endif;?>
                        'floor': [jQuery('.floor-filter_0').val(), jQuery('.floor-filter_1').val()]
                    };
                    if (houses.length > 0)
                        params['house'] = houses;
                    if (rooms.length > 0)
                        params['rooms'] = rooms;
                    if (areas.length > 0)
                        params['areas'] = areas;
                    if (jQuery('input[name="finishtype"]:checked').val())
                        params['finishtype'] = parseInt(jQuery('input[name="finishtype"]:checked').val());
                    if (jQuery('input[name="discount"]').prop('checked'))
                        params['discount'] = 1;
                    if (jQuery('input[name="not-first-and-last"]').prop('checked'))
                        params['not-first-and-last'] = 1;

                    var queryParams = setUrlParams(params);
                    var url = '/novostroyki/' + queryParams;
                    location.href = url;

                    <?endif;?>
                }

                function getPage() {
                    var page = parseInt(jQuery('#show-more').data('page')) + 1;

                    var params = checkFilterParams();

                    params.showMore = true;
                    params.page = page;


                    jQuery.ajax({
                        type: "GET",
                        data: params,
                        dataType: 'html',
                        beforeSend: function () {
                            $('#show-more').prop('disabled', true);
                        },
                        success: function (html) {
                            //console.log(html);
                            if (html) {
                                if ($('#show-more').hasClass('button--refresh-load')) {
                                    $('#show-more').removeClass('button--refresh-load');
                                } else {
                                    $('#show-more').addClass('button--refresh-load');
                                }
                                ;
                                if (jQuery('.details-empty.no-result'))
                                    jQuery('.details-empty.no-result').fadeOut(300).remove();

                                let allCount = jQuery('#apply-filter > span').text();

                                //console.log(allCount);
                                //console.log($('.details-objects-list').find('div.details-objects-item').length);

                                $('.details-objects-list').append(html);

                                if ($('.details-objects-list').find('div.details-objects-item').length<?=$arResult["HIDE_PRICE"] ? " / 2" : null;?> == allCount)
                                    jQuery('#show-more').fadeOut(300).queue(function () {
                                        $(this).remove();
                                        $(this).dequeue();
                                    });
                                jQuery('#show-more').data('page', page);
                                $('#show-more').prop('disabled', false);

                                revealNew();
                                lazyload.update();
                            }
                        }
                    });
                }

                $('[data-price]').each(function (indx, element) {
                    $(element).get(0).noUiSlider.on('change', function (values, handle) {
                        getCount(this);
                    });
                });

                jQuery(document).on('change', '[name="rooms_mobile"]', function (e) {
                    e.preventDefault();
                    if ($(this).prop('checked')) {
                        $('[name="rooms"][value="' + $(this).val() + '"]').attr("checked", "checked");
                    } else {
                        $('[name="rooms"][value="' + $(this).val() + '"]').removeAttr("checked");
                        $('[name="rooms"][value="' + $(this).val() + '"]').prop("checked", false);
                    }
                    setTimeout(function(){
                        //getCount(e);
                        getObjects();
                    }, 10);

                });

                jQuery(document).on('change', '[data-price-from], [data-price-to], [name="rooms"], [name="decoration"], [name="additional-options"], [data-filter-name="square-from"], [data-filter-name="square-to"], [data-filter-name="kitchen-square-from"], [data-filter-name="kitchen-square-to"], [data-filter-name="floor-from"], [data-filter-name="floor-to"]', function (e) {
                    getCount(e);
                });

                jQuery(document).on('click', '#apply-filter, [name="currency"]', function (e) {
                    e.preventDefault();
                    getObjects();
                });

                jQuery(document).on('click', '#filter-clear', function (e) {
                    e.preventDefault();
                    <?if (SITE_ID == 's1' || SITE_ID == 's2'):?>
                    var url = "<?= $arResult["OBJECT"]["SECTION_PAGE_URL"]?>";
                    <?else:?>
                    <?$urlClear = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);?>
                    var url = "<?=$urlClear;?>";
                    <?endif;?>
                    location.href = url;
                });
                jQuery(document).on('click', '#show-more', function (e) {
                    e.preventDefault();
                    $('#show-more').addClass('button--refresh-load');
                    getPage();
                });

            });
        </script>
        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=82e46027-5420-42fe-ba21-2755432ad262"></script>
        <script>
            window.mapOptions = {
                center: [<?= $arResult["OBJECT"]["UF_COORD"]; ?>],
                zoom: 17,
                objectArea: [
                    <?= $arResult["OBJECT"]["UF_BORDER"] ? $arResult["OBJECT"]["UF_BORDER"] : null; ?>
                ],
                iconsFolder: '<?= SITE_TEMPLATE_PATH; ?>/img/detail/new_markers/',
                searchRadius: 400
            }
            <?if (!empty($arResult['INFRASTRUCTURE_OBJECTS'])):?>
            window.mapGroups = [
                <? foreach ($arResult['INFRASTRUCTURE_OBJECTS']['CATEGORY'] as $item): ?>
                <?if ($item['CODE'] == 'vse') {
                continue;
            } ?>
                {
                    groupTitle: '<?= $item['NAME']; ?>',
                    icon: '<?= $item['UF_CATEGORY_ICON']['SRC']; ?>',
                    key: '<?= $item['CODE']; ?>',
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
                    customMarkers: []
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
                }]
            <?endif;?>
        </script>
        <script>
            var mapInit = false
            window.addEventListener('modalBeforeOpen', function (event) {
                if (event.detail.id === 'modal-map' && !mapInit) {
                    ymaps.ready(function () {
                        var mapObject = new ymaps.Map('map-object',
                            {
                                center: [<?= $arResult["OBJECT"]["UF_COORD"]; ?>],
                                zoom: 13,
                                controls: []
                            })
                        mapObject.behaviors.disable('scrollZoom')
                        mapObject.controls.add(new ymaps.control.ZoomControl(
                            {
                                options:
                                    {
                                        position:
                                            {
                                                right: 10,
                                                bottom: 30
                                            }
                                    }
                            }))
                        mapObject.panes.get('ground').getElement().style.filter = 'grayscale(100%)'
                        var mapObjectMarker = new ymaps.Placemark([<?= $arResult["OBJECT"]["UF_COORD"]; ?>], null,
                            {
                                iconLayout: 'default#image',
                                iconImageHref: '<?= SITE_TEMPLATE_PATH ?>/img/static-map-marker.svg',
                                iconImageSize: [56, 60],
                                iconImageOffset: [-28, -60]
                            })
                        mapObject.geoObjects.add(mapObjectMarker)
                    })
                    mapInit = true
                }
            })
        </script>
        <script>
            var hrefPdf = $('.details-objects-list .pdf-generate').attr('href');
            $('[name=currency]').on('click', function () {
                var currency = $(this).val();
                $('.details-objects-list .pdf-generate').attr('href', hrefPdf + '&currency=' + currency);
            });
            jQuery(document).on('click', '[href="#object-form"]', function () {
                var arr = $(this).attr('data-apartment');

                if (!$("#object-new-form input:first").is("[name='tipApartment']")) {
                    $("#object-new-form").prepend("<input hidden='' type='text' name='tipApartment' value='" + arr + "' placeholder=''>");
                } else {
                    $("#object-new-form input[name='tipApartment']").val(arr);
                }
            });
            jQuery(document).on('click', '[data-broker-object-info]', function () {
                var arr = $(this).attr('data-broker-object-info');

                if (!$("#viewing-form input:first").is("[name='tipObject']")) {
                    $("#viewing-form").prepend("<input hidden='' type='text' name='tipObject' value='" + arr + "' placeholder=''>");
                } else {
                    $("#viewing-form input[name='tipObject']").val(arr);
                }
            });
            jQuery(document).on('click', '[href="#excursion"]', function () {
                var arr = $(this).attr('data-apartment');

                if (!$("#excursion-form input:first").is("[name='tipApartment']")) {
                    $("#excursion-form").prepend("<input hidden='' type='text' name='tipApartment' value='" + arr + "' placeholder=''>");
                } else {
                    $("#excursion-form input[name='tipApartment']").val(arr);
                }
            });
            jQuery(document).on('click', '[href="#backcall"]', function () {
                var arr = '<?=json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE)?>';

                if (!$("#backcall-form input:first").is("[name='tipObject']")) {
                    $("#backcall-form").prepend("<input hidden='' type='text' name='tipObject' value='" + arr + "' placeholder=''>");
                } else {
                    $("#backcall-form input[name='tipObject']").val(arr);
                }
            });
            jQuery(document).on('click', '[href="#landing-backcall"]', function () {
                var arr = '<?=json_encode($arObjectInfo, JSON_UNESCAPED_UNICODE)?>';

                if (!$("#landing-broker-form input:first").is("[name='tipObject']")) {
                    $("#landing-broker-form").prepend("<input hidden='' type='text' name='tipObject' value='" + arr + "' placeholder=''>");
                } else {
                    $("#landing-broker-form input[name='tipObject']").val(arr);
                }
            });
            window.addEventListener('resize', function (event) {
                if (window.innerWidth < 1024) {
                    $("[data-objects-mobile]").addClass('mob');
                }
                if (window.innerWidth > 1024) {
                    $("[data-objects-mobile]").removeClass('mob');
                }
            })
        </script>
        <script>
            $(document).ready(function () {
                $('.content_toggle').click(function () {
                    $('.content_block').toggleClass('hide');
                    if ($('.content_block').hasClass('hide')) {
                        $('.content_toggle').html('Читать далее');
                    } else {
                        $('.content_toggle').html('Скрыть');
                    }
                    return false;
                });
            });
        </script>
        <? if (!empty($arResult["OBJECT"]["UF_PRESENTATION"])) : ?>
            <script>
                $(document).ready(function () {
                    var pdfValue = "<?= $arResult['OBJECT']['UF_PRESENTATION']['SRC']; ?>";
                    $("#detail-presentation input[name='pdf']").val(pdfValue);
                });
            </script>
        <? endif; ?>
        <? if (!empty($arResult["OBJECT"]["UF_CONST_PROGRESS"])) : ?>
            <script>
                var splide = new Splide( '.splide', {
                    direction: 'ttb',
                    heightRatio: 0.55,
                } );
                splide.mount();
            </script>
        <? endif; ?>
