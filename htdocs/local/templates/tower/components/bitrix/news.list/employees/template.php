<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="person" data-scroll-fx>
    <?foreach ($arResult["ITEMS"] as $key => $arItem):?>
        <div class="person-item" data-person-show="true">
            <div class="person-item-holder lazy" data-bg="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" data-modal-image="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" data-person>
                <div class="person-item-info">
                    <div class="div-title div-title-h4"><?=$arItem["NAME"];?></div>
                    <div class="person-item-info-hide">
                        <div class="person-item-info-data">
                            <div class="person-item-position"><?=$arItem["PROPERTIES"]["DEPARTMENT"]["VALUE"];?></div>
                            <button class="button button--light" data-show-contacts="<?=$arItem["ID"]?>">Показать контакты</button>
                            <?/*if (!empty($arItem["PROPERTIES"]["PHONE"]["VALUE"]) || !empty($arItem["PROPERTIES"]["EMAIL"]["VALUE"])):?>
                                <ul class="list">
                                    <?if (!empty($arItem["PROPERTIES"]["PHONE"]["VALUE"])):?>
                                        <li class="list-item"><a class="list-link" href="tel:<?=str_replace(array("(",")"," ","-"),"",$arItem["PROPERTIES"]["PHONE"]["VALUE"]);?>"><?=$arItem["PROPERTIES"]["PHONE"]["VALUE"];?></a></li>
                                    <?endif;?>
                                    <?if (!empty($arItem["PROPERTIES"]["EMAIL"]["VALUE"])):?>
                                        <li class="list-item"><a class="list-link" href="mailto:<?=$arItem["PROPERTIES"]["EMAIL"]["VALUE"];?>"><?=$arItem["PROPERTIES"]["EMAIL"]["VALUE"];?></a></li>
                                    <?endif;?>
                                </ul>
                            <?endif;*/?>
                            <a class="button button--white" href="#broker" data-modal>Связаться</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?
        $arBrokerInfo[$arItem["ID"]] = [
            'tel' => $arItem["PROPERTIES"]["PHONE"]["VALUE"] ? str_replace(array("(",")"," ","-"),"",$arItem["PROPERTIES"]["PHONE"]["VALUE"]) : str_replace(array("(",")"," ","-"),"",COMPANY_PHONE),
            'phone' => $arItem["PROPERTIES"]["PHONE"]["VALUE"] ? $arItem["PROPERTIES"]["PHONE"]["VALUE"] : COMPANY_PHONE,
            'email' => $arItem["PROPERTIES"]["EMAIL"]["VALUE"],
        ];
        ?>
    <?endforeach;?>
    <script>
        let brokers = <?=json_encode($arBrokerInfo, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);?>;
    </script>
</div>
<div class="pagination-more" data-scroll-fx="data-scroll-fx"><button class="button button--inverse button--refresh"><svg class="icon icon--refresh" width="18" height="22" viewbox="0 0 18 22">
            <use xlink:href="#refresh" />
        </svg><span>Показать еще</span></button></div>