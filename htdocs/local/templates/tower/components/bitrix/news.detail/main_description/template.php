<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div class="hero-about-text">
    <?= $arResult['~PREVIEW_TEXT']; ?>
    <a class="text-control text-control--long-arrow" href="<?= $arResult['PROPERTIES']['LINK']['VALUE']; ?>">
        <span><?= $arResult['PROPERTIES']['LINK']['DESCRIPTION']; ?></span><svg class="icon icon--arrow-long" width="30" height="12" viewbox="0 0 30 12">
            <use xlink:href="#arrow-long" />
        </svg>
    </a>
</div>