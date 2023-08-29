<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);
$APPLICATION->SetPageProperty("title", "Tower Group - Выбрать квартиру новостройку");
$APPLICATION->SetPageProperty("description", "Выбор квартиры новостройки Tower Group.");
$APPLICATION->SetPageProperty("page", "catalog_new_build");
$APPLICATION->SetPageProperty("body-page", "class='-catalog-list'");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/catalog_new_build.min.css");
//xprint($arResult['OBJECTS']);
?>

    <section class="section-heading" data-scroll-fx="data-scroll-fx">
        <div class="container">
            <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","page_mobile",Array(
                    "START_FROM" => (SITE_ID == "s2") ? 1 : 0,
                    "PATH" => "",
                )
            );?>
            <h1 class="h1"><?=$arResult['LOCATION']['NAME']?></h1>
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
                    <h4 class="h2">Фильтры</h4><button data-catalog-filter-close="data-catalog-filter-close"><svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                            <use xlink:href="#cross-light-large" />
                        </svg></button>
                </div>
                <div class="catalog-filter-body">
                    <div class="catalog-filter-scroll">
                        <div class="catalog-filter-tags" data-tags="data-tags">
                            <ul class="list">
                                <li class="list-item list-item--control" data-tags-control="data-tags-control"><button class="button button--light" type="button">Сбросить всё</button></li>
                            </ul>
                        </div>
                        <div class="catalog-filter-hero">
                            <div class="catalog-filter-fields">
                                <div class="catalog-filter-item catalog-filter-item--search">
                                    <div class="dropdown dropdown--search" id="filter-search" data-search-line="data-search-line"><input type="hidden" value data-search-line-value="data-search-line-value">
                                        <div class="field field--search"><input class="field-input" type="text" data-placeholder-long="Начните вводить метро, район, локацию, улицу, ЖК, застройщика или банк" data-placeholder-short="Метро, район, ЖК, застройщик" placeholder="Метро, район, ЖК, застройщик" data-search-line-input="data-search-line-input"><button class="field-search-clear" type="button" data-search-line-clear="data-search-line-clear"><svg class="icon icon--cross-light" width="16" height="16" viewbox="0 0 16 16">
                                                    <use xlink:href="#cross-light" />
                                                </svg></button><label class="checkbox-trigger"><input type="checkbox" data-search-line-toggler="data-search-line-toggler"><span>Исключить из выдачи</span></label></div>
                                        <div class="dropdown-values">
                                            <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                                <div class="dropdown-values-search-list">
                                                    <div class="dropdown-values-search-item">
                                                        <div class="dropdown-values-search-title">Район</div>
                                                        <ul class="list">
                                                            <li class="list-item" data-dropdown-id="1">Район 1</li>
                                                            <li class="list-item" data-dropdown-id="2">Район 2</li>
                                                            <li class="list-item" data-dropdown-id="3">Район 3</li>
                                                        </ul>
                                                    </div>
                                                    <div class="dropdown-values-search-item">
                                                        <div class="dropdown-values-search-title">Застройщик</div>
                                                        <ul class="list">
                                                            <li class="list-item" data-dropdown-id="4">Застройщик 1</li>
                                                            <li class="list-item" data-dropdown-id="5">Застройщик 2</li>
                                                        </ul>
                                                    </div>
                                                    <div class="dropdown-values-search-item">
                                                        <div class="dropdown-values-search-title">Банк</div>
                                                        <ul class="list">
                                                            <li class="list-item" data-dropdown-id="6">Банк 1</li>
                                                            <li class="list-item" data-dropdown-id="7">Банк 2</li>
                                                            <li class="list-item" data-dropdown-id="8">Банк 3</li>
                                                            <li class="list-item" data-dropdown-id="9">Банк 4</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="catalog-filter-item catalog-filter-item--links">
                                    <div class="dropdown dropdown--link" data-dropdown="link">
                                        <div class="field-input">Новостройки</div>
                                        <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                <use xlink:href="#dropdown" />
                                            </svg></div>
                                        <div class="dropdown-values">
                                            <ul class="list">
                                                <li class="list-item list-item--active"><a class="list-link">Новостройки</a></li>
                                                <li class="list-item"><a class="list-link" href="#">Вторичная</a></li>
                                                <li class="list-item"><a class="list-link" href="#">Загородная</a></li>
                                                <li class="list-item"><a class="list-link" href="#">Зарубежная</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="catalog-filter-item">
                                    <div class="dropdown dropdown--checkbox" id="filter-apartment-type" data-dropdown="checkbox"><input type="hidden" value data-dropdown-value="data-dropdown-value">
                                        <div class="field-input">Тип квартиры</div>
                                        <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                <use xlink:href="#dropdown" />
                                            </svg></div>
                                        <div class="dropdown-values">
                                            <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                                <ul class="list">
                                                    <li class="list-item" data-dropdown-id="1">
                                                        <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                                <use xlink:href="#check" />
                                                            </svg></div><span>Тип квартиры 1</span>
                                                    </li>
                                                    <li class="list-item" data-dropdown-id="2">
                                                        <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                                <use xlink:href="#check" />
                                                            </svg></div><span>Тип квартиры 2</span>
                                                    </li>
                                                    <li class="list-item" data-dropdown-id="3">
                                                        <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                                <use xlink:href="#check" />
                                                            </svg></div><span>Тип квартиры 3</span>
                                                    </li>
                                                    <li class="list-item" data-dropdown-id="4">
                                                        <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                                <use xlink:href="#check" />
                                                            </svg></div><span>Тип квартиры 4</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="catalog-filter-item">
                                    <div class="dropdown dropdown--radio" id="filter-deadline" data-dropdown="radio" data-dropdown-tags="data-dropdown-tags"><input type="hidden" value data-dropdown-value="data-dropdown-value">
                                        <div class="field-input">Срок сдачи</div>
                                        <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                <use xlink:href="#dropdown" />
                                            </svg></div>
                                        <div class="dropdown-values dropdown-values--small">
                                            <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                                <ul class="list">
                                                    <li class="list-item" data-dropdown-id="2021">2021</li>
                                                    <li class="list-item" data-dropdown-id="2022">2022</li>
                                                    <li class="list-item" data-dropdown-id="2023">2023</li>
                                                    <li class="list-item" data-dropdown-id="2024">2024</li>
                                                    <li class="list-item" data-dropdown-id="2025">2025</li>
                                                    <li class="list-item" data-dropdown-id="2026">2026</li>
                                                    <li class="list-item" data-dropdown-id="2027">2027</li>
                                                    <li class="list-item" data-dropdown-id="2028">2028</li>
                                                    <li class="list-item" data-dropdown-id="2029">2029</li>
                                                </ul>
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
                                    </svg><span>Списком</span></button><button class="button button--light" type="button">Показать</button></div>
                        </div>
                        <div class="catalog-filter-other">
                            <div class="catalog-filter-item">
                                <div class="catalog-filter-item-label">Цена</div>
                                <div class="dropdown" id="filter-price" data-dropdown="price" data-dropdown-prefix-form="от" data-dropdown-prefix-to="до" data-dropdown-postfix="[&quot;тыс&quot;,&quot;млн&quot;,&quot;млрд&quot;]">
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
                                                <div class="filter-price-from"><input class="field-input" data-price-from-label="data-price-from-label" disabled="disabled"><input class="field-input" type="number" data-price-from="data-price-from" value="<?= $arResult["FILTER_VALUES"]["PRICE"][0] ? $arResult["FILTER_VALUES"]["PRICE"][0] * 1000000  : $arResult["FILTER_RANGES"]["MIN_PRICE"]; ?>"></div>
                                                <div class="filter-price-to"><input class="field-input" data-price-to-label="data-price-to-label" disabled="disabled"><input class="field-input" type="number" data-price-to="data-price-to" value="<?= $arResult["FILTER_VALUES"]["PRICE"][1] ? $arResult["FILTER_VALUES"]["PRICE"][1] * 1000000  : $arResult["FILTER_RANGES"]["MAX_PRICE"]; ?>"></div>
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
                            <div class="catalog-filter-item">
                                <div class="dropdown dropdown--checkbox" id="filter-features" data-dropdown="checkbox"><input type="hidden" value data-dropdown-value="data-dropdown-value">
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
                                                <li class="list-item" data-dropdown-id="0">
                                                    <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                            <use xlink:href="#check" />
                                                        </svg></div><span>C террасой</span>
                                                </li>
                                                <li class="list-item" data-dropdown-id="1">
                                                    <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                            <use xlink:href="#check" />
                                                        </svg></div><span>Двухуровневые</span>
                                                </li>
                                                <li class="list-item" data-dropdown-id="2">
                                                    <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                            <use xlink:href="#check" />
                                                        </svg></div><span>С высокими потолками</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="catalog-filter-item">
                                <div class="catalog-filter-item-label">Этаж</div>
                                <div class="dropdown dropdown--radio" id="filter-floor" data-dropdown="range-select" data-dropdown-min="1" data-dropdown-max="16" data-dropdown-prefix-form="от" data-dropdown-prefix-to="до" data-dropdown-postfix="этажа" data-dropdown-postfix-equal="этаж">
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
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">от<div>,</div> <span data-dropdown-empty-default="1">1</span> этажа</div><input class="field-input" type="number" value data-dropdown-value="data-dropdown-value">
                                                </div>
                                                <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                                    <ul class="list">
                                                        <li class="list-item" data-dropdown-id="1">1 этаж</li>
                                                        <li class="list-item" data-dropdown-id="2">2 этаж</li>
                                                        <li class="list-item" data-dropdown-id="3">3 этаж</li>
                                                        <li class="list-item" data-dropdown-id="4">4 этаж</li>
                                                        <li class="list-item" data-dropdown-id="5">5 этаж</li>
                                                        <li class="list-item" data-dropdown-id="6">6 этаж</li>
                                                        <li class="list-item" data-dropdown-id="7">7 этаж</li>
                                                        <li class="list-item" data-dropdown-id="8">8 этаж</li>
                                                        <li class="list-item" data-dropdown-id="9">9 этаж</li>
                                                        <li class="list-item" data-dropdown-id="10">10 этаж</li>
                                                        <li class="list-item" data-dropdown-id="11">11 этаж</li>
                                                        <li class="list-item" data-dropdown-id="12">12 этаж</li>
                                                        <li class="list-item" data-dropdown-id="13">13 этаж</li>
                                                        <li class="list-item" data-dropdown-id="14">14 этаж</li>
                                                        <li class="list-item" data-dropdown-id="15">15 этаж</li>
                                                        <li class="list-item" data-dropdown-id="16">16 этаж</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                                <div class="dropdown-values-range-select-label">
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">до<div>,</div> <span data-dropdown-empty-default="16">16</span> этаж</div><input class="field-input" type="number" value data-dropdown-value="data-dropdown-value">
                                                </div>
                                                <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                                    <ul class="list">
                                                        <li class="list-item" data-dropdown-id="1">1 этаж</li>
                                                        <li class="list-item" data-dropdown-id="2">2 этаж</li>
                                                        <li class="list-item" data-dropdown-id="3">3 этаж</li>
                                                        <li class="list-item" data-dropdown-id="4">4 этаж</li>
                                                        <li class="list-item" data-dropdown-id="5">5 этаж</li>
                                                        <li class="list-item" data-dropdown-id="6">6 этаж</li>
                                                        <li class="list-item" data-dropdown-id="7">7 этаж</li>
                                                        <li class="list-item" data-dropdown-id="8">8 этаж</li>
                                                        <li class="list-item" data-dropdown-id="9">9 этаж</li>
                                                        <li class="list-item" data-dropdown-id="10">10 этаж</li>
                                                        <li class="list-item" data-dropdown-id="11">11 этаж</li>
                                                        <li class="list-item" data-dropdown-id="12">12 этаж</li>
                                                        <li class="list-item" data-dropdown-id="13">13 этаж</li>
                                                        <li class="list-item" data-dropdown-id="14">14 этаж</li>
                                                        <li class="list-item" data-dropdown-id="15">15 этаж</li>
                                                        <li class="list-item" data-dropdown-id="16">16 этаж</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="catalog-filter-item">
                                <div class="catalog-filter-item-label">Размер квартиры</div>
                                <div class="dropdown dropdown--radio" id="filter-size" data-dropdown="range-select" data-dropdown-min="10" data-dropdown-max="110" data-dropdown-prefix-label="Размер квартиры" data-dropdown-prefix-form="от" data-dropdown-prefix-to="до" data-dropdown-postfix="м²">
                                    <div class="field-input field-input--center">
                                        <div class="field-input-inside"><span>Размер квартиры</span>
                                            <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                                    <use xlink:href="#dropdown" />
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-values">
                                        <div class="dropdown-values-range-select">
                                            <div class="dropdown-values-range-select-item" data-dropdown-from="data-dropdown-from">
                                                <div class="dropdown-values-range-select-label">
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">от<div>,</div> <span data-dropdown-empty-default="10">10</span> м²</div><input class="field-input" type="number" value data-dropdown-value="data-dropdown-value">
                                                </div>
                                            </div>
                                            <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                                <div class="dropdown-values-range-select-label">
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">до<div>,</div> <span data-dropdown-empty-default="110">110</span> м²</div><input class="field-input" type="number" value data-dropdown-value="data-dropdown-value">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="catalog-filter-item">
                                <div class="catalog-filter-item-label">Размер кухни</div>
                                <div class="dropdown dropdown--radio" id="filter-kitchen-size" data-dropdown="range-select" data-dropdown-min="10" data-dropdown-max="50" data-dropdown-prefix-label="Размер кухни" data-dropdown-prefix-form="от" data-dropdown-prefix-to="до" data-dropdown-postfix="м²">
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
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">от<div>,</div> <span data-dropdown-empty-default="10">10</span> м²</div><input class="field-input" type="number" value data-dropdown-value="data-dropdown-value">
                                                </div>
                                            </div>
                                            <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                                <div class="dropdown-values-range-select-label">
                                                    <div class="field-input" data-dropdown-label="data-dropdown-label">до<div>,</div> <span data-dropdown-empty-default="50">50</span> м²</div><input class="field-input" type="number" value data-dropdown-value="data-dropdown-value">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="catalog-filter-item">
                                <div class="dropdown dropdown--checkbox" id="filter-finish" data-dropdown="checkbox"><input type="hidden" value data-dropdown-value="data-dropdown-value">
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
                                                <li class="list-item" data-dropdown-id="0">
                                                    <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                            <use xlink:href="#check" />
                                                        </svg></div><span>Подчистовая</span>
                                                </li>
                                                <li class="list-item" data-dropdown-id="1">
                                                    <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                            <use xlink:href="#check" />
                                                        </svg></div><span>Чистовая</span>
                                                </li>
                                                <li class="list-item" data-dropdown-id="2">
                                                    <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                            <use xlink:href="#check" />
                                                        </svg></div><span>Черновая</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="catalog-filter-item">
                                <div class="dropdown dropdown--checkbox" id="filter-property-type" data-dropdown="checkbox"><input type="hidden" value data-dropdown-value="data-dropdown-value">
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
                                                <li class="list-item" data-dropdown-id="0">
                                                    <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                            <use xlink:href="#check" />
                                                        </svg></div><span>Жилая</span>
                                                </li>
                                                <li class="list-item" data-dropdown-id="1">
                                                    <div class="list-item-checkbox"><svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                            <use xlink:href="#check" />
                                                        </svg></div><span>Апартаменты</span>
                                                </li>
                                            </ul>
                                        </div>
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
        <section class="section-heading" data-scroll-fx="data-scroll-fx">
            <div class="container">
                <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","page",Array(
                        "START_FROM" => (SITE_ID == "s2") ? 1 : 0,
                        "PATH" => "",
                    )
                );?>
                <h1><?=$arResult['LOCATION']['NAME']?></h1>
            </div>
        </section>
        <section class="section-catalog">
            <div class="container">
                <div class="catalog">
                    <div class="catalog-headnote" data-scroll-fx="data-scroll-fx">
                        <div class="catalog-headnote-data">
                            <div>350 предложений</div>
                            <div><a href="#broker" data-modal="data-modal">Заказать подбор боркеру</a></div>
                        </div>
                        <div class="catalog-headnote-sort">
                            <div class="dropdown dropdown--radio dropdown--no-border" data-dropdown="radio"><input type="hidden" value data-dropdown-value="data-dropdown-value">
                                <div class="field-input">Сортировка <span data-dropdown-label="по умолчанию">по умолчанию</span></div>
                                <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                        <use xlink:href="#dropdown" />
                                    </svg></div>
                                <div class="dropdown-values">
                                    <div class="dropdown-values-scroll" data-simplebar="data-simplebar">
                                        <ul class="list">
                                            <li class="list-item list-item--active" data-dropdown-id>по умолчанию</li>
                                            <li class="list-item" data-dropdown-id="1">по цене (возрастание)</li>
                                            <li class="list-item" data-dropdown-id="2">по цене (убывание)</li>
                                            <li class="list-item" data-dropdown-id="3">по площади (возрастание)</li>
                                            <li class="list-item" data-dropdown-id="4">по площади (убывание)</li>
                                            <li class="list-item" data-dropdown-id="5">по сроку сдачи (возрастание)</li>
                                            <li class="list-item" data-dropdown-id="5">по сроку сдачи (убывание)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="catalog-list" data-scroll-fx>
                        <?foreach ($arResult['OBJECTS'] as $arObject):?>
                            <div class="catalog-item"><a class="object-new" href="/novostroyki/<?= $arObject['CODE']; ?>/">
                                    <div class="object-new-preview lazy" data-bg="<?= $arObject['PICTURE']['SRC']; ?>"></div>
                                    <div class="object-new-info">
                                        <h4 class="h3">«<?= $arObject['NAME']; ?>»</h4>
                                        <div class="object-new-info-hide">
                                            <div class="text-overflow"><?= $arObject['UF_AREA']; ?> р-н. </div>
                                            <div class="text-overflow"><?= plural_form($arObject['ELEMENT_CNT'], array("квартира", "квартиры", "квартир"))?></div>
                                            <div class="text-overflow"><?= $arObject['UF_DEADLINE']; ?></div>
                                        </div>
                                    </div>
                                </a></div>
                            <div class="catalog-item catalog-item--large">
                                <div class="object-new-help">
                                    <h4 class="h2">Устали искать Подберем для вас то, что нужно в 2 счета</h4><a class="text-control text-control--long-arrow" href="#broker" data-modal><span>Оставить заявку</span><svg class="icon icon--arrow-long" width="30" height="12" viewbox="0 0 30 12">
                                            <use xlink:href="#arrow-long" />
                                        </svg></a>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                    <div class="pagination-more" data-scroll-fx="data-scroll-fx"><button class="button button--inverse button--refresh button--refresh-load"><svg class="icon icon--refresh" width="18" height="22" viewbox="0 0 18 22">
                                <use xlink:href="#refresh" />
                            </svg><span>Показать еще</span></button></div>
                </div>
            </div>
        </section>
        <section class="section-quiz" data-scroll-fx="data-scroll-fx">
            <div class="container">
                <div class="quiz">
                    <div class="quiz-content">
                        <div class="quiz-headnote">
                            <div>Пройдите тест и получите индивидуальную подборку предложений для вас</div>
                            <div><span data-quiz-current="data-quiz-current"></span> из <span data-quiz-total="data-quiz-total"></span> вопросов</div>
                        </div>
                        <div class="quiz-list" data-quiz-list="data-quiz-list">
                            <h4 data-quiz-question="data-quiz-question"></h4><span class="quiz-footnote">Выберите вариант ответа</span>
                            <div class="quiz-answers" data-quiz-answers="data-quiz-answers"></div>
                            <div class="quiz-control"><button class="button button--default" disabled="disabled" data-quiz-next="data-quiz-next">Дальше</button></div>
                        </div>
                        <div class="quiz-form" data-quiz-form="data-quiz-form">
                            <h4>Остался последний шаг.</h4><span class="quiz-footnote">Укажите Ваши контактные данные</span>
                            <form class="form"><input type="hidden" data-quiz-input="data-quiz-input" value>
                                <div class="form-item">
                                    <div class="field"><input class="field-input" type="text" value placeholder="Имя"></div>
                                </div>
                                <div class="form-item">
                                    <div class="field"><input class="field-input" type="tel" value placeholder="Телефон"></div>
                                </div>
                                <div class="form-control"><button class="button button--default">Отправить</button></div>
                                <div class="form-item form-item--checkbox"><label class="checkbox"><input type="checkbox"><span>Согласен на обработку <a href="#">персональных данных</a></span></label></div>
                            </form>
                        </div>
                    </div>
                    <div class="quiz-icon">
                        <div><img class="lazy" data-src="<?= SITE_TEMPLATE_PATH ?>/img/catalog/catalog.png" alt></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-catalog-about" data-scroll-fx="data-scroll-fx">
            <div class="container">
                <div class="catalog-about">
                    <div class="catalog-about-title">
                        <h3 class="h1">Элитная жилая недвижимость в&nbsp;Санкт-Петербурге</h3>
                    </div>
                    <div class="catalog-about-text">
                        <p>Богатство мировой литературы от Платона до Ортеги-и-Гассета свидетельствует о том, что интерпретация заканчивает горизонт ожидания, таким образом, сходные законы контрастирующего развития характерны и для процессов в психике. Добавлю, что художественное опосредование диссонирует резкий этикет. Трагическое готично образует гений, что-то подобное можно встретить в работах Ауэрбаха и Тандлера. Возрождение изящно дает непосредственный символизм, подобный исследовательский подход к проблемам художественной типологии можно обнаружить у К.Фосслера. Интерпретация, согласно традиционным представлениям, готично продолжает невротический канон.</p>
                        <p>Добавлю, что цвет просветляет невротический классицизм. Художественный вкус, в том числе, выстраивает самодостаточный диахронический подход. Диахронический подход конвенционален. Согласно теории "вчувствования", разработанной Теодором Липпсом, символический метафоризм представляет собой самодостаточный миракль. Опера-буффа, так или иначе, монотонно трансформирует реконструктивный подход.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="catalog-tab" data-catalog-map="data-catalog-map">
        <section class="section-catalog-map">
            <div class="catalog-map">
                <div id="map"></div>
                <div class="catalog-map-object" data-map-object="data-map-object"></div>
            </div>
        </section>
    </div>
<?/*\
<section class="section section--genplan">
    <div class="genplan-wrapper" data-corpus="1" data-floor="8" data-swipe-threshold="50">
        <div class="paper-wrapper">
            <div class="paper-outer">
                <div id="paper" data-image="<?=$arParams["MAIN_PAGE_BACKGROUND"]?>" data-size-width="<?=$arParams["MAIN_PAGE_BACKGROUND_WIDTH"]?>" data-size-height="<?=$arParams["MAIN_PAGE_BACKGROUND_HEIGHT"]?>"></div>
            </div>
        </div>
    </div>
    <div class="tip-small" data-tip-small>
        <h4></h4>
        <div class="genplan-tip-apartment"></div>
        <div class="genplan-tip-progress">
            <div class="genplan-tip-progress-label"></div>
            <div class="genplan-tip-progress-line" style="display: none;"><span data-tip-progress style="width: 0%;"></span></div>
            <div class="genplan-tip-progress-footnote" style="display: none;">Готов на <span data-tip-progress-footnote>0%</span></div>
        </div>
        <a class="genplan-tip-more" href>Выбрать квартиру</a>
        <div class="tip-small-controls">
            <button class="tip-small-up" data-floor-up>
                <svg class="icon icon--arrow-slider" width="10" height="6" viewbox="0 0 10 6">
                    <use xlink:href="#arrow-slider" />
                </svg>
            </button>
            <button class="tip-small-down" data-floor-down>
                <svg class="icon icon--arrow-slider" width="10" height="6" viewbox="0 0 10 6">
                    <use xlink:href="#arrow-slider" />
                </svg>
            </button>
        </div>
    </div>
    <div class="genplan-heading">
        <div class="container">
			<? /*$APPLICATION->IncludeComponent(
				"bitrix:breadcrumb",
				"breadcrumb_new",
				Array(
					"PATH" => "",
					"SITE_ID" => "s1",
					"START_FROM" => "0"
				)
); /?>
            <div class="pagetitle-row">
                <h1 class="pagetitle" data-scroll-fx>Выбрать квартиру</h1>
                <div class="pagetitle-control" data-scroll-fx>
                    <a class="button button--inverse button--head-font" href="/search/parametrical/">
                        <svg class="icon icon--params" width="20" height="20" viewbox="0 0 20 20">
                            <use xlink:href="#params" />
                        </svg>
                        <span>Выбор по параметрам</span>
                        <span class="button-mobile">По параметрам</span>
                    </a>
                </div>
            </div>
            <div class="tabs-row" data-corpus-change>
                <ul class="list list--flex layout-tabs">
                    <li class="list-item -active" data-scroll-fx="data-scroll-fx"><a class="list-link" href="#">Корпус с отделкой под ключ</a></li>
                    <li class="list-item" data-scroll-fx="data-scroll-fx"><a class="list-link" href="#">Корпус с предчистовой отделкой</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>


<script>
    /*if (window.innerWidth < 1024) {
        window.location.href = '/search/parametrical/'
    }
    window.addEventListener('resize', function (event) {
        if (window.innerWidth < 1024) {
            window.location.href = '/search/parametrical/'
        }
    })

    function isTouchDevice() {
        var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ')
        var mq = function (query) {
            return window.matchMedia(query).matches
        }
        if ('ontouchstart' in window || (window.DocumentTouch && document instanceof DocumentTouch)) {
            return true
        }
        var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('')
        return mq(query)
    }

    if (isTouchDevice()) {
        window.location.href = '/search/parametrical/'
    }/
    var points = [
        <?foreach ($arResult["FLOORS"] as $arFloor):?>
        {
            section:
                {
                    path: '<?=$arFloor[UF_F_COORDS_GENPLAN];?>', // координаты (М в начале и Z в конце необходимо сохранять)
                    pathAttributes:
                        {
                            'stroke-width': 0,
                            'cursor': 'pointer',
                            'fill': '#FFFFFF', // цвет без наведения
                            'fill-opacity': 0, // прозрачность без наведения
                            'href': '/search/house-<?=$arFloor["NUMBER_HOUSE"]?>/floor-<?=$arFloor["NUMBER"]?>/' // ссылка
                        },
                    pathAttributesTouch:
                        {
                            'stroke-width': 0,
                            'fill': '#FFFFFF',
                            'fill-opacity': 0,
                        },
                    pathAttributesActive:
                        {
                            'fill': '#FFFFFF', // цвет наведения
                            'fill-opacity': .5 // прозрачность наведения
                        },
                    tip:
                        '<div class="genplan-tip genplan-tip--<?if($arFloor["NUMBER_HOUSE"] == 1):?>left<?else:?>right<?endif;?> genplan-tip--top<?=$arFloor["NUMBER"] - 1?> ">' +
                        <?if($arFloor["NUMBER_HOUSE"] == 1):?>
                        '<span class="genplan-tip-headnote">Корпус с отделкой под ключ</span>' +
                        <?elseif ($arFloor["NUMBER_HOUSE"] == 2):?>
                        '<span class="genplan-tip-headnote">Корпус с предчистовой отделкой</span>' +
                        <?endif;?>
                        '<h4><?=$arFloor["NUMBER"]?> этаж</h4>' +
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["FLATS_COUNT"][$arFloor["NUMBER"]][0] > 0):?>
                        '<div class="genplan-tip-apartment"><span><?=plural_form($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["FLATS_COUNT"][$arFloor["NUMBER"]][0],
                            array(
                                'квартира',
                                'квартиры',
                                'квартир'
                            ))?></span> в продаже</div>' +

                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][5] > 0 || $arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][6] > 0 || $arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][7] > 0 || $arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][8] > 0 || $arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][18] > 0):?>

                        '<ul class="list">' +
                        <?if(FLATS_NO_PRICE != "Y"):?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][5] > 0):?>'<li class="list-item"><div class="list-title">Студии</div><div class="list-value">от <?=number_format($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][5],
                            0, '.', ' ')?> <?=$arParams["CURRENCY"]?></div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][6] > 0):?>'<li class="list-item"><div class="list-title">1-комнатные</div><div class="list-value">от <?=number_format($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][6],
                            0, '.', ' ')?> <?=$arParams["CURRENCY"]?></div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][7] > 0):?>'<li class="list-item"><div class="list-title">2-комнатные</div><div class="list-value">от <?=number_format($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][7],
                            0, '.', ' ')?> <?=$arParams["CURRENCY"]?></div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][8] > 0):?>'<li class="list-item"><div class="list-title">3-комнатные</div><div class="list-value">от <?=number_format($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][8],
                            0, '.', ' ')?> <?=$arParams["CURRENCY"]?></div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][18] > 0):?>'<li class="list-item"><div class="list-title">4-комнатные</div><div class="list-value">от <?=number_format($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][18],
                            0, '.', ' ')?> <?=$arParams["CURRENCY"]?></div></li>' +
                        <?endif;?>
                        <?else:?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][5] > 0):?>
                        '<li class="list-item"><div class="list-title">Студии</div><div class="list-value">по запросу</div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][6] > 0):?>
                        '<li class="list-item"><div class="list-title">1-комнатные</div><div class="list-value">по запросу</div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][7] > 0):?>
                        '<li class="list-item"><div class="list-title">2-комнатные</div><div class="list-value">по запросу</div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][8] > 0):?>
                        '<li class="list-item"><div class="list-title">3-комнатные</div><div class="list-value">по запросу</div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]][18] > 0):?>
                        '<li class="list-item"><div class="list-title">4-комнатные</div><div class="list-value">по запросу</div></li>' +
                        <?endif;?>
                        <?endif;?>
                        '</ul>' +
                        <?endif;?>
                        <?elseif($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["FLATS_COUNT"][$arFloor["NUMBER"]][2] > 0):?>
                        '<div class="genplan-tip-apartment"><span><?=plural_form($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["FLATS_COUNT"][$arFloor["NUMBER"]][2],
                            array(
                                'квартира',
                                'квартиры',
                                'квартир'
                            ))?></span> скоро в продаже</div>' +
                        '<ul class="list">' +
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][5]):?>'<li class="list-item"><div class="list-title">Студии</div><div class="list-value"><?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][5];?></div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][6]):?>'<li class="list-item"><div class="list-title">1-комнатные</div><div class="list-value"><?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][6];?></div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][7]):?>'<li class="list-item"><div class="list-title">2-комнатные</div><div class="list-value"><?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][7];?></div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][8]):?>'<li class="list-item"><div class="list-title">3-комнатные</div><div class="list-value"><?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][8];?></div></li>' +
                        <?endif;?>
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][18]):?>'<li class="list-item"><div class="list-title">4-комнатные</div><div class="list-value"><?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["MIN_PRICES"][$arFloor["NUMBER"]]["SOON"][18];?></div></li>' +
                        <?endif;?>
                        '</ul>' +
                        <?else:?>
                        '<div class="genplan-tip-apartment"><span>Все квартиры проданы</span></div>' +
                        <?endif;?>
                        '<div class="genplan-tip-progress">' +
                        '<div class="genplan-tip-progress-label"><?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["UF_TERM"];?> года</div>' +
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["UF_PERCENT"] != 0):?>
                        '<div class="genplan-tip-progress-line"><span style="width: <?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["UF_PERCENT"];?>%;"></span></div>' +
                        '<div class="genplan-tip-progress-footnote">Готов на <?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["UF_PERCENT"];?>%</div>' +
                        <?endif;?>
                        '</div>' +
                        '<a class="genplan-tip-more" href="/search/house-<?=$arFloor["NUMBER_HOUSE"]?>/floor-<?=$arFloor["NUMBER"]?>/">Выбрать квартиру</a>' +
                        '</div>', // подсказка
                    corpusNumber: <?=$arFloor["NUMBER_HOUSE"];?>,
                    floorNumber: <?=$arFloor["NUMBER"]?> ,
                    mobileTip: {
                        title: '<?=$arFloor["NUMBER"]?> этаж',
                        <?if($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["FLATS_COUNT"][$arFloor["NUMBER"]][0] > 0):?>
                            apartment: '<span><?=plural_form($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["FLATS_COUNT"][$arFloor["NUMBER"]][0],
                                        array(
                                            'квартира',
                                            'квартиры',
                                            'квартир'
                                        ))?></span> в продаже',
                        <?elseif($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["FLATS_COUNT"][$arFloor["NUMBER"]][2] > 0):?>
                            apartment: '<span><?=plural_form($arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["FLATS_COUNT"][$arFloor["NUMBER"]][2],
                                        array(
                                            'квартира',
                                            'квартиры',
                                            'квартир'
                                        ))?></span> скоро в продаже</div>',
                        <?endif;?>
                        progressLabel: '<?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["UF_TERM"];?> года',
                        progress: '<?=$arResult["HOUSES"][$arFloor["NUMBER_HOUSE"]]["UF_PERCENT"];?>%',
                        link: '/search/house-<?=$arFloor["NUMBER_HOUSE"]?>/floor-<?=$arFloor["NUMBER"]?>/'
                    },
                    genplan: true
                }
        },
        <?endforeach;?>
    ]
</script>
\*/?>