<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$rsCurCity = CIBlockElement::GetList(array("SORT" => "ASC"), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $arParams['CITY_CODE']), false, false,
    array('IBLOCK_ID', 'IBLOCK_SECTION_ID', 'ID', 'NAME', 'CODE', 'PROPERTY_PHONE', 'PROPERTY_PHONE_COUNTRY', 'PROPERTY_PHONE_NEW_BUILD', 'PROPERTY_PHONE_RESALE', 'PROPERTY_PHONE_OVERSEAS'))->Fetch();

$arResult["CURRENT_CITY"] = $rsCurCity;