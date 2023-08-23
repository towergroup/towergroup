<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


CModule::IncludeModule('iblock');

if (!Main\Loader::includeModule('highloadblock')) {
    throw new Main\LoaderException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
}

$request = Application::getInstance()->getContext()->getRequest();


/**
 * Sorting item flats
 */
if ($request->get('text') != null){
    $text = urldecode($request->get('text'));
}


if ($request->get('id') != null){
    $textId = json_decode(($request->get('id')));
}

if ($text || $textId)
{
    $arFilterCountry = [
        "IBLOCK_ID" => REGION_OVERSEAS_IBLOCK_ID,
        "ACTIVE" => "Y",
        "DEPTH_LEVEL" => "1",
    ];

    $arFilterArea = [
        "IBLOCK_ID" => REGION_OVERSEAS_IBLOCK_ID,
        "ACTIVE" => "Y",
        "DEPTH_LEVEL" => "2",
    ];

    $arFilterStreet = [
        "IBLOCK_ID" => FOREIGN_IBLOCK_ID,
        "ACTIVE" => "Y",
    ];

    $arFilterCity = [
        "IBLOCK_ID" => FOREIGN_IBLOCK_ID,
        "ACTIVE" => "Y",
    ];

    if($text != null) {
        $reqText = (is_array($text)) ? $text : explode(',', $text);
        if(count($reqText) > 0) {
            $arFilterCountry["%NAME"] = $reqText;
            $arFilterArea["%NAME"] = $reqText;
            $arFilterStreet["%PROPERTY_ADDRESS"] = $reqText;
            $arFilterCity["%PROPERTY_CITY"] = $reqText;
        }
    }

    $arCountries = [
        'group' => 'Страна',
        'groupID' => 'country',
        'options' => [],
    ];

    $arAreas = [
        'group' => 'Регион',
        'groupID' => 'area',
        'options' => [],
    ];


    $arStreets = [
        'group' => 'Улица',
        'groupID' => 'street',
        'options' => [],
    ];

    $arCitys = [
        'group' => 'Город',
        'groupID' => 'city',
        'options' => [],
    ];

    $resCountry = CIBlockSection::GetList(
        array(),
        $arFilterCountry,
        false,
        array(
            "ID",
            "IBLOCK_ID",
            "CODE",
            "NAME",
            "PICTURE",
            "UF_*"
        ),
        false
    );

    while($arCountry = $resCountry->GetNext()){
        if (!in_array('country_'.$arCountry['ID'],$textId)) {
            $arCountries['options'][] = [
                'id' => 'country_'.$arCountry['ID'],
                'title' => $arCountry['NAME']
            ];
        }
    }

    $resAreas = CIBlockSection::GetList(
        array(),
        $arFilterArea,
        false,
        array(
            "ID",
            "IBLOCK_ID",
            "CODE",
            "NAME",
            "PICTURE",
            "UF_*"
        ),
        false
    );

    while($arArea = $resAreas->GetNext()){
        if (!in_array('area_'.$arArea['ID'],$textId)) {
            $arAreas['options'][] = [
                'id' => 'area_'.$arArea['ID'],
                'title' => $arArea['NAME']
            ];
        }
    }

//город
    $resCity = CIBlockElement::GetList(
        false,
        $arFilterCity,
        false,
        false,
        array(
            "ID",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "CODE",
            "NAME",
            "SHOW_COUNTER",
            "PREVIEW_PICTURE",
            "DETAIL_PICTURE",
            "PROPERTY_HOUSE",
            "PROPERTY_FLOOR",
            "PROPERTY_SQUARE",
            "PROPERTY_HIGH_CEILINGS",
            "PROPERTY_TERRACE",
            "PROPERTY_ROOMS",
            "PROPERTY_ROOMS_NUMBER",
            "PROPERTY_PRICE_RUB",
            "PROPERTY_PRICE_USD",
            "PROPERTY_PRICE_EUR",
            "PROPERTY_PRICE_GBP",
            "PROPERTY_BUILDING",
            "PROPERTY_DECORATION",
            "PROPERTY_TWO_TIER",
            "PROPERTY_DEADLINE_YEAR",
            "PROPERTY_PHOTOS",
            "PROPERTY_MAP",
            "PROPERTY_SUBWAY",
            "PROPERTY_ADDRESS",
            "PROPERTY_CITY",
        )
    );

    while($arCity = $resCity->GetNext()){
        if (!in_array('city_'.$arCity['PROPERTY_CITY_VALUE'],$textId)) {
            if (in_array($arCity['PROPERTY_CITY_VALUE'], $arCitys['options'])) continue;
            $arCitys['options'][] = [
                'id' => 'city_'.$arCity['PROPERTY_CITY_VALUE'],
                'title' => $arCity['PROPERTY_CITY_VALUE']
            ];
        }
    }
    $arCitys['options'] = array_unique($arCitys['options']);

//улицы
    $resStreets = CIBlockElement::GetList(
        false,
        $arFilterStreet,
        false,
        false,
        array(
            "ID",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "CODE",
            "NAME",
            "SHOW_COUNTER",
            "PREVIEW_PICTURE",
            "DETAIL_PICTURE",
            "PROPERTY_HOUSE",
            "PROPERTY_FLOOR",
            "PROPERTY_SQUARE",
            "PROPERTY_HIGH_CEILINGS",
            "PROPERTY_TERRACE",
            "PROPERTY_ROOMS",
            "PROPERTY_ROOMS_NUMBER",
            "PROPERTY_PRICE_RUB",
            "PROPERTY_PRICE_USD",
            "PROPERTY_PRICE_EUR",
            "PROPERTY_PRICE_GBP",
            "PROPERTY_BUILDING",
            "PROPERTY_DECORATION",
            "PROPERTY_TWO_TIER",
            "PROPERTY_DEADLINE_YEAR",
            "PROPERTY_PHOTOS",
            "PROPERTY_MAP",
            "PROPERTY_SUBWAY",
            "PROPERTY_ADDRESS",
        )
    );

    while($arStreet = $resStreets->GetNext()){
        if (!in_array('street_'.$arStreet['PROPERTY_ADDRESS_VALUE'],$textId)) {
            $street = explode(",",$arStreet['PROPERTY_ADDRESS_VALUE'])[0];
            if (in_array($street, $arStreets['options'])) continue;
            $arStreets['options'][] = [
                'id' => 'street_'.$street,
                'title' => $street
            ];
        }
    }
    $arStreets['options'] = array_unique($arStreets['options']);

    //xprint($arStreets);

    if ($arCountries)$arReturn[] = $arCountries;
    if ($arAreas)$arReturn[] = $arAreas;
    if ($arSubways) $arReturn[] = $arSubways;
    if ($arStreets) $arReturn[] = $arStreets;
    if ($arCitys) $arReturn[] = $arCitys;

    ?>
    <?
    //xprint($arReturn);
    echo $encode = json_encode($arReturn, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
else {
    echo "Повторите запрос";
}
?>