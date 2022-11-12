<?php
/**
 * Автор: Котельников Кирилл
 * 
 * Дата реализации:10.11.2022
 * 
 * Дата изменения: 10.11.2022
 */
require_once 'User.php';
$link = mysqli_connect('localhost', 'root', 'citroen82', 'tbl', '3308');
$tbl_name = 'users';
$col_name = ['id','name','surname','year','sex', 'city'];
if ($link == false) {
    print('error');
} else {
    $kek = new User($link, $tbl_name, $col_name, 1);
    print($kek->getCity());
    $lol = new User($link, $tbl_name, $col_name, 2, 'asd', 'asdsad', 1999, 1, 'Gomel');
    print($lol->getCity());
}