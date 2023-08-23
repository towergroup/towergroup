<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
//$phoneReplaceClass = $APPLICATION->GetPageProperty("PHONE_REPLACE_CLASS");
$phoneReplacePhone = $APPLICATION->GetPageProperty("PHONE_REPLACE_VALUE");
//$phoneReplacePhone = COMPANY_PHONE;
?>

<div class="contact-us">
    <a class="contact-us-icon <?= $phoneReplaceClass ?>" href="tel:<?= str_replace(array("(", ")", " ", "-"), "", $phoneReplacePhone); ?>">
        <div style="background-image: url('<?= $arResult['ITEMS'][0]['DISPLAY_PROPERTIES']['ICON']['FILE_VALUE']['SRC'] ?>');">
            <span>
                <svg class="icon icon--phone" width="17" height="17" viewBox="0 0 17 17">
                    <use xlink:href="#phone"></use>
                </svg>
            </span>
        </div>
    </a>
</div>