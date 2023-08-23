<?
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;

ini_set("max_execution_time", 0);

if (!$_SERVER['DOCUMENT_ROOT']) {
    $_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../../../');
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");
Loader::includeModule("highloadblock");
$el = new CIBlockElement();

/**
 * Курс валют
 */
function getCBRRates()
{
    $xml_daily_file = __DIR__ . '/daily.xml';

    // кеш на четыре часа
    if (!is_file($xml_daily_file) || filemtime($xml_daily_file) < time() - 7200) {
        if ($xml_daily = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp')) {
            file_put_contents($xml_daily_file, $xml_daily);
        }
    }

    $result = array();
    foreach (simplexml_load_file($xml_daily_file) as $el) {
        $result[strval($el->CharCode)] = (float)strtr($el->Value, ',', '.');
    }

    return $result;
}

/**
 * @param $price
 * @param $rate
 * @param $id
 * @param $iblockId
 * Обновление цен у помещений
 */
function priceUpdate($price, $rate, $id, $iblockId){

    $arProps = array(
        'PRICE_USD' => ceil($price / $rate['USD']),
        'PRICE_EUR' => ceil($price / $rate['EUR']),
        'PRICE_GBP' => ceil($price / $rate['GBP']),
    );

    CIBlockElement::SetPropertyValuesEx(
        $id,
        $iblockId,
        $arProps
    );
}

$rate = getCBRRates();

/**
 *  Вторичка МСК
 */
$rsFlats = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array(
        'IBLOCK_ID' => RESALE_IBLOCK_ID
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'CODE',
        'PROPERTY_PRICE_RUB',
    )
);
while ($arFlat = $rsFlats->Fetch()) {
    priceUpdate($arFlat['PROPERTY_PRICE_RUB_VALUE'], $rate, $arFlat['ID'], RESALE_IBLOCK_ID);
}

/**
 *  Вторичка СПБ
 */
$rsFlats = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array(
        'IBLOCK_ID' => RESALE_SPB_IBLOCK_ID
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'CODE',
        'PROPERTY_PRICE_RUB',
    )
);
while ($arFlat = $rsFlats->Fetch()) {
    priceUpdate($arFlat['PROPERTY_PRICE_RUB_VALUE'], $rate, $arFlat['ID'], RESALE_SPB_IBLOCK_ID);
}

/**
 *  Загородная МСК
 */
$rsFlats = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array(
        'IBLOCK_ID' => COUNTRY_IBLOCK_ID
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'CODE',
        'PROPERTY_PRICE_RUB',
    )
);
while ($arFlat = $rsFlats->Fetch()) {
    priceUpdate($arFlat['PROPERTY_PRICE_RUB_VALUE'], $rate, $arFlat['ID'], COUNTRY_IBLOCK_ID);
}

/**
 *  Загородная СПБ
 */
$rsFlats = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array(
        'IBLOCK_ID' => COUNTRY_SPB_IBLOCK_ID
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'CODE',
        'PROPERTY_PRICE_RUB',
    )
);
while ($arFlat = $rsFlats->Fetch()) {
    priceUpdate($arFlat['PROPERTY_PRICE_RUB_VALUE'], $rate, $arFlat['ID'], COUNTRY_SPB_IBLOCK_ID);
}

/**
 *  Зарубежная
 */
$rsFlats = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array(
        'IBLOCK_ID' => FOREIGN_IBLOCK_ID
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'CODE',
        'PROPERTY_PRICE_RUB',
    )
);
while ($arFlat = $rsFlats->Fetch()) {
    priceUpdate($arFlat['PROPERTY_PRICE_RUB_VALUE'], $rate, $arFlat['ID'], FOREIGN_IBLOCK_ID);
}

/**
 *  Новостройки (не выгрузка) МСК
 */
$rsFlats = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array(
        'IBLOCK_ID' => NEW_BUILD_IBLOCK_ID,
        'PROPERTY_NO_UPDATE_VALUE' => 'Y'
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'CODE',
        'PROPERTY_PRICE_RUB',
    )
);
while ($arFlat = $rsFlats->Fetch()) {
    priceUpdate($arFlat['PROPERTY_PRICE_RUB_VALUE'], $rate, $arFlat['ID'], FOREIGN_IBLOCK_ID);
}

/**
 *  Новостройки (не выгрузка) СПБ
 */
$rsFlats = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array(
        'IBLOCK_ID' => NEW_BUILD_SPB_IBLOCK_ID,
        'PROPERTY_NO_UPDATE_VALUE' => 'Y'
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'CODE',
        'PROPERTY_PRICE_RUB',
    )
);
while ($arFlat = $rsFlats->Fetch()) {
    priceUpdate($arFlat['PROPERTY_PRICE_RUB_VALUE'], $rate, $arFlat['ID'], FOREIGN_IBLOCK_ID);
}

?>