<?php
declare(strict_types=1);
namespace model;

class Message extends AbstractEntity
{
    public int $author = -1;
    public int $threadId = -1;
    public int $rank = -1;
    public string $content = '';

    public User $authorInstance {
        get {
            if(empty($this->authorInstance)) {
                $this->authorInstance = new UserManager()->fromId($this->author);
            }
            return $this->authorInstance;
        }
    }
    public Thread $thread {
        get {
            if(empty($this->thread)) {
                $this->thread = new ThreadManager()->fromId($this->threadId);
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

    public function getAuthor() : User
    {
        if(empty($this->authorInstance)) {
            $this->authorInstance = new UserManager()->fromId($this->author);
        }
        return $this->authorInstance;
    }

    public function getThread() : Thread
    {
        if(empty($this->thread)) {
            $this->thread = new ThreadManager()->fromId($this->threadId);
        }
        return $this->thread;
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