<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name,
$component->arParams['AJAX_OPTION_ADDITIONAL']);
//$sortDefault = $arResult['SORT_DEFAULT'];
if ($_GET['reviews_type'] == 'video') {

    $APPLICATION->SetPageProperty("page", 'reviews_video');
    $this->addExternalCss(SITE_TEMPLATE_PATH . "/css/reviews_video.min.css");
}
else {
    $APPLICATION->SetPageProperty("page", 'reviews');
    $this->addExternalCss(SITE_TEMPLATE_PATH . "/css/reviews.min.css");
}
//xprint($arResult["REVIEWS"]);
//xprint($arResult["SORT"]);
?>
<div class="reviews" data-scroll-fx="data-scroll-fx">
    <div class="reviews-control">
        <ul class="list">
            <?if ($_GET['reviews_type'] == 'text' || !isset($_GET['reviews_type'])) :?>
                <li class="list-item list-item--active"><span class="list-link">Текстовые</span></li>
                <li class="list-item"><a class="list-link" href="?reviews_type=video">Видео</a></li>
            <?else:?>
                <li class="list-item"><a class="list-link" href="?reviews_type=text">Текстовые</a></li>
                <li class="list-item list-item--active"><span class="list-link">Видео</span></li>
            <?endif;?>
        </ul>
        <div class="reviews-control-sort">
            <div class="reviews-control-sort-item">
                <div class="dropdown dropdown--radio dropdown--no-border" data-dropdown="radio"><input type="hidden" value="<?=($_GET['broker_id'])? $_GET['broker_id']: null?>" data-dropdown-value>
                    <div class="field-input" data-dropdown-label="Выбор брокера">
                        <?if ($_GET['broker_id'] && array_key_exists($_GET['broker_id'], $arResult['BROKERS'])) {
                            echo $arResult['BROKERS'][$_GET['broker_id']]['NAME'];
                        }
                        else {
                            echo "Выбор брокера";
                        }
                        ?>
                    </div>
                    <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                            <use xlink:href="#dropdown" />
                        </svg></div>
                    <div class="dropdown-values">
                        <div class="dropdown-values-scroll" data-simplebar>
                            <ul class="list">
                                <li class="list-item<?=(!$_GET['broker_id'])? " list-item--active": null?>" data-dropdown-id>Все</li>
                                <?foreach ($arResult['BROKERS'] as $arBroker): ?>
                                    <li class="list-item<?=($_GET['broker_id'] == $arBroker['ID'])? " list-item--active": null?>" data-dropdown-id="<?=$arBroker['ID']?>"><?=$arBroker['NAME']?></li>
                                <?endforeach;?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="reviews-control-sort-item">
                <div class="dropdown dropdown--radio dropdown--no-border" data-dropdown="radio"><input type="hidden" value data-dropdown-value>
                    <div class="field-input" data-dropdown-label="Сортировка по дате">
                        <?if ($sortDefault) {
                            echo "Сортировка по дате";
                        }
                        else {
                            switch ($arResult['SORT']['ACTIVE_FROM']) {
                                case "DESC" :
                                    echo "Сначала новые";
                                    break;
                                case "ASC" :
                                    echo "Сначала старые";
                                    break;
                            }
                        }
                        ?>
                    </div>
                    <div class="dropdown-icon"><svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                            <use xlink:href="#dropdown" />
                        </svg></div>
                    <div class="dropdown-values">
                        <div class="dropdown-values-scroll" data-simplebar>
                            <ul class="list">
                                <?/*<li class="list-item <?=$sortDefault ? "list-item--active" : null;?>" data-dropdown-id>по умолчанию</li>*/?>
                                <li class="list-item <?=(!$sortDefault && $arResult['SORT']['ACTIVE_FROM'] === 'DESC') ? "list-item--active" : null;?>" data-dropdown-id="1">Сначала новые</li>
                                <li class="list-item <?=($arResult['SORT']['ACTIVE_FROM'] === 'ASC') ? "list-item--active" : null;?>" data-dropdown-id="2">Сначала старые</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="reviews-control-button"><a class="button button--light" href="#review" data-modal>Отправить отзыв</a></div>
    </div>
    <?
    if ($arResult["IS_AJAX"]) {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
    }
    ?>
	<div class="reviews-list <?if(count($arResult['REVIEWS']) == 0):?>reviews-list-min-height<?endif;?>" data-scroll-fx id="comp_<?= $bxajaxid ?>">
        <?foreach ($arResult['REVIEWS'] as $arReview): ?>
            <?if ($_GET['reviews_type'] == 'text' || !isset($_GET['reviews_type'])) :?>
                <div class="reviews-item ajax-list-item">
                    <a class="reviews-link" href="#review-item" data-modal>
                        <div class="reviews-item-content">
                            <div class="reviews-item-title"><?= $arReview['NAME'] ?></div>
                            <div class="reviews-item-footnote">Брокер: <?= $arResult['BROKERS'][$arReview['PROPERTY_BROKER_VALUE']]['NAME'] ?></div>
                            <ul class="list list--stars">
                                <? $i = 1;
                                while ($i <= 5) :?>
                                    <li class="list-item<?=($i<=$arReview['PROPERTY_STARS_VALUE']) ? " list-item--active" : null?>">
                                        <svg class="icon icon--star" width="20" height="20" viewbox="0 0 20 20">
                                            <use xlink:href="#star" />
                                        </svg>
                                    </li>
                                   <? $i++;?>
                                <?endwhile;?>
                            </ul>
                            <div class="reviews-item-text">
                                <p>
                                    <?= str_replace(array("\r\n", "\r", "\n"), '</p><p>', $arReview['DETAIL_TEXT']); ?>
                                </p>
                            </div>
                            <span class="text-control text-control--long-arrow">
                                <span>Читать полностью</span>
                                <svg class="icon icon--arrow-long" width="30" height="12" viewbox="0 0 30 12">
                                    <use xlink:href="#arrow-long" />
                                </svg>
                            </span>
                        </div>
                    </a>
                </div>
            <?else :?>
                <div class="reviews-item ajax-list-item">
                    <a class="reviews-link reviews-link--video" href="https://www.youtube.com/watch?v=ULmi8IhzCXY" data-modal-video>
                        <div class="reviews-item-preview lazy" data-bg="<?= SITE_TEMPLATE_PATH; ?>/img/reviews/video-review.jpg"></div>
                        <div class="reviews-item-content">
                            <div class="reviews-item-title"><?= $arReview['NAME'] ?>
                                <div class="reviews-item-footnote">Брокер: <?= $arResult['BROKERS'][$arReview['PROPERTY_BROKER_VALUE']]['NAME'] ?></div>
                                <ul class="list list--stars">
                                    <? $i = 1;
                                    while ($i <= 5) :?>
                                        <li class="list-item<?=($i<=$arReview['PROPERTY_STARS_VALUE']) ? " list-item--active" : null?>">
                                            <svg class="icon icon--star" width="20" height="20" viewbox="0 0 20 20">
                                                <use xlink:href="#star" />
                                            </svg>
                                        </li>
                                        <? $i++;?>
                                    <?endwhile;?>
                                </ul>
                            </div>
                        </div>
                    </a></div>
            <?endif;?>
        <?endforeach;?>
    </div>
    <? if ($arResult["NAV_RESULT"]->NavPageCount > 1 && $arResult["NAV_RESULT"]->NavPageNomer < $arResult["NAV_RESULT"]->NavPageCount): ?>
        <div class="pagination-ajax">
            <div class="pagination-more" id="btn_<?= $bxajaxid ?>" data-scroll-fx="data-scroll-fx">
                <button class="button button--inverse button--refresh"
                        data-ajax-id="<?= $bxajaxid ?>"
                        data-show-more="<?= $arResult["NAV_RESULT"]->NavNum ?>"
                        data-next-page="<?= ($arResult["NAV_RESULT"]->NavPageNomer + 1) ?>"
                        data-max-page="<?= $arResult["NAV_RESULT"]->NavPageCount ?>">
                    <svg class="icon icon--refresh" width="18" height="22" viewbox="0 0 18 22">
                        <use xlink:href="#refresh" />
                    </svg>
                    <span>Показать еще</span>
                </button>
            </div>
        </div>
    <? endif ?>
    <?
    if ($arResult["IS_AJAX"])
    die();
    ?>
</div>