<?php 
include_once 'utils.php';

function getLoaderInfo($loader)
{
    global $db;

    $cheatid_sec = fix_string($loader);

    $query = "SELECT * FROM `loaders` WHERE `id` = '{$cheatid_sec}'";

    $result = mysqli_query($db, $query);
    $keyinfo = mysqli_fetch_assoc($result);

    return $keyinfo;
}

function getAllLoaders()
{
    global $db;

    $query = "SELECT * FROM `loaders` WHERE 1";

    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $array[] = $row;
    }
    return $array;
}

function setLoaderVersion($loaderid, $loaderversion){
    global $db;

    $loader = fix_string($loaderid);
    $version = fix_string($loaderversion);

    $query = "UPDATE `loaders` SET `version` = '{$version}' WHERE `id` = '{$loader}'";

    mysqli_query($db, $query);
}