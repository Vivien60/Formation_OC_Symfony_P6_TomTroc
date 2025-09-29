<?php

namespace model;

use DateTime;
use PDO;
use services\DBManager;

class BookCopy
{

    public static DBManager $db;
    public int $id = -1;
    public string $auteur;
    public string $title;
    public string $description;
    public int $availabilityStatus;
    public string $image = '';
    public ?DateTime $createdAt;
    public int $ownerId;

    public static function fromId(int $id) : ?static
    {
        $sql = "select id, title, auteur, availability_status, image, description, created_at, user_id from book_copy where id = :id";
        $stmt = static::$db->query($sql, ['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['id']))
            return null;
        return static::fromArray($result);
    }

    public static function fromOwner(int $ownerId) : array
    {
        $sql = "select id, title, auteur, availability_status, image, description, created_at, user_id from book_copy where user_id = :ownerId";
        $stmt = static::$db->query($sql, ['ownerId' => $ownerId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    public static function fromArray(array $fieldVals) : static
    {
        $bookCopy = new static;
        $bookCopy->id = $fieldVals['id']??-1;
        $bookCopy->auteur = $fieldVals['auteur'];
        $bookCopy->title = $fieldVals['title'];
        $bookCopy->description = $fieldVals['description'];
        $bookCopy->image = $fieldVals['image'];
        $bookCopy->availabilityStatus = $fieldVals['availability_status'];
        $bookCopy->createdAt = isset($fieldVals['created_at'])?new DateTime($fieldVals['created_at']):null;
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

    private function checkExistId() : bool
    {
        $stmt = static::$db->query("select count(*) as nb from user where id = :id", ['id' => $this->id]);
        $nb = $stmt->fetchColumn();
        return $nb > 0;
    }
}