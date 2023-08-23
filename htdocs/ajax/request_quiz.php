<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$metricsID = getIDMetrics(SITE_ID);

$phoneCheck = str_replace(array("(", ")", " ","+", "-"),"",$_REQUEST['phone']);

if (!empty($_REQUEST) && is_numeric($phoneCheck)) {
    CModule::IncludeModule("form");
    $site = $_REQUEST['site_id'];
    $city = $site == "s2" ? "СПБ" : "МСК" ;
    $url  = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    $url .= parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

    $arUtm = [];
    if (!empty($_COOKIE['utm'])) {
        foreach (unserialize($_COOKIE['utm']) as $keyUtm => $valueUtm) {
            $arUtm[$keyUtm] = $valueUtm;
        }
    }

    $roistat = $_COOKIE['roistat_visit'];
    if (!empty($_REQUEST["quizAnswer"])) {
        $quiz = json_decode($_REQUEST['quizAnswer'],true);
        $FORM_ID = 8;
        $FORM_EVENT = 'QUIZ';
        $PROP = [];
        $PROP['form_text_26'] = $_REQUEST['name'];
        $PROP['form_text_27'] = $_REQUEST['phone'];
        $PROP['form_checkbox_POLITICS[]'] = array(
            0 => 28,
        );

        $PROP['web_form_apply'] = 'Y';

        $FIELD_PROP['QUIZ_ANSWERS'] = '';

        foreach ($quiz as $arQuiz) {
            $FIELD_PROP['QUIZ_ANSWERS'] .= $arQuiz['question']." : ".$arQuiz['answer'].'<br><br>';
            $CRM_PROP['QUIZ_ANSWERS'][$arQuiz['question']] = $arQuiz['answer'];
        }

        $message .= 'Имя: ' . $_REQUEST['name'] . '<br>';
        $message .= 'Телефон: ' . $_REQUEST['phone'] . '<br><br>';
        $message .= 'Квиз: ' .$FIELD_PROP['QUIZ_ANSWERS']. '<br><br>';

        /**
         * Отправка в amoCRM
         */
        $data = [
            'name' => $_REQUEST['name'],
            'phone' => $_REQUEST['phone'],
            'quiz' => $CRM_PROP['QUIZ_ANSWERS'],
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
    }

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