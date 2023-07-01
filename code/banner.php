<?php

const MYSQL_HOST = 'db';
const MYSQL_DB = 'banners';
const MYSQL_USER = 'root';
const MYSQL_PASS = 'root';
const MYSQL_BANNER_LOAD_TABLE = 'banner_loads';

function getIp(): string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return $_SERVER['REMOTE_ADDR'];
}

function getUserAgent(): string
{
    return $_SERVER['HTTP_USER_AGENT'] ?? '';
}

function getReferer(): string
{
    return $_SERVER['HTTP_REFERER'] ?? '';
}

if (
    empty(getIp())
    || empty(getUserAgent())
    || empty(getReferer())
) {
    header('X-Track-Status: Faked');
    die();
}

try {
    $conn = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB, MYSQL_USER, MYSQL_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
    header('X-Track-Status: MYSQL exception occurred:' . $e->getMessage());
    die();
}

$stmt = $conn->prepare('
    INSERT INTO `' . MYSQL_BANNER_LOAD_TABLE . '`(
        `ip_address`,
        `user_agent`,
        `view_date`,
        `page_url`,
        `views_count`
    ) VALUES (
        :ip_address,
        :user_agent,
        NOW(),
        :page_url,
        1
    )
    ON DUPLICATE KEY UPDATE
        `view_date` = NOW(),
        `views_count` = `views_count` + 1;
');

try {
    $stmt->execute([
        ':ip_address' => getIp(),
        ':user_agent' => getUserAgent(),
        ':page_url' => getReferer(),
    ]);
} catch (\Exception $e) {
    header('X-Track-Status: MYSQL recording exception occurred:' . $e->getMessage());
    die();
}
