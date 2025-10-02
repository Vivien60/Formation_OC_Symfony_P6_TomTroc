<?php

namespace model;

use PDO;

class Thread extends AbstractEntity
{
    protected static string $selectSql = "select id, created_at from thread";
    /**
     * @var User[] $participants
     */
    public array $participants;

    /**
     * @var Message[] $messages
     */
    public array $messages;


    public function __construct()
    {
        parent::__construct();
    }

    public static function fromUsers(User $user1, User $user2) : static
    {
        //TODO : modifier fonction pour supporter un nombre indéfini d'utilisateurs
        $listId = [$user1->id, $user2->id];
        sort($listId);
        $users = implode(',', $listId);
        $sql = "SELECT *, GROUP_CONCAT(participer.user_id ASC SEPARATOR ',') as users
                FROM thread 
                    INNER JOIN participer on thread.id = participer.thread_id 
                GROUP BY thread.id
                HAVING users = :users";
        $stmt = static::$db->query($sql, ['users' => $users]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return static::fromArray($result);
    }

    public function save() : void
    {
        $sql = "insert into thread (created_at) values (:created_at)";
        static::$db->query($sql, ['created_at' => $this->createdAt->format("Y-m-d H:i:s")]);
        $this->id = (int)static::$db->getPDO()->lastInsertId();
        foreach($this->participants as $participant)
        {
            $sql = "insert into participer (thread_id, user_id, etat) values (:threadId, :userId, 1)";
            static::$db->query($sql, ['threadId' => $this->id, 'userId' => $participant->id]);
        }
    }

    public function getMessages() : array
    {
        if(empty($this->messages)) {
            $this->messages = Message::fromThread($this);
        }
        return $this->messages;
    }

    public function getMessageAtRank(int $rank)
    {
        $this->getMessages();
        return $this->messages[$rank];
    }

    public function createNewMessage($content, $author) : void
    {
        //TODO : Utiliser un repo pourrait permettre de laisser à Thread l'orchestration du rank
        $message = new Message($this, -1, $author, $content);
        $message->save();
        $this->getMessages();
        $this->messages[] = $message;
    }
}