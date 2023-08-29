<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);
$APPLICATION->SetPageProperty("title", "Tower Group - Выбрать квартиру");
$APPLICATION->SetPageProperty("description", "Выбор вторичной недвижимости Tower Group.");
$APPLICATION->SetPageProperty("page", "catalog_resale");
$APPLICATION->SetPageProperty("body-page", "class='-catalog-list'");
$APPLICATION->SetPageProperty("body-page", $_GET['showMap'] == 'Y' ? "class='-catalog-map'" : "class='-catalog-list'");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/catalog_resale.min.css");
$banner = 0;

$GLOBALS['filter_employees'] = array("ID" => $arResult["DEFAULT_BROKER"]["ID"]);

$GLOBALS['default_broker'] = array(
    "ID" => $arResult["DEFAULT_BROKER"]["ID"],
    "NAME" => $arResult["DEFAULT_BROKER"]["NAME"],
    "PICTURE" => $arResult["DEFAULT_BROKER"]["PREVIEW_PICTURE"]["SRC"],
    "DEPARTMENT" => $arResult["DEFAULT_BROKER"]['PROPERTY_DEPARTMENT_VALUE'],
    "PHONE" => $arResult["DEFAULT_BROKER"]['PROPERTY_PHONE_VALUE'],
    "EMAIL" =>$arResult["DEFAULT_BROKER"]['PROPERTY_EMAIL_VALUE'],
    "WHATSAPP" =>$arResult["DEFAULT_BROKER"]['PROPERTY_WHATSAPP_VALUE'],
    "TELEGRAM" =>$arResult["DEFAULT_BROKER"]['PROPERTY_TELEGRAM_VALUE'],
    "TYPE_OF_PROPERTY" => "Вторичная"
);
//xprint($arResult['FILTER_VALUES']);
//xprint($arResult['BUILDING_TYPES']);
?>
<script>
    window.mapOptions = {
        center: [55.751574, 37.573856],
        zoom: 12,
        markers: {
            cluster: {
                default: '<?= SITE_TEMPLATE_PATH; ?>/img/catalog/markers/cluster.svg',
                hover: '<?= SITE_TEMPLATE_PATH; ?>/img/catalog/markers/cluster-hover.svg'
            },
            marker: {
                default: '<?= SITE_TEMPLATE_PATH; ?>/img/catalog/markers/marker.svg',
                hover: '<?= SITE_TEMPLATE_PATH; ?>/img/catalog/markers/marker-hover.svg',
                active: '<?= SITE_TEMPLATE_PATH; ?>/img/catalog/markers/marker-active.svg'
            }
        }
    }
</script>
<section class="section-heading" data-scroll-fx="data-scroll-fx">
    <div class="container">
        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","page_mobile",Array(
                "START_FROM" => (SITE_ID == "s2") ? 1 : 0,
                "PATH" => "",
            )
        );?>
        <?
        global $profi_seo_page_id;
        if(!empty($profi_seo_page_id)):
            ?>
            <h1 class="h1">
                <?
                $APPLICATION->IncludeComponent(
                    "profistudio:seo.page",
                    "h1",
                    array(
                        "COMPONENT_TEMPLATE" => "h1",
                        "FIELDS" => array(
                            0 => "DETAIL_TEXT",
                        ),
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000"
                    ),
                    false
                );?>
            </h1>
        <?else:?>
            <h1 class="h1">Вторичная недвижимость <span class="desctop-title-city"><?if(SITE_ID == "s2"):?>Санкт-Петербурга<?else:?>Москвы<?endif;?></span> <span class="mobile-title-city"><?if(SITE_ID == "s2"):?>СПБ<?else:?>МСК<?endif;?></span></h1>
        <?endif; ?>
    </div>
</section>
<section class="section-filters" data-scroll-fx="data-scroll-fx">
    <div class="catalog-filter-mobile"><button class="catalog-filter-mobile-control" data-catalog-filter-open="data-catalog-filter-open">
            <div></div><span>Фильтры</span>
        </button><button class="catalog-filter-mobile-control" data-catalog-change-map="data-catalog-change-map"><svg class="icon icon--location" width="11" height="16" viewbox="0 0 11 16">
                <use xlink:href="#location" />
            </svg><span>На карте</span></button><button class="catalog-filter-mobile-control" data-catalog-change-list="data-catalog-change-list"><svg class="icon icon--list" width="16" height="10" viewbox="0 0 16 10">
                <use xlink:href="#list" />
            </svg><span>Списком</span></button></div>
    <div class="container">
        <form class="catalog-filter" data-filter-tags="data-filter-tags">
            <div class="catalog-filter-title">
                <div class="div-title div-title-h4">Фильтры</div><button data-catalog-filter-close="data-catalog-filter-close" type="button"><svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                        <use xlink:href="#cross-light-large" />
                    </svg></button>
            </div>
            <div class="catalog-filter-body">
                <div class="catalog-filter-scroll">
                    <div class="catalog-filter-tags" data-tags="data-tags">
                        <ul class="list">
                            <li class="list-item list-item--control" data-tags-control="data-tags-control"><button id="filter-clear" class="button button--light" type="button">Сбросить всё</button></li>
                        </ul>
                    </div>
                    <div class="catalog-filter-hero">
                        <div class="catalog-filter-fields">
                            <div class="catalog-filter-tags" data-tags="data-tags">
                                <ul class="list">
                                    <li class="list-item list-item--control" data-tags-control="data-tags-control"><button id="filter-clear" class="button button--light" type="button">Сбросить всё</button></li>
                                </ul>
                            </div>
                            <div class="catalog-filter-item catalog-filter-item--search">
                                <div class="dropdown dropdown--search" id="filter-search" data-search-line="data-search-line" data-search-line-url="/ajax/search_resale_<?=$arParams['CITY_CODE'];?>.php">
                                    <input type="hidden" value='<?= ($arResult["FILTER_VALUES"]["SEARCH"]) ? $arResult["FILTER_VALUES"]["SEARCH"] : '[]'?>' data-search-line-value="data-search-line-value">
                                    <div class="field field--search"><input class="field-input" type="text" data-placeholder-long="Начните вводить метро, район, улицу, застройщика" data-placeholder-short="Метро, район, улица, застройщик" placeholder="Метро, район, улица, застройщик" data-search-line-input="data-search-line-input"><button class="field-search-clear" type="button" data-search-line-clear="data-search-line-clear"><svg class="icon icon--cross-light" width="16" height="16" viewbox="0 0 16 16">
                                                <use xlink:href="#cross-light" />
                                            </svg></button><label class="checkbox-trigger"><input type="checkbox" data-search-line-toggler="data-search-line-toggler"><span>Исключить из выдачи</span></label></div>
                                    <div class="dropdown-values">
                                        <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                            <div class="dropdown-values-search-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?if (!empty($arResult["APARTMENT_TYPES"])) :?>
                                <div class="catalog-filter-item">
                                    <div class="dropdown dropdown--checkbox" id="filter-apartment-type" data-dropdown="checkbox">
                                        <input type="hidden" value="<?= ($arResult["FILTER_VALUES"]["APARTMENT_TYPE"])? $arResult["FILTER_VALUES"]["APARTMENT_TYPE"] : null?>" data-dropdown-value="data-dropdown-value">
                                        <div class="field-input">Тип квартиры</div>
                                        <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                <use xlink:href="#dropdown" />
                                            </svg></div>
                                        <div class="dropdown-values">
                                            <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                                <ul class="list">
                                                    <?foreach ($arResult["APARTMENT_TYPES"] as $arApartmenttypes):?>
                                                        <?if ($arApartmenttypes["ID"] == 0) $arApartmenttypes["UF_ID"] = "s";?>
                                                        <li class="list-item"
                                                            data-dropdown-id="<?= $arApartmenttypes['ID']; ?>">
                                                            <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                                    <use xlink:href="#check" />
                                                                </svg></div><span><?= $arApartmenttypes['VALUE']; ?></span>
                                                        </li>
                                                    <?endforeach;?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?endif;?>
                            <?if ($arResult["FILTER_RANGES"]["MIN_SQUARE"] != $arResult["FILTER_RANGES"]["MAX_SQUARE"]): ?>
                                <div class="catalog-filter-item">
                                    <div class="catalog-filter-item-label">Площадь</div>
                                    <div class="dropdown dropdown--radio" id="filter-size" data-dropdown="range-select" data-dropdown-min="<?= $arResult["FILTER_RANGES"]["MIN_SQUARE"]; ?>" data-dropdown-max="<?= $arResult["FILTER_RANGES"]["MAX_SQUARE"]; ?>" data-dropdown-prefix-label="Площадь" data-dropdown-prefix-form="от" data-dropdown-prefix-to="до" data-dropdown-postfix="м²">
                                        <div class="field-input field-input--center">
                                            <div class="field-input-inside"><span>Площадь</span>
                                                <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                        <use xlink:href="#dropdown" />
                                                    </svg></div>
                                            </div>
                                        </div>
                                        <div class="dropdown-values">
                                            <div class="dropdown-values-range-select">
                                                <div class="dropdown-values-range-select-item" data-dropdown-from="data-dropdown-from">
                                                    <div class="dropdown-values-range-select-label">
                                                        <div class="field-input" data-dropdown-label="data-dropdown-label">от<div>,</div> <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MIN_SQUARE"]; ?>"><?= $arResult["FILTER_RANGES"]["MIN_SQUARE"]; ?></span> м²</div><input class="field-input" type="number" value="<?= ($arResult["FILTER_VALUES"]["SQUARE"][0])? $arResult["FILTER_VALUES"]["SQUARE"][0] : null?>" data-dropdown-value="data-dropdown-value">
                                                    </div>
                                                </div>
                                                <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                                    <div class="dropdown-values-range-select-label">
                                                        <div class="field-input" data-dropdown-label="data-dropdown-label">до<div>,</div> <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MAX_SQUARE"]; ?>"><?= $arResult["FILTER_RANGES"]["MAX_SQUARE"]; ?></span> м²</div><input class="field-input" type="number" value="<?= ($arResult["FILTER_VALUES"]["SQUARE"][1])? $arResult["FILTER_VALUES"]["SQUARE"][1] : null?>" data-dropdown-value="data-dropdown-value">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?endif;?>
                            <div class="catalog-filter-item">
                                <div class="catalog-filter-item-label">Цена</div>
                                <div class="dropdown" id="filter-price" data-dropdown="price" data-dropdown-prefix-form="от" data-dropdown-prefix-to="до">
                                    <div class="field-input field-input--center">
                                        <div class="field-input-inside"><span>Цена</span>
                                            <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                    <use xlink:href="#dropdown" />
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-values dropdown-values--price">
                                        <div class="filter-price" data-price-filter="data-price-filter">
                                            <div class="filter-price-values">
                                                <div class="filter-price-from">
                                                    <input class="field-input" data-price-from-label="data-price-from-label" disabled="disabled">
                                                    <input class="field-input" type="number" data-price-from="data-price-from" value="<?= (!empty($arResult["FILTER_VALUES"]["PRICE"][0]) && (($arResult["FILTER_VALUES"]["PRICE"][0] * 1000000) != $arResult["FILTER_RANGES"]["MIN_PRICE"])) ? $arResult["FILTER_VALUES"]["PRICE"][0] * 1000000  : ''; ?>">
                                                </div>
                                                <div class="filter-price-to">
                                                    <input class="field-input" data-price-to-label="data-price-to-label" disabled="disabled">
                                                    <input class="field-input" type="number" data-price-to="data-price-to" value="<?= (!empty($arResult["FILTER_VALUES"]["PRICE"][1]) && (($arResult["FILTER_VALUES"]["PRICE"][1] * 1000000) != $arResult["FILTER_RANGES"]["MAX_PRICE"])) ? $arResult["FILTER_VALUES"]["PRICE"][1] * 1000000  : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="filter-price-line"
                                                 data-price="data-price"
                                                 data-price-step="1"
                                                 data-price-min="<?= $arResult["FILTER_RANGES"]["MIN_PRICE"]; ?>"
                                                 data-price-max="<?= $arResult["FILTER_RANGES"]["MAX_PRICE"]; ?>"
                                                 data-price-min-current="<? if (isset($arResult["FILTER_VALUES"]["PRICE"][0])): ?><?= $arResult["FILTER_VALUES"]["PRICE"][0]*1000000 ?><? else: ?><?= $arResult["FILTER_RANGES"]["MIN_PRICE"] ?><? endif; ?>"
                                                 data-price-max-current="<? if (isset($arResult["FILTER_VALUES"]["PRICE"][1])): ?><?= $arResult["FILTER_VALUES"]["PRICE"][1]*1000000 ?><? else: ?><?= $arResult["FILTER_RANGES"]["MAX_PRICE"] ?><? endif; ?>"
                                            >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="catalog-filter-more"><button data-filter-more="data-filter-more" data-filter-more-show="Еще фильтры" data-filter-more-hide="Скрыть" type="button"><span>Еще фильтры</span><svg class="icon icon--cross" width="8" height="8" viewbox="0 0 8 8">
                                    <use xlink:href="#cross" />
                                </svg></button></div>
                        <div class="catalog-filter-controls"><button class="button button--map" type="button" data-catalog-change-map="data-catalog-change-map"><svg class="icon icon--location" width="11" height="16" viewbox="0 0 11 16">
                                    <use xlink:href="#location" />
                                </svg><span>На карте</span></button><button class="button button--default" type="button" data-catalog-change-list="data-catalog-change-list"><svg class="icon icon--list" width="16" height="10" viewbox="0 0 16 10">
                                    <use xlink:href="#list" />
                                </svg><span>Списком</span></button><button id="apply-filter" disabled="disabled" class="button button--light" type="button">Показать (<span><?= $arResult["FILTER_RANGES"]["ALL_COUNT"] ?></span>)</button></div>
                    </div>
                    <div class="catalog-filter-other">
                        <div class="catalog-filter-item">
                            <div class="dropdown dropdown--checkbox" id="filter-features" data-dropdown="checkbox">
                                <input type="hidden" value="<?= ($arResult["FILTER_VALUES"]["FEATURES"])? $arResult["FILTER_VALUES"]["FEATURES"] : null?>" data-dropdown-value="data-dropdown-value">
                                <div class="field-input field-input--center">
                                    <div class="field-input-inside"><span>Особенности</span>
                                        <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                <use xlink:href="#dropdown" />
                                            </svg></div>
                                    </div>
                                </div>
                                <div class="dropdown-values">
                                    <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                        <ul class="list">
                                            <li class="list-item" data-dropdown-id="terrace">
                                                <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                        <use xlink:href="#check" />
                                                    </svg></div><span>C террасой</span>
                                            </li>
                                            <li class="list-item" data-dropdown-id="twotier">
                                                <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                        <use xlink:href="#check" />
                                                    </svg></div><span>Двухуровневые</span>
                                            </li>
                                            <li class="list-item" data-dropdown-id="highceilings">
                                                <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                        <use xlink:href="#check" />
                                                    </svg></div><span>С высокими потолками</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?if ($arResult["FILTER_RANGES"]["MIN_FLOOR"] != $arResult["FILTER_RANGES"]["MAX_FLOOR"]): ?>
                            <div class="catalog-filter-item">
                                <div class="catalog-filter-item-label">Этаж</div>
                                <div class="dropdown dropdown--radio" id="filter-floor" data-dropdown="range-select" data-dropdown-min="<?= $arResult["FILTER_RANGES"]["MIN_FLOOR"]; ?>" data-dropdown-max="<?= $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?>" data-dropdown-prefix-form="от" data-dropdown-prefix-to="до" data-dropdown-postfix="этажа" data-dropdown-postfix-equal="этаж">
                                    <div class="field-input field-input--center">
                                        <div class="field-input-inside"><span>Этаж</span>
                                            <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                    <use xlink:href="#dropdown" />
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-values">
                                        <div class="dropdown-values-range-select">
                                            <div class="dropdown-values-range-select-item" data-dropdown-from="data-dropdown-from">
                                                <div class="dropdown-values-range-select-label">
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">от<div>,</div> <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MIN_FLOOR"]; ?>"><?= $arResult["FILTER_RANGES"]["MIN_FLOOR"]; ?></span> этажа</div><input class="field-input" type="number" value="<?=$arResult["FILTER_VALUES"]["FLOOR"][0] ? $arResult["FILTER_VALUES"]["FLOOR"][0] : null?>" data-dropdown-value="data-dropdown-value">
                                                </div>
                                                <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                                    <ul class="list">
                                                        <?
                                                        $floor = $arResult["FILTER_RANGES"]["MIN_FLOOR"];
                                                        while ($floor <= $arResult["FILTER_RANGES"]["MAX_FLOOR"]) {
                                                            if ($floor == 0) $floor++;
                                                            echo '<li class="list-item" data-dropdown-id="'.$floor.'">'.$floor.' этаж</li>';
                                                            $floor++ ;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                                <div class="dropdown-values-range-select-label">
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">до<div>,</div> <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?>"><?= $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?></span> этаж</div><input class="field-input" type="number" value="<?=$arResult["FILTER_VALUES"]["FLOOR"][1] ? $arResult["FILTER_VALUES"]["FLOOR"][1] : null?>" data-dropdown-value="data-dropdown-value">
                                                </div>
                                                <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                                    <ul class="list">
                                                        <?
                                                        $floor = $arResult["FILTER_RANGES"]["MIN_FLOOR"];
                                                        while ($floor <= $arResult["FILTER_RANGES"]["MAX_FLOOR"]) {
                                                            if ($floor == 0) $floor++;
                                                            echo '<li class="list-item" data-dropdown-id="'.$floor.'">'.$floor.' этаж</li>';
                                                            $floor++ ;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>
                        <?if ($arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"] != $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]): ?>
                            <div class="catalog-filter-item">
                                <div class="catalog-filter-item-label">Размер кухни</div>
                                <div class="dropdown dropdown--radio" id="filter-kitchen-size" data-dropdown="range-select" data-dropdown-min="<?= $arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"]; ?>" data-dropdown-max="<?= $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]; ?>" data-dropdown-prefix-label="Размер кухни" data-dropdown-prefix-form="от" data-dropdown-prefix-to="до" data-dropdown-postfix="м²">
                                    <div class="field-input field-input--center">
                                        <div class="field-input-inside"><span>Размер кухни</span>
                                            <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                    <use xlink:href="#dropdown" />
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-values">
                                        <div class="dropdown-values-range-select">
                                            <div class="dropdown-values-range-select-item" data-dropdown-from="data-dropdown-from">
                                                <div class="dropdown-values-range-select-label">
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">от<div>,</div> <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"]; ?>"><?= $arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"]; ?></span> м²</div><input class="field-input" type="number" value="<?= ($arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][0])? $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][0] : null?>" data-dropdown-value="data-dropdown-value">
                                                </div>
                                            </div>
                                            <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                                <div class="dropdown-values-range-select-label">
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">до<div>,</div> <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]; ?>"><?= $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]; ?></span> м²</div><input class="field-input" type="number" value="<?= ($arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][1])? $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][1] : null?>" data-dropdown-value="data-dropdown-value">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>
                        <?if (!empty($arResult["FINISHES"])) :?>
                            <div class="catalog-filter-item">
                                <div class="dropdown dropdown--checkbox" id="filter-finish" data-dropdown="checkbox">
                                    <input type="hidden" value="<?= ($arResult["FILTER_VALUES"]["DECORATION"])? $arResult["FILTER_VALUES"]["DECORATION"] : null?>" data-dropdown-value="data-dropdown-value">
                                    <div class="field-input field-input--center">
                                        <div class="field-input-inside"><span>Отделка</span>
                                            <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                    <use xlink:href="#dropdown" />
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-values">
                                        <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                            <ul class="list">
                                                <?foreach ($arResult["FINISHES"] as $arFinish):?>
                                                    <li class="list-item"
                                                        data-dropdown-id="<?= $arFinish['UF_ID']; ?>">
                                                        <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                                <use xlink:href="#check" />
                                                            </svg></div><span><?= $arFinish['UF_NAME']; ?></span>
                                                    </li>
                                                <?endforeach;?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>
                        <?if ($arResult["BUILDING_TYPES"]) :?>
                            <div class="catalog-filter-item">
                                <div class="dropdown dropdown--checkbox" id="filter-property-type" data-dropdown="checkbox">
                                    <input type="hidden" value="<?= ($arResult["FILTER_VALUES"]["BUILD_TYPES"])? $arResult["FILTER_VALUES"]["BUILD_TYPES"] : null?>" data-dropdown-value="data-dropdown-value">
                                    <div class="field-input field-input--center">
                                        <div class="field-input-inside"><span>Тип недвижимости</span>
                                            <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                    <use xlink:href="#dropdown" />
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-values">
                                        <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                            <ul class="list">
                                                <?foreach ($arResult['BUILDING_TYPES'] as $arBuildtype):?>
                                                    <li class="list-item"
                                                        data-dropdown-id="<?= $arBuildtype['UF_XML_ID'];?>">
                                                        <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                                <use xlink:href="#check" />
                                                            </svg></div><span><?= $arBuildtype['UF_NAME'];?></span>
                                                    </li>
                                                <?endforeach;?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>
                        <div class="catalog-filter-item catalog-filter-item--links">
                            <div class="dropdown dropdown--link" data-dropdown="link">
                                <div class="field-input field-input--center">
                                    <div class="field-input-inside"><span>Вторичная</span>
                                        <div class="dropdown-icon">
                                            <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                <use xlink:href="#dropdown"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-values">
                                    <ul class="list">
                                        <li class="list-item list-item--active"><span class="list-link">Вторичная</span></li>
                                        <li class="list-item"><a class="list-link" href="<?="/".$arParams['CITY_CODE']."/novostroyki/";?>">Новостройки</a></li>
                                        <li class="list-item"><a class="list-link" href="<?="/".$arParams['CITY_CODE']."/kupit-dom/";?>">Загородная</a></li>
                                        <li class="list-item"><a class="list-link" href="<?="/".$arParams['CITY_CODE']."/nedvizhimost-za-rubezhom/";?>">Зарубежная</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<div class="catalog-tab" data-catalog-list>
    <section class="section-heading" data-scroll-fx="data-scroll-fx" itemtype="https://schema.org/Product" itemscope>
        <div itemprop="offers" itemtype="https://schema.org/AggregateOffer" itemscope>
            <meta itemprop="lowPrice" content="<?= $arResult["FILTER_RANGES"]["MIN_PRICE"] ?>" />
            <meta itemprop="highPrice" content="<?= $arResult["FILTER_RANGES"]["MAX_PRICE"] ?>" />
            <meta itemprop="offerCount" content="<?= $arResult["FILTER_RANGES"]["ALL_COUNT"] ?>" />
            <meta itemprop="priceCurrency" content="RUB" />
        </div>

        <div class="container">
            <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","page",Array(
                    "START_FROM" => (SITE_ID == "s2") ? 1 : 0,
                    "PATH" => "",
                )
            );?>
            <?
            global $profi_seo_page_id;
            if(!empty($profi_seo_page_id)):?>
                <h1 itemprop="name">
                    <?
                    $APPLICATION->IncludeComponent(
                        "profistudio:seo.page",
                        "h1",
                        array(
                            "COMPONENT_TEMPLATE" => "h1",
                            "FIELDS" => array(
                                0 => "DETAIL_TEXT",
                            ),
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000"
                        ),
                        false
                    );?>
                </h1>
				<meta itemprop="description" content="<?
                    $APPLICATION->IncludeComponent(
                        "profistudio:seo.page",
                        "h1",
                        array(
                            "COMPONENT_TEMPLATE" => "h1",
                            "FIELDS" => array(
                                0 => "DETAIL_TEXT",
                            ),
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000"
                        ),
                        false
                    );?>" />
            <?else:?>
                <h1 itemprop="name">Вторичная недвижимость <span class="desctop-title-city"><?if(SITE_ID == "s2"):?>Санкт-Петербурга<?else:?>Москвы<?endif;?></span> <span class="mobile-title-city"><?if(SITE_ID == "s2"):?>СПБ<?else:?>МСК<?endif;?></span></h1>
				<meta itemprop="description" content="Вторичная недвижимость <?if(SITE_ID == "s2"):?>Санкт-Петербурга<?else:?>Москвы<?endif;?>" />
            <?endif; ?>
        </div>
    </section>
    <section class="section-catalog">
        <?
        if ($arResult['IS_AJAX'])
            $APPLICATION->RestartBuffer();
        ?>
        <? if ($arResult["FILTER_RANGES"]["ALL_COUNT"] > 0): ?>
            <div class="params-content" data-scroll-fx>
                <div class="container">
                    <div class="catalog">
                        <div class="catalog-headnote" data-scroll-fx="data-scroll-fx">
                            <div class="catalog-headnote-data">
                                <div><?= plural_form($arResult["FILTER_RANGES"]["ALL_COUNT"], array("предложение", "предложения", "предложений")); ?></div>
                                <div><a href="#broker" data-modal="data-modal">Заказать подбор брокеру</a></div>
                            </div>
                            <div class="catalog-headnote-sort">
                                <div id="sort" class="dropdown dropdown--radio dropdown--no-border" data-dropdown="radio">
                                    <input type="hidden" value="<?= ($arResult['SORT']['TYPE']) ? $arResult['SORT']['TYPE']."-".$arResult['SORT']['BY'] : null?>" data-dropdown-value="data-dropdown-value">
                                    <div class="field-input">Сортировка <span data-dropdown-label="<?=$arResult['SORT']['NAME']?>"><?=$arResult['SORT']['NAME']?></span></div>
                                    <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                            <use xlink:href="#dropdown" />
                                        </svg></div>
                                    <div class="dropdown-values">
                                        <div class="dropdown-values-scroll" data-simplebar="data-simplebar">
                                            <ul class="list">
                                                <?//<li class="list-item" data-dropdown-id="default">по умолчанию</li>?>
                                                <li class="list-item" data-dropdown-id="price-asc">по цене (возрастание)</li>
                                                <li class="list-item" data-dropdown-id="price-desc">по цене (убывание)</li>
                                                <li class="list-item" data-dropdown-id="square-asc">по площади (возрастание)</li>
                                                <li class="list-item" data-dropdown-id="square-desc">по площади (убывание)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ajax-content">
                            <div class="catalog-list catalog-list--other" data-scroll-fx>
                                <script>
                                    window.mapItems = [];
                                </script>
                                <?
                                if ($arResult['IS_AJAX'] && $arResult["SHOW_MORE"])
                                    $APPLICATION->RestartBuffer();
                                ?>
                                <?foreach ($arResult["OBJECTS"]["ITEMS"] as $arObject):?>
                                    <div class="catalog-item">
                                        <a class="object-other" href="<?= $arObject['SECTION_PAGE_URL']; ?>">
                                            <? if (!empty($arObject['PROPERTY_SLIDER_MINI_VALUE']) && count($arObject['PROPERTY_SLIDER_MINI_VALUE']) > 1): ?>
                                                <div class="object-other-gallery" data-hover-gallery>
                                                    <?
                                                    $countPicture = 1;
                                                    foreach ($arObject['PROPERTY_SLIDER_MINI_VALUE'] as $picture):
                                                        ?>
                                                        <div class="<?= $countPicture == 1 ? 'object-other-gallery-active' : '' ?> lazy"
                                                             data-bg="<?= $picture; ?>" alt="<?= $arObject['NAME']; ?>"></div>
                                                        <?
                                                        $countPicture++;
                                                    endforeach; ?>
                                                    <ul class="list">
                                                        <?
                                                        $i =1;
                                                        for ($i; $i < $countPicture; $i++) {?>
                                                            <li class="list-item <?= $i == 1 ? 'list-item--active' : ''; ?>"><?= $i;?></li>
                                                        <?} ?>
                                                    </ul>
                                                </div>
                                            <? elseif ($arObject['PROPERTY_SLIDER_MINI_VALUE'][0]): ?>
                                                <div class="object-other-image lazy"
                                                     data-bg="<?= $arObject['PROPERTY_SLIDER_MINI_VALUE'][0]; ?>" alt="<?= $arObject['NAME']; ?>"></div>
                                            <? elseif ($arObject['DETAIL_PICTURE']['SRC']): ?>
                                                <div class="object-other-image lazy"
                                                     data-bg="<?= $arObject['DETAIL_PICTURE']['SRC']; ?>" alt="<?= $arObject['NAME']; ?>"></div>
                                            <? else: ?>
                                                <div class="object-other-image-empty">
                                                    <div>На данный момент фотографии этого объекта отсутствуют</div>
                                                </div>
                                            <? endif; ?>
                                            <div class="object-other-info">
                                                <div class="h4-title"><?= $arObject['NAME']; ?></div>
                                                <div class="object-other-data">
                                                    <? if (!empty($arObject['PROPERTY_SQUARE_VALUE'])): ?>
                                                        <span><?= $arObject['PROPERTY_SQUARE_VALUE']; ?> м²</span>
                                                    <? endif; ?>
                                                    <? if (!empty($arObject['PROPERTY_ROOMS_NUMBER_VALUE'])): ?>
                                                        <span><?= plural_form($arObject['PROPERTY_ROOMS_NUMBER_VALUE'], array("комната", "комнаты", "комнат")); ?></span>
                                                    <? endif; ?>
                                                    <? if (!empty($arObject['PROPERTY_DECORATION_VALUE'])): ?>
                                                        <span><?= $arResult['FINISHES'][$arObject['PROPERTY_DECORATION_VALUE']]['UF_NAME']; ?></span>
                                                    <? endif; ?>
                                                </div>
                                                <? if (!empty($arObject['PROPERTY_PRICE_RUB_VALUE'])): ?>
                                                    <div class="object-other-price">
                                                        <?= number_format($arObject['PROPERTY_PRICE_RUB_VALUE'], 0, ',', ' '); ?> ₽
                                                    </div>
                                                <? endif; ?>
                                            </div>
                                        </a>
                                    </div>
                                    <?if ($arResult['IS_AJAX'] && $arResult["SHOW_MORE"] && $arResult["PAGE"] == 2 && $banner == 0): ?>
                                        <div class="catalog-item">
                                            <div class="object-other-help">
                                                <h4 class="h2">Устали искать? Подберем для вас то, что нужно в&nbsp;2&nbsp;счета</h4>
                                                <p>Оставьте заявку и&nbsp;наши брокеры помогут вам найти то&nbsp;самое жилье</p><a class="button button--default" href="#broker" data-modal="data-modal">Отправить заявку</a>
                                            </div>
                                        </div>
                                        <?$banner++;?>
                                    <?endif;?>
                                <?endforeach;?>
                                <?
                                if ($arResult['IS_AJAX'] && $arResult["SHOW_MORE"])
                                    die();
                                ?>
                                <?foreach ($arResult["ALL_OBJECTS"] as $arObject):?>
                                    <script>
                                        <?
                                        $arObject['PROPERTY_MAP_VALUE'] = explode(",",$arObject['PROPERTY_MAP_VALUE']);
                                        $itemMap = [];
                                        $itemMap['id'] = $arObject["ID"];
                                        $itemMap['coords'] = [$arObject['PROPERTY_MAP_VALUE'][0], $arObject['PROPERTY_MAP_VALUE'][1]];
                                        $itemMap['link'] = $arObject["SECTION_PAGE_URL"];
                                        //$itemMap['images'] = [$arObject['PROPERTY_SLIDER_MINI_VALUE'][0]];
                                        if (!empty($arObject['PROPERTY_SLIDER_MINI_VALUE']) && count($arObject['PROPERTY_SLIDER_MINI_VALUE']) > 1){
                                            foreach ($arObject['PROPERTY_SLIDER_MINI_VALUE'] as $arPhoto) {
                                                $itemMap['images'][] = $arPhoto;
                                            }
                                        }
                                        elseif (!empty($arObject['PROPERTY_SLIDER_MINI_VALUE'][0])) {
                                            $itemMap['images'] = [$arObject['PROPERTY_SLIDER_MINI_VALUE'][0]];
                                        }
                                        else {
                                            $itemMap['images'] = "";
                                        }
                                        $itemMap['title'] = $arObject["NAME"];
                                        $itemMap['options'] = [
                                            (!empty($arObject["PROPERTY_SQUARE_VALUE"])) ?
                                                $arObject["PROPERTY_SQUARE_VALUE"].' м²' : "",
                                            (!empty($arObject['PROPERTY_ROOMS_NUMBER_VALUE'])) ?
                                                plural_form($arObject['PROPERTY_ROOMS_NUMBER_VALUE'], array("комната", "комнаты", "комнат")) : "",
                                            (!empty($arResult['FINISHES'][$arObject['PROPERTY_DECORATION_VALUE']]['UF_NAME'])) ?
                                                $arResult['FINISHES'][$arObject['PROPERTY_DECORATION_VALUE']]['UF_NAME'] : ""
                                        ];
                                        $itemMap['price'] = (!empty($arObject["PROPERTY_PRICE_RUB_VALUE"])) ? number_format($arObject["PROPERTY_PRICE_RUB_VALUE"], 0, ',', ' ')." ₽" : "";
                                        ?>
                                        mapItems.push(<?=json_encode($itemMap, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);?>);
                                    </script>
                                <?endforeach;?>
                            </div>
                            <?if ($arResult["OBJECTS"]["SHOW_MORE"]): ?>
                                <div class="pagination-more" data-scroll-fx="data-scroll-fx">
                                    <button id="show-more" style="pointer-events: all;" class="button button--inverse button--refresh" data-page="1">
                                        <svg class="icon icon--refresh" width="18" height="22" viewbox="0 0 18 22">
                                            <use xlink:href="#refresh" />
                                        </svg>
                                        <span>Показать еще</span>
                                    </button>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
                </div>
            </div>
        <? else: ?>
            <div class="params-content" data-scroll-fx>
                <div class="container">
                    <div class="catalog-empty params-noresult" data-scroll-fx>По вашему запросу не найдено предложений.</div>
                </div>
            </div>
        <? endif; ?>
        <?
        if ($arResult['IS_AJAX'])
            die();
        ?>
    </section>
    <section class="section-quiz" data-scroll-fx="data-scroll-fx">
        <div class="container">
            <div class="quiz">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/local/include/forms/form_quiz_catalog.php",
                        "EDIT_TEMPLATE" => "",
                        "FORM_CODE" => "quiz-catalog-form",
                    ),
                    false
                );?>
            </div>
        </div>
    </section>

    <?
    global $profi_seo_page_id;
    if(!empty($profi_seo_page_id)):
        $APPLICATION->IncludeComponent(
            "profistudio:seo.page",
            ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "FIELDS" => array(
                    0 => "DETAIL_TEXT",
                ),
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000"
            ),
            false
        );
    else:?>
        <?
        $arGet = [
            "search" => "",
            "deadline" => "",
            "features" => "",
            "finish" => "",
            "buildtype" => "",
            "sort" => "",
            "sortBy" => "",
            "price_min" => "",
            "price_max" => "",
            "floor_min" => "",
            "floor_max" => "",
            "square_min" => "",
            "square_max" => "",
            "kitchensizemin" => "",
            "kitchensizemax" => "",
            "apartmenttype" => "",
        ];
        $arFindedGet = array_intersect_key ($arGet, $_GET);
        if (!$arFindedGet) :?>
            <section class="section-catalog-about" data-scroll-fx="data-scroll-fx">
                <div class="container">
                    <div class="catalog-about">
                        <div class="catalog-about-title">
                            <h2 class="h1"><?= $arResult['SEO']['PROPERTY_H_RESALE_VALUE']?></h2>
                        </div>
                        <div class="catalog-about-text">
                            <?= $arResult['SEO']['~PROPERTY_DESC_RESALE_VALUE']['TEXT']; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?endif;?>
    <?endif; ?>
</div>
<div class="catalog-tab" data-catalog-map="data-catalog-map">
    <section class="section-catalog-map">
        <div class="catalog-map">
            <div id="map"></div>
            <div class="catalog-map-object" data-map-object="data-map-object"></div>
            <button class="catalog-map-close" data-catalog-change-list="data-catalog-change-list">
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large" />
                </svg>
                <span>Списком</span>
            </button>
        </div>
    </section>
</div>
<script>
    $(function () {
        function checkFilterParams() {
            var sort = $('#sort input').val();
            if (!sort) var sort = 'default';

            var search = jQuery('#filter-search input').val();

            var features = jQuery('#filter-features input').val();

            var apartmenttype = jQuery('#filter-apartment-type input').val();

            var finish = jQuery('#filter-finish input').val();

            var buildtype = jQuery('#filter-property-type input').val();

            if (jQuery('[data-price-from]').val().replace(/ /g, '') != jQuery('[data-price-min]').attr('data-price-min')) {
                var priceMin = parseFloat(jQuery('[data-price-from]').val().replace(/ /g, ''))/1000000;
            }

            if (jQuery('[data-price-to]').val().replace(/ /g, '') != jQuery('[data-price-max]').attr('data-price-max')) {
                var priceMax = parseFloat(jQuery('[data-price-to]').val().replace(/ /g, '')) / 1000000;
            }

            if (parseInt(jQuery('#filter-floor [data-dropdown-from] input').val())) {
                var floorMin = parseInt(jQuery('#filter-floor [data-dropdown-from] input').val());
            }

            if (parseInt(jQuery('#filter-floor [data-dropdown-to] input').val())) {
                var floorMax = parseInt(jQuery('#filter-floor [data-dropdown-to] input').val());
            }

            if (parseFloat(jQuery('#filter-size [data-dropdown-from] input').val())) {
                var squareMin = parseFloat(jQuery('#filter-size [data-dropdown-from] input').val());
            }

            if (parseFloat(jQuery('#filter-size [data-dropdown-to] input').val())) {
                var squareMax = parseFloat(jQuery('#filter-size [data-dropdown-to] input').val());
            }

            if (parseFloat(jQuery('#filter-kitchen-size [data-dropdown-from] input').val())) {
                var squareKitchenMin = parseFloat(jQuery('#filter-kitchen-size [data-dropdown-from] input').val());
            }

            if (parseFloat(jQuery('#filter-kitchen-size [data-dropdown-to] input').val())) {
                var squareKitchenMax = parseFloat(jQuery('#filter-kitchen-size [data-dropdown-to] input').val());
            }

            var params = {
                'search': (search && search.length > 0) ? search : null,
                'apartmenttype': (apartmenttype && apartmenttype.length > 0) ? apartmenttype : null,
                'deadline': $('#filter-deadline input').val() ? $('#filter-deadline input').val() : null,
                'features': (features && features.length > 0) ? features : null,
                'finish': (finish && finish.length > 0) ? finish : null,
                'buildtype': (buildtype && buildtype.length > 0) ? buildtype : null
            };
            if (sort != 'default' && sort.length >= 1) {
                sort = sort.split('-');
                params.sort = sort[0];
                params.sortBy = sort[1];
            } else {
                params.sort = 'default';
            }
            if (priceMin) params.pricemin = priceMin;
            if (priceMax) params.pricemax = priceMax;
            if (floorMin) params.floormin = floorMin;
            if (floorMax) params.floormax = floorMax;
            if (squareMin) params.sizemin = squareMin;
            if (squareMax) params.sizemax = squareMax;
            if (squareKitchenMin) params.kitchensizemin = squareKitchenMin;
            if (squareKitchenMax) params.kitchensizemax = squareKitchenMax;
            //console.log(params);
            return params;
        }

        function setUrlParamsPageParametrical(params) {
            var arr = [];
            for (var key in params){
                if(params[key] != null){
                    arr.push(key + '=' + params[key]);
                }
            }

            return (arr.length > 0) ? '?' + arr.join('&') : window.location.origin + window.location.pathname;
        }

        function getCount(event = null) {
            var event = event.target || event;
            var params = checkFilterParams();

            params.getCount = true;

            //console.log(params);

            jQuery.ajax({
                type: "GET",
                data: params,
                dataType: 'html',
                success: function (count) {
                    jQuery('#apply-filter > span').text(count);
                    if (count > 0 ) {
                        jQuery('#apply-filter').removeAttr('disabled');
                    }
                    else {
                        jQuery('#apply-filter').attr('disabled','disabled');
                    }
                }
            });
        }

        function getObjects(count) {

            <?if($arParams["AJAX"]):?>
            var params = checkFilterParams();
            //console.log(params);

            jQuery.ajax({
                type: "GET",
                data: params,
                dataType: 'html',
                success: function (html) {
                    //console.log(html);
                    if (html) {
                        if (count) {
                            let allCount = parseInt($(html).find('.catalog-headnote-data div:first-child').text());
                            jQuery('#apply-filter > span').text(allCount);
                        }
                        if (jQuery('.catalog-empty.params-noresult'))
                            jQuery('.catalog-empty.params-noresult').fadeOut(300).remove();

                        jQuery('#apply-filter').attr('disabled', 'disabled');
                        //console.log($(html).find('.ajax-content').html());
                        jQuery('.ajax-content').html($(html).find('.ajax-content').html());
                        jQuery('.catalog-headnote-data').html($(html).find('.catalog-headnote-data').html());
                        //jQuery('.section-catalog:not(.-noresult)').html($(html).html());

                        var queryParams = setUrlParamsPageParametrical(params);
                        history.replaceState(null, null, queryParams);

                        revealNew();
                        lazyload.update();
                        catalogMap.handleClear();
                        catalogMap.handleCreateMarkers();
                        catalogMap.handleAddObjects();
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
            var url = '/<?= $arResult['CITY']; ?>/kupit-kvartiru/' + queryParams;
            location.href = url;

            <?endif;?>
        }

        function getPage() {
            var page = parseInt(jQuery('#show-more').data('page')) + 1;

            var params = checkFilterParams();

            params.showMore = true;
            params.page = page;

            // 'sort': sort,
            //'sortBy': sortBy
            //console.log(params);

            jQuery.ajax({
                type: "GET",
                data: params,
                dataType: 'html',
                beforeSend : function () {
                    $('#show-more').prop('disabled', true);
                },
                success: function (html) {
                    //console.log(html);
                    if (html) {
                        if (jQuery('.catalog-empty.no-result'))
                            jQuery('.catalog-empty.no-result').fadeOut(300).remove();

                        let allCount = Number(jQuery('#apply-filter > span').text())+1;
                        $('.catalog-list').append(html);

                        if ($('.catalog-list').find('div.catalog-item:not(.catalog-item--large)').length == allCount)
                            jQuery('#show-more').fadeOut(300).queue(function () {
                                $(this).remove();
                                $(this).dequeue();
                            });
                        jQuery('#show-more').data('page', page);
                        $('#show-more').prop('disabled', false);

                        revealNew();
                        lazyload.update();
                        catalogMap.handleCreateMarkers();
                        catalogMap.handleAddObjects();
                    }
                }
            });
        }

        jQuery(document).on('click', '#show-more', function (e) {
            e.preventDefault();

            /*var selector = jQuery('.apartment-table .apartment-table-head .apartment-sort.-active');
             var sort   = selector.data('filter') ? selector.data('filter') : 'sort';
             var sortBy = (selector.hasClass('-inverse')) ? 'asc' : 'desc';*/
            //console.log(sort);
            //console.log(sortBy);
            $('#show-more').addClass('button--refresh-load');
            getPage();
            $('#show-more').removeClass('button--refresh-load');
        });

        jQuery('[data-price]').each(function (indx, element) {
            $(element).get(0).noUiSlider.on('change', function (values, handle) {

                getCount(this);
            });
        });

        jQuery('#filter-size, #filter-kitchen-size, [data-price-from], [data-price-to]').on('change', function () {
            getCount(this);
        });

        jQuery('.params-sidebar .form-item[data-dropdown!="true"] input, .params-sidebar select, [name="finishtype"], [name="rooms"]').on('change', function (e) {
            getCount(e);
        });

        $('.catalog-filter').on('click', '[data-dropdown-id]', function (e) {
            e.preventDefault();
            setTimeout(function(){
                getCount(e);
            }, 500);
        });

        $('.catalog-filter').on('click', '[data-tage-remove]', function (e) {
            e.preventDefault();
            setTimeout(function(){
                if (/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                    getCount(e);
                }
                else {
                    getObjects(true);
                }
            }, 500);
        });

        $('#sort').on('click', '[data-dropdown-id]', function (e) {
            e.preventDefault();
            setTimeout(function(){
             getObjects();
             }, 300);
        });

        jQuery(document).on('click', '#apply-filter', function (e) {
            e.preventDefault();
            getObjects();
        });

        jQuery(document).on('click', '#filter-clear', function (e) {
            e.preventDefault();
            var url = '/<?=$arParams['CITY_CODE'];?>/kupit-kvartiru/';
            location.href = url;
        });
    });
</script>