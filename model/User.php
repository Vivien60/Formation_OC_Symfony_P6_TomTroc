<?php
declare(strict_types=1);

namespace model;

use DateTime;
use PDO;
use services\DBManager;

class User extends AbstractEntity
{
    /**
     * @var BookCopy[] $library
     */
    public array $library = [] {
        get {
            if(empty($this->library)) {
                $this->retrieveLibrary();
            }
            return $this->library;
        }
        set {
            $this->library = $value;
        }
    }
    public string $username = '';
    public ?string $password = null {
        get {
            return $this->password;
        }
        set {
            $this->password = $value?: $this->password;
        }
    }

    public string $email = '';

    public string $avatar = '';

    protected static string $selectSql = "select id, name as username, email, password, DATE(created_at) as createdAt, avatar from user";

    protected function __construct(array $fieldVals)
    {
        parent::__construct($fieldVals);
    }

    protected function hydrate(array $data): void
    {
        parent::hydrate($data);
        $this->avatar = $this->avatar?:'default.png';
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

    public static function fromMemory() : ?User
    {
        return !empty($_SESSION['user'])? static::fromId($_SESSION['user']) : null;
    }

    /**
     * @throws \Exception
     */
    public function save() : void
    {
        if(!User::fromId($this->id)) {
            throw new \Exception('User not found');
        }
        if($this->identifyAnotherUser()) {
            throw new \Exception('Another user exist with this information');
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
        if($this->isAnyMissingField()) {
            throw new \Exception('Missing fields');
        }
        if($this->identifyAnotherUser()) {
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

    public function toMemory(): void
    {
        $_SESSION['user'] = $this->id;
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

    protected function identifyAnotherUser(): bool
    {
        $sql = "select * from user where (email = :email or name = :username) and id != :id";
        $stmt = static::$db->query(
            $sql,
            [
                'email' => $this->email,
                'username' => $this->username,
                'id' => $this->id,
            ]);
        $nb = $stmt->fetchColumn();
        return $nb > 0;
    }

    private function isAnyMissingField() : bool
    {
        if(empty($this->username) || empty($this->password) || empty($this->email)) {
            return true;
        }
        return false;
    }

    public function retrieveLibrary()
    {
        $this->library = BookCopy::fromOwner($this);
    }
}