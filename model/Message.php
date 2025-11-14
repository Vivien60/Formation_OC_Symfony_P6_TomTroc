<?php
declare(strict_types=1);
namespace model;

use PDO;

class Message extends AbstractEntity
{
    protected static string $selectSql = "select m.id, m.thread_id, m.`rank`, m.author, m.content, m.created_at from message m";
    public int $author = -1;
    public int $threadId = -1;
    public int $rank = -1;
    public string $content = '';

    protected User $authorInstance {
        get {
            if(empty($this->authorInstance)) {
                $this->authorInstance = User::fromId($this->author);
            }
            return $this->authorInstance;
        }
    }
    protected Thread $thread {
        get {
            if(empty($this->thread)) {
                $this->thread = Thread::fromId($this->threadId);
            }
            return $this->thread;
        }
    }

    /**
     * @internal
     */
    public function __construct(array $fieldVals)
    {
        //
        parent::__construct($fieldVals);
    }

    /**
     * Get the latest message for each thread for a user, unread first
     * @param User $user
     * @return Message[]
     */
    public static function latestByThreadsFor(User $user) : array
    {
        $sql = static::$selectSql."
                    inner join participer p on m.thread_id = p.thread_id  and p.user_id = :userId
                    inner join (
                        select max(message.id) as id from message group by message.thread_id
                    ) last_message on m.id = last_message.id
                    left join message_status ms on m.id = ms.message_id and ms.user_id = :userId
                    order by ms.status asc, m.id desc";
        $stmt = static::$db->query($sql, ['userId' => $user->id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    public function getAuthor() : User
    {
        if(empty($this->authorInstance)) {
            $this->authorInstance = User::fromId($this->author);
        }
        return $this->authorInstance;
    }

    public function getThread() : Thread
    {
        if(empty($this->thread)) {
            $this->thread = Thread::fromId($this->threadId);
        }
        return $this->thread;
    }

    /**
     * @internal
     * @param array $fieldVals
     * @return static
     * @throws \Exception
     */

    public static function fromArray(array $fieldVals) : static
    {
        return new static($fieldVals);
    }

    /**
     * @internal
     * @param Thread $thread
     * @return array
     */
    public static function fromThread(Thread $thread) : array
    {
        $sql = static::$selectSql." where thread_id = :threadId order by `rank` asc";
        $stmt = static::$db->query($sql, ['threadId' => $thread->id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    /**
     * @internal
     * @return void
     */
    public function save() : void
    {
        //TODO : voir pour déplacer la récupération de rank dans thread
        static::$db->getPDO()->beginTransaction();

        $sql = "SELECT COALESCE(MAX(`rank`), -1) + 1 as next_rank 
              FROM message WHERE thread_id = :threadId FOR UPDATE";
        $nextRank = static::$db->query($sql, ['threadId' => $this->thread->id])
            ->fetchColumn();

        $sql = "insert into message (thread_id, `rank`, author, content, created_at) values (:threadId, :rank, :authorId, :content, NOW())";
        static::$db->query($sql, [
            'threadId' => $this->thread->id,
            'authorId' => $this->authorInstance->id,
            'content' => $this->content,
            'rank' => $nextRank
        ]);
        $this->rank = $nextRank;
        $this->id = (int)static::$db->getPDO()->lastInsertId();
        static::$db->getPDO()->commit();
    }

    public function validate(): bool
    {
        if (empty($this->content)) {
            throw new \Exception('Le message ne peut pas être vide.');
        }

        if (mb_strlen($this->content) > 500) {
            throw new \Exception('Le message ne peut pas dépasser 500 caractères.');
        }

        return true;
    }
}