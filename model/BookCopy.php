<?php

namespace model;

use DateTime;
use PDO;

class BookCopy extends AbstractEntity
{

    public string $auteur;
    public string $title;
    public string $description;
    public int $availabilityStatus;
    public string $image;
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

    public static function fromArray(array $fieldVals) : static
    {
        $bookCopy = new static;
        $bookCopy->id = $data['id']??null;
        $bookCopy->auteur = $data['auteur'];
        $bookCopy->title = $data['title'];
        $bookCopy->description = $data['description'];
        $bookCopy->image = $data['image'];
        $bookCopy->availabilityStatus = $data['availability_status'];
        $bookCopy->createdAt = isset($data['created_at'])?new DateTime($data['created_at']):null;
        $bookCopy->ownerId = $data['user_id'];
        return $bookCopy;
    }
}