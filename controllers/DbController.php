<?php

$dbHOST = "localhost";
$dbUSER = "root";
$dbPASS = "";
$dbNAME = "cheat_panel";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

@$db = mysqli_connect($dbHOST, $dbUSER, $dbPASS, $dbNAME);

if (!$db) {
    echo 'Ошибка подключения к Базе Данных';
    exit();
}

mysqli_set_charset($db, 'utf8');
