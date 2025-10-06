<?php

namespace model;

use PDO;

class Message extends AbstractEntity
{
    protected static string $selectSql = "select thread_id, `rank`, author, content, created_at from message";
    public int $author = -1;
    public int $threadId = -1;
    public int $rank = -1;
    public string $content = '';

    protected User $authorInstance;
    protected Thread $thread;

    /**
     * @internal
     */
    public function __construct(array $fieldVals)
    {
        //
        parent::__construct($fieldVals);
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
        static::$db->getPDO()->commit();
        $this->id = (int)static::$db->getPDO()->lastInsertId();
    }
}