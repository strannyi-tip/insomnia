<?php

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        die(\json_encode(['response' => 'Your ip is 127.0.0.1']));
    case 'POST':
        if (isset($_POST['name'])) {
            die(\json_encode(['response' => 'Your name is ' . $_POST['name']]));
        } elseif (isset($_POST['cookies'])) {
            die(\json_encode(['response' => [
                'token' => 'Your token: ' . $_COOKIE['token'],
                'name' => 'Your name: ' . $_COOKIE['name'],
            ]]));
        } elseif (isset($_POST['headers'])) {
            die(\json_encode([
                'response' => 'Your agent: ' . $_SERVER['HTTP_USER_AGENT'],
            ]));
        } elseif (isset($_POST['files'])) {
            $files = \json_decode($_POST['files'], true);
            die(\json_encode(['response' => [
                'file' => $files[0],
                'name' => $_POST['my_name']
            ]]));
        }
}
