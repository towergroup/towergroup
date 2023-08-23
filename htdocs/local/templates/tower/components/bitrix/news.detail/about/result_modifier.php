<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult["PROPERTIES"]["BACKGROUND"]["FILE_VALUE"] = CFile::GetFileArray($arResult["PROPERTIES"]["BACKGROUND"]["VALUE"]);;