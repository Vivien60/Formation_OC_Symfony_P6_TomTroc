<?php

namespace model;

use DateTime;
use PDO;
use services\DBManager;

class BookCopy extends AbstractEntity
{
    public string $auteur;
    public string $title;
    public string $description;
    public int $availabilityStatus;
    public string $image = '';
    public int $ownerId;
    protected static string $selectSql = "select id, title, auteur, availability_status, image, description, created_at, user_id from book_copy";

    protected function __construct()
    {
        parent::__construct();
    }

    public static function fromOwner(int $ownerId) : array
    {
        $sql = static::$selectSql." where user_id = :ownerId";
        $stmt = static::$db->query($sql, ['ownerId' => $ownerId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    public static function fromArray(array $fieldVals) : static
    {
        $bookCopy = parent::fromArray($fieldVals);
        $bookCopy->ownerId = $fieldVals['user_id'];
        return $bookCopy;
    }

    public function save() : void
    {
        if(!$this->checkExistId()) {
            throw new \Exception('Book not found');
        }
        $sql = "update book_copy 
                set auteur = :auteur, title = :title, description = :description, 
                    created_at = :created_at, availability_status = :availability, image = :image where id = :id";
        $stmt = static::$db->query($sql, [
            'id' => $this->id,
            'title' => $this->title,
            'auteur' => $this->auteur,
            'description' => $this->description,
            'created_at' => $this->createdAt->format("Y-m-d H:i:s"),
            'availability' => $this->availabilityStatus,
            'ownerId' => $this->ownerId,
            'image' => $this->image,
        ]);
        $stmt->execute();
    }

    /**
     * @throws \Exception
     */
    public function create() : void
    {
        if(User::fromId($this->ownerId) === null) {
            throw new \Exception('User not found');
        }
        $sql = "insert into book_copy (auteur, title, description, created_at, availability_status, user_id) 
                values (:auteur, :title, :description, :created_at, :availability, :ownerId)";
        $stmt = static::$db->query($sql, [
            'auteur' => $this->auteur,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->createdAt->format("Y-m-d H:i:s"),
            'availability' => $this->availabilityStatus,
            'ownerId' => $this->ownerId,
        ]);
        $this->id = (int)static::$db->getPDO()->lastInsertId();
    }
}