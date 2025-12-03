<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=kartvizit_db;charset=utf8", "root", "");
} catch (PDOException $e) {
    die($e->getMessage());
}
?>