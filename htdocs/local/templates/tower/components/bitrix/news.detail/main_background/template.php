<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

?>
<div class="hero-bg">
    <div class="hero-bg-overlay"></div>
<div itemscope itemtype="http://schema.org/ImageObject">
  

  
  <meta itemprop="url" content="<?=$arResult['PREVIEW_PICTURE']['SRC'] ?>">
  <meta itemprop="description" content="<?=$arResult['PREVIEW_PICTURE']['DESCRIPTION'] ?>">

    <div class="hero-bg-image lazy" data-bg="<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>"></div>
</div>
</div>