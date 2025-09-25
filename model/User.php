<?php
declare(strict_types=1);

namespace model;

use Cassandra\Date;
use DateTime;
use mysql_xdevapi\Statement;
use PDO;
use services\DBManager;

class User
{
    public ?int $id;
    public string $username;
    private ?string $password = null;
    public string $email;
    public ?DateTime $createdAt = null;

    public static DBManager $db;

    private function __construct()
    {
    }

    public static function fromArray(array $data) : User
    {
        $user = new static;
        $user->id = $data['id']??null;
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->createdAt = isset($data['created_at'])?new DateTime($data['created_at']):null;
        return $user;
    }

    public static function fromId(int $id) : ?User
    {
        $sql = "select id, name as username, email, password, created_at from user where id = :id";
        $stmt = static::$db->query($sql, ['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['id']))
            return null;
        return static::fromArray($result);
    }

    public static function fromEmail(string $email) : ?User
    {
        $sql = "select id, name as username, email, password, DATE(created_at) as createdAt from user where email = :email";
        $stmt = static::$db->query($sql, ['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['id']))
            return null;
        return static::fromArray($result);
    }

    /**
     * @throws \Exception
     */
    public function save() : void
    {
        if(!$this->checkExistId()) {
            throw new \Exception('User not found');
        }
        $sql = "update user set name = :username, email = :email, password = :password, created_at = :created_at where id = :id";
        $stmt = static::$db->query($sql, [
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'created_at' => $this->createdAt
        ]);
        $stmt->execute();
    }

    public function create() : void
    {
        if($this->checkExist()) {
            throw new \Exception('User already registered');
        }
        $sql = "insert into user (name, email, password, created_at) values (:username, :email, :password, NOW())";
        $stmt = static::$db->query($sql, [
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
        ]);
        $this->id = (int)static::$db->getPDO()->lastInsertId();
    }

    public function toMemory()
    {
        $_SESSION['user'] = $this->id;
    }

    public static function fromMemory() : ?User
    {
        return $_SESSION['user']? static::fromId($_SESSION['user']) : null;
    }

    public static function authenticate($email, $password) : bool
    {
        $stmt = static::$db->query(
            "select count(*) as nb from user where email like :email and password like :password",
            ['email' => $email, 'password' => $password]
        );
        $nb = $stmt->fetchColumn();
        return $nb > 0;
    }

    private function checkExistId() : bool
    {
        $stmt = static::$db->query("select count(*) as nb from user where id = :id", ['id' => $this->id]);
        $nb = $stmt->fetchColumn();
        return $nb > 0;
    }

    private function checkExist()
    {
        $stmt = static::$db->query("select count(*) as nb from user where email = :email", ['email' => $this->email]);
        $nb = $stmt->fetchColumn();
        return $nb > 0;
    }
}