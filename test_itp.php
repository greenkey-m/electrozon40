<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 27.12.2018
 * Time: 3:23
 */

//Формируем JSON
//$request_data = array('type' => 'reqColor', 'value' => '#aa00cc');
//$json = json_encode($request_data);

$json = '{
    "data": {
        "login": "greenkey",
        "passwd": "merlin"
    },
    "request": {
        "method": "login",
        "module": "system"
    }
}';

//Настраиваем cURL
$ch = curl_init('https://b2b.i-t-p.pro/api');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Получаем данные
$response = curl_exec($ch);

print_r($response);