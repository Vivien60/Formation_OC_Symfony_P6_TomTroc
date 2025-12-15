<?php
declare(strict_types=1);
namespace model;

use model\enum\MessageStatus;

class ThreadManager extends AbstractEntityManager
{
    protected static string $selectSql = "select id, created_at from thread";

    public function __construct()
    {
        parent::__construct(Thread::class);
    }

    public function openNewOne(array $participants) : Thread
    {
        return Thread::create($participants);
    }

    public function loadParticipants(Thread $thread) : array
    {
        $sql = "select user_id from participate where thread_id = :threadId";
        $stmt = static::$db->query($sql, ['threadId' => $thread->id]);
        $userManager = new UserManager();
        return $userManager->fromIds(array_column($stmt->fetchAll(), 'user_id'));
    }

    /**
     * List threads ordered from the most recent message received to the latest.
     * Add the last Message to the list of threads returned.
     * @param User $user
     * @return array
     */
    public function recentThreadsWithMessage(User $user) : array
    {
        $threads = [];
        $latestMessagesByThread = MessageManager::latestByThreadsFor($user);
        foreach($latestMessagesByThread as $latestMessage) {
            $thread = $this->fromId($latestMessage->threadId);
            $thread->lastMessage = $latestMessage;
            $thread->currentUser = $user;
            $threads[] = $thread;
        }
        return $threads;
    }

    /**
     * Returns the threads the user is participating in.
     * @param User $user
     * @return Thread[]
     */
    public function fromParticipant(User $user) : array
    {
        $sql = static::$selectSql." 
                    inner join participate p on thread.id = p.thread_id 
                    where p.user_id = :userId
                    order by thread.updated_at desc";
        $stmt = static::$db->query($sql, ['userId' => $user->id]);

        return array_map(Thread::fromArray(...), $stmt->fetchAll());
    }

    public function store(Thread $thread) : void
    {
        $sql = "insert into thread (created_at, updated_at) values (:created_at, :updated_at)";
        static::$db->query($sql, [
            'created_at' => $thread->createdAt->format("Y-m-d H:i:s"),
            'updated_at' => $thread->updatedAt->format("Y-m-d H:i:s")
        ]);
        $thread->id = (int)static::$db->getPDO()->lastInsertId();
    }

    public function save(Thread $thread) : void
    {
        $sql = "update thread set updated_at = :updated_at where id = :id";
        static::$db->query($sql, [
            'updated_at' => $thread->updatedAt->format("Y-m-d H:i:s"),
            'id' => $thread->id
        ]);
    }

    public function storeParticipants(Thread $thread) : void
    {
        $sql = "insert into participate (thread_id, user_id) values (:threadId, :userId)";
        foreach($thread->participants as $participant)
        {
            static::$db->query($sql, ['threadId' => $thread->id, 'userId' => $participant->id]);
        }
    }

    public function persistReadStatusForThread(Thread $thread, User $user) : void
    {
        $sql = "insert into message_status (user_id, message_id, status)
                values (:userId, :messageId, :readStatus) 
                on duplicate key update status = :readStatus";
        foreach($thread->getMessages() as $message) {
            static::$db->query($sql, ['userId' => $user->id, 'messageId' => $message->id, 'readStatus' => MessageStatus::READ->value]);
        }
    }

    public function create(Thread $thread) : void
    {
        $this->store($thread);
        $this->storeParticipants($thread);
    }

    /**
     * Updates the status of a message for all participants except the author.
     * @param Message $message The message object whose status needs to be updated.
     * @param array $recipients An array of participants recipients
     * @return void
     */
    public function addMessageStatus(Message $message, array $recipients): void
    {
        foreach($recipients as $participant) {
            $sql = "insert into message_status (user_id, message_id, status)
                    values (:userId, :messageId, :status) 
                    on duplicate key update status = :status";
            $stmt = static::$db->query($sql, [
                'userId' => $participant->id, 'messageId' => $message->id, 'status' => MessageStatus::UNREAD->value
            ]);
        }
    }
}