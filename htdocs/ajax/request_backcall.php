<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$arParams = array(
    'secret' => '6LfiazYfAAAAAFQP8PNFQEKHbBsXW6sBqMARr_fI',
    'response' => $_REQUEST['recaptcha_response'],
    'remoteip' => $_SERVER['HTTP_X_REAL_IP']
);

$ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_POSTFIELDS, $arParams);
$body = curl_exec($ch);

curl_close($ch);
$result = json_decode($body, true);

if ($result['success'] !== true && $result['score'] < 0.5) {
    echo json_encode(array('result' => 'captcha', 'data' => $result));
    die();
} else {
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

        if (!empty($_REQUEST["message"]) || !empty($_REQUEST["email"])) {
            $FORM_ID = 2;
            $FORM_EVENT = 'FEEDBACK';
            $PROP = [];
            $PROP['form_text_4'] = $_REQUEST['name'];
            $PROP['form_text_5'] = $_REQUEST['phone'];
            $PROP['form_textarea_7'] = $_REQUEST['message'];
            $PROP['form_checkbox_POLITICS[]'] = array(
                0 => 8,
            );


            $PROP['web_form_apply'] = 'Y';

            $message .= 'Имя: ' . $_REQUEST['name'] . '<br>';
            $message .= 'Телефон: ' . $_REQUEST['phone'] . '<br><br>';
            $message .= 'Сообщение: ' .$_REQUEST['message']. '<br><br>';


            /**
             * Отправка в amoCRM
             */
            $data = [
                'name' => $_REQUEST['name'],
                'phone' => $_REQUEST['phone'],
                'message' => $_REQUEST['message'],
                'city'  => $city
            ];

            if (!empty($_REQUEST['email'])) {
                $PROP['form_email_6'] = $_REQUEST['email'];
                $message .= 'Email: ' .$_REQUEST['email']. '<br><br>';
                $data['email'] = $_REQUEST['email'];
            }

        }

        else {
            $FORM_ID = 1;
            $FORM_EVENT = 'BACKCALL';
            $PROP = [];
            $PROP['form_text_1'] = $_REQUEST['name'];
            $PROP['form_text_2'] = $_REQUEST['phone'];
            $PROP['form_checkbox_POLITICS[]'] = array(
                0 => 3,
            );

            $PROP['web_form_apply'] = 'Y';

            $message .= 'Имя: ' . $_REQUEST['name'] . '<br>';
            $message .= 'Телефон: ' . $_REQUEST['phone'] . '<br><br>';

            /**
             * Отправка в amoCRM
             */
            $data = [
                'name' => $_REQUEST['name'],
                'phone' => $_REQUEST['phone'],
                'city' => $city
            ];

            if (!empty($_REQUEST["tipObject"])) {
                $tipParse = json_decode($_REQUEST['tipObject'], true);
                if ($tipParse['city'])
                    $city = $tipParse['city'] == "spb" ? "СПБ" : "МСК";
                $FIELD_PROP['BACKCALL_OBJECT'] = $tipParse['type-of-property']." ".$tipParse["object-class"]." ".$tipParse["object"];

                /**
                 * Отправка в amoCRM
                 */
                $data = [
                    'name' => $_REQUEST['name'],
                    'phone' => $_REQUEST['phone'],
                    'brokerName' => $_REQUEST['broker-name'],
                    'object' => $tipParse['object'],
                    'class' => $tipParse['object-class'],
                    'typeOfProperty' => $tipParse['type-of-property'],
                    'city' => $city
                ];
            }
            if (!empty($_REQUEST["tipObject"])) $message .= 'Объект: '.$tipParse['type-of-property'].' '.$tipParse['object-class'].' '.$tipParse['object']. '<br><br>';
        }

        $data['formName'] = $_REQUEST['form_name'];
        if (!empty($_REQUEST['type-of-property']))
            $data['typeOfProperty'] = $_REQUEST['type-of-property'];
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
    } else{
        echo 'Данные не заполнены';
        die();
    }
}
?>