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

    protected static $searchFieldsAllowed = ['auteur', 'title', 'description', 'availabilityStatus'];

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
            'auteur' => "%$searchTerm%",
            'title' => "%$searchTerm%",
        ];
        $sql = static::buildAvailableBooksQuery($searchParams);
        return self::queryBooks($sql, $searchParams);
    }

    private static function buildAvailableBooksQuery(array $searchParams, int $limit = 0) : string
    {
        $sqlBase = static::$selectSql." where availability_status = 1 and (%s)";
        $limit = intval($limit);
        $sqlBase .= $limit > 0 ? " limit $limit" : "";
        $sqlSearchPart = [];
        $sqlSearchPart[] = "0=1"; //always false: avoid syntax error because of empty parenthesis
        foreach($searchParams as $key => $value) {
            if(!in_array($key, static::$searchFieldsAllowed)) {
                continue;
            }
            $fieldName = static::propertyToField($key);
            $sqlSearchPart[] = "$fieldName like :$key";
        }
        $sqlSearch = implode(" or ", $sqlSearchPart);
        $sql = sprintf($sqlBase, $sqlSearch);
        var_dump($sql);
        return $sql;
    }

    /*
    private function search() : array
    {
        $sql = static::$selectSql." where 1=1";
        $searchParam = [];
        foreach($this as $key => $value) {
            if(!in_array($key, $this->searchFieldsAllowed)) {
                continue;
            }
            if($value === '' || intval($value) === -1) {
                continue;
            }
            $searchParam[$key] = $value;
            $fieldName = $this->propertyToField($key);
            $sql .= " and $fieldName like :$key";
        }
        var_dump($sql);
        var_dump($searchParam);
        $stmt = static::$db->query($sql, $searchParam);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }
*/
    /**
     * @param array|string $sql
     * @return array
     */
    protected static function queryBooks(array|string $sql, array $params = []): array
    {
        $stmt = static::$db->query($sql, $params);
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
        return self::queryBooks($sql);
    }

    public static function blank() : static
    {
        return new static([
            'auteur' => '',
            'title' => '',
            'description' => '',
            'availabilityStatus' => -1,
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