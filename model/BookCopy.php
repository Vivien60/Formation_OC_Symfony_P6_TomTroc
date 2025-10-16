<?php
declare(strict_types=1);
namespace model;

use DateTime;
use PDO;
use services\DBManager;

class BookCopy extends AbstractEntity
{
    public ?User $owner = null {
        set {
            $this->owner = $value;
            $this->ownerId = $value->id;
        }
        get {
            if(empty($this->owner) && $this->ownerId > 0) {
                $this->owner = User::fromId($this->ownerId);
            }
            return $this->owner;
        }
    }
    public string $auteur = '';
    public string $title = '';
    public string $description = '';
    public int $availabilityStatus = -1 {
        set {
           $this->availabilityStatus = $value;
           $this->availabilityLibelle = $value? 'Disponible' : 'Indisponible';
        }
    }
    public string $availabilityLibelle = '';
    public string $image = '';
    public int $ownerId = -1;
    protected static string $selectSql = "select id, title, auteur, availability_status, image, description, created_at, user_id from book_copy";

    protected function __construct(array $fieldVals)
    {
        parent::__construct($fieldVals);
    }

    /**
     * @return static[]
     */
    public static function all(): array
    {
        $sql = static::$selectSql;
        $stmt = static::$db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    /**
     * @return static[]
     */
    public static function listAvailableBookCopies(): array
    {
        $sql = static::$selectSql." where availability_status = 1";
        $stmt = static::$db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    public function modify(array $fieldVals) : void
    {
        $this->hydrate($fieldVals);
    }

    protected function hydrate(array $fieldVals) : void
    {
        parent::hydrate($fieldVals);
    }

    public static function fromArray(array $fieldVals) : static
    {
        $bookCopy = parent::fromArray($fieldVals);
        if(isset($fieldVals['user_id']) && $bookCopy->ownerId <= 0 )
            $bookCopy->ownerId = $fieldVals['user_id'];
        return $bookCopy;
    }

    public static function fromOwner(User $owner) : array
    {
        $sql = static::$selectSql." where user_id = :ownerId";
        $stmt = static::$db->query($sql, ['ownerId' => $owner->id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    public function save() : void
    {
        if(!static::fromId($this->id)) {
            throw new \Exception('Book not found');
        }
        $sql = "update book_copy 
                set auteur = :auteur, title = :title, description = :description, 
                    created_at = :created_at, availability_status = :availability, 
                    image = :image, user_id = :ownerId where id = :id";
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
        $sql = "insert into book_copy (auteur, title, description, created_at, availability_status, user_id, image) 
                values (:auteur, :title, :description, :created_at, :availability, :ownerId, :image)";
        $stmt = static::$db->query($sql, [
            'auteur' => $this->auteur,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->createdAt->format("Y-m-d H:i:s"),
            'availability' => $this->availabilityStatus,
            'ownerId' => $this->ownerId,
            'image' => $this->image,
        ]);
        $this->id = (int)static::$db->getPDO()->lastInsertId();
    }

    public function delete(): void
    {
        $sql = "delete from book_copy where id = :id";
        static::$db->query($sql, ['id' => $this->id]);
    }
}