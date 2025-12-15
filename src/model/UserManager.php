<?php
declare(strict_types=1);
namespace model;

use lib\Utils;
use PDO;

class UserManager extends AbstractEntityManager
{
    protected static string $selectSql = "select id, username, email, password, avatar, DATE(created_at) as createdAt from user";

    public function __construct()
    {
        parent::__construct(User::class);
    }

    /**
     * @param array $ownerIds
     * @return User[]
     */
    public function fromIds(array $ownerIds): array
    {
        $sql = static::$selectSql." where id in (".implode(',', $ownerIds).")";
        $stmt = static::$db->query($sql);

        $users = [];
        while ($row = $stmt->fetch()) {
            $user = User::fromArray($row);
            $users[$user->id] = $user;  // ← Indexé par ID directement
        }
        Utils::trace($users);
        return $users;
    }

    public function fromEmail(string $email) : ?User
    {
        $sql = static::$selectSql." where email = :email";
        $stmt = static::$db->query($sql, ['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['id']))
            return null;
        return User::fromArray($result);
    }

    public function fromMemory() : ?User
    {
        return !empty($_SESSION['user'])? $this->fromId($_SESSION['user']) : null;
    }

    public function toMemory(User $user): void
    {
        $_SESSION['user'] = $user->id;
    }

    /**
     * @throws \Exception
     */
    public function save(User $user) : void
    {
        $this->checkUser($user);
        $sql = "update user set username = :username, email = :email, password = :password, avatar = :avatar, created_at = :created_at where id = :id";
        $stmt = static::$db->query($sql, [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'password' => $user->password,
            'avatar' => $user->avatar,
            'created_at' => $user->createdAt->format("Y-m-d H:i:s")
        ]);
        $stmt->execute();
    }

    public function create(User $user) : void
    {
        if($this->identifyAnotherUser($user->id, $user->getUniqueIdentifiers())) {
            throw new \Exception('User already registered');
        }
        $sql = "insert into user (username, email, password, avatar, created_at) values (:username, :email, :password, :avatar, NOW())";
        $stmt = static::$db->query($sql, [
            'username' => $user->username,
            'email' => $user->email,
            'password' => $user->password,
            'avatar' => $user->avatar,
        ]);
        $user->id = (int)static::$db->getPDO()->lastInsertId();
    }

    protected function identifyAnotherUser(int $userId, array $uniqueFields): bool
    {
        $whereUniqueFields = implode(
            ' or ',
            array_map( fn($uniqueFields) => "$uniqueFields = :$uniqueFields", array_keys($uniqueFields) )
        );
        $sql = "select count(id) from user where ($whereUniqueFields) and id != :id";
        $stmt = static::$db->query(
            $sql,
            [
                ...$uniqueFields,
                'id' => $userId,
            ]);
        $nb = $stmt->fetchColumn();
        return $nb > 0;
    }

    /**
     * @param User $user
     * @return void
     * @throws \Exception
     */
    protected function checkUser(User $user): void
    {
        if (!$this->fromId($user->id)) {
            throw new \Exception('User not found');
        }
        if ($this->identifyAnotherUser($user->id, $user->getUniqueIdentifiers())) {
            throw new \Exception('Another user exist with this information');
        }
    }
}