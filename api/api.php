<?php
require '../controllers/DbController.php';
include '../controllers/ApiController.php';
include '../controllers/KeysController.php';
include '../controllers/LogsController.php';
include '../controllers/BanController.php';
include '../controllers/CheatController.php';

if (!isset($_POST["method"]))
    die();

if ($_POST["method"] == "auth") {
    if (!isset($_POST["key"]) || !isset($_POST["hwid"])) {
        die();
    }

    $keyData = getKeyInfo($_POST["key"]);
    if (!$keyData) {
        $array = array('Status' => 'Error', 'msg' => 'Key not found');
        echo encryptRequest(json_encode($array));
        die();
    }

    $hwidBan = checkIfBanned($_POST["hwid"]);

    if ($hwidBan) {
        banKey($_POST["key"], "Banned system due find HWID in blacklist");

        $array = array('Status' => 'Error', 'msg' => 'Banned');
        echo encryptRequest(json_encode($array));

        die();
    }

    if ($keyData["status"] == "waiting") {
        $endTime = time() + $keyData["subscribe"];
        setKeyEnd($_POST["key"], $endTime);
        setKeyHwid($_POST["key"], $_POST["hwid"]);
        setFirstIp($_POST["key"]);

        $keyData = getKeyInfo($_POST["key"]); //

        $array = array('Status' => makeToken($_POST["key"], $_POST["hwid"]));
        echo encryptRequest(json_encode($array));
        die();
    } else if ($keyData["status"] == "banned") {
        $keyData = getKeyInfo($_POST["key"]);
        $array = array('Status' => 'Error', 'msg' => 'Banned');
        echo encryptRequest(json_encode($array));
        die();
    } else {
        if ($keyData["hwid"] == NULL) {
            setKeyHwid($_POST["key"], $_POST["hwid"]);
        }

        $keyData = getKeyInfo($_POST["key"]);

        if ($_POST["hwid"] != $keyData["hwid"]) {
            $array = array('Status' => 'Error', 'msg' => 'HWID Doesn\'t match');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["subscribeend"] <= time()) {
            $array = array('Status' => 'Error', 'msg' => 'Subscribe ended');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["hwid"] == $_POST["hwid"] && $keyData["subscribeend"] >= time()) {
            setLastIp($_POST["key"]);
            $array = array('Status' => makeToken($_POST["key"], $_POST["hwid"]));
            echo encryptRequest(json_encode($array));
            die();
        }
    }
} else if ($_POST["method"] == "dll") {
    if (!isset($_POST["key"]) || !isset($_POST["hwid"])) die();

    $keyData = getKeyInfo($_POST["key"]);
    if (!$keyData) {
        $array = array('Status' => 'Error', 'msg' => 'Key not found');
        echo encryptRequest(json_encode($array));
        die();
    }

    $hwidBan = checkIfBanned($_POST["hwid"]);

    if ($hwidBan) {
        banKey($_POST["key"], "Banned system due find HWID in blacklist");
        $array = array('Status' => 'Error', 'msg' => 'HWID Banned');
        echo encryptRequest(json_encode($array));
        die();
    }

    if ($keyData["status"] == "banned") {
        $keyData = getKeyInfo($_POST["key"]);
        $array = array('Status' => 'Error', 'msg' => 'Key banned');
        echo encryptRequest(json_encode($array));
        die();
    } else {
        $keyData = getKeyInfo($_POST["key"]);

        if ($_POST["hwid"] != $keyData["hwid"]) {
            $array = array('Status' => 'Error', 'msg' => 'HWID Doesn\'t match');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["subscribeend"] <= time()) {
            $array = array('Status' => 'Error', 'msg' => 'Subscribe ended');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["hwid"] == $_POST["hwid"] && $keyData["subscribeend"] >= time()) {
            $cheatInfo = getCheatInfo($keyData["cheat"]);
            $cheatFile = "../files/" . $cheatInfo["filename"];
            echo encryptRequest(file_get_contents($cheatFile));
            die();
        }
    }
} else if ($_POST["method"] == "process") {
    if (!isset($_POST["key"]) || !isset($_POST["hwid"])) die();

    $keyData = getKeyInfo($_POST["key"]);
    if (!$keyData) {
        $array = array('Status' => 'Error', 'msg' => 'Key not found');
        echo encryptRequest(json_encode($array));
        die();
    }

    $hwidBan = checkIfBanned($_POST["hwid"]);

    if ($hwidBan) {
        banKey($_POST["key"], "Banned system due find HWID in blacklist");
        $array = array('Status' => 'Error', 'msg' => 'HWID Banned');
        echo encryptRequest(json_encode($array));
        die();
    }

    if ($keyData["status"] == "banned") {
        $keyData = getKeyInfo($_POST["key"]);
        $array = array('Status' => 'Error', 'msg' => 'Key banned');
        echo encryptRequest(json_encode($array));
        die();
    } else {
        $keyData = getKeyInfo($_POST["key"]);

        if ($_POST["hwid"] != $keyData["hwid"]) {
            $array = array('Status' => 'Error', 'msg' => 'HWID Doesn\'t match');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["subscribeend"] <= time()) {
            $array = array('Status' => 'Error', 'msg' => 'Subscribe ended');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["hwid"] == $_POST["hwid"] && $keyData["subscribeend"] >= time()) {
            $cheatInfo = getCheatInfo($keyData["cheat"]);

            $array = array('ProcessName' => $cheatInfo["process"]);
            echo encryptRequest(json_encode($array));
            die();
        }
    }
} else if ($_POST["method"] == "ban") {
    if (!isset($_POST["hwid"])) die();

    banHwid($_POST["hwid"], "System banned due crack trying");

    $array = array('Status' => 'Success');
    echo encryptRequest(json_encode($array));
} else if ($_POST["method"] == "driver") {
    if (!isset($_POST["key"]) || !isset($_POST["hwid"])) die();

    $keyData = getKeyInfo($_POST["key"]);
    if (!$keyData) {
        $array = array('Status' => 'Error', 'msg' => 'Key not found');
        echo encryptRequest(json_encode($array));
        die();
    }

    $hwidBan = checkIfBanned($_POST["hwid"]);

    if ($hwidBan) {
        banKey($_POST["key"], "Banned system due find HWID in blacklist");
        $array = array('Status' => 'Error', 'msg' => 'HWID Banned');
        echo encryptRequest(json_encode($array));
        die();
    }

    if ($keyData["status"] == "banned") {
        $keyData = getKeyInfo($_POST["key"]);
        $array = array('Status' => 'Error', 'msg' => 'Key banned');
        echo encryptRequest(json_encode($array));
        die();
    } else {
        $keyData = getKeyInfo($_POST["key"]);

        if ($_POST["hwid"] != $keyData["hwid"]) {
            $array = array('Status' => 'Error', 'msg' => 'HWID Doesn\'t match');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["subscribeend"] <= time()) {
            $array = array('Status' => 'Error', 'msg' => 'Subscribe ended');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["hwid"] == $_POST["hwid"] && $keyData["subscribeend"] >= time()) {
            $cheatFile = "../files/driver.sys";
            echo encryptRequest(file_get_contents($cheatFile));
            die();
        }
    }
} else if ($_POST["method"] == "spoofer") {
    if (!isset($_POST["key"]) || !isset($_POST["hwid"])) die();

    $keyData = getKeyInfo($_POST["key"]);
    if (!$keyData) {
        $array = array('Status' => 'Error', 'msg' => 'Key not found');
        echo encryptRequest(json_encode($array));
        die();
    }

    $hwidBan = checkIfBanned($_POST["hwid"]);

    if ($hwidBan) {
        banKey($_POST["key"], "Banned system due find HWID in blacklist");
        $array = array('Status' => 'Error', 'msg' => 'HWID Banned');
        echo encryptRequest(json_encode($array));
        die();
    }

    if ($keyData["status"] == "banned") {
        $keyData = getKeyInfo($_POST["key"]);
        $array = array('Status' => 'Error', 'msg' => 'Key banned');
        echo encryptRequest(json_encode($array));
        die();
    } else {
        $keyData = getKeyInfo($_POST["key"]);

        if ($_POST["hwid"] != $keyData["hwid"]) {
            $array = array('Status' => 'Error', 'msg' => 'HWID Doesn\'t match');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["subscribeend"] <= time()) {
            $array = array('Status' => 'Error', 'msg' => 'Subscribe ended');
            echo encryptRequest(json_encode($array));
            die();
        }

        if ($keyData["hwid"] == $_POST["hwid"] && $keyData["subscribeend"] >= time()) {
            $cheatInfo = getCheatInfo($keyData["cheat"]);
            $cheatFile = "../files/spoof.dll";
            echo encryptRequest(file_get_contents($cheatFile));
            die();
        }
    }
} else if ($_POST["method"] == "log") {
    if (!isset($_POST["message"]) || !isset($_POST["key"]) || !isset($_POST["hwid"])) die();

    createNewLog($_POST["message"], $_POST["key"], $_POST["hwid"]);

    $array = array('Status' => 'Success');
    echo encryptRequest(json_encode($array));
    die();
}
