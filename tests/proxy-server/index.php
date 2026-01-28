<?php

include_once '../../vendor/autoload.php';


use StrannyiTip\Insomnia\Core\Insomnia;



$insomnia = new Insomnia();
$insomnia
    ->connect('127.0.0.1', 8000)
    ->post(['name' => $_POST['name']]);

die(\json_encode([
    'response' => 'PROXY: ' . $insomnia->asObject()->get('response'),
]));