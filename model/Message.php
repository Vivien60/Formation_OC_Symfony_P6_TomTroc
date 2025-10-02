<?php

namespace model;

use PDO;

class Message extends AbstractEntity
{
    protected static string $selectSql = "select thread_id, `rank`, author, content, created_at from message";

    /**
     * @internal
     */
    public function __construct(public Thread $thread, public int $rank, public User $author, public string $content)
    {
        parent::__construct();
    }

    /**
     * @internal
     * @param array $fieldVals
     * @return static
     * @throws \Exception
     */
    public static function fromArray(array $fieldVals) : static
    {
        //TODO : voir si on inverse : appel de fromArray depuis le constructeur
        // dans tous les cas il faut diminuer le nombre de params du constructeur
        // on peut séparer les propriétés "identifiant" le message : ici rank et thread_id
        $thread = Thread::fromId($fieldVals['thread_id']);
        $author = User::fromId($fieldVals['author_id']);
        if($thread === null) {
            throw new \Exception('Thread not found');
        }
        if($author === null) {
            throw new \Exception('Author not found in registered users');
        }
        return new static($thread, $fieldVals['rank'], $author, $fieldVals['content']);
    }

    /**
     * @internal
     * @param Thread $thread
     * @return array
     */
    public static function fromThread(Thread $thread) : array
    {
        $sql = static::$selectSql." where thread_id = :threadId order by rank asc";
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
            'authorId' => $this->author->id,
            'content' => $this->content,
            'rank' => $nextRank
        ]);
        $this->id = (int)static::$db->getPDO()->lastInsertId();
        $this->rank = $nextRank;
        static::$db->getPDO()->commit();


    }
}