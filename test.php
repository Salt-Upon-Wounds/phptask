<?php
/**
 * Автор: Котельников Кирилл
 * 
 * Дата реализации:10.11.2022 22:00
 * 
 * Дата изменения: 12.11.2022 5:40
 * 
 * Утилита для работы с бд
 */
require_once 'User.php';
require_once 'UserList.php';
$link = mysqli_connect('localhost', 'root', 'password', 'table', '3306');//подключение к бд
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

    $a = new User($link, $tbl_name, $col_name, 4, 'ert', 'dssvs', 2000, 1, 'Gomel');
    $b = new User($link, $tbl_name, $col_name, 5, 'dfg', 'mkmno', 1979, 0, 'Brest');
    $c = new User($link, $tbl_name, $col_name, 6, 'cvb', 'ntrgnm', 1999, 1, 'Smolensk');

    $d = new UserList($link, $tbl_name, $col_name, '> 3');
    $e = $d->getUserArr();
    foreach ($e as $value) {
        print('<br>');
        var_dump($value->formatedObject());
    }
    $d->deleteAll();
    $link->close();
}