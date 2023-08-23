<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$metricsID = getIDMetrics(SITE_ID);

$phoneCheck = str_replace(array("(", ")", " ","+", "-"),"",$_REQUEST['phone']);

if (!empty($_REQUEST) && is_numeric($phoneCheck)) {
    CModule::IncludeModule("form");

    $FORM_ID = 12;
    $FORM_EVENT = 'MORTGAGE_FORM';
    $PROP = [];
    $PROP['form_text_39'] = $_REQUEST['name'];
    $PROP['form_text_40'] = $_REQUEST['phone'];

    $message .=  'Имя: '.$_REQUEST['name']. '<br>';
    $message .=  'Телефон: '.$_REQUEST['phone'].'<br><br>';

    $site = $_REQUEST['site_id'];

    $url  = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    $url .= parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

    $arUtm = [];
    if (!empty($_COOKIE['utm'])) {
        foreach (unserialize($_COOKIE['utm']) as $keyUtm => $valueUtm) {
            $arUtm[$keyUtm] = $valueUtm;
        }
    }

    $roistat = $_COOKIE['roistat_visit'];

    if (!empty($_REQUEST["tipObject"])) {
        $tipParse = json_decode($_REQUEST['tipObject'], true);
        if ($tipParse['city'])
            $city = $tipParse['city'] == "spb" ? "СПБ" : "МСК";
        $FIELD_PROP['SIMPLE_QUESTION_OBJECT_NAME'] = $tipParse['object'];
    }

	$data = [
		'name' => $_REQUEST['name'],
		'phone' => $_REQUEST['phone'],
        'object' => $tipParse['object'],
        'class' => $tipParse['object-class'],
        'typeOfProperty' => $tipParse['type-of-property'],
        'city' => $city
	];

    $data['formName'] = $_REQUEST['form_name'];
    if (!empty($arUtm)) {
        foreach ($arUtm as $keyUtm => $valueUtm) {
            $data[$keyUtm] = $valueUtm;
        }
    }
    $data['clientIP'] = $_SERVER['REMOTE_ADDR'];
    $data['roistat'] = $roistat;
    $data['urlPage'] = $url;

    $headers = array(
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
    );

	$ch  = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_URL => AMO_CRM_URL,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POSTFIELDS => json_encode($data)
	));
	$response = curl_exec($ch);
	curl_close($ch);

    //создадим новый результат
    if ($RESULT_ID = CFormResult::Add($FORM_ID, $PROP))
    {
        foreach ($FIELD_PROP as $field => $value) {
            CFormResult::SetField($RESULT_ID, $field, $value);
        }

		$numbersAnalytic = array('number_ym'=> $metricsID['PROPERTY_YM_NUMBER_VALUE'], 'number_ga'=> $metricsID['PROPERTY_GA_NUMBER_VALUE']);
		echo json_encode($numbersAnalytic);
		//echo "Результат #".$RESULT_ID." успешно создан ";

        CEvent::Send('FORM_FILLING_'.$FORM_EVENT, $site, array('MESSAGE' => $message));
    }
    else
    {
        global $strError;
        echo $strError;
    }
}
else echo 'Данные не заполнены'
?>