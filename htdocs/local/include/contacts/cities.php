<?
$arCities = [
    "moscow" => [
        "URL" => "/",
        "NAME" => "Москва",
        "PHONE" => "+7 (812) 467-45-05"
    ],
    "spb" => [
        "URL" => "/spb",
        "NAME" => "Санкт-Петербург",
        "PHONE" => "+7 (812) 467-45-05"
    ]
];
if (!$arParams["CITY_CODE"]) $arParams['CITY_CODE'] = "moscow"
?>
<div class="header-contacts-phone"><a href="tel:<?=str_replace(array("(", " ", ")"),"",$arCities[$arParams['CITY_CODE']]['PHONE'])?>"><?=$arCities[$arParams['CITY_CODE']]['PHONE']?></a></div>
<div class="header-contacts-location">
    <button class="text-control text-control--dropdown" data-location-toggle><span><?=$arCities[$arParams['CITY_CODE']]['NAME']?></span>
        <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
            <use xlink:href="#dropdown"/>
        </svg>
    </button>
    <div class="header-contacts-location-dropdown">
        <button data-location-close>
            <svg class="icon icon--cross-light" width="16" height="16" viewbox="0 0 16 16">
                <use xlink:href="#cross-light"/>
            </svg>
        </button>
        <div>Выберите город:</div>
        <ul class="list">
            <? foreach ($arCities as $keyCode => $arCity) : ?>
                <li class="list-item">
                    <? if ($keyCode === $arParams['CITY_CODE']) : ?>
                        <span class="list-link"><?= $arCity['NAME'] ?></span>
                    <? else: ?>
                        <a class="list-link" href="<?= $arCity['URL'] ?>"><?= $arCity['NAME'] ?></a>
                    <? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
</div>