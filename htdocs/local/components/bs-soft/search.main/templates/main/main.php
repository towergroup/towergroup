<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

//echo "<pre hidden>"; print_r($arResult["OBJECTS"]["ITEMS"]); echo "</pre>";
//xprint($arResult["DEFAULT_PARAMS"]);
//xprint($arResult["FILTER_RANGES"]);
//xprint($arResult["FILTER_VALUES"]);
?>
<form class="catalog-filter" data-filter-tags="data-filter-tags">
    <div class="catalog-filter-city">
        <?= $arParams["CITY_CODE"] == 'spb' ? 'Санкт-Петербург' : 'Москва' ?>
    </div>
    <div class="catalog-filter-body">
        <?
        if ($arResult['IS_AJAX'])
            $APPLICATION->RestartBuffer();
        ?>
        <div class="catalog-filter-scroll">
            <div class="catalog-filter-tags" data-tags="data-tags">
                <ul class="list">
                    <li class="list-item list-item--control" data-tags-control="data-tags-control">
                        <button id="filter-clear" class="button button--light" type="button">Сбросить всё</button>
                    </li>
                </ul>
            </div>
            <div class="catalog-filter-hero">
                <div class="catalog-filter-fields">
                    <div class="catalog-filter-item catalog-filter-item--search">
                        <div class="dropdown dropdown--search" id="filter-search" data-search-line="data-search-line"
                             data-search-line-url="<?= $arResult["SEARCH_URL"] ?>">
                            <input type="hidden"
                                   value='<?= ($arResult["FILTER_VALUES"]["SEARCH"]) ? $arResult["FILTER_VALUES"]["SEARCH"] : '[]' ?>'
                                   data-search-line-value="data-search-line-value">
                            <div class="field field--search"><input class="field-input" type="text"
                                                                    data-placeholder-long="<?= $arResult["FILTER_VALUES"]["CATEGORY"] ? $arResult["CATEGORIES"][$arResult["FILTER_VALUES"]["CATEGORY"]] : $arResult["CATEGORIES"]["novostroyki"]?>, метро, район, застройщики"
                                                                    data-placeholder-short="<?= $arResult["FILTER_VALUES"]["CATEGORY"] ? $arResult["CATEGORIES"][$arResult["FILTER_VALUES"]["CATEGORY"]] : $arResult["CATEGORIES"]["novostroyki"]?>, метро, район, застройщики"
                                                                    placeholder="<?= $arResult["FILTER_VALUES"]["CATEGORY"] ? $arResult["CATEGORIES"][$arResult["FILTER_VALUES"]["CATEGORY"]] : $arResult["CATEGORIES"]["novostroyki"]?>, метро, район, застройщики"
                                                                    data-search-line-input="data-search-line-input">
                                <button class="field-search-clear" type="button"
                                        data-search-line-clear="data-search-line-clear">
                                    <svg class="icon icon--cross-light" width="16" height="16" viewbox="0 0 16 16">
                                        <use xlink:href="#cross-light"/>
                                    </svg>
                                </button>
                                <label class="checkbox-trigger">
                                    <input type="checkbox"
                                           data-search-line-toggler="data-search-line-toggler">
                                    <span>Исключить из выдачи</span>
                                </label>
                            </div>
                            <div class="dropdown-values">
                                <div class="dropdown-values-scroll" data-dropdown-scroll>
                                    <div class="dropdown-values-search-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?if (!empty($arResult["DEFAULT_PARAMS"]["CITIES"])) :?>
                        <div class="catalog-filter-item">
                            <div class="dropdown dropdown--radio" id="filter-city" data-dropdown="radio" data-dropdown-tags="data-dropdown-tags"><input type="hidden" value="<?= ($arResult["FILTER_VALUES"]["CITIES"]) ? $arResult["FILTER_VALUES"]["CITIES"] : null; ?>" data-dropdown-value="data-dropdown-value">
                                <div class="field-input">Город</div>
                                <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                        <use xlink:href="#dropdown" />
                                    </svg></div>
                                <div class="dropdown-values dropdown-values--small">
                                    <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                        <ul class="list">
                                            <?
                                            foreach ($arResult["DEFAULT_PARAMS"]["CITIES"] as $arCity) : ?>
                                                <li class="list-item" data-dropdown-id="<?= $arCity; ?>"><?= $arCity; ?></li>
                                            <?endforeach;?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?endif;?>
                    <? if (!empty($arResult["DEFAULT_PARAMS"]["APARTMENT_TYPES"])) : ?>
                        <div class="catalog-filter-item">
                            <div class="dropdown dropdown--checkbox" id="filter-apartment-type"
                                 data-dropdown="checkbox">
                                <input type="hidden"
                                       value="<?= ($arResult["FILTER_VALUES"]["APARTMENT_TYPE"]) ? $arResult["FILTER_VALUES"]["APARTMENT_TYPE"] : null ?>"
                                       data-dropdown-value="data-dropdown-value">
                                <div class="field-input"><?= $arResult["FILTER_VALUES"]["CATEGORY"] == 'kupit-dom' ? 'Количество спален' : 'Тип квартиры'?></div>
                                <div class="dropdown-icon">
                                    <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                        <use xlink:href="#dropdown"/>
                                    </svg>
                                </div>
                                <div class="dropdown-values">
                                    <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                        <ul class="list">
                                            <? foreach ($arResult["DEFAULT_PARAMS"]["APARTMENT_TYPES"] as $arApartmenttypes): ?>
                                                <? if ($arApartmenttypes["ID"] == 0) $arApartmenttypes["UF_ID"] = "s"; ?>
                                                <li class="list-item"
                                                    data-dropdown-id="<?= $arApartmenttypes['ID']; ?>">
                                                    <div class="list-item-checkbox">
                                                        <svg class="icon icon--check" width="13" height="9"
                                                             viewbox="0 0 13 9">
                                                            <use xlink:href="#check"/>
                                                        </svg>
                                                    </div>
                                                    <span><?= $arApartmenttypes['VALUE']; ?></span>
                                                </li>
                                            <? endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? endif; ?>
                    <? if (!empty($arResult["DEFAULT_PARAMS"]["DEADLINES"])) : ?>
                    <div class="catalog-filter-item">
                        <div class="dropdown dropdown--radio" id="filter-deadline" data-dropdown="radio"
                             data-dropdown-tags="data-dropdown-tags"><input type="hidden"
                                                                            value="<?= ($_GET["deadline"]) ? $_GET["deadline"] : null; ?>"
                                                                            data-dropdown-value="data-dropdown-value">
                            <div class="field-input">Срок сдачи</div>
                            <div class="dropdown-icon">
                                <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                    <use xlink:href="#dropdown"/>
                                </svg>
                            </div>
                            <div class="dropdown-values dropdown-values--small">
                                <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                    <ul class="list">
                                        <?
                                        foreach ($arResult["DEFAULT_PARAMS"]["DEADLINES"] as $arDeadline) : ?>
                                            <li class="list-item"
                                                data-dropdown-id="<?= $arDeadline; ?>"><?= ($arDeadline != "Сдан") ? "До " . $arDeadline : $arDeadline; ?></li>
                                        <? endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?endif;?>
                    <div class="catalog-filter-item">
                        <div class="catalog-filter-item-label">Цена</div>
                        <div class="dropdown" id="filter-price" data-dropdown="price" data-dropdown-prefix-form="от"
                             data-dropdown-prefix-to="до">
                            <div class="field-input field-input--center">
                                <div class="field-input-inside"><span>Цена</span>
                                    <div class="dropdown-icon">
                                        <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                            <use xlink:href="#dropdown"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-values dropdown-values--price">
                                <div class="filter-price" data-price-filter="data-price-filter">
                                    <div class="filter-price-values">
                                        <div class="filter-price-from">
                                            <input class="field-input" data-price-from-label="data-price-from-label"
                                                   disabled="disabled">
                                            <input class="field-input" type="number" data-price-from="data-price-from"
                                                   value="<?= (!empty($arResult["FILTER_VALUES"]["PRICE"][0]) && (($arResult["FILTER_VALUES"]["PRICE"][0] * 1000000) != $arResult["FILTER_RANGES"]["MIN_PRICE"])) ? $arResult["FILTER_VALUES"]["PRICE"][0] * 1000000 : ''; ?>">
                                        </div>
                                        <div class="filter-price-to">
                                            <input class="field-input" data-price-to-label="data-price-to-label"
                                                   disabled="disabled">
                                            <input class="field-input" type="number" data-price-to="data-price-to"
                                                   value="<?= (!empty($arResult["FILTER_VALUES"]["PRICE"][1]) && (($arResult["FILTER_VALUES"]["PRICE"][1] * 1000000) != $arResult["FILTER_RANGES"]["MAX_PRICE"])) ? $arResult["FILTER_VALUES"]["PRICE"][1] * 1000000 : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="filter-price-line"
                                         data-price="data-price"
                                         data-price-step="1"
                                         data-price-min="<?= $arResult["FILTER_RANGES"]["MIN_PRICE"]; ?>"
                                         data-price-max="<?= $arResult["FILTER_RANGES"]["MAX_PRICE"]; ?>"
                                         data-price-min-current="<? if (isset($arResult["FILTER_VALUES"]["PRICE"][0])): ?><?= $arResult["FILTER_VALUES"]["PRICE"][0] * 1000000 ?><? else: ?><?= $arResult["FILTER_RANGES"]["MIN_PRICE"] ?><? endif; ?>"
                                         data-price-max-current="<? if (isset($arResult["FILTER_VALUES"]["PRICE"][1])): ?><?= $arResult["FILTER_VALUES"]["PRICE"][1] * 1000000 ?><? else: ?><?= $arResult["FILTER_RANGES"]["MAX_PRICE"] ?><? endif; ?>"
                                    >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="catalog-filter-more">
                    <button data-filter-more="data-filter-more" data-filter-more-show="Еще фильтры"
                            data-filter-more-hide="Скрыть" type="button"><span><?= $arResult["IS_AJAX"] ? "Скрыть" : "Еще фильтры" ?></span>
                        <svg class="icon icon--cross" width="8" height="8" viewbox="0 0 8 8">
                            <use xlink:href="#cross"/>
                        </svg>
                    </button>
                </div>
                <div class="catalog-filter-controls">
                    <a class="text-control text-control--phone" href="#request-quiz" data-modal><span>Оставить заявку на подбор</span><svg class="icon icon--phone" width="17" height="17" viewbox="0 0 17 17">
                            <use xlink:href="#phone" />
                        </svg>
                    </a>
                    <a class="button button--map" href="#">
                        <svg class="icon icon--location" width="11" height="16" viewbox="0 0 11 16">
                            <use xlink:href="#location" />
                        </svg>
                        <span>На карте</span>
                    </a>
                    <button id="apply-filter" class="button button--light" type="button">
                        <svg class="icon icon--search" width="14" height="15" viewbox="0 0 14 15">
                            <use xlink:href="#search" />
                        </svg>
                        <span>
                            Найти
                        </span>
                    </button>
                </div>
            </div>
            <div class="catalog-filter-other">
                <? if (!empty($arResult["FILTER_RANGES"]["TERRACE"])
                || !empty($arResult["FILTER_RANGES"]["TWO_TIER"])
                || !empty($arResult["FILTER_RANGES"]["HIGH_CEILINGS"])
                ) : ?>
                    <div class="catalog-filter-item">
                    <div class="dropdown dropdown--checkbox" id="filter-features" data-dropdown="checkbox">
                        <input type="hidden"
                               value="<?= ($arResult["FILTER_VALUES"]["FEATURES"]) ? $arResult["FILTER_VALUES"]["FEATURES"] : null ?>"
                               data-dropdown-value="data-dropdown-value">
                        <div class="field-input field-input--center">
                            <div class="field-input-inside"><span>Особенности</span>
                                <div class="dropdown-icon">
                                    <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                        <use xlink:href="#dropdown"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-values">
                            <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                <ul class="list">
                                    <? if (!empty($arResult["FILTER_RANGES"]["TERRACE"])): ?>
                                        <li class="list-item" data-dropdown-id="terrace">
                                            <div class="list-item-checkbox">
                                                <svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                    <use xlink:href="#check"/>
                                                </svg>
                                            </div>
                                            <span>C террасой</span>
                                        </li>
                                    <? endif; ?>
                                    <? if (!empty($arResult["FILTER_RANGES"]["TWO_TIER"])): ?>
                                        <li class="list-item" data-dropdown-id="twotier">
                                            <div class="list-item-checkbox">
                                                <svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                    <use xlink:href="#check"/>
                                                </svg>
                                            </div>
                                            <span>Двухуровневые</span>
                                        </li>
                                    <? endif; ?>
                                    <? if (!empty($arResult["FILTER_RANGES"]["HIGH_CEILINGS"])): ?>
                                        <li class="list-item" data-dropdown-id="highceilings">
                                            <div class="list-item-checkbox">
                                                <svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                    <use xlink:href="#check"/>
                                                </svg>
                                            </div>
                                            <span>С высокими потолками</span>
                                        </li>
                                    <? endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?endif;?>
                <? if ($arResult["FILTER_RANGES"]["MIN_FLOOR"] != $arResult["FILTER_RANGES"]["MAX_FLOOR"]): ?>
                    <div class="catalog-filter-item">
                        <div class="catalog-filter-item-label">Этаж</div>
                        <div class="dropdown dropdown--radio" id="filter-floor" data-dropdown="range-select"
                             data-dropdown-min="<?= $arResult["FILTER_RANGES"]["MIN_FLOOR"]; ?>"
                             data-dropdown-max="<?= $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?>"
                             data-dropdown-prefix-form="от" data-dropdown-prefix-to="до" data-dropdown-postfix="этажа"
                             data-dropdown-postfix-equal="этаж">
                            <div class="field-input field-input--center">
                                <div class="field-input-inside"><span>Этаж</span>
                                    <div class="dropdown-icon">
                                        <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                            <use xlink:href="#dropdown"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-values">
                                <div class="dropdown-values-range-select">
                                    <div class="dropdown-values-range-select-item"
                                         data-dropdown-from="data-dropdown-from">
                                        <div class="dropdown-values-range-select-label">
                                            <div class="field-input" data-dropdown-label="data-dropdown-label">от
                                                <div>,</div>
                                                <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MIN_FLOOR"]; ?>"><?= $arResult["FILTER_RANGES"]["MIN_FLOOR"]; ?></span>
                                                этажа
                                            </div>
                                            <input class="field-input" type="number"
                                                   value="<?= $arResult["FILTER_VALUES"]["FLOOR"][0] ? $arResult["FILTER_VALUES"]["FLOOR"][0] : null ?>"
                                                   data-dropdown-value="data-dropdown-value">
                                        </div>
                                        <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                            <ul class="list">
                                                <?
                                                $floor = $arResult["FILTER_RANGES"]["MIN_FLOOR"];
                                                while ($floor <= $arResult["FILTER_RANGES"]["MAX_FLOOR"]) {
                                                    if ($floor == 0) $floor++;
                                                    echo '<li class="list-item" data-dropdown-id="' . $floor . '">' . $floor . ' этаж</li>';
                                                    $floor++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                        <div class="dropdown-values-range-select-label">
                                            <div class="field-input" data-dropdown-label="data-dropdown-label">до
                                                <div>,</div>
                                                <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?>"><?= $arResult["FILTER_RANGES"]["MAX_FLOOR"]; ?></span>
                                                этаж
                                            </div>
                                            <input class="field-input" type="number"
                                                   value="<?= $arResult["FILTER_VALUES"]["FLOOR"][1] ? $arResult["FILTER_VALUES"]["FLOOR"][1] : null ?>"
                                                   data-dropdown-value="data-dropdown-value">
                                        </div>
                                        <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                            <ul class="list">
                                                <?
                                                $floor = $arResult["FILTER_RANGES"]["MIN_FLOOR"];
                                                while ($floor <= $arResult["FILTER_RANGES"]["MAX_FLOOR"]) {
                                                    if ($floor == 0) $floor++;
                                                    echo '<li class="list-item" data-dropdown-id="' . $floor . '">' . $floor . ' этаж</li>';
                                                    $floor++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
                <? if ($arResult["FILTER_RANGES"]["MIN_SQUARE"] != $arResult["FILTER_RANGES"]["MAX_SQUARE"]): ?>
                    <div class="catalog-filter-item">
                        <div class="catalog-filter-item-label"><?= $arResult["FILTER_VALUES"]["CATEGORY"] == 'kupit-dom' || $arResult["FILTER_VALUES"]["CATEGORY"] == 'nedvizhimost-za-rubezhom' ? 'Площадь' : 'Размер квартиры'?></div>
                        <div class="dropdown dropdown--radio" id="filter-size" data-dropdown="range-select"
                             data-dropdown-min="<?= $arResult["FILTER_RANGES"]["MIN_SQUARE"]; ?>"
                             data-dropdown-max="<?= $arResult["FILTER_RANGES"]["MAX_SQUARE"]; ?>"
                             data-dropdown-prefix-label="<?= $arResult["FILTER_VALUES"]["CATEGORY"] == 'kupit-dom' || $arResult["FILTER_VALUES"]["CATEGORY"] == 'nedvizhimost-za-rubezhom' ? 'Площадь' : 'Размер квартиры'?>" data-dropdown-prefix-form="от"
                             data-dropdown-prefix-to="до" data-dropdown-postfix="м²">
                            <div class="field-input field-input--center">
                                <div class="field-input-inside"><span><?= $arResult["FILTER_VALUES"]["CATEGORY"] == 'kupit-dom' || $arResult["FILTER_VALUES"]["CATEGORY"] == 'nedvizhimost-za-rubezhom' ? 'Площадь' : 'Размер квартиры'?></span>
                                    <div class="dropdown-icon">
                                        <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                            <use xlink:href="#dropdown"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-values">
                                <div class="dropdown-values-range-select">
                                    <div class="dropdown-values-range-select-item"
                                         data-dropdown-from="data-dropdown-from">
                                        <div class="dropdown-values-range-select-label">
                                            <div class="field-input" data-dropdown-label="data-dropdown-label">от
                                                <div>,</div>
                                                <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MIN_SQUARE"]; ?>"><?= $arResult["FILTER_RANGES"]["MIN_SQUARE"]; ?></span>
                                                м²
                                            </div>
                                            <input class="field-input" type="number"
                                                   value="<?= ($arResult["FILTER_VALUES"]["SQUARE"][0]) ? $arResult["FILTER_VALUES"]["SQUARE"][0] : null ?>"
                                                   data-dropdown-value="data-dropdown-value">
                                        </div>
                                    </div>
                                    <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                        <div class="dropdown-values-range-select-label">
                                            <div class="field-input" data-dropdown-label="data-dropdown-label">до
                                                <div>,</div>
                                                <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MAX_SQUARE"]; ?>"><?= $arResult["FILTER_RANGES"]["MAX_SQUARE"]; ?></span>
                                                м²
                                            </div>
                                            <input class="field-input" type="number"
                                                   value="<?= ($arResult["FILTER_VALUES"]["SQUARE"][1]) ? $arResult["FILTER_VALUES"]["SQUARE"][1] : null ?>"
                                                   data-dropdown-value="data-dropdown-value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
                <? if ($arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"] != $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]): ?>
                    <div class="catalog-filter-item">
                        <div class="catalog-filter-item-label">Размер кухни</div>
                        <div class="dropdown dropdown--radio" id="filter-kitchen-size" data-dropdown="range-select"
                             data-dropdown-min="<?= $arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"]; ?>"
                             data-dropdown-max="<?= $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]; ?>"
                             data-dropdown-prefix-label="Размер кухни" data-dropdown-prefix-form="от"
                             data-dropdown-prefix-to="до" data-dropdown-postfix="м²">
                            <div class="field-input field-input--center">
                                <div class="field-input-inside"><span>Размер кухни</span>
                                    <div class="dropdown-icon">
                                        <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                            <use xlink:href="#dropdown"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-values">
                                <div class="dropdown-values-range-select">
                                    <div class="dropdown-values-range-select-item"
                                         data-dropdown-from="data-dropdown-from">
                                        <div class="dropdown-values-range-select-label">
                                            <div class="field-input" data-dropdown-label="data-dropdown-label">от
                                                <div>,</div>
                                                <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"]; ?>"><?= $arResult["FILTER_RANGES"]["MIN_KITCHEN_SQUARE"]; ?></span>
                                                м²
                                            </div>
                                            <input class="field-input" type="number"
                                                   value="<?= ($arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][0]) ? $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][0] : null ?>"
                                                   data-dropdown-value="data-dropdown-value">
                                        </div>
                                    </div>
                                    <div class="dropdown-values-range-select-item" data-dropdown-to="data-dropdown-to">
                                        <div class="dropdown-values-range-select-label">
                                            <div class="field-input" data-dropdown-label="data-dropdown-label">до
                                                <div>,</div>
                                                <span data-dropdown-empty-default="<?= $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]; ?>"><?= $arResult["FILTER_RANGES"]["MAX_KITCHEN_SQUARE"]; ?></span>
                                                м²
                                            </div>
                                            <input class="field-input" type="number"
                                                   value="<?= ($arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][1]) ? $arResult["FILTER_VALUES"]["KITCHEN_SQUARE"][1] : null ?>"
                                                   data-dropdown-value="data-dropdown-value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
                <? if (!empty($arResult["DEFAULT_PARAMS"]["FINISHES"])) : ?>
                    <div class="catalog-filter-item">
                        <div class="dropdown dropdown--checkbox" id="filter-finish" data-dropdown="checkbox">
                            <input type="hidden"
                                   value="<?= ($arResult["FILTER_VALUES"]["DECORATION"]) ? $arResult["FILTER_VALUES"]["DECORATION"] : null ?>"
                                   data-dropdown-value="data-dropdown-value">
                            <div class="field-input field-input--center">
                                <div class="field-input-inside"><span>Отделка</span>
                                    <div class="dropdown-icon">
                                        <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                            <use xlink:href="#dropdown"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-values">
                                <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                    <ul class="list">
                                        <? foreach ($arResult["DEFAULT_PARAMS"]["FINISHES"] as $arFinish): ?>
                                            <li class="list-item"
                                                data-dropdown-id="<?= $arFinish['UF_ID']; ?>">
                                                <div class="list-item-checkbox">
                                                    <svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                        <use xlink:href="#check"/>
                                                    </svg>
                                                </div>
                                                <span><?= $arFinish['UF_NAME']; ?></span>
                                            </li>
                                        <? endforeach; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <? if (!empty($arResult["DEFAULT_PARAMS"]["BUILDING_TYPES"])) : ?>
                    <div class="catalog-filter-item">
                        <div class="dropdown dropdown--checkbox" id="filter-property-type" data-dropdown="checkbox">
                            <input type="hidden"
                                   value="<?= ($arResult["FILTER_VALUES"]["BUILD_TYPES"]) ? $arResult["FILTER_VALUES"]["BUILD_TYPES"] : null ?>"
                                   data-dropdown-value="data-dropdown-value">
                            <div class="field-input field-input--center">
                                <div class="field-input-inside"><span>Тип недвижимости</span>
                                    <div class="dropdown-icon">
                                        <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                            <use xlink:href="#dropdown"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-values">
                                <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                    <ul class="list">
                                        <? foreach ($arResult["DEFAULT_PARAMS"]['BUILDING_TYPES'] as $arBuildtype): ?>
                                            <li class="list-item"
                                                data-dropdown-id="<?= $arBuildtype['UF_XML_ID']; ?>">
                                                <div class="list-item-checkbox">
                                                    <svg class="icon icon--check" width="13" height="9" viewbox="0 0 13 9">
                                                        <use xlink:href="#check"/>
                                                    </svg>
                                                </div>
                                                <span><?= $arBuildtype['UF_NAME']; ?></span>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <div class="catalog-filter-item">
                    <div class="dropdown dropdown--radio" id="filter-category" data-category-id="filter-category" data-dropdown="radio">
                        <input type="hidden"
                               value=""
                               data-dropdown-value="data-dropdown-value">
                        <div class="field-input"><?= $arResult["FILTER_VALUES"]["CATEGORY"] ? $arResult["CATEGORIES"][$arResult["FILTER_VALUES"]["CATEGORY"]] : $arResult["CATEGORIES"]["novostroyki"]?></div>
                        <div class="dropdown-icon">
                            <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                                <use xlink:href="#dropdown"/>
                            </svg>
                        </div>
                        <div class="dropdown-values dropdown-values--small">
                            <div class="dropdown-values-scroll" data-dropdown-scroll="data-dropdown-scroll">
                                <ul class="list">
                                    <?
                                    foreach ($arResult["CATEGORIES"] as $keyCategory => $categoryName) : ?>
                                        <li class="list-item<?= ($arResult["FILTER_VALUES"]["CATEGORY"] == $keyCategory) ?  " list-item--active" : !$arResult["FILTER_VALUES"]["CATEGORY"] && $keyCategory == "novostroyki" ? " list-item--active" : null; ?>"
                                            data-dropdown-id="<?= $keyCategory; ?>"><?= $categoryName; ?></li>
                                    <? endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?
        if ($arResult['IS_AJAX'])
            die();
        ?>
    </div>
</form>

<script>
    $(function () {
        function checkFilterParams() {
            var search = jQuery('#filter-search input').val();

            var city = jQuery('#filter-city input').val();

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
                'deadline': $('#filter-deadline input').val() ? $('#filter-deadline input').val() : null,
                'features': (features && features.length > 0) ? features : null,
                'finish': (finish && finish.length > 0) ? finish : null,
                'buildtype': (buildtype && buildtype.length > 0) ? buildtype : null
            };

            var category = $("[data-category-id='filter-category'] .list-item--active").attr('data-dropdown-id');

            if (category != 'novostroyki'){
                if (apartmenttype) params.apartmenttype = apartmenttype;
            }
            else {
                if (apartmenttype) params.rooms = apartmenttype;
            }

            if (category != 'novostroyki' || category != 'kupit-kvartiru'){
                if (squareMin) params.sizemin = squareMin;
                if (squareMax) params.sizemax = squareMax;
            }
            else {
                if (squareMin) params.square_min = squareMin;
                if (squareMax) params.square_max = squareMax;
            }

            if (city && city.length > 0) params.city = city;
            if (priceMin) params.price_min = priceMin;
            if (priceMax) params.price_max = priceMax;
            if (floorMin) params.floor_min = floorMin;
            if (floorMax) params.floor_max = floorMax;
            if (squareKitchenMin) params.kitchensizemin = squareKitchenMin;
            if (squareKitchenMax) params.kitchensizemax = squareKitchenMax;
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

        function getCategoryFilter(categoryCode) {
            var params = {};
            params.categoryCode = categoryCode;

            jQuery.ajax({
                type: "GET",
                data: params,
                dataType: 'html',
                success: function (html) {
                    jQuery('.catalog-filter-body').html(html);
                    revealNew();
                    lazyload.update();
                    rangeFilter.init();
                    sliderPriceInit();

                    var filter = $(".catalog-filter");
                    var filterMore = $("[data-filter-more]");

                    if (filterMore) {
                        filterMore.on('click', function (e) {
                            filter.toggleClass('catalog-filter--more');
                            if (filter.hasClass('catalog-filter--more')) {
                                filterMore.find('span').text(filterMore.attr('data-filter-more-hide'))
                            } else {
                                filterMore.find('span').text(filterMore.attr('data-filter-more-show'))
                            }
                        });
                    }
                }
            });
        }

        function getMapObjects(count) {
            var params = checkFilterParams();
            params.showMap = 'Y';

            var category = $("[data-category-id='filter-category'] .list-item--active").attr('data-dropdown-id');

            var queryParams = setUrlParamsPageParametrical(params);
            history.replaceState(null, null, null);

            var url = '/<?=$arParams['CITY_CODE'];?>/'+ category + '/' + queryParams;
            location.href = url;
        }

        function getObjects(count) {
            var params = checkFilterParams();

            var category = $("[data-category-id='filter-category'] .list-item--active").attr('data-dropdown-id');

            var queryParams = setUrlParamsPageParametrical(params);
            history.replaceState(null, null, null);

            var url = '/<?=$arParams['CITY_CODE'];?>/'+ category + '/' + queryParams;
            location.href = url;
        }

        jQuery(document).on('click', '#show-more', function (e) {
            e.preventDefault();
            $('#show-more').addClass('button--refresh-load');
            getPage();
            $('#show-more').removeClass('button--refresh-load');
        });

        jQuery(document).on('click', '#filter-deadline [data-dropdown-id]', function () {
            $('#filter-deadline').removeClass("dropdown--show");
        });


        $('#sort').on('click', '[data-dropdown-id]', function (e) {
            e.preventDefault();
            //getObjects(false);
            setTimeout(function(){
                getObjects(false);
            }, 200);
        });

        jQuery(document).on('click', '#apply-filter', function (e) {
            e.preventDefault();
            getObjects(false);
        });

        jQuery(document).on('click', '#filter-clear', function (e) {
            e.preventDefault();
            <?if (SITE_ID == 's1' || SITE_ID == 's2'):?>
            var url = '/<?=$arParams['CITY_CODE'];?>/novostroyki/';
            <?else:?>
            <?//$urlClear = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);?>
            <?$urlClear = '/';?>
            var url = "<?=$urlClear;?>";
            <?endif;?>
            location.href = url;
        });

        jQuery(document).on('click', '[data-category-id="filter-category"] [data-dropdown-id]', function (e) {
            let categoryCode = $(this).attr('data-dropdown-id');
            let categoryName = $(this).text();
            $('[data-category-id="filter-category"] .field-input').text(categoryName);
            setTimeout(function(){
                getCategoryFilter(categoryCode);
            }, 200);
        });
        jQuery(document).on('click', '.button--map', function (e) {
            e.preventDefault();
            getMapObjects(false);
        });
    });
</script>