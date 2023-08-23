<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<? if (!empty($arResult)): ?>
    <div class="dropdown-values dropdown-values--modal dropdown-values--autosize">
    <div class="dropdown-modal">
        <div class="dropdown-modal-heading">
            <span>Тип недвижимости</span>
            <button data-dropdown-modal-close>
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large" />
                </svg>
            </button>
        </div>
        <div class="dropdown-modal-body">
            <div class="dropdown-values-list">
                <?
                $i = 0;
                $n = 0;
                $previousLevel = 0;
                foreach ($arResult as $key => $arItem):?>
                <?if($arItem["PARAMS"]["IS_PARENT"] == 1):?>
                <?if($i > 0):?>
                </ul>
            </div>
            <?endif;?>
            <div class="dropdown-values-item">
                <div class="dropdown-values-item-title"><?=$arItem["TEXT"];?></div>
                <ul class="list">
                    <?$i++;?>
                    <?elseif($arItem["PARAMS"]["IS_PARENT"] == 0):?>
                        <li class="list-item" data-dropdown-id="<?=$key;?>" data-link='<?=($arItem["PARAMS"]["CITY_CODE"]) ? '/'.$arItem["PARAMS"]["CITY_CODE"].$arItem["LINK"] : $arItem["LINK"];?>'><?=$arItem["TEXT"];?></li>
                    <?endif;?>
                    <?if($key == array_key_last($arResult)):?>
                </ul>
            </div>
        <?endif;?>
            <? endforeach ?>
        </div>
        </div>
		</div>
    </div>
<? endif ?>

<?/*
<? if (!empty($arResult)): ?>
    <ul class="list">
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
    <li class="list-item <? if ($arItem["SELECTED"]): ?>list-item--active<? endif ?>" data-navigation-subs>
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
        <? if ($arItem["SELECTED"]): ?>
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
<? endif ?>*/?>