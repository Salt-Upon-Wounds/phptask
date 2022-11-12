<?php
/**
 * 
 */

 require_once "User.php";

 class UserList {
    private $conn;
    private $tbl_name;
    private $column_names;//must be ['id','name','surname','year','sex', 'city']
    private $id_arr = [];

    public function __construct($conn, $tbl_name, $column_names, $exp) {
        if ($conn->connect_error) {
            die("Ошибка: " . $conn->connect_error);
        }
        $this->conn = $conn;
        $this->tbl_name = $tbl_name;
        $this->column_names = $column_names;

        $sql = "SELECT {$column_names[0]} FROM {$tbl_name} WHERE {$column_names[0]} {$exp}";
        $result = $conn->query($sql);
        while ($row = $result->fetch_row()) {
            array_push($this->id_arr, $row[0]);
        }
    }

    public function getIdArr() {
        return $this->id_arr;
    }

    public function getUserArr() {
        $res = [];
        foreach ($this->id_arr as $value) {
            array_push($res, new User($this->conn, $this->tbl_name, $this->column_names, $value));
        }
        return $res;
    }

    public function deleteAll() {
        $arr = $this->GetUserArr();
        foreach ($arr as $value) {
            $value->delete();
        }
    }
 }