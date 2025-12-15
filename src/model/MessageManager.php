<?php
declare(strict_types=1);
namespace model;

use model\enum\MessageStatus;
use PDO;

class MessageManager extends AbstractEntityManager
{
    protected static string $selectSql = "select m.id, m.thread_id, m.`rank`, m.author, m.content, m.created_at from message m";

    public function __construct()
    {
        parent::__construct(Message::class);
    }

    /**
     * Get the latest message for each thread for a user, unread first
     * @param User $user
     * @return Message[]
     */
    public static function latestByThreadsFor(User $user) : array
    {
        $sql = static::$selectSql."
                    inner join participate p on m.thread_id = p.thread_id  and p.user_id = :userId
                    inner join (
                        select max(message.id) as id from message group by message.thread_id
                    ) last_message on m.id = last_message.id
                    left join message_status ms on m.id = ms.message_id and ms.user_id = :userId
                    order by ms.status asc, m.id desc";
        $stmt = static::$db->query($sql, ['userId' => $user->id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(Message::fromArray(...), $result);
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
        return array_map(Message::fromArray(...), $result);
    }

    /**
     * @param Message $message
     * @return void
     * @internal
     */
    public function save(Message $message) : void
    {
        $sql = "insert into message (thread_id, `rank`, author, content, created_at) values (:threadId, :rank, :authorId, :content, NOW())";
        static::$db->query($sql, [
            'threadId' => $message->thread->id,
            'authorId' => $message->authorInstance->id,
            'content' => $message->content,
            'rank' => $message->rank
        ]);
        $message->id = (int)static::$db->getPDO()->lastInsertId();
    }

    public function getUnreadMessagesCountForUser(User $user) : int
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
                'id' => $user->id,
                'readStatus' => MessageStatus::UNREAD->value
            ]);
        return intval($stmt->fetchColumn());
    }
}