<?php
use \Bitrix\Main\Data\Cache;
use \Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Page\Asset;

global $APPLICATION;

$cache = Cache::createInstance();
$taggedCache = Application::getInstance()->getTaggedCache();
$cachePath = SITE_ID . '/custom/';
$cacheTtl = 60*60*24*7;
$cacheKey = 'calculator';
$data = [];
if ($cache->initCache($cacheTtl, $cacheKey, $cachePath)) {
	$data = $cache->getVars();

} elseif ($cache->startDataCache()) {
	$taggedCache->startTagCache($cachePath);

    Loader::includeModule('iblock');
    Loader::includeModule('highloadblock');

    $banks = [];
    $tableBanks = HighloadBlockTable::getRow(['filter' => ['TABLE_NAME' => 'banks']]);
    if ($tableBanks) {
        $hl = HighloadBlockTable::compileEntity($tableBanks['ID'])->getDataClass();
        $banksList = $hl::getList()->fetchAll();
        foreach ($banksList as $bank) {
            $banks[$bank['UF_XML_ID']] = [
                'NAME' => $bank['UF_NAME'],
                'LOGO' => $bank['UF_LOGO'] ? CFile::GetFileSRC(CFile::GetFileArray($bank['UF_LOGO'])) : ''
            ];
        }
    }

	$rsElements = CIBlockElement::GetList(['SORT' => 'ASC', 'PROPERTY_PERCENT' => 'ASC'], ['IBLOCK_ID' => 33, 'ACTIVE' => 'Y'], false, false, ['ID', 'NAME', 'IBLOCK_ID']);

	while($ob = $rsElements->GetNextElement()) {
	    $item = $ob->GetFields();
	    $properties = $ob->GetProperties();
		$taggedCache->registerTag("iblock_id_" . $item["IBLOCK_ID"]);

		if (empty($banks[$properties['BANK']['VALUE']]) || !isset($banks[$properties['BANK']['VALUE']])) {
		    continue;
        }
		$item['BANK'] = $banks[$properties['BANK']['VALUE']];
		unset($properties['BANK']);

        foreach ($properties as $property) {
            $item[$property['CODE']] = $property['VALUE'];
		}

		$data[] = $item;
	}

	$taggedCache->registerTag("iblock_id_new");
	$taggedCache->endTagCache();
	$cache->endDataCache($data);
}

if (empty($data)) {
    return;
}

Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/calculator.css');
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/calculator.js');

$minSum = min(array_column($data, 'MIN_SUM'));
$minSum = ceil($minSum/50000)*50000;
$maxSum = max(array_column($data, 'MAX_SUM'));
$currentSum = 3000000 >= $minSum && 3000000 <= $maxSum ? 3000000 : $minSum;
$inputSumParams = [
    'min' => $minSum,
    'max' => $maxSum,
    'value' => $currentSum,
    'step' => 50000,
];
$minInitPayment = min(array_column($data, 'MIN_INITIAL_FEE')) * $currentSum / 100;
$inputInitPayParams = [
    'min' => $minInitPayment,
    'max' =>  $currentSum * 0.9,
    'value' => $currentSum * 0.5,
    'step' => 1000,
];
$minYear = min(array_column($data, 'MIN_YEAR'));
$maxYear = max(array_column($data, 'MAX_YEAR'));
$inputYearParams = [
    'min' => $minYear,
    'max' => $maxYear,
    'value' => 10 > $minYear && 10 < $maxYear ? 10 : $minYear,
    'step' => 1,
];

use Bitrix\Main\Grid\Declension;
$yearDeclension = new Declension('год', 'года', 'лет');
?>
<script type="text/javascript">
    let calculatorData = <?= json_encode($data, JSON_NUMERIC_CHECK) ?>,
        calculatorMinInitFeePercent = <?= min(array_column($data, 'MIN_INITIAL_FEE')) ?>;
</script>

<section class="section-calculator">
    <div class="container">
        <div class="h1">Ипотечный калькулятор</div>

        <form class="form js-calculator-form">
            <div class="form-params">
                <div class="field">
                    <span>Стоимость жилья</span>
                    <span data-value><?= $inputSumParams['value'] ?></span>
                    <input type="range" name="sum" min="<?= $inputSumParams['min'] ?>" max="<?= $inputSumParams['max'] ?>" value="<?= $inputSumParams['value'] ?>" step="<?= $inputSumParams['step'] ?>" style="">
                    <span class="range-values"><?= $inputSumParams['min']/1000 ?> тыс. ₽</span>
                    <span class="range-values"><?= $inputSumParams['max']/1000000 ?> млн. ₽</span>
                </div>
                <div class="field">
                    <span>Взнос</span>
                    <span data-value><?= $inputInitPayParams['value'] ?></span>
                    <input type="range" name="init_fee" min="<?= $inputInitPayParams['min'] ?>" max="<?= $inputInitPayParams['max'] ?>" value="<?= $inputInitPayParams['value'] ?>" step="<?= $inputInitPayParams['step'] ?>" style="">
                    <span class="range-values" data-init-fee-min><?= $inputInitPayParams['min']/1000 ?> тыс. ₽</span>
                    <span class="range-values" data-init-fee-max><?= $inputInitPayParams['max']/1000000 ?> млн. ₽</span>
                </div>
                <div class="field">
                    <span>Срок кредита</span>
                    <span data-value><?= $inputYearParams['value'] ?></span>
                    <input type="range" name="year" min="<?= $inputYearParams['min'] ?>" max="<?= $inputYearParams['max'] ?>" value="<?= $inputYearParams['value'] ?>" step="1" style="">
                    <span class="range-values"><?= $inputYearParams['min'] ?> <?= $yearDeclension->get($inputYearParams['min']) ?></span>
                    <span class="range-values"><?= $inputYearParams['max'] ?> <?= $yearDeclension->get($inputYearParams['max']) ?></span>
                </div>
            </div>

            <div class="form-result js-calculator-result"></div>
        </form>
        <div class="form-notice">результаты расчета не являются офертой</div>
    </div>
</section>