<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? if (!empty($arResult)): ?>
<?//echo "<pre hidden>"; echo $APPLICATION->GetCurDir(); echo "</pre>";?>
    <? $APPLICATION->ShowProperty("menu-inner") ?>
    <ul class="list" data-menu-main="<? $APPLICATION->ShowProperty("inner-menu")?>">
    <?
    $i = 0;
    $n = 0;
    $previousLevel = 0;
    foreach ($arResult as $arItem):?>

        <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
            <?= str_repeat("</ul></div></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));
            $i++;
            $n++; ?>
        <? endif ?>

        <? if ($arItem["IS_PARENT"]): ?>

            <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                <li class="list-item <? if ($arItem["SELECTED"] || (stripos($APPLICATION->GetCurDir() ,'/pressa/')) && $arItem['TEXT'] == 'О нас'): ?>list-item--active<? endif ?>" data-navigation-subs>
                <a href="<?= $arItem["LINK"]; ?>" <?=$arItem["PARAMS"]["target"];?> class="list-link">
                    <?= $arItem["TEXT"] ?>
                </a>
                <div class="navigation-subs">
                    <ul class="list">
            <? else: ?>
                <li class="list-item <? if ($arItem["SELECTED"]): ?>list--item-active<? endif ?>">
                    <a href="<?= $arItem["LINK"] ?>" <?=$arItem["PARAMS"]["target"];?> class="list-link">
                        <span><?= $arItem["TEXT"] ?></span>
                    </a>
            <? endif ?>

        <? else: ?>
            <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                <? $i++; ?>
                <? $n++; ?>
                <? if ($arItem["SELECTED"] && $arItem["LINK"] === $APPLICATION->GetCurPage()): ?>
                    <li class="list-item list-item--active">
                        <span class="list-link"><?= $arItem["TEXT"] ?></span>
                    </li>
                <? else: ?>
                    <li class="list-item">
                        <a href="<?= $arItem["LINK"]; ?>" <?=$arItem["PARAMS"]["target"];?> class="list-link">
                            <span><?= $arItem["TEXT"] ?></span>
                        </a>
                    </li>
                <? endif; ?>
            <? else: ?>
                <? if ($arItem["SELECTED"]): ?>
                    <li class="list-item list-item--active <?if($arItem["PARAMS"]["HIDDEN_MOB"] == 1):?>hidden-mobile<?endif;?>">
                        <span class="list-link"><?= $arItem["TEXT"] ?></span>
                    </li>
                <? else: ?>
                    <li class="list-item <?if($arItem["PARAMS"]["HIDDEN_MOB"] == 1):?>hidden-mobile<?endif;?>">
                        <a href="<?= $arItem["LINK"]; ?>" <?=$arItem["PARAMS"]["target"];?> class="list-link">
                            <span><?= $arItem["TEXT"] ?></span>
                        </a>
                    </li>
                <? endif; ?>
            <? endif ?>
        <? endif ?>

        <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>
    <? endforeach ?>

    <? if ($previousLevel > 1)://close last item tags?>
        <?= str_repeat("</ul></div></li>", ($previousLevel - 1)); ?>
    <? endif ?>

    </ul>
    <? $APPLICATION->ShowProperty("data-inner-menu") ?>
<? endif ?>