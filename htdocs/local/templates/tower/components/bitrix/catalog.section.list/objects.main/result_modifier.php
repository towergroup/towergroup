<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$cityCode = $arParams['CITY_CODE'];
?>
<?if (!empty($arResult['SECTIONS'])) {
    foreach ($arResult['SECTIONS'] as $keySection => $arSection) {
        if ($arSection["ELEMENT_CNT"] == 0) continue;

        $arResult["SECTIONS"][$keySection]["SECTION_PAGE_URL"] = "/".$cityCode."/novostroyki/".$arSection['CODE']."/";
        //xprint($arSection);
        $rsAreaObject = CIBlockSection::GetList(
            false,
            array(
                "IBLOCK_ID" => REGION_IBLOCK_ID,
                "ACTIVE" => "Y",
                "ID" => $arSection["UF_AREA"],
                "DEPTH_LEVEL" => 2
            ),
            array("CNT_ACTIVE" => "Y"),
            array(
                "ID",
                "IBLOCK_ID",
                "CODE",
                "NAME",
                "DESCRIPTION",
                "PICTURE",
                "UF_*"
            ),
            false
        )->Fetch();
        $arResult["SECTIONS"][$keySection]["UF_AREA"] = $rsAreaObject['NAME'];
    }
}
?>