<?php
declare(strict_types=1);
namespace model;

use DateTime;
use DateTimeInterface;
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
    public array $messages = [];

    /**
     * @var Message|null $lastMessage
     */
    public ?Message $lastMessage = null;

    public DateTimeInterface|string|null $updatedAt = null {
        set {
            if(!is_subclass_of($value, 'DateTimeInterface') && $value !== null) {
                $this->updatedAt = new DateTime($value);
            } else {
                $this->updatedAt = $value;
            }
        }
    }
    /**
     * Cache of other participants : the ones who will receive messages sended by current user.
     * @var User[]
     */
    private array $otherParticipants = [];


    public function __construct(array $thread, public ?User $currentUser = null )
    {
        parent::__construct($thread);
    }

    protected function hydrate(array $data): void
    {
        parent::hydrate($data);
        if(!empty($this->currentUser)) {
            $this->otherParticipants();
        }
    }

    public static function openNewOne(array $participants) : static
    {
        $thread = new static([
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        $thread->participants = array_map(fn($participant) => User::fromId($participant), $participants);
        $thread->create();
        return $thread;
    }

    public static function openForUser(User $user, int $id, array $threads = []) : ?static
    {
        $threadToOpen = null;
        if(empty($threads)) {
            $threads = static::fromParticipant($user);
        }
        if(empty($threads)) {
            return null;
        }
        if($id === 0) {
            $threadToOpen = $threads[0];
        } else {
            foreach($threads as $thread) {
                if($thread->id === $id) {
                    $threadToOpen = $thread;
                    break;
                }
            }
        }
        $threadToOpen->getMessages();
        $threadToOpen?->markAsRead($user);
        return $threadToOpen;
    }

    public function getParticipants()
    {
        if(empty($this->participants)) {
            $sql = "select user_id from participer where thread_id = :threadId";
            $stmt = static::$db->query($sql, ['threadId' => $this->id]);
            $this->participants = array_map(fn($participant) => User::fromId($participant['user_id']), $stmt->fetchAll());
        }
        return $this->participants;
    }

    /**
     * Returns the other participants from the point of view of the user requesting thread.
     * It means it returns the people who will receive his messages.
     * @param User $userAsking
     * @return array
     */
    public function otherParticipants() : array
    {
        if(empty($this->getParticipants()) || empty($this->currentUser)) {
            return [];
        }
        if(empty($this->otherParticipants)) {
            $this->otherParticipants = array_filter($this->getParticipants(), fn($participant) => ($participant->id != $this->currentUser->id));
        }
        return $this->otherParticipants;
    }

    /**
     * List threads ordered from the most recent message received to the latest.
     * Add the last Message to the list of threads returned.
     * @param User $user
     * @return array
     */
    public static function lastThreadsUpdatedWithMessage(User $user) : array
    {
        $threads = [];
        $latestMessagesByThread = Message::latestByThreadsFor($user);
        foreach($latestMessagesByThread as $latestMessage) {
            $thread = Thread::fromId($latestMessage->threadId);
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
    public static function fromParticipant(User $user) : array
    {
        $sql = static::$selectSql." 
                    inner join participer p on thread.id = p.thread_id 
                    where p.user_id = :userId
                    order by thread.updated_at desc";
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

    public function getLastMessage() : ?Message
    {
        return $this->lastMessage;
    }

    public function getMessageAtRank(int $rank) : ?Message
    {
        $this->getMessages();
        if(empty($this->messages))
            return null;
        return $this->messages[$rank-1];
    }

    /**
     * Creates a new message within the thread.
     * Updates the thread's last update date.
     *
     * @param string $content The content of the message to be created.
     * @param User $author The author of the message
     * @return void
     */
    public function createNewMessage(string $content, User $author) : void
    {
        //TODO : Utiliser un repo pourrait permettre de laisser à Thread l'orchestration du rank
        $message = $this->createMessage($author, $content);
        $this->addMessage($message);
        $this->updateMessageStatus($message, $this->otherParticipants($author));
        $this->updateLastDateModification();
    }

    /**
     * @param User $author
     * @param string $content
     * @return Message
     */
    protected function createMessage(User $author, string $content): Message
    {
        $message = new Message([
            'threadId' => $this->id,
            'author' => $author->id,
            'content' => $content,
            'etat' => -1,
        ]);
        $message->save();
        return $message;
    }

    /**
     * @return void
     */
    protected function updateLastDateModification(): void
    {
        $this->updatedAt = date("Y-m-d H:i:s");
        $this->save();
    }

    /**
     * @param Message $message
     * @return void
     */
    protected function addMessage(Message $message): void
    {
        $this->getMessages();
        $this->messages[] = $message;
    }

    /**
     * Updates the status of a message for all participants except the author.
     * @param Message $message The message object whose status needs to be updated.
     * @param array $recipients An array of participants recipients
     * @return void
     */
    private function updateMessageStatus(Message $message, array $recipients): void
    {
        foreach($recipients as $participant) {
            $sql = "insert into message_status (user_id, message_id, status)
                    values (:userId, :messageId, 'unread') 
                    on duplicate key update status = 'unread'";
            $stmt = static::$db->query($sql, ['userId' => $participant->id, 'messageId' => $message->id]);
        }
    }

    private function store() : void
    {
        $sql = "insert into thread (created_at, updated_at) values (:created_at, :updated_at)";
        static::$db->query($sql, [
            'created_at' => $this->createdAt->format("Y-m-d H:i:s"),
            'updated_at' => $this->updatedAt->format("Y-m-d H:i:s")
        ]);
        $this->id = (int)static::$db->getPDO()->lastInsertId();
    }

    private function save()
    {
        $sql = "update thread set updated_at = :updated_at where id = :id";
        static::$db->query($sql, [
            'updated_at' => $this->updatedAt->format("Y-m-d H:i:s"),
            'id' => $this->id
        ]);
    }

    private function storeParticipants()
    {
        $sql = "insert into participer (thread_id, user_id, etat) values (:threadId, :userId, 1)";
        foreach($this->participants as $participant)
        {
            static::$db->query($sql, ['threadId' => $this->id, 'userId' => $participant->id]);
        }
    }

    private function markAsRead(User $user)
    {
        $sql = "insert into message_status (user_id, message_id, status)
                values (:userId, :messageId, 'read') 
                on duplicate key update status = 'read'";
        foreach($this->getMessages() as $message) {
            static::$db->query($sql, ['userId' => $user->id, 'messageId' => $message->id]);
        }
    }
}