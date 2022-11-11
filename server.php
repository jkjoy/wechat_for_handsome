<?php
require __DIR__ . '/vendor/autoload.php';
include 'config.php';
require('cross.php');

use EasyWeChat\Factory;

$app = Factory::officialAccount($config);
$app->server->push(CrossHandler::class);

$response = $app->server->serve();

// 将响应输出
$response->send();
