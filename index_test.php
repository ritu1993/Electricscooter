<?php

require_once 'db_pdo.php';

$DB = new db_pdo();
$DB->query('INSERT INTO users(name,email,pw,user_level) values ("Ritu","ritumishra@gmail.com","112233","employee")');

$users = $DB->querySelect('SELECT * from users');
var_dump($users);
$DB->disconnect();
