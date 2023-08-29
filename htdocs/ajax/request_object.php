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

    $phoneCheck = str_replace(array("(", ")", " ", "+", "-"), "", $_REQUEST['phone']);

    if (!empty($_REQUEST) && is_numeric($phoneCheck)) {
        CModule::IncludeModule("form");

        $url = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        $url .= parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

        $arUtm = [];
        if (!empty($_COOKIE['utm'])) {
            foreach (unserialize($_COOKIE['utm']) as $keyUtm => $valueUtm) {
                $arUtm[$keyUtm] = $valueUtm;
            }
        }

        $roistat = $_COOKIE['roistat_visit'];

        if (!empty($_REQUEST["tipApartment"])) {
            $tipParse = json_decode($_REQUEST['tipApartment'], true);
            $PROP = [];
            $site = $_REQUEST['site_id'];
            $city = $tipParse['city'] == "spb" ? "СПБ" : "МСК";

            if (!empty($_REQUEST["excursion-broker-name"])) {
                $FORM_ID = 11;
                $FORM_EVENT = 'EXCURSION';
                $PROP['form_text_36'] = $_REQUEST['name'];
                $PROP['form_text_37'] = $_REQUEST['phone'];
                $PROP['form_checkbox_POLITICS[]'] = array(
                    0 => 38,
                );
            } else {
                $FORM_ID = 3;
                $FORM_EVENT = 'OBJECT_NEW';
                $PROP['form_text_9'] = $_REQUEST['name'];
                $PROP['form_text_10'] = $_REQUEST['phone'];
                $PROP['form_checkbox_POLITICS[]'] = array(
                    0 => 11,
                );
            }

            if ($tipParse['apartment-price'] == "По запросу") {
                $db_props = CIBlockElement::GetProperty($tipParse['city'] == "spb" ? NEW_BUILD_SPB_IBLOCK_ID : NEW_BUILD_IBLOCK_ID,
                    $tipParse['apartment-id'], array("sort" => "asc"), array("CODE" => "PRICE_RUB"));
                if ($ar_props = $db_props->Fetch()) {
                    $tipParse['apartment-price'] = number_format($ar_props["VALUE"], 0, '.', ' ') . " ₽";
                }
            }

            $FIELD_PROP['SIMPLE_QUESTION_OBJECT_CLASS'] = $tipParse['object-class'];
            $FIELD_PROP['SIMPLE_QUESTION_OBJECT_NAME'] = $tipParse['object'];
            $FIELD_PROP['SIMPLE_QUESTION_APARTMENT_ROOM_TYPE'] = $tipParse['apartment-room'];
            $FIELD_PROP['SIMPLE_QUESTION_APARTMENT_SQUARE'] = $tipParse['apartment-square'];
            $FIELD_PROP['SIMPLE_QUESTION_APARTMENT_PRICE'] = $tipParse['apartment-price'];
            if (!empty($_REQUEST["excursion-broker-name"])) {
                $FIELD_PROP['SIMPLE_QUESTION_BROKER_NAME'] = $_REQUEST['excursion-broker-name'];
            }
            $PROP['web_form_apply'] = 'Y';

            $message .= 'Имя: ' . $_REQUEST['name'] . '<br>';
            $message .= 'Телефон: ' . $_REQUEST['phone'] . '<br><br>';
            if (!empty($_REQUEST["excursion-broker-name"])) {
                $message .= 'Брокер: ' . $_REQUEST['excursion-broker-name'] . '<br><br>';
            }
            $message .= 'Запрашиваемая квартира: ' . implode(",", array(
                    $tipParse['object'],
                    $tipParse['apartment-name'],
                    $tipParse['apartment-room'],
                    $tipParse['apartment-square'],
                    $tipParse['apartment-price']
                )) . '<br><br>';

        }

        /**
         * Отправка SMS
         */
        $smsText = 'Добрый день!
Цена запрашиваемой вами планировки по ЖК ' . $tipParse['object'] . ', ' . $tipParse['apartment-room'] . ', ' . $tipParse['apartment-square'] . ', составляет ' . $tipParse['apartment-price'] . '.
Узнать подробную информацию:
8 (812) 467-45-05
www.towergroup.ru';

        if (!empty($_REQUEST["excursion-broker-name"])) {
            $smsText = 'Добрый день!
Вы записались на экскурсию по ЖК ' . $tipParse['object'] . ', по планировке ' . $tipParse['apartment-room'] . '.
Узнать подробную информацию:
8 (812) 467-45-05
www.towergroup.ru';
        }

        require_once 'sms.ru.php';

        $smsru = new SMSRU('31D04BBA-AB59-0027-22EA-C7873C66DD73'); // Ваш уникальный программный ключ, который можно получить на главной странице

        $data = new stdClass();
        $data->to = $_REQUEST['phone'];
        $data->text = $smsText; // Текст сообщения
        //$sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную

        $link = '';

        if(isset($_REQUEST["telegram_button"]) && $_REQUEST["telegram_button"] == 'true'){
            $link = 'https://salebot.site/towergroup_1?price='.$tipParse['apartment-price'].'&object='.$tipParse['object'].'&phone='.$_REQUEST['phone'].'&name='.$_REQUEST['name'];
        } elseif (isset($_REQUEST["whatsapp_button"]) && $_REQUEST["whatsapp_button"] == 'true') {
            $link = 'https://wa.me/79219526406?text=%D0%A3%D0%B7%D0%BD%D0%B0%D1%82%D1%8C%20%D1%81%D1%82%D0%BE%D0%B8%D0%BC%D0%BE%D1%81%D1%82%D1%8C';
        }

        /**
         * Отправка в amoCRM
         */
        
        $data = [
            'name' => $_REQUEST['name'],
            'phone' => $_REQUEST['phone'],
            'formName' => $_REQUEST['form_name'],
            'object' => 'ЖК ' . $tipParse['object'],
            'class' => $tipParse['object-class'],
            'typeOfProperty' => $tipParse['type-of-property'],
            'flatPrice' => $tipParse['apartment-price'],
            'flatInfo' => implode(", ", array(
                $tipParse['apartment-name'],
                $tipParse['apartment-room'],
                $tipParse['apartment-square'],
                $tipParse['apartment-kitchen-square'],
                $tipParse['apartment-decoration'],
                $tipParse['apartment-price']
            )),
            'roistat' => $roistat,
            'clientIP' => $_SERVER['REMOTE_ADDR'],
            'urlPage' => $url,
            'city' => $city
        ];
        if (!empty($arUtm)) {
            foreach ($arUtm as $keyUtm => $valueUtm) {
                $data[$keyUtm] = $valueUtm;
            }
        }
        if (!empty($_REQUEST["excursion-broker-name"])) {
            $data['brokerName'] = $_REQUEST['excursion-broker-name'];
        }

        $headers = array(
            "Content-Type: application/json; charset=utf-8",
            "Accept: application/json",
            "User-Agent: " . $_SERVER['HTTP_USER_AGENT'],
        );

        $ch = curl_init();
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
            $numbersAnalytic = array(
                'number_ym' => $metricsID['PROPERTY_YM_NUMBER_VALUE'],
                'number_ga' => $metricsID['PROPERTY_GA_NUMBER_VALUE'],
                'link_redirect' => $link
            );
            echo json_encode($numbersAnalytic);
            //echo "Результат #".$RESULT_ID." успешно создан ";

            CEvent::Send('FORM_FILLING_' . $FORM_EVENT, $site, array('NUMBER' => $number, 'MESSAGE' => $message));
        } else {
            global $strError;
            echo $strError;
        }

    } else echo 'Данные не заполнены';
}
?>