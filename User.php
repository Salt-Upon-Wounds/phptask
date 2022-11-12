<?php
/**
 * 
 */

class User {
    private $conn;
    private $tbl_name;
    private $column_names;//must be ['id','name','surname','year','sex', 'city']
    private $id;
    private $id_old;//на случай, если пользователь изменит id, храним старый для функций update и delete
    private $name;
    private $surname;
    private $year;
    private $sex;
    private $city;

    public function __construct($conn, $tbl_name, $column_names, int $id, string $name = null,
        string $surname = null, int $year = null, $sex = null, string $city = null
    ) {
        if ($conn->connect_error) {
            die("Ошибка: " . $conn->connect_error);
        }
        $this->conn = $conn;
        $this->tbl_name = $tbl_name;
        $this->column_names = $column_names;
        $sql = "SELECT " . implode(',', $column_names) . " FROM {$tbl_name} "
             . "WHERE {$column_names[0]} = {$id}";
        $result = $conn->query($sql);
        if ($result && $result->num_rows) {
            $res_arr = $result->fetch_row();
            $this->id = $res_arr[0];
            $this->name = $res_arr[1];
            $this->surname = $res_arr[2];
            $this->year = $res_arr[3];
            $this->sex = $res_arr[4];
            $this->city = $res_arr[5];
        } else {
            $sql = "INSERT INTO {$tbl_name} (" . implode(',', $column_names) . ") "
                 . "VALUES ($id, '$name', '$surname', $year, $sex, '$city')";
            $this->setId($id);
            $this->setName($name);
            $this->setSurname($surname);
            $this->setYear($year);
            $this->setSex($sex);
            $this->setCity($city);
            if (!$conn->query($sql)) {
                throw new Exception("Error: " . $sql . " " . $conn->error);
            }
        }
        $this->id_old = $this->id;
    }

    public static function yearToAge(int $year) {
        return date('Y') - $year;
    }

    public static function SexString($sex) {
        if ($sex) {
            return 'муж';
        } else {
            return 'жен';
        }
    }

    public function formatedObject() {
        $obj = new stdClass();
        $obj->id = $this->id;
        $obj->name = $this->name;
        $obj->surname = $this->surname;
        $obj->age = User::yearToAge($this->year);
        $obj->sex = User::SexString($this->sex);
        $obj->city = $this->city;
        return $obj;
    }

    //Сохранение полей экземпляра класса в БД
    public function update() {
        $sql = "UPDATE {$this->tbl_name} SET "
             . "{$this->column_names[0]} = {$this->id}, "
             . "{$this->column_names[1]} = '{$this->name}', "
             . "{$this->column_names[2]} = '{$this->surname}', "
             . "{$this->column_names[3]} = {$this->year}, "
             . "{$this->column_names[4]} = {$this->sex}, "
             . "{$this->column_names[5]} = '{$this->city}'"
             . " WHERE {$this->column_names[0]} = {$this->id_old}";
        if (!$this->conn->query($sql)) {
            throw new Exception("Error: " . $sql . " " . $this->conn->error);
        } else {
            $this->id_old = $this->id;
        }
    }

    //Удаление человека из БД в соответствии с id объекта
    public function delete() {
        $sql = "DELETE FROM {$this->tbl_name} WHERE {$this->column_names[0]} = {$this->id_old}";
        if (!$this->conn->query($sql)) {
            throw new Exception("Error: " . $sql . " " . $this->conn->error);
        } else {
            $this->id_old = $this->id;
        }
    }

    public function setId(int $id) {
        if (isset($id)) {
            $this->id = $id;
        } else {
            throw new Exception('Error: $id in setId is empty');
        }
    }

    //в имени только буквы
    public function setName(string $name) {
        if (isset($name)) {
            $name = htmlspecialchars(trim($name));
            if (!preg_match("/^\p{L}+$/", $name)) {
                throw new Exception("Error: name validation failed");
            } else {
                $this->name = $name;
            }
        } else {
            throw new Exception('Error: $name in setName is empty');
        }
    }

    //в фамилии только буквы
    public function setSurname(string $surname) {
        if (isset($surname)) {
            $surname = htmlspecialchars(trim($surname));
            if (!preg_match("/^\p{L}+$/", $surname)) {
                throw new Exception("Error: surname validation failed");
            } else {
                $this->surname = $surname;
            }
        } else {
            throw new Exception('Error: $surname in setSurname is empty');
        }
    }

    //если год больше текущего и меньше минимального для типа YEAR, то ошибка
    public function setYear(int $year) {
        if (isset($year)) {
            if (date('Y') - $year < 0 && $year < 1900) {
                throw new Exception("Error: year validation failed");
            } else {
                $this->year = $year;
            }
        } else {
            throw new Exception('Error: $year in setYear is empty');
        }
    }

    public function setSex($sex) {
        if (isset($sex)) {
            if ($sex == 'муж' || $sex == 1) {
                $this->sex = 1;
            } elseif ($sex == 'жен' || $sex == 0) {
                $this->sex = 0;
            } else {
                throw new Exception("Error: sex validation failed");
            }
        } else {
            throw new Exception('Error: $sex in setSex is empty');
        }
    }

    //в названии города буквы с учетом возможных особенностей названия типо пробелов, апострофов и дефисов
    public function setCity(string $city) {
        if (isset($city)) {
            $city = htmlspecialchars(trim($city));
            if (!preg_match("/^(\p{L}+(?:(\. )|-| |'))*\p{L}*$/", $city)) {
                throw new Exception("Error: city validation failed");
            } else {
                $this->city = $city;
            }
        } else {
            throw new Exception('Error: $city in setCity is empty');
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function getYear() {
        return $this->year;
    }

    public function getSex() {
        return $this->sex;
    }

    public function getCity() {
        return $this->city;
    }
}