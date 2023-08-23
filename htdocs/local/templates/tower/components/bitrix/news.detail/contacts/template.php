<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$phoneReplaceClass = $APPLICATION->GetPageProperty("PHONE_REPLACE_CLASS");

if (SITE_ID == "s1" || SITE_ID == "s2") {
	if(CSite::InDir('/'.$arResult['CODE'].'/novostroyki/') && !empty($arResult['PROPERTIES']['PHONE_NEW_BUILD']['VALUE'])) {
		$phoneValue = $arResult['PROPERTIES']['PHONE_NEW_BUILD']['VALUE'];
	} elseif (CSite::InDir('/'.$arResult['CODE'].'/kupit-kvartiru/') && !empty($arResult['PROPERTIES']['PHONE_RESALE']['VALUE'])) {
		$phoneValue = $arResult['PROPERTIES']['PHONE_RESALE']['VALUE'];
	} elseif (CSite::InDir('/'.$arResult['CODE'].'/kupit-dom/') && !empty($arResult['PROPERTIES']['PHONE_COUNTRY']['VALUE'])) {
		$phoneValue = $arResult['PROPERTIES']['PHONE_COUNTRY']['VALUE'];
	} elseif (CSite::InDir('/'.$arResult['CODE'].'/nedvizhimost-za-rubezhom/') && !empty($arResult['PROPERTIES']['PHONE_OVERSEAS']['VALUE'])) {
		$phoneValue = $arResult['PROPERTIES']['PHONE_OVERSEAS']['VALUE'];
	} else {
		$phoneValue = $arResult['PROPERTIES']['PHONE']['VALUE'];
	}
} else {
	$phoneValueLanding = getIDMetrics(SITE_ID);
	if (!empty($phoneValueLanding["PROPERTY_PHONE_LANDING_VALUE"])) {
		$phoneValue = $phoneValueLanding["PROPERTY_PHONE_LANDING_VALUE"];
	} else {
		$phoneValue = $arResult['PROPERTIES']['PHONE']['VALUE'];
	}
    if (!empty($phoneValueLanding["PROPERTY_PHONE_REPLACE_VALUE"])) {
        $phoneReplaceClass = $phoneValueLanding["PROPERTY_PHONE_REPLACE_VALUE"];
    }
}
?>
<?if($arParams["PAGE_TYPE_AND_POSITION"] === "MAIN_TOP"):?>
    <div class="hero-about-contacts">
        <ul class="list">
            <li class="list-item">
                <div class="list-item-label">Телефон</div>
                <div class="list-item-value"><a class="<?= $phoneReplaceClass ?>" href="tel:<?=str_replace(array(" ","(",")","-"),"",$arResult['PROPERTIES']['PHONE']['VALUE'])?>"><?=$arResult['PROPERTIES']['PHONE']['VALUE']?></a></div>
            </li>
            <li class="list-item">
                <div class="list-item-label">E-mail</div>
                <div class="list-item-value"><a href="mailto:<?=$arResult['PROPERTIES']['EMAIL']['VALUE']?>"><?=$arResult['PROPERTIES']['EMAIL']['VALUE']?></a></div>
            </li>
            <li class="list-item">
                <div class="list-item-label">Время работы</div>
                <div class="list-item-value"><?=$arResult['PROPERTIES']['WORKTIME']['~VALUE']['TEXT']?></div>
            </li>
        </ul>
        <a class="text-control text-control--phone" href="#contact-with-us" data-modal=""><span>Связаться с нами</span><svg class="icon icon--phone" width="17" height="17" viewBox="0 0 17 17">
                <use xlink:href="#phone"></use>
            </svg>
        </a>
    </div>
<?elseif ($arParams["PAGE_TYPE_AND_POSITION"] === "MAIN_BOTTOM"):?>
    <div class="container">
        <div class="static-map-contacts" data-scroll-fx="data-scroll-fx">
            <div><?=$arResult['PROPERTIES']['ADDRESS']['VALUE']?></div>
            <div class="static-map-contacts-phone"><a class="<?= $phoneReplaceClass ?>" href="tel:<?=str_replace(array(" ","(",")","-"),"",$arResult['PROPERTIES']['PHONE']['VALUE'])?>"><?=$arResult['PROPERTIES']['PHONE']['VALUE']?></a></div>
            <div><a href="mailto:<?=$arResult['PROPERTIES']['EMAIL']['VALUE']?>"><?=$arResult['PROPERTIES']['EMAIL']['VALUE']?></a></div><a class="button button--default" href="#feedback" data-modal="data-modal">Написать нам</a>
        </div>
    </div>
<?elseif ($arParams["PAGE_TYPE_AND_POSITION"] === "DEFAULT_FOOTER"):?>
    <div class="footer-common-contacts-phone"><a class="<?= $phoneReplaceClass ?>" href="tel:<?=str_replace(array(" ","(",")","-"),"",$phoneValue)?>"><?=$phoneValue?></a></div>
    <div><?=$arResult['PROPERTIES']['ADDRESS']['VALUE']?></div>
    <div><?=$arResult['PROPERTIES']['WORKTIME']['~VALUE']['TEXT']?></div>
    <div><a href="mailto:<?=$arResult['PROPERTIES']['EMAIL']['VALUE']?>"><?=$arResult['PROPERTIES']['EMAIL']['VALUE']?></a></div>
<?else:?>
    <section class="section">
        <div class="container">
            <div class="contacts" data-scroll-fx>
                <div class="contacts-info">
                    <h1>Контакты</h1>
                    <ul class="list list--contacts">
                        <?if (!empty($arResult['PROPERTIES']['ADDRESS']['VALUE'])) :?>
                            <li class="list-item">
                                <div class="list-item-label">Адрес</div>
                                <div class="list-item-value"><?=$arResult['PROPERTIES']['ADDRESS']['VALUE']?></div>
                            </li>
                        <?endif;?>
                        <?if (!empty($arResult['PROPERTIES']['WORKTIME']['~VALUE']['TEXT'])) :?>
                            <li class="list-item">
                                <div class="list-item-label">Время работы</div>
                                <div class="list-item-value"><?=$arResult['PROPERTIES']['WORKTIME']['~VALUE']['TEXT']?></div>
                            </li>
                        <?endif;?>
                        <li class="list-item">
                            <div class="list-item-label">Телефон</div>
                            <div class="list-item-value"><a class="replace-phone" href="tel:<?=str_replace(array(" ","(",")","-"),"",$arResult['PROPERTIES']['PHONE']['VALUE'])?>"><?=$arResult['PROPERTIES']['PHONE']['VALUE']?></a></div>
                        </li>
                        <li class="list-item">
                            <div class="list-item-label">E-mail</div>
                            <div class="list-item-value"><a href="mailto:<?=$arResult['PROPERTIES']['EMAIL']['VALUE']?>"><?=$arResult['PROPERTIES']['EMAIL']['VALUE']?></a></div>
                        </li>
                    </ul>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "socials",
                        Array(
                            "IBLOCK_ID" => SOCIALS_IBLOCK_ID,
                            "DISPLAY_PROPERTIES" => array(
                                0 => "",
                                1 => "",
                            ),
                            "PROPERTY_CODE" => array(
                                0 => "LINK",
                                1 => "SVG"
                            ),
                            "SET_TITLE" => "N",
                            "NEWS_COUNT" => "20",
                            "SORT_BY1" => "SORT",
                            "SORT_ORDER1" => "ASC",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N"
                        )
                    );?>
                    <a class="button" href="#feedback" data-modal>Написать нам</a>
                </div>
                <div class="contacts-map">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
    <script>
        <?
        $coordMarker = explode(",", $arResult['PROPERTIES']['COORDS']['VALUE']);
        $pixelCenter = explode(",", $arResult['PROPERTIES']['PIXEL_CENTER']['VALUE']);
        ?>
        ymaps.ready(function()
        {
            var map = new ymaps.Map('map',
                {
                    center: [<?=$coordMarker[0]?>, <?=$coordMarker[1]?>],
                    zoom: 13,
                    controls: []
                },
				{
					suppressMapOpenBlock: true
				})
            map.behaviors.disable('scrollZoom')
            map.panes.get('ground').getElement().style.filter = 'grayscale(100%)'
            var object = new ymaps.Placemark([<?=$coordMarker[0]?>, <?=$coordMarker[1]?>], null,
                {
                    iconLayout: 'default#image',
                    iconImageHref: '<?= SITE_TEMPLATE_PATH; ?>/img/static-map-marker.svg',
                    iconImageSize: [36, 40],
                    iconImageOffset: [-38, -80]
                })
            /*
            var pixelCenter = map.getGlobalPixelCenter(<?=$coordMarker[0]?>, <?=$coordMarker[1]?>)
            pixelCenter = [
                pixelCenter[0] - <?=$pixelCenter[0]?>,
                pixelCenter[1] - <?=$pixelCenter[1]?>
            ]
            var geoCenter = map.options.get('projection').fromGlobalPixels(pixelCenter, map.getZoom())
            map.setCenter(geoCenter)
            */
            map.geoObjects.add(object)
        })
    </script>
<?endif;?>