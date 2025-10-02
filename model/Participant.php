<?php

namespace model;

use PDO;

class Participant extends AbstractEntity
{
    static string $selectSql = "select thread_id, user_id, etat from participer";

    public function __construct(public Thread $thread, public User $user, public string $etat = '1')
    {
        parent::__construct();
    }

    public static function fromArray(array $fieldVals) : static
    {
        return new static(
            Thread::fromId($fieldVals['thread_id']),
            User::fromId($fieldVals['user_id']),
            $fieldVals['etat']
        );
    }

    public static function fromThreadAndUser(Thread $thread, User $user) : ?static
    {
        $sql = static::$selectSql." where thread_id = :threadId and user_id = :userId";
        $stmt = static::$db->query($sql, ['threadId' => $thread->id, 'userId' => $user->id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['id']))
            return null;
        return static::fromArray($result);
    }
}