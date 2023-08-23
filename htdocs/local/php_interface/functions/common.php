<?php

use Bitrix\Main;
use Bitrix\Main\Application;
use \Bitrix\Main\Data\Cache;

if (!Main\Loader::includeModule('iblock')) {
    throw new Main\LoaderException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
}

function xprint($v) { // Функция вывода информации на экран
    echo '<pre hidden>';
    print_r($v);
    echo '</pre>';
}

if(!function_exists(array_key_last)){
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }

        return array_keys($array)[count($array)-1];
    }
}

function plural_form($number, $after) { // Склонение слов
    $cases = array (2, 0, 1, 1, 1, 2);
    return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

function transliterate($string, $reverse=false){ // Фукция транслитерации русских букв
    $array = Array(
        'а'=>"a",  'А'=>"A",
        'б'=>"b",  'Б'=>"B",
        'в'=>"v",  'В'=>"V",
        'г'=>"g",  'Г'=>"G",
        'д'=>"d",  'Д'=>"D",
        'е'=>"e",  'Е'=>"E",
        'ё'=>"e",  'Ё'=>"E",
        'ж'=>"zh", 'Ж'=>"Zh",
        'з'=>"z",  'З'=>"Z",
        'и'=>"i",  'И'=>"I",
        'й'=>"j",  'Й'=>"J",
        'к'=>"k",  'К'=>"K",
        'л'=>"l",  'Л'=>"L",
        'м'=>"m",  'М'=>"M",
        'н'=>"n",  'Н'=>"N",
        'о'=>"o",  'О'=>"O",
        'п'=>"p",  'П'=>"P",
        'р'=>"r",  'Р'=>"R",
        'с'=>"s",  'С'=>"S",
        'т'=>"t",  'Т'=>"T",
        'у'=>"u",  'У'=>"U",
        'ф'=>"f",  'Ф'=>"F",
        'х'=>"h",  'Х'=>"H",
        'ц'=>"c",  'Ц'=>"C",
        'ч'=>"ch", 'Ч'=>"Ch",
        'ш'=>"sh", 'Ш'=>"Sh",
        'щ'=>"sh", 'Щ'=>"Sh",
        'ъ'=>"y",  'Ъ'=>"Y",
        'ы'=>"i",  'Ы'=>"I",
        'ь'=>"",   'Ь'=>"",
        'э'=>"e",  'Э'=>"E",
        'ю'=>"yu", 'Ю'=>"Yu",
        'я'=>"ya", 'Я'=>"Ya",
    );

    $arr_in = array_keys($array);
    $arr_out = array_values($array);

    if($reverse===true) return str_replace($arr_out, $arr_in, $string);
    return str_replace($arr_in, $arr_out, $string);
}


function transliterate_en($string, $reverse=false){ // Фукция транслитерации английских букв
    $array = Array(
        'a'=>"а",  'A'=>"А",
        'b'=>"б",  'B'=>"Б",
        'c'=>"с",  'C'=>"С",
        'd'=>"д",  'D'=>"Д",
        'e'=>"е",  'E'=>"Е",
        'f'=>"ф",  'F'=>"Ф",
        'g'=>"г",  'G'=>"Г",
        'h'=>"х",  'H'=>"Х",
        'i'=>"и",  'I'=>"И",
        'j'=>"дж", 'J'=>"Дж",
        'k'=>"к",  'K'=>"К",
        'l'=>"л",  'L'=>"Л",
        'm'=>"м",  'M'=>"М",
        'n'=>"н",  'N'=>"Н",
        'o'=>"о",  'O'=>"О",
        'p'=>"п",  'P'=>"П",
        'q'=>"ку", 'Q'=>"Ку",
        'r'=>"р",  'R'=>"Р",
        's'=>"с",  'S'=>"С",
        't'=>"т",  'T'=>"Т",
        'u'=>"у",  'U'=>"У",
        'v'=>"в",  'V'=>"В",
        'w'=>"в",  'W'=>"В",
        'x'=>"икс",'X'=>"Икс",
        'y'=>"и",  'Y'=>"И",
        'z'=>"з",  'Z'=>"З",
    );

    $arr_in = array_keys($array);
    $arr_out = array_values($array);

    if($reverse===true) return str_replace($arr_out, $arr_in, $string);
    return str_replace($arr_in, $arr_out, $string);
}

function transliterate_keyboard($string, $reverse=false){ // Фукция для конверсии раскладок клавиатуры
    $array = Array(
        'й'=>"q", 'Й'=>"Q",
        'ц'=>"w", 'Ц'=>"W",
        'у'=>"e", 'У'=>"E",
        'к'=>"r", 'К'=>"R",
        'е'=>"t", 'Е'=>"T",
        'н'=>"y", 'Н'=>"Y",
        'г'=>"u", 'Г'=>"U",
        'ш'=>"i", 'Ш'=>"I",
        'щ'=>"o", 'Щ'=>"O",
        'з'=>"p", 'З'=>"P",
        'х'=>"[", 'Х'=>"{",
        'ъ'=>"]", 'Ъ'=>"}",
        'ф'=>"a", 'Ф'=>"A",
        'ы'=>"s", 'Ы'=>"S",
        'в'=>"d", 'В'=>"D",
        'а'=>"f", 'А'=>"F",
        'п'=>"g", 'П'=>"G",
        'р'=>"h", 'Р'=>"H",
        'о'=>"j", 'О'=>"J",
        'л'=>"k", 'Л'=>"K",
        'д'=>"l", 'Д'=>"L",
        'ж'=>";", 'Ж'=>":",
        'э'=>"'", 'Э'=>"\"",
        'я'=>"z", 'Я'=>"Z",
        'ч'=>"x", 'Ч'=>"X",
        'с'=>"c", 'С'=>"C",
        'м'=>"v", 'М'=>"V",
        'и'=>"b", 'И'=>"B",
        'т'=>"n", 'Т'=>"N",
        'ь'=>"m", 'Ь'=>"M",
        'б'=>",", 'Б'=>"<",
        'ю'=>".", 'Ю'=>">",
        '.'=>"/", ','=>"?",
        'ё'=>"`", 'Ё'=>"~",
    );

    $arr_in = array_keys($array);
    $arr_out = array_values($array);

    if($reverse===true) return str_replace($arr_out, $arr_in, $string);
    return str_replace($arr_in, $arr_out, $string);
}

function getFormInfo ($formCode) {
    $cache = Cache::createInstance(); // получаем экземпляр класса
    if ($cache->initCache(7200, "cache_data_".$formCode)) { // проверяем кеш и задаём настройки
        $arResult = $cache->getVars(); // достаем переменные из кеша
    }
    elseif ($cache->startDataCache()) {
        $queryLang = \Bitrix\Iblock\ElementTable::getList(array(
            'order'  =>  array("SORT" => "ASC"),
            'select' => array('IBLOCK_ID', 'ID', 'NAME', 'XML_ID', 'PREVIEW_TEXT'),
            'filter' => array('IBLOCK_ID' => FORM_INFO_IBLOCK_ID, 'CODE' => $formCode),
            'cache' => array(
                'ttl' => 86400,
                'cache_joins' => true,
            )
        ));
        $resultLang = $queryLang->fetch();
        $arResult["NAME"] = $resultLang["NAME"];
        $arResult["FOOTNOTE"] = $resultLang["PREVIEW_TEXT"];
        $dbProperty = \CIBlockElement::getProperty($resultLang['IBLOCK_ID'], $resultLang['ID'], array("sort", "asc"), array('CODE' => "TITLE"));
        if ($arProperty = $dbProperty->GetNext()) {
            $arResult["TITLE"] = $arProperty["VALUE"];
        }
        $cache->endDataCache($arResult); // записываем в кеш
    }

    return $arResult;
}

function getIDMetrics($siteID){

    $rsElements = CIBlockElement::GetList(
        array("SORT" => "ASC"),
        array(
            "IBLOCK_ID" => METRICS_IBLOCK_ID,
            "ACTIVE" => "Y",
            "PROPERTY_SITE_ID" => $siteID,
        ),
        false,
        false,
        array(
            "ID",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "CODE",
            "NAME",
            "PREVIEW_PICTURE",
            "PREVIEW_TEXT",
            "DETAIL_TEXT",
            "PROPERTY_YM_NUMBER",
            "PROPERTY_GA_NUMBER",
			"PROPERTY_GTM_NUMBER",
			"PROPERTY_PHONE_LANDING",
            "PROPERTY_PHONE_REPLACE",
			"PROPERTY_PIXEL_CODE"
        )
    );

    while($arElement = $rsElements->GetNext()) {
        $arResult = $arElement;
    }


    return $arResult;
}

function getLogo($siteID){
    $cache = Cache::createInstance(); // получаем экземпляр класса
    if ($cache->initCache(7200, "cache_data_logo_".$siteID)) { // проверяем кеш и задаём настройки
        $arResult = $cache->getVars(); // достаем переменные из кеша
    }
    elseif ($cache->startDataCache()) {
        $rsElements = CIBlockElement::GetList(
            false,
            [
                "IBLOCK_ID" => LOGO_IBLOCK_ID,
                "ACTIVE" => "Y",
                "PROPERTY_SITE_ID" => $siteID,
            ],
            false,
            false,
            [
                "ID",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "CODE",
                "NAME",
                "PROPERTY_VIEWBOX",
                "PROPERTY_PATH",
            ]
        );
        $arResult = $rsElements->GetNext();

        $cache->endDataCache($arResult); // записываем в кеш
    }

    return $arResult;
}

function nofollow($html, $skip = null) {
    return preg_replace_callback(
        "#(<a[^>]+?)>#is", function ($mach) use ($skip) {
            return (
                !($skip && strpos($mach[1], $skip) !== false) &&
                strpos($mach[1], 'rel=') === false
            ) ? $mach[1] . ' rel="noopener noreferrer nofollow">' : $mach[0];
        },
        $html
    );
}