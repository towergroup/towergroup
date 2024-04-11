<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<? if (!empty($arResult['SECTIONS'])): ?>
    <section class="section-slider" data-scroll-fx>
        <div class="container">
            <div class="slider slider--objects-new" data-slider-objects>

                <div class="slider-headnote">
                    <div class="h1 h3-title"><?= $arParams["TITLE_H1"]; ?></div>
                    <? if (count($arResult['SECTIONS']) > 1) : ?>
                        <div class="slider-controls">
                            <div class="swiper-button-prev">
                                <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                    <use xlink:href="#arrow"/>
                                </svg>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next">
                                <svg class="icon icon--arrow" width="16" height="12" viewbox="0 0 16 12">
                                    <use xlink:href="#arrow"/>
                                </svg>
                            </div>
                        </div>
                    <? endif; ?>
                </div>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <? foreach ($arResult['SECTIONS'] as $arSection): ?>
                            <? if ($arSection["ELEMENT_CNT"] == 0) {
                                continue;
                            } ?>
                            <?if ($arSection['PICTURE']['WIDTH'] > 640)
                                $arSection["PICTURE_RESIZE"] = CFile::ResizeImageGet($arSection['PICTURE']["ID"], array('width' => 640, 'height' => 480),
                                    BX_RESIZE_IMAGE_PROPORTIONAL, false)['src']
                            ?>
                            <div class="swiper-slide"><a class="object-new"
                                                         href="<?= $arSection['SECTION_PAGE_URL']; ?>">
 <meta itemprop="url" content="<?= $arSection["PICTURE_RESIZE"] ? $arSection["PICTURE_RESIZE"] : $arSection['PICTURE']['SRC']; ?>">

<div class="object-new-preview swiper-lazy"
                                         data-background="<?= $arSection["PICTURE_RESIZE"] ? $arSection["PICTURE_RESIZE"] : $arSection['PICTURE']['SRC']; ?>"></div>
                                    <div class="object-new-info">
                                        <div class="h3 h4-title">«<?= $arSection['NAME']; ?>»</div>
                                        <div class="object-new-info-hide">
                                            <div class="text-overflow"><?= $arSection['UF_AREA']; ?></div>
                                            <div class="text-overflow"><?= plural_form($arSection["ELEMENT_CNT"],
                                                    array("квартира", "квартиры", "квартир")); ?></div>
                                            <div class="text-overflow"><?= $arSection['UF_DEADLINE']; ?></div>
                                        </div>
                                    </div>
                                </a></div>
                        <? endforeach; ?>
                        <div class="swiper-slide"><a class="object-new-all"
                                                     href="/<?= $arParams["CITY_CODE"] ?>/novostroyki/">
                                <div class="h3 h4-title">Другие предложения</div>
                                <div>Мы подготовили для вас множество отличных вариантов недвижимости</div>
                                <span class="text-control text-control--long-arrow"><span>В каталог</span><svg
                                            class="icon icon--arrow-long" width="30" height="12" viewbox="0 0 30 12">
													<use xlink:href="#arrow-long"/>
												</svg></span>
                            </a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>