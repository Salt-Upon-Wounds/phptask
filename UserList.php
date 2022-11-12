<?php
/**
 * Автор: Котельников Кирилл
 * 
 * Дата реализации:10.11.2022 22:00
 * 
 * Дата изменения: 12.11.2022 5:40
 * 
 * Класс для работы с бд
 */

 //если нет User.php, выкинет ошибку
 require_once "User.php";

 /**
  * UserList
  * список id людей из таблицы в бд, 
  * искомый по заданному шаблону 'знак сравнения + число'
  */
 class UserList 
 {
    private $conn;
    private $tbl_name;
    private $column_names;//must be ['id','name','surname','year','sex', 'city']
    private $id_arr = [];

    /**
     * 
     * Конструктор находит все id, подходящие под условие
     * Вслучае ошибки выбрасывает исключение
     * 
     * @param mixed $conn подключние к бд
     * @param mixed $tbl_name название таблицы
     * @param mixed $column_names название колонок в строгом порядке, например ['id','name','surname','year','sex', 'city']
     * @param string $exp 'знак сравнения + число' 
     */
    public function __construct($conn, $tbl_name, $column_names, string $exp) 
    {
        if ($conn->connect_error) {
            die("Ошибка: " . $conn->connect_error);
        }
        $this->conn = $conn;
        $this->tbl_name = $tbl_name;
        $this->column_names = $column_names;

        $sql = "SELECT {$column_names[0]} FROM {$tbl_name} WHERE {$column_names[0]} {$exp}";
        $result = $conn->query($sql);
        if ($result && $result->num_rows) {
            while ($row = $result->fetch_row()) {
                array_push($this->id_arr, $row[0]);
            }
        }
    }

    public function getIdArr() 
    {
        return $this->id_arr;
    }

    public function getUserArr() 
    {
        $res = [];
        foreach ($this->id_arr as $value) {
            array_push($res, new User($this->conn, $this->tbl_name, $this->column_names, $value));
        }
        return $res;
    }

    public function deleteAll() 
    {
        $arr = $this->GetUserArr();
        foreach ($arr as $value) {
            $value->delete();
        }
    }
 }