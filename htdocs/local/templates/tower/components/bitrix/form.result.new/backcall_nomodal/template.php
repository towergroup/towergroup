<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, '');
?>
<? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>


<?
/***********************************************************************************
 * form header
 ***********************************************************************************/
?>

<?
/***********************************************************************************
 * form questions
 ***********************************************************************************/
?>


<?//= $arResult["FORM_HEADER"] ?>
<form name="<?=$arResult["WEB_FORM_NAME"]?>_NOMODAL" action="<?=POST_FORM_ACTION_URI?>" method="POST" enctype="multipart/form-data" class="form" novalidate="novalidate">
    <input type="hidden" name="WEB_FORM_ID" value="<?=$arParams["WEB_FORM_ID"]?>">
    <div class="form-row-border">
    <?=bitrix_sessid_post()?>
<?
if ($_REQUEST["formresult"] == "addok" && $_REQUEST["AJAX_CALL"] == "Y" && $_REQUEST["bxajaxid"] == $bxajaxid && $arResult["isFormErrors"] != "Y") {
    $APPLICATION->RestartBuffer();
    echo
    "<script>
        window.dispatchEvent(
            new CustomEvent('successForm', {
                bubbles: false,
                detail: {
                    'closePopup': 'backcall',
                }
            })
        );
    </script>";
}
?>
<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
    <? if ($arQuestion['CAPTION'] == 'Телефон') {
        $arQuestion['HTML_CODE'] = str_replace('type="text"', 'type="tel"', $arQuestion['HTML_CODE']);
    }?>

    <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
        echo $arQuestion["HTML_CODE"];
    elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'checkbox'): ?>
        <div class="form-control">
            <button type="submit" name="web_form_submit" disabled="disabled" id="button-form-callback-1"
                                          value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"
                                          class="button button--default"><?= $arResult["arForm"]["BUTTON"]; ?>
            </button>
        </div>
        </div>
        <div class="form-item form-item--checkbox">
            <label class="checkbox">
                <input type="checkbox" id="3" name="form_checkbox_POLITICS[]" value="3" checked>
                <span>Согласен на обработку <a href="/privacy/" target="_blank">персональных данных</a></span>
            </label>
        </div>
    <? else: ?>
        <div class="form-item">
            <div class="field">
                <?/*<label class="field-label"><?= $arQuestion["CAPTION"] ?></label>*/?>
                <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'): ?>
                        <?= $arQuestion["HTML_CODE"] ?>
                <?else:?>
                        <?= $arQuestion["HTML_CODE"] ?>
                <?endif;?>
            </div>
        </div>
    <? endif; ?>

<? endforeach; ?>
<?= $arResult["FORM_FOOTER"] ?>
