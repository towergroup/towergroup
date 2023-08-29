<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="awards-wrapper" data-scroll-fx>
    <input type="checkbox" id="load-more"/>
    <?foreach ($arResult["ITEMS"] as $key => $arItem):?>
        <div class="awards-wrapper-year">
            <div class="awards-wrapper-year-title">
                - <?=$arItem["NAME"];?> -
            </div>
            <div class="award-items">
                <?foreach ($arItem["DISPLAY_PROPERTIES"]["AWARDS"]["FILE_VALUE"] as $key => $arAward):?>
                    <div class="award-item">
                        <img src="<?=$arAward["SRC"];?>">
                    </div>
                <?endforeach;?>
            </div>
        </div>
    <?endforeach;?>
</div>

<div class="pagination-more" data-scroll-fx="data-scroll-fx">
    <button class="button button--inverse button--refresh load-more-btn pagination-awards">
        <svg class="icon icon--refresh" width="18" height="22" viewbox="0 0 18 22">
            <use xlink:href="#refresh" />
        </svg>
        <span>Показать еще</span>
    </button>
</div>
