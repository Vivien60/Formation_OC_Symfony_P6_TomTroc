<?php
declare(strict_types=1);
namespace model;

use PDO;

class Participant extends AbstractEntity
{
    static string $selectSql = "select thread_id, user_id, etat from participer";

    public int $etat = 1;

    public function __construct(public Thread $thread, public User $user, array $fieldVals = [])
    {
        parent::__construct($fieldVals);
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

    public function create() : void
    {

    }
}