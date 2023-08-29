<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<svg xmlns="http://www.w3.org/2000/svg" style="width: 0; height: 0; overflow: hidden; position: absolute; visibility: hidden;">
    <? foreach ($arResult['ITEMS'] as $keyCode => $arSvg) : ?>
        <symbol id="<?=$arSvg['CODE']?>" viewbox="<?=$arSvg['PROPERTIES']['VIEWBOX']['VALUE']?>">
            <?=$arSvg['PROPERTIES']['PATH']['~VALUE']['TEXT']?>
        </symbol>
    <? endforeach; ?>
</svg>