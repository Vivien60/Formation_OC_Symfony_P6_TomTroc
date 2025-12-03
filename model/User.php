<?php
declare(strict_types=1);

namespace model;

use DateTime;
use model\enum\MessageStatus;
use PDO;
use services\DBManager;

class User extends AbstractEntity
{
    /**
     * @var Thread[] $threads
     */
    public array $threads = [];
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

    protected static string $selectSql = "select id, username, email, password, avatar, DATE(created_at) as createdAt, avatar from user";

    protected function __construct(array $fieldVals)
    {
        parent::__construct($fieldVals);
    }

    public function getThreads()
    {
        if(empty($this->threads)) {
            $this->threads = Thread::getManager()->recentThreadsWithMessage($this);
        }
        return $this->threads;
    }

    public function openThread(int $id = 0) : ?Thread
    {
        $this->getThreads();
        if(empty($this->threads)) {
            return null;
        }
        return Thread::openForUser($this, $id, $this->threads);
    }

    public function newPassword(string $password)
    {
        if(empty($password))
            return;
        $this->password = $password;
        $this->validatePassword();
        $this->hashPassword();
    }

    public function addThread(Thread $thread): void
    {
        $this->getThreads();
        $this->threads[] = $thread;
    }

    protected function hydrate(array $data): void
    {
        parent::hydrate($data);
        $this->avatar = $this->avatar?:'default.png';
    }

    public static function authenticate($email, $password) : ?static
    {
        $manager = new UserManager();
        $user = $manager->fromEmail($email);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function isAnyMissingField() : bool
    {
        if(empty($this->username) || empty($this->password) || empty($this->email)) {
            return true;
        }
        return false;
    }

    public function retrieveLibrary()
    {
        $bookManager = new BookCopyManager();
        $this->library = $bookManager->fromOwner($this);
    }

    public function getUnreadMessagesCount() : int
    {
        $sql = "select count(*) 
                from 
                    participate p
                    inner join message m on p.thread_id = m.thread_id 
                    left join message_status ms on m.id = ms.message_id and p.user_id = ms.user_id
                where p.user_id = :id and (ms.status = :readStatus or ms.status IS NULL)";
        $stmt = static::$db->query(
            $sql,
            [
                'id' => $this->id,
                'readStatus' => MessageStatus::UNREAD->value
            ]);
        return intval($stmt->fetchColumn());
    }

    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->password;
    }

    public function getUniqueIdentifiers(): array {
        return [
            'email' => $this->email,
            'username' => $this->username,
        ];
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function validate(): bool
    {
        $this->validateEmail();
        $this->validateUsername();
        $this->validatePassword();
        return true;
    }

    private function validatePassword()
    {
        if ($this->password === null ||
            (strlen($this->password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $this->password))) {
            throw new \Exception(
                'Les mots de passe doivent contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre'
            );
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function validateUsername(): void
    {
        if (strlen($this->username) < 3 || strlen($this->username) > 50 ||
            !preg_match('/^[\p{L}0-9_-]+$/', $this->username)) {
            throw new \Exception(
                "Les noms d'utilisateurs doivent contenir entre 3 et 50 caractères, et uniquement des lettres accentuées ou non, des chiffres, des tirets et des underscores"
            );
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function validateEmail(): void
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Format d'email invalide");
        }
    }
}