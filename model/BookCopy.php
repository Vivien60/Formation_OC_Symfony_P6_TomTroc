<?php
declare(strict_types=1);
namespace model;

use DateTime;
use model\enum\BookAvailabilityStatus;
use PDO;
use services\DBManager;
use services\Utils;

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
    public string $author = '';
    public string $title = '';
    public string $description = '';
    public BookAvailabilityStatus|int $availabilityStatus = -1 {
        set {
            if(is_int($value)) {
                $status = BookAvailabilityStatus::from($value);
            } else {
                $status = $value;
            }
            $this->availabilityStatus = $status->value;
            $this->availabilityStatusLabel = $status->label();
        }
    }
    public string $availabilityStatusLabel = '';
    public string $image = '';
    public int $ownerId = -1;
    protected static string $selectSql = "select id, title, author, availability_status, image, description, created_at, user_id from book_copy";

    protected static $searchFieldsAllowed = ['author', 'title', 'description', 'availabilityStatus'];

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
        return self::queryBooks($sql);
    }

    /**
     * @return static[]
     */
    public static function listAvailableBookCopies(int $limit = 0): array
    {
        $sql = static::buildAvailableBooksQuery([], $limit);
        return self::queryBooks($sql);
    }

    public static function searchBooksForExchange(mixed $searchTerm, int $limit = 0)
    {
        $searchParams = [
            'author' => "%$searchTerm%",
            'title' => "%$searchTerm%",
        ];
        $sql = static::buildAvailableBooksQuery($searchParams);
        return self::queryBooks($sql, $searchParams);
    }

    private static function buildAvailableBooksQuery(array $searchParams, int $limit = 0) : string
    {
        $sqlBase = static::$selectSql." where availability_status = %s and (%s)";
        $limit = intval($limit);
        $sqlBase .= $limit > 0 ? " limit $limit" : "";
        if(empty($searchParams)) {
            $sqlSearchPart = ["1=1"];
        } else {
            foreach($searchParams as $key => $value) {
                if(!in_array($key, static::$searchFieldsAllowed)) {
                    continue;
                }
                $fieldName = static::propertyToField($key);
                $sqlSearchPart[] = "$fieldName like :$key";
            }
        }
        $sqlSearch = implode(" or ", $sqlSearchPart);
        $sql = sprintf($sqlBase, BookAvailabilityStatus::AVAILABLE->value, $sqlSearch);
        return $sql;
    }

    public function modify(array $fieldVals) : void
    {
        $this->hydrate($fieldVals);
    }

    protected function hydrate(array $fieldVals) : void
    {
        parent::hydrate($fieldVals);
        //WORKAROUND FOR IMAGE SAVED WITH PATH
        $this->image = basename($this->image)?:'default.png';
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
        return self::queryBooks($sql, ['ownerId' => $owner->id]);
    }

    public static function blank() : static
    {
        return new static([
            'author' => '',
            'title' => '',
            'description' => '',
            'image' => '',
            'ownerId' => -1,
        ]);
    }

    public function save() : void
    {
        if(!static::fromId($this->id)) {
            throw new \Exception('Book not found');
        }
        $sql = "update book_copy 
                set author = :author, title = :title, description = :description, 
                    created_at = :created_at, availability_status = :availability, 
                    image = :image, user_id = :ownerId where id = :id";
        $stmt = static::$db->query($sql, [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
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
        $sql = "insert into book_copy (author, title, description, created_at, availability_status, user_id, image) 
                values (:author, :title, :description, :created_at, :availability, :ownerId, :image)";
        $stmt = static::$db->query($sql, [
            'author' => $this->author,
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


    /**
     * @param array|string $sql
     * @param array $params
     * @return array
     */
    protected static function queryBooks(array|string $sql, array $params = []): array
    {
        $stmt = static::$db->query($sql, $params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    public function validate(): bool
    {
        if (empty($this->title) || strlen($this->title) > 255) {
            throw new \Exception('Le titre est obligatoire et ne doit pas dépasser 255 caractères');
        }

        if (empty($this->author) || strlen($this->author) > 255) {
            throw new \Exception('L\'auteur est obligatoire et ne doit pas dépasser 255 caractères');
        }

        if (strlen($this->description) > 1000) {
            throw new \Exception('La description ne doit pas dépasser 1000 caractères');
        }

        return true;
    }
}