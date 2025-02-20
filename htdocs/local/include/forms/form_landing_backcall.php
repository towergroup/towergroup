<?
$arInfo = getFormInfo($arParams["FORM_CODE"]);
if (SITE_ID == "s1") {
    $urlPrivacy = "/moskva/privacy/";
} elseif(SITE_ID == "s2") {
    $urlPrivacy = "/spb/privacy/";
} else {
    $urlPrivacy = "/privacy/";
}
?>
<div class="modal modal--broker" id="landing-backcall">
    <div class="modal-container"><button class="modal-close" data-modal-close><svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                <use xlink:href="#cross-light-large" />
            </svg></button>
        <div class="modal-broker">
            <div class="modal-broker-form">
                <h3 class="h2"><?= htmlspecialcharsBack($arInfo["TITLE"]); ?></h3>
                <div class="modal-footnote"><?= htmlspecialcharsBack($arInfo["FOOTNOTE"]); ?></div>
                <form class="form" id="landing-broker-form">
                    <input type="hidden" id="broker-name-landing" name="broker-name" value="<?= $GLOBALS['default_broker']['NAME']?>">
                    <input type="hidden" name="site_id" value="<?=SITE_ID;?>">
                    <input type="hidden" name="form_name" value="<?= htmlspecialcharsBack($arInfo["NAME"]); ?>">
                    <div class="form-item">
                        <div class="field"><input class="field-input" type="text" name="name" value placeholder="Имя"></div>
                    </div>
                    <div class="form-item">
                        <div class="field"><input class="field-input" type="tel" name="phone" value placeholder="Телефон"></div>
                    </div>
                    <div class="form-control"><button class="button button--default">Отправить</button></div>
                    <div class="form-item form-item--checkbox form-item--validate form-item--success"><label class="checkbox"><input type="checkbox" checked name="privacy"><span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span></label></div>
                </form>
            </div>
            <div class="modal-broker-image" <?= ($GLOBALS['default_broker']['PICTURE']) ? 'style="background-image: url('.$GLOBALS['default_broker']['PICTURE'].')"':null; ?>>
                <div class="modal-broker-info">
                    <div class="h3 modal-broker-info-name"><?= $GLOBALS['default_broker']['NAME']; ?></div>
                    <div class="modal-broker-info-position"><?= $GLOBALS['default_broker']['DEPARTMENT']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>