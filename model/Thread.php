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


    public function __construct(array $users)
    {
        $this->participants = $users;
        parent::__construct();
    }

    /**
     * Returns the threads the user is participating in.
     * @param User $user
     * @return Thread[]
     */
    public static function fromParticipant(User $user) : array
    {
        $sql = static::$selectSql." 
                    inner join participer p on thread.id = p.thread_id 
                    where p.user_id = :userId";
        $stmt = static::$db->query($sql, ['userId' => $user->id]);

        return array_map(static::fromArray(...), $stmt->fetchAll());
    }

    public function create() : void
    {
        $this->store();
        $this->storeParticipants();
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

    private function store() : void
    {
        $sql = "insert into thread (created_at) values (:created_at)";
        static::$db->query($sql, ['created_at' => $this->createdAt->format("Y-m-d H:i:s")]);
        $this->id = (int)static::$db->getPDO()->lastInsertId();
    }

    private function storeParticipants()
    {
        $sql = "insert into participer (thread_id, user_id, etat) values (:threadId, :userId, 1)";
        foreach($this->participants as $participant)
        {
            static::$db->query($sql, ['threadId' => $this->id, 'userId' => $participant->id]);
        }
    }
}