<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$metricsID = getIDMetrics(SITE_ID);

$phoneCheck = str_replace(array("(", ")", " ","+", "-"),"",$_REQUEST['phone']);

if (!empty($_REQUEST) && is_numeric($phoneCheck)) {
    CModule::IncludeModule("form");
    $site = $_REQUEST['site_id'];
    $city = $site == "s2" ? "СПБ" : "МСК";

    $url  = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    $url .= parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

    $arUtm = [];
    if (!empty($_COOKIE['utm'])) {
        foreach (unserialize($_COOKIE['utm']) as $keyUtm => $valueUtm) {
            $arUtm[$keyUtm] = $valueUtm;
        }
    }

    $roistat = $_COOKIE['roistat_visit'];

    if (!empty($_REQUEST["broker-name"]) || !empty($_REQUEST["viewing-broker-name"])) {
        if (!empty($_REQUEST["broker-name"])) {
            $FORM_ID = 7;
            $FORM_EVENT = 'BROKER';
            $FIELD_PROP['BROKER_NAME'] = $_REQUEST['broker-name'];
            //$city = SITE_ID == "s2" ? "СПБ" : "МСК" ;

            $PROP = [];
            $PROP['form_text_22'] = $_REQUEST['name'];
            $PROP['form_text_23'] = $_REQUEST['phone'];
            $PROP['form_text_25'] = $_REQUEST['time'];
            $PROP['form_checkbox_POLITICS[]'] = array(
                0 => 24,
            );

            $PROP['web_form_apply'] = 'Y';

            /**
             * Отправка в amoCRM
             */

            $data = [
                'name' => $_REQUEST['name'],
                'phone' => $_REQUEST['phone'],
                'brokerName' => $_REQUEST['broker-name'],
                'time' => $_REQUEST['time'],
                'city' => $city
            ];

            if (!empty($_REQUEST['type-of-property']))
                $data['typeOfProperty'] = $_REQUEST['type-of-property'];
        }
        elseif (!empty($_REQUEST["viewing-broker-name"])) {
            $FORM_ID = 10;
            $FORM_EVENT = 'VIEWING';
            $FIELD_PROP['BROKER_NAME'] = $_REQUEST['viewing-broker-name'];

            $PROP = [];
            $PROP['form_text_32'] = $_REQUEST['name'];
            $PROP['form_text_33'] = $_REQUEST['phone'];
            $PROP['form_checkbox_POLITICS[]'] = array(
                0 => 34,
            );

            $PROP['web_form_apply'] = 'Y';
        }

        if (!empty($_REQUEST["tipObject"])) {
            $tipParse = json_decode($_REQUEST['tipObject'], true);
            if ($tipParse['city'])
                $city = $tipParse['city'] == "spb" ? "СПБ" : "МСК";
            $FIELD_PROP['BROKER_OBJECT'] = $tipParse['type-of-property']." ".$tipParse["object-class"]." ".$tipParse["object"];

            /**
             * Отправка в amoCRM
             */

            $data = [
                'name' => $_REQUEST['name'],
                'phone' => $_REQUEST['phone'],
                'brokerName' => $_REQUEST['broker-name'] ? $_REQUEST['broker-name'] : $_REQUEST['viewing-broker-name'],
                'object' => $tipParse['object'],
                'class' => $tipParse['object-class'],
                'typeOfProperty' => $tipParse['type-of-property'],
                'city' => $city
            ];

        }

        $message .= 'Имя: ' . $_REQUEST['name'] . '<br>';
        $message .= 'Телефон: ' . $_REQUEST['phone'] . '<br><br>';
        $message .= 'Брокер: ' .$_REQUEST['broker-name']. '<br><br>';
        if (!empty($_REQUEST['time'])) {
            $message .= 'Время: ' .$_REQUEST['time']. '<br><br>';
        }
        if (!empty($_REQUEST["tipObject"])) $message .= 'Объект: '.$tipParse['type-of-property'].' ' .$tipParse['object-class'].' '.$tipParse['object']. '<br><br>';
    }


    $headers = array(
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json",
        "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
    );

    $data['formName'] = $_REQUEST['form_name'];
    if (!empty($arUtm)) {
        foreach ($arUtm as $keyUtm => $valueUtm) {
            $data[$keyUtm] = $valueUtm;
        }
    }
    $data['clientIP'] = $_SERVER['REMOTE_ADDR'];
    $data['roistat'] = $roistat;
    $data['urlPage'] = $url;
    
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
    if ($RESULT_ID = CFormResult::Add($FORM_ID, $PROP)) {
        foreach ($FIELD_PROP as $field => $value) {
            CFormResult::SetField($RESULT_ID, $field, $value);
        }
		$numbersAnalytic = array('number_ym'=> $metricsID['PROPERTY_YM_NUMBER_VALUE'], 'number_ga'=> $metricsID['PROPERTY_GA_NUMBER_VALUE']);
		echo json_encode($numbersAnalytic);
		//echo "Результат #".$RESULT_ID." успешно создан ";

        CEvent::Send('FORM_FILLING_'.$FORM_EVENT, $site, array('NUMBER' => $number, 'MESSAGE' => $message));
    } else {
        global $strError;
        echo $strError;
    }

} else echo 'Данные не заполнены'
?>