<?php

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $userType;

    public function __construct($id, $name, $email, $password, $userType)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->userType = $userType;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUserType()
    {
        return $this->userType;
    }

    public static function create($name, $email, $password, $userType)
    {
        $db = DB::getInstance();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->getConnection()->prepare("INSERT INTO HL_User (u_name, u_email, u_password, u_userType) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $userType);
        $stmt->execute();
        return new User($db->getConnection()->insert_id, $name, $email, $password, $userType);
    }

    public static function update($id, $name, $email, $password, $userType)
    {
        $db = DB::getInstance();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->getConnection()->prepare("UPDATE HL_User SET u_name = ?, u_email = ?, u_password = ?, u_userType = ? WHERE u_id = ?");
        $stmt->bind_param("ssssi", $name, $email, $password, $userType, $id);
        $stmt->execute();
        return new User($id, $name, $email, $password, $userType);
    }

    public static function delete($id)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_User WHERE u_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public static function getById($id)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_User WHERE u_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return null;
        }
        $row = $result->fetch_assoc();
        return new User($row['u_id'], $row['u_name'], $row['u_email'], $row['u_password'], $row['u_userType']);
    }

    public static function getByEmail($email)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_User WHERE u_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return null;
        }
        $row = $result->fetch_assoc();
        return new User($row['u_id'], $row['u_name'], $row['u_email'], $row['u_password'], $row['u_userType']);
    }
}