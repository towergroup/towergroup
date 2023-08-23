<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);

$phoneReplaceClass = $APPLICATION->GetPageProperty("PHONE_REPLACE_CLASS");

if (SITE_ID == "s1" || SITE_ID == "s2") {
	if(CSite::InDir('/'.$arResult['CURRENT_CITY']['CODE'].'/novostroyki/') && !empty($arResult['CURRENT_CITY']['PROPERTY_PHONE_NEW_BUILD_VALUE'])) {
		$phoneValue = $arResult['CURRENT_CITY']['PROPERTY_PHONE_NEW_BUILD_VALUE'];
	} elseif (CSite::InDir('/'.$arResult['CURRENT_CITY']['CODE'].'/kupit-kvartiru/') && !empty($arResult['CURRENT_CITY']['PROPERTY_PHONE_RESALE_VALUE'])) {
		$phoneValue = $arResult['CURRENT_CITY']['PROPERTY_PHONE_RESALE_VALUE'];
	} elseif (CSite::InDir('/'.$arResult['CURRENT_CITY']['CODE'].'/kupit-dom/') && !empty($arResult['CURRENT_CITY']['PROPERTY_PHONE_COUNTRY_VALUE'])) {
		$phoneValue = $arResult['CURRENT_CITY']['PROPERTY_PHONE_COUNTRY_VALUE'];
	} elseif (CSite::InDir('/'.$arResult['CURRENT_CITY']['CODE'].'/nedvizhimost-za-rubezhom/') && !empty($arResult['CURRENT_CITY']['PROPERTY_PHONE_OVERSEAS_VALUE'])) {
		$phoneValue = $arResult['CURRENT_CITY']['PROPERTY_PHONE_OVERSEAS_VALUE'];
	} else {
		$phoneValue = $arResult['CURRENT_CITY']['PROPERTY_PHONE_VALUE'];
	}
} else {
	$phoneValueLanding = getIDMetrics(SITE_ID);
	if (!empty($phoneValueLanding["PROPERTY_PHONE_LANDING_VALUE"])) {
		$phoneValue = $phoneValueLanding["PROPERTY_PHONE_LANDING_VALUE"];
	} else {
		$phoneValue = $arResult['CURRENT_CITY']['PROPERTY_PHONE_VALUE'];
	}
    if (!empty($phoneValueLanding["PROPERTY_PHONE_REPLACE_VALUE"])) {
        $phoneReplaceClass = $phoneValueLanding["PROPERTY_PHONE_REPLACE_VALUE"];
    }
}
?>
<div class="header-contacts-phone">
    <a class="<?= $phoneReplaceClass ?>" href="tel:<?= str_replace(array("(", "-", " ", ")"), "",
        $phoneValue) ?>">
        <?= $phoneValue ?>
    </a>
</div>
<? if (SITE_ID == 's1' || SITE_ID == 's2'): ?>
    <div class="header-contacts-location desktop">
        <button class="text-control text-control--dropdown" data-location-toggle>
            <span><?= $arResult['CURRENT_CITY']['NAME'] ?></span>
            <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                <use xlink:href="#dropdown"/>
            </svg>
        </button>
        <div class="header-contacts-location-dropdown">
            <div>Выберите город:</div>
            <ul class="list">
                <?
                $dir = $APPLICATION->GetCurDir();
                $dir = explode("/", $dir);
                //xprint($dir);
                if (count($dir) > 3) {
                    if ($dir[2] == 'about' && count($dir) > 4) {
                        $url = $dir[2] . '/' . $dir[3];
                    } else {
                        $url = $dir[2];
                    }
                }
                foreach ($arResult['ITEMS'] as $keyCode => $arCity) : ?>
                    <li class="list-item">
                        <? if ($arCity['CODE'] === $arParams['CITY_CODE']) : ?>
                            <span class="list-link"><?= $arCity['NAME'] ?></span>
                        <? else: ?>
                            <? if ($arCity['CODE'] === 'spb' && !$url): ?>
                                <a class="list-link"
                                   href="/"><?= $arCity['NAME'] ?></a>
                            <? else: ?>
                                <a class="list-link"
                                   href="/<?= $arCity['CODE']. '/'; ?><?= $url ? $url . '/' : ''; ?>"><?= $arCity['NAME'] ?></a>
                            <? endif; ?>
                        <? endif; ?>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
    </div>
<? endif; ?>