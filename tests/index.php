<?php

include_once '../vendor/autoload.php';

use StrannyiTip\Insomnia\Core\Insomnia;
use StrannyiTip\Insomnia\Core\Proxy;

$insomnia = new Insomnia();
$proxy = new Proxy();

$proxy
    ->setAddress('127.0.0.1')
    ->setPort(33333);

$insomnia
    ->connect('http://127.0.0.1', 8000)
    ->setProxy($proxy)
    ->post(['name' => 'Nightmare']);

die($insomnia->asString() . PHP_EOL);