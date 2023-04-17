<?php
class User implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $userType;


    public function __construct(int $id, string $name, string $email, string $password, string $userType)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->userType = $userType;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getPosts(): array
    {
        return Post::getByUserId($this->id);
    }

    public static function create(string $name, string $email, string $password, string $userType): User
    {
        $db = DB::getInstance();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->getConnection()->prepare("INSERT INTO HL_User (u_name, u_email, u_password, u_userType) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $userType);
        $stmt->execute();
        return new User($db->getConnection()->insert_id, $name, $email, $password, $userType);
    }

    public static function update(int $id, string $name, string $email, string$password, string $userType): User
    {
        $db = DB::getInstance();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->getConnection()->prepare("UPDATE HL_User SET u_name = ?, u_email = ?, u_password = ?, u_userType = ? WHERE u_id = ?");
        $stmt->bind_param("ssssi", $name, $email, $password, $userType, $id);
        $stmt->execute();
        return new User($id, $name, $email, $password, $userType);
    }

    public static function delete(int $id): void
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_User WHERE u_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public static function deleteByMail(string $email): void
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_User WHERE u_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
    }

    public static function getById(int $id): ?User
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

    public static function getByEmail(string $email): ?User
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

    public static function getAll(): array
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_User");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = new User($row['u_id'], $row['u_name'], $row['u_email'], $row['u_password'], $row['u_userType']);
        }
        return $users;
    }

    public function getLikedPosts(): array
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Like WHERE l_u_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = Post::getById($row['l_postId']);
        }
        return $posts;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'userType' => $this->userType
        ];
    }
}