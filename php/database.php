<?php

require_once "config.php";

function db()
{
    static $pdo = null;

    if ($pdo === null) {

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
    }
    return $pdo;
}

function dbRun($sql, $param = null)
{
    $statement = db()->prepare($sql);

    $statement->execute($param);

    return $statement;
}

function beginTransaction()
{
    db()->beginTransaction();
}

function commit()
{
    db()->commit();
}

function rollBack()
{
    db()->rollBack();
}

function lastInsertId()
{
    return db()->lastInsertId();
}
