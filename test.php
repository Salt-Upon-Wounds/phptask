<?php
/**
 * Автор: Котельников Кирилл
 * 
 * Дата реализации:10.11.2022
 * 
 * Дата изменения: 10.11.2022
 */
require_once 'User.php';
$link = mysqli_connect('localhost', 'root', 'citroen82', 'tbl', '3308');//подключение к бд
$tbl_name = 'users';//название таблицы
$col_name = ['id','name','surname','year','sex', 'city'];//названия столбцов
if ($link == false) {
    print('error');
} else {
    //создание экземпляра уже существующего в бд человека
    //если не находит -> exception
    $kek = new User($link, $tbl_name, $col_name, 1);
    print($kek->getCity() . '<br>');

    //создание экземпляра человека в бд, если данные проходят валидацию
    $lol = new User($link, $tbl_name, $col_name, 2, 'asd', 'asdsad', 1999, 1, 'Gomel');
    //можем изменить поле класса, но чтобы сохранить изменения, нужно вызвать update
    $lol->setCity('zxc');
    $lol->update();
    print($lol->getCity() . '<br>');

    $cheburek = new User($link, $tbl_name, $col_name, 3, 'qwe', 'asdsad', 1999, 1, 'Grodno');
    var_dump($cheburek->formatedObject());
    $cheburek->delete();
}