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
//xprint($arResult["VACANCIES"]);
?>

<?
if ($arResult["IS_AJAX"]) {
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
}
?>
    <div class="vacancy" id="comp_<?= $bxajaxid ?>">
        <?foreach ($arResult['VACANCIES'] as $arVacancy): ?>
            <div class="vacancy-item ajax-list-item" data-scroll-fx>
                <div class="container">
                    <div class="vacancy-heading">
                        <div class="div-title div-title-h2"><?=$arVacancy['NAME']?></div>
                        <div class="vacancy-salary"><?=$arVacancy['PROPERTY_SALARY_VALUE']?></div><svg class="icon icon--plus" width="24" height="24" viewbox="0 0 24 24">
                            <use xlink:href="#plus" />
                        </svg>
                    </div>
                    <div class="vacancy-body">
                        <div class="vacancy-content">
                            <p><?=$arVacancy['PREVIEW_TEXT']?></p>
                            <?if (!empty($arVacancy['PROPERTY_REQUIREMENTS_VALUE'])):?>
                                <h3>Требования:</h3>
                                <ul>
                                    <?foreach ($arVacancy['PROPERTY_REQUIREMENTS_VALUE'] as $keyRequirements => $arRequirements):?>
                                        <li><?=$arRequirements?><?=$keyRequirements == count($arVacancy['PROPERTY_REQUIREMENTS_VALUE'])-1 ? "." : ";"?></li>
                                    <?endforeach;?>
                                </ul>
                            <?endif;?>
                            <?if (!empty($arVacancy['PROPERTY_DUTIES_VALUE'])):?>
                                <h3>Обязанности:</h3>
                                <ul>
                                    <?foreach ($arVacancy['PROPERTY_DUTIES_VALUE'] as $keyDuties => $arDuti):?>
                                        <li><?=$arDuti?><?=$keyDuties == count($arVacancy['PROPERTY_DUTIES_VALUE'])-1 ? "." : ";"?></li>
                                    <?endforeach;?>
                                </ul>
                            <?endif;?>
                            <div class="vacancy-content-control"><a class="button button--white" href="#vacancy" data-modal>Откликнуться</a></div>
                        </div>
                    </div>
                </div>
            </div>
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
<div class="modal" id="vacancy">
    <div class="modal-container"><button class="modal-close" data-modal-close><svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                <use xlink:href="#cross-light-large" />
            </svg></button>
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/forms/form_vacancy.php",
                "EDIT_TEMPLATE" => "",
                "FORM_CODE" => "vacancy-form",
            ),
            false
        );?>
    </div>
</div>