<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

CModule::IncludeModule('highloadblock');
CModule::includeModule('iblock');

$request  = Application::getInstance()->getContext()->getRequest();
$id       = $request->getQuery('id');
$currency = mb_strtolower($request->getQuery('currency'));

$infoblockId  = CIBlockElement::GetIBlockByID($id);

$arFlat = CIBlockElement::GetList(
    array(),
    array(
        "ACTIVE"    => "Y",
        "ID"        => intval($id),
        "IBLOCK_ID" => intval($infoblockId),
    ),
    false,
    false,
    array(
        "ID",
        "IBLOCK_ID",
        "IBLOCK_SECTION_ID",
        "CODE",
        "NAME",
        "DETAIL_TEXT",
        "SHOW_COUNTER",
        "PREVIEW_PICTURE",
        "DETAIL_PICTURE",
        "PROPERTY_HOUSE",
        "PROPERTY_FLOOR",
        "PROPERTY_FLOOR_MAX",
        "PROPERTY_SQUARE",
        "PROPERTY_HIGH_CEILINGS",
        "PROPERTY_TERRACE",
        "PROPERTY_ROOMS",
        "PROPERTY_PRICE_RUB",
        "PROPERTY_PRICE_USD",
        "PROPERTY_PRICE_EUR",
        "PROPERTY_PRICE_GBP",
        "PROPERTY_BUILDING",
        "PROPERTY_DECORATION",
        "PROPERTY_TWO_TIER",
        "PROPERTY_PHOTOS",
        "PROPERTY_PRICE_FROM",
    )
)->Fetch();

$arParams = array(
    "IBLOCK_ID_FLATS"     => $arFlat["IBLOCK_ID"],
    "ELEMENT_ID_CONTACTS" => CONTACTS_MSK_ELEMENT_ID,
    "CURRENCY"            => "₽",
    "CURRENCY_PER_MONTH"  => "₽/м",
    "SQUARE_MEASURE"      => "м²",
);

if($arParams["IBLOCK_ID_FLATS"] == NEW_BUILD_SPB_IBLOCK_ID) {
    $arParams["ELEMENT_ID_CONTACTS"]  = CONTACTS_SPB_ELEMENT_ID;
}

$arContacts = CIBlockElement::GetList(
    array(),
    array(
        "IBLOCK_ID" => CONTACTS_IBLOCK_ID,
        "ACTIVE"    => "Y",
        "ID"        => $arParams["ELEMENT_ID_CONTACTS"],
    ),
    false,
    false,
    array(
        "ID",
        "IBLOCK_ID",
        "CODE",
        "NAME",
        "PROPERTY_ADDRESS",
        "PROPERTY_PHONE",
    )
)->Fetch();

if (Cmodule::IncludeModule('asd.iblock')) {
    $arFieldsIb = CASDiblockTools::GetIBUF($infoblockId);
}

$priceHide = $arFieldsIb["UF_HIDE_PRICE"];

//$arFlat["DETAIL_PICTURE"] = CFile::ResizeImageGet($arFlat["DETAIL_PICTURE"], array('width'=>390, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
$arFlat["DETAIL_PICTURE"] = CFile::GetPath($arFlat["DETAIL_PICTURE"]);

$arEnum = CIBlockPropertyEnum::GetByID($arFlat["PROPERTY_ROOMS_ENUM_ID"]);
$arFlat["ROOMS_COUNT"] = str_replace('rooms_', '', $arEnum['XML_ID']);
if ($arFlat["ROOMS_COUNT"] === "0") {
    $arFlat["ROOMS_COUNT"] = "Студия";
} elseif ($arFlat["ROOMS_COUNT"] === "4") {
    $arFlat["ROOMS_COUNT"] = "4 и более";
}

if (!empty($currency)) {
    if ($currency == "usd") {
        $arFlat["PRICE_PDF"] = number_format($arFlat["PROPERTY_PRICE_USD_VALUE"], 0, '.', ' ');
        $arFlat["PRICE_PER_MONTH_PDF"] = number_format($arFlat["PROPERTY_PRICE_USD_VALUE"]/$arFlat["PROPERTY_SQUARE_VALUE"], 0, '.', ' ');
        $arParams["CURRENCY"] = "$";
        $arParams["CURRENCY_PER_MONTH"] = "$/м";
    } elseif($currency == "eur") {
        $arFlat["PRICE_PDF"] = number_format($arFlat["PROPERTY_PRICE_EUR_VALUE"], 0, '.', ' ');
        $arFlat["PRICE_PER_MONTH_PDF"] = number_format($arFlat["PROPERTY_PRICE_EUR_VALUE"]/$arFlat["PROPERTY_SQUARE_VALUE"], 0, '.', ' ');
        $arParams["CURRENCY"] = "€";
        $arParams["CURRENCY_PER_MONTH"] = "€/м";
    } elseif($currency == "gbp") {
        $arFlat["PRICE_PDF"] = number_format($arFlat["PROPERTY_PRICE_GBP_VALUE"], 0, '.', ' ');
        $arFlat["PRICE_PER_MONTH_PDF"] = number_format($arFlat["PROPERTY_PRICE_GBP_VALUE"]/$arFlat["PROPERTY_SQUARE_VALUE"], 0, '.', ' ');
        $arParams["CURRENCY"] = "£";
        $arParams["CURRENCY_PER_MONTH"] = "£/м";
    } elseif($currency == "rur" || $currency == "rub") {
        $arFlat["PRICE_PDF"] = number_format($arFlat["PROPERTY_PRICE_RUB_VALUE"], 0, '.', ' ');
        $arFlat["PRICE_PER_MONTH_PDF"] = number_format($arFlat["PROPERTY_PRICE_RUB_VALUE"]/$arFlat["PROPERTY_SQUARE_VALUE"], 0, '.', ' ');
        $arParams["CURRENCY"] = "₽";
        $arParams["CURRENCY_PER_MONTH"] = "₽/м";
    }
} else {
    $arFlat["PRICE_PDF"] = number_format($arFlat["PROPERTY_PRICE_RUB_VALUE"], 0, '.', ' ');
    $arFlat["PRICE_PER_MONTH_PDF"] = number_format($arFlat["PROPERTY_PRICE_RUB_VALUE"]/$arFlat["PROPERTY_SQUARE_VALUE"], 0, '.', ' ');
}


$arObject = CIBlockSection::GetList(
    array(),
    array(
        "IBLOCK_ID"   => $arParams["IBLOCK_ID_FLATS"],
        "ACTIVE"      => "Y",
        "ID"          => $arFlat["IBLOCK_SECTION_ID"],
        "DEPTH_LEVEL" => 1
    ),
    false,
    array(
        "ID",
        "IBLOCK_ID",
        "IBLOCK_SECTION_ID",
        "CODE",
        "NAME",
        "PICTURE",
        "DESCRIPTION",
        "UF_*"
    ),
    false
)->Fetch();

if($arObject["UF_PHOTOS"][0]) {
    $arObject["SMALL_PHOTO"] = CFile::ResizeImageGet($arObject["UF_PHOTOS"][0], array('width'=>460, 'height'=>372), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
}

if($arObject["UF_PHOTOS"][1]) {
    $arObject["LARGE_PHOTO"] = CFile::ResizeImageGet($arObject["UF_PHOTOS"][1], array('width' => 460, 'height' => 372), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
}

if($arFlat["PROPERTY_PHOTOS_VALUE"]["0"]) {
    $arObject["SMALL_PHOTO"] = CFile::ResizeImageGet($arFlat["PROPERTY_PHOTOS_VALUE"][0], array('width'=>460, 'height'=>372), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
}

if($arFlat["PROPERTY_PHOTOS_VALUE"]["1"]) {
    $arObject["LARGE_PHOTO"] = CFile::ResizeImageGet($arFlat["PROPERTY_PHOTOS_VALUE"][1], array('width' => 460, 'height' => 372), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
}

$hlblock = HL\HighloadBlockTable::getById(BUILDINGS_HIGHLOADBLOCK_ID)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array("UF_COMPLEX" => $arObject["UF_ID"])
));
while($arData = $rsData->Fetch()){
    $arObject["CORPUS_INFO"] = $arData;
}

//xprint($arParams);
//xprint($arObject);
//xprint($arFlat);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title></title>
    <meta name="keywords" content>
    <meta name="description" content>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="preload" href="<?=SITE_TEMPLATE_PATH;?>/fonts/Code-Pro.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?=SITE_TEMPLATE_PATH;?>/fonts/Navigo-Bold.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?=SITE_TEMPLATE_PATH;?>/fonts/Navigo-Medium.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?=SITE_TEMPLATE_PATH;?>/fonts/Navigo-Regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/app.min.css?v=1618037291179">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/css/pdf.min.css?v=1618037291180">
</head>
<body>

<div class="pdf">
    <header class="pdf-header">
        <div class="pdf-header-logotype"><img src="<?=SITE_TEMPLATE_PATH;?>/img/pdf/pdf-logo.png" alt></div>
        <div class="pdf-header-contacts">
            <h4 style="font-size: 1.7rem;"><?=$arContacts["PROPERTY_PHONE_VALUE"];?></h4><span><?=$arContacts["PROPERTY_ADDRESS_VALUE"];?></span>
        </div>
    </header>
    <main class="pdf-main">
        <div class="pdf-hero">
            <div class="pdf-hero-params">
                <h1><?=$arFlat["NAME"];?></h1>
                <ul class="list">
                    <li class="list-item">
                        <div class="list-item-label">Площадь</div>
                        <div class="list-item-value"><?if($arFlat["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?> <?=$arFlat["PROPERTY_SQUARE_VALUE"];?> <?=$arParams["SQUARE_MEASURE"];?></div>
                    </li>
                    <li class="list-item">
                        <div class="list-item-label">Количество комнат</div>
                        <div class="list-item-value"><?=$arFlat["ROOMS_COUNT"];?></div>
                    </li>
                    <?if($arFlat["PROPERTY_FLOOR_MAX_VALUE"] || $arObject["CORPUS_INFO"]["UF_FLOORS"]):?>
                        <li class="list-item">
                            <div class="list-item-label">Этаж/этажей в доме</div>
                            <div class="list-item-value"><?=$arFlat["PROPERTY_FLOOR_VALUE"];?>/<?=$arFlat["PROPERTY_FLOOR_MAX_VALUE"] ? $arFlat["PROPERTY_FLOOR_MAX_VALUE"] : $arObject["CORPUS_INFO"]["UF_FLOORS"];?></div>
                        </li>
                    <?elseif (empty($arFlat["PROPERTY_FLOOR_MAX_VALUE"]) && empty($arObject["CORPUS_INFO"]["UF_FLOORS"]) && !empty($arFlat["PROPERTY_FLOOR_VALUE"])):?>
                        <li class="list-item">
                            <div class="list-item-label">Этаж в доме</div>
                            <div class="list-item-value"><?=$arFlat["PROPERTY_FLOOR_VALUE"];?></div>
                        </li>
                    <?elseif (!empty($arFlat["PROPERTY_FLOOR_VALUE"])):?>
                        <li class="list-item">
                            <div class="list-item-label">Этаж в доме</div>
                            <div class="list-item-value"><?=$arFlat["PROPERTY_FLOOR_VALUE"];?></div>
                        </li>
                    <?endif;?>
                </ul>
                <?if ($priceHide == 1) :?>
                    <div class="pdf-hero-params-price">По запросу</div>
                <?else:?>
                    <div class="pdf-hero-params-price"><?if($arFlat["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?>  <?=$arFlat["PRICE_PDF"];?> <?=$arParams["CURRENCY"];?></div>
                    <div class="pdf-hero-params-price-footnote"><?if($arFlat["PROPERTY_PRICE_FROM_VALUE"] === "Да"):?>от<?endif;?>  <?=$arFlat["PRICE_PER_MONTH_PDF"];?> <?=$arParams["CURRENCY_PER_MONTH"];?></div>
                <?endif;?>
            </div>
            <div class="pdf-hero-image"><img src="<?=$arFlat["DETAIL_PICTURE"];?>" alt></div>
        </div>
        <?if(!empty($arObject["DESCRIPTION"]) || !empty($arFlat["DETAIL_TEXT"])):?>
            <div class="pdf-about">
                <div class="pdf-about-title">Подробнее об объекте</div>
                <div class="pdf-about-text">
                    <?=mb_strimwidth($arObject["DESCRIPTION"], 0, 1500, "...");?>
                    <?=mb_strimwidth($arFlat["DETAIL_TEXT"], 0, 1500, "...");?>
                </div>
            </div>
        <?endif;?>
        <?if(!empty($arObject["DESCRIPTION"]) && mb_strlen($arObject["DESCRIPTION"], 'utf-8') < 600 || (!empty($arFlat["DETAIL_TEXT"]) && mb_strlen($arFlat["DETAIL_TEXT"], 'utf-8') < 600)):?>
            <?if(!empty($arObject["SMALL_PHOTO"])):?>
                <div class="pdf-gallery">
                    <div class="pdf-gallery-small" style="background-image: url('<?=$arObject["SMALL_PHOTO"]["src"];?>');"></div>
                    <div class="pdf-gallery-large" style="background-image: url('<?=$arObject["LARGE_PHOTO"]["src"];?>');"></div>
                </div>
            <?endif;?>
        <?endif;?>
    </main>
</div>
<?if((mb_strlen($arObject["DESCRIPTION"], 'utf-8') > 600 && !empty($arObject["SMALL_PHOTO"])) || (!empty($arFlat["DETAIL_TEXT"]) && mb_strlen($arFlat["DETAIL_TEXT"], 'utf-8') > 600 && !empty($arObject["SMALL_PHOTO"]))):?>
    <div class="pdf">
        <header class="pdf-header">
            <div class="pdf-header-logotype"><img src="<?=SITE_TEMPLATE_PATH;?>/img/pdf/pdf-logo.png" alt></div>
            <div class="pdf-header-contacts">
                <h4 style="font-size: 1.7rem;"><?=$arContacts["PROPERTY_PHONE_VALUE"];?></h4><span><?=$arContacts["PROPERTY_ADDRESS_VALUE"];?></span>
            </div>
        </header>
        <main class="pdf-main">
            <?if(!empty($arObject["SMALL_PHOTO"])):?>
                <div class="pdf-gallery">
                    <div class="pdf-gallery-small" style="background-image: url('<?=$arObject["SMALL_PHOTO"]["src"];?>');"></div>
                    <div class="pdf-gallery-large" style="background-image: url('<?=$arObject["LARGE_PHOTO"]["src"];?>');"></div>
                </div>
            <?endif;?>
        </main>
    </div>
<?endif;?>
</body>
</html>
