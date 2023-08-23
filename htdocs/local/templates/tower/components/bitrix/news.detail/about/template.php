<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<section class="section-about-hero">
    <div class="about-hero" style="background-image: url('<?= $arResult['PROPERTIES']['BACKGROUND']['FILE_VALUE']['SRC']; ?>');">
        <div class="container">
            <ul class="list list--crumbs">
                <li class="list-item">
                    <svg class="icon icon--circle" width="3" height="3" viewBox="0 0 3 3">
                        <use xlink:href="#circle"></use></svg>
                    <a class="list-link" href="<?= (SITE_ID != "s2") ? "/" : "/spb/"?>" title="Главная">Главная</a>
                </li>
                <li class="list-item">
                    <span class="list-link">О компании</span>
                </li>
            </ul>
            <?if (!empty($arResult['PROPERTIES']['VIDEO']['VALUE'])):?>
                <a class="about-hero-video" href="<?= $arResult['PROPERTIES']['VIDEO']['VALUE']; ?>" data-modal-video rel="noopener noreferrer">
                    <div class="about-hero-video-icon"><svg class="icon icon--play" width="14" height="18" viewbox="0 0 14 18">
                            <use xlink:href="#play" />
                        </svg></div><span><?= $arResult['PROPERTIES']['VIDEO']['~DESCRIPTION']; ?></span>
                </a>
            <?endif;?>
        </div>
    </div>
</section>
<section class="section-about">
    <div class="container">
        <div class="about" data-scroll-fx>
            <div class="about-title">
                <?= $arResult['PROPERTIES']['TITLE']['~VALUE']['TEXT']; ?>
            </div>
            <div class="about-text">
                <?= $arResult['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']; ?>
            </div>
        </div>
    </div>
</section>