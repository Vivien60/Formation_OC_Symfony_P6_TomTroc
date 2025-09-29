<?php
declare(strict_types=1);

namespace model;

use DateTime;
use PDO;
use services\DBManager;

class User extends AbstractEntity
{
    public string $username;
    public ?string $password = null {
        get {
            return $this->password;
        }
        set {
            $this->password = $value?: $this->password;
        }
    }

    public string $email;

    protected static string $selectSql = "select id, name as username, email, password, DATE(created_at) as createdAt from user";

    protected function __construct()
    {
        parent::__construct();
    }

    public static function fromEmail(string $email) : ?User
    {
        $sql = static::$selectSql." where email = :email";
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
        //TODO : vérifier email pas en double et pseudo pas en double aussi
        if(!$this->checkExistId()) {
            throw new \Exception('User not found');
        }
        $sql = "update user set name = :username, email = :email, password = :password, created_at = :created_at where id = :id";
        $stmt = static::$db->query($sql, [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'created_at' => $this->createdAt->format("Y-m-d H:i:s")
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

    protected function checkExistId() : bool
    {
        $stmt = static::$db->query("select count(*) as nb from user where id = :id", ['id' => $this->id]);
        $nb = $stmt->fetchColumn();
        return $nb > 0;
    }

    protected function checkExist()
    {
        $stmt = static::$db->query("select count(*) as nb from user where email = :email", ['email' => $this->email]);
        $nb = $stmt->fetchColumn();
        return $nb > 0;
    }
}