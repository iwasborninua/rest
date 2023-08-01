<?php

require_once('vendor/autoload.php');

use GuzzleHttp\Client;

$client = new Client([
    'verify' => false
]);

$loginUrl = 'https://actinium-service.carrierpro.com/carrierpro/login';
$dateUrl  = 'https://gatekeeper-service.rtspro.com/';


try {
    $loginResponse = $client->post($loginUrl, [
        'form_params' => [
            'username' => $_GET['username'] ? $_GET['username'] :  'mykhailo.kharchenko1@nure.ua',
            'password' => $_GET['password'] ? $_GET['password'] : '~U%&tu!pGA9%hM:'
        ]
    ]);
} catch (\GuzzleHttp\Exception\RequestException $exception) {
    if ($exception->getCode() == 401) {
        echo PHP_EOL . "Не верный логин или пароль!" . PHP_EOL;
    }
}

$loginResponse = json_decode($loginResponse->getBody());

try {
    $dateResponse = $client->post($dateUrl, [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/115.0',
            'Accept' => '*/*',
            'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Content-Type' => 'application/json',
            'Origin' => 'https://rtspro.com',
            'Connection' => 'keep-alive',
            'Referer' => 'https://rtspro.com/',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-site',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache',
            'TE' => 'trailers',
        ],
        'json' => [
            'headers' => [
                'common' => ['Accept' => 'application/json, text/plain, */*'],
                'delete' => [],
                'get' => [],
                'head' => [],
                'post' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'put' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'patch' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            ],
            'url' => 'https://credit-service.carrierpro.com/debtor/search?type=mcNumber&value=' . $_GET['mcNumber'] ? $_GET['mcNumber'] : '01392149' ,
            'method' => 'get',
            'requester' => 'mykhailo.kharchenko1@nure.ua',
        ],
    ]);

    $dateResponse = json_decode($dateResponse->getBody());

    foreach ($dateResponse as $item) {
        $updatedItem = (object) [
            'id' => $item->_id, // Здесь меняем ключ '_id' на 'id'
            'name' => $item->name,
            'rating' => $item->rating,
            'mcNumber' => $item->mcNumber,
            'dotNumber' => $item->dotNumber,
            'address1' => $item->address1,
            'address2' => $item->address2,
            'city' => $item->city,
            'state' => $item->state,
            'zip' => $item->zip,
            'country' => $item->country,
        ];

        // Добавляем обновленный объект в массив $updatedData
        $updatedData[] = $updatedItem;
    }

    print_r($updatedData);

    return json_encode($updatedData);

} catch (\GuzzleHttp\Exception\RequestException $e) {
    echo $e->getMessage();
}