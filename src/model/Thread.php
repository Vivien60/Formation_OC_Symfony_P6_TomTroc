<?php
declare(strict_types=1);
namespace model;

use DateTime;
use DateTimeInterface;

class Thread extends AbstractEntity
{
    protected static string $selectSql = "select id, created_at from thread";
    /**
     * @var User[] $participants
     */
    public ?array $participants = null {
        get {
            if($this->participants === null)
                $this->loadParticipants();
            return $this->participants;
        }
    }
    /**
     * Cache of other participants: the ones who will receive messages sended by current user.
     * @var User[]
     */
    public ?array $otherParticipants = null {
        get {
            if($this->otherParticipants === null) {
                $this->filterOtherParticipants();
            }
            return $this->otherParticipants;
        }
    }
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


    public function __construct(array $thread, public ?User $currentUser = null )
    {
        parent::__construct($thread);
    }

    protected function hydrate(array $data): void
    {
        parent::hydrate($data);
    }

    public static function create(array $participants) : static
    {
        $thread = new static([
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        $thread->participants = array_map(fn($participant) => User::fromId($participant), $participants);
        static::getManager()->create($thread);
        return $thread;
    }

    public static function openForUser(User $user, int $id, array $threads = []) : ?static
    {
        $threadToOpen = null;
        if($id > 0) {
            $threadToOpen = static::findFromArrayAndRef($threads, $id);
        }
        if($threadToOpen === null) {
            $threadToOpen = static::defaultThreadToOpen($user, $threads);
        }
        $threadToOpen->currentUser = $user;
        $threadToOpen->markAsRead($user);
        return $threadToOpen;
    }

    protected static function defaultThreadToOpen(User $user, array $threads) : ?static
    {
        if(empty($threads)) {
            $threads = static::getManager()->fromParticipant($user);
        }
        return empty($threads) ? null : $threads[0];
    }

    /**
     * @param array $threads
     * @param int $id
     * @return Thread|null
     */
    protected static function findFromArrayAndRef(array $threads, int $id): ?static
    {
        $threadToOpen = null;
        foreach ($threads as $thread) {
            if ($thread->id === $id) {
                $threadToOpen = $thread;
                break;
            }
        }
        return $threadToOpen;
    }

    /**
     * @param User $user
     * @return void
     */
    protected function markAsRead(User $user): void
    {
        static::getManager()->persistReadStatusForThread($this, $user);
    }

    /**
     * @return array|User[]|null[]
     */
    private function loadParticipants() : array
    {
        $this->participants = self::getManager()->loadParticipants($this);
        return $this->participants;
    }

    /**
     * Load the other participants from the point of view of the user requesting thread.
     * It means it will contain the people who will receive his messages.
     */
    public function filterOtherParticipants() : array
    {
        if(empty($this->currentUser)) {
            $this->otherParticipants = [];
        } else {
            $this->otherParticipants = array_values(array_filter($this->participants, fn($participant) => ($participant->id != $this->currentUser->id)));
        }
        return $this->otherParticipants;
    }

    public function getMessages() : array
    {
        if(empty($this->messages)) {
            $this->messages = MessageManager::fromThread($this);
        }
        return $this->messages;
    }

    public function getLastMessage() : ?Message
    {
        return $this->lastMessage;
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
        $message = $this->createMessage($author, $content);
        $this->addMessage($message);
        static::getManager()->addMessageStatus($message, $this->filterOtherParticipants($author));
        $this->updateLastDateModification();
    }

    /**
     * @param User $author
     * @param string $content
     * @return Message
     * @throws \Exception
     */
    protected function createMessage(User $author, string $content): Message
    {
        //Prévoir, lors du passage à l'échelle, que le rank pourra être en double. Normalement, pas besoin d'afficher un message en séparé, mais bon.
        $message = new Message([
            'threadId' => $this->id,
            'author' => $author->id,
            'content' => $content,
            'status' => -1,
            'rank' => count($this->getMessages()) + 1,
        ]);
        $message->validate();
        $manager = new MessageManager();
        $manager->save($message);
        return $message;
    }

    /**
     * @return void
     */
    protected function updateLastDateModification(): void
    {
        $this->updatedAt = date("Y-m-d H:i:s");
        static::getManager()->save($this);
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

    public function validate(): bool
    {
        return true;
    }

    /**
     * @return ThreadManager
     */
    protected static function getManager(): AbstractEntityManager
    {
        return static::$manager;
    }
}