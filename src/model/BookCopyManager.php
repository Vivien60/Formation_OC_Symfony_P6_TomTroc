<?php
declare(strict_types=1);
namespace model;

use model\enum\BookAvailabilityStatus;
use PDO;
use lib\Utils;

class BookCopyManager extends AbstractEntityManager
{
    protected static string $selectSql = "select id, title, author, availability_status, image, description, created_at, user_id from book_copy";

    public function __construct()
    {
        parent::__construct(BookCopy::class);
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
     * @param mixed $searchTerm
     * @param int $limit
     * @return BookCopy[]
     */
    public function searchBooksForExchange(BookCopySearch $searchBook, int $limit = 0) : array
    {
        $sql = $this->buildAvailableBooksQuery($searchBook->getSearchFieldsAllowed(), $searchBook->getSearchParams(), $limit);
        return self::queryBooks($sql, $searchBook->getSearchParams());
    }

    private function buildAvailableBooksQuery(array $searchFieldsAllowed, array $searchParams, int $limit = 0) : string
    {
        $sqlBase = $this->buildMainSqlSearch($limit);
        $sqlSearch = $this->getSqlSearchFilter($searchParams, $searchFieldsAllowed);
        return sprintf($sqlBase, BookAvailabilityStatus::AVAILABLE->value, $sqlSearch);
    }

    /**
     * @param int $limit
     * @return string
     */
    protected function buildMainSqlSearch(int $limit): string
    {
        $sqlBase = static::$selectSql . " where availability_status = %s and (%s)";
        $limit = intval($limit);
        $sqlBase .= $limit > 0 ? " limit $limit" : "";
        return $sqlBase;
    }

    /**
     * @param array $searchParams
     * @param array $searchFieldsAllowed
     * @return string
     */
    protected function getSqlSearchFilter(array $searchParams, array $searchFieldsAllowed): string
    {
        $sqlSearchPart = [];
        if (!empty($searchParams)) {
            foreach ($searchParams as $key => $value) {
                if (!in_array($key, $searchFieldsAllowed)) {
                    continue;
                }
                $fieldName = static::propertyToField($key);
                $sqlSearchPart[] = "$fieldName like :$key";
            }
        }
        if(empty($sqlSearchPart)) {
            $sqlSearchPart = ["1=1"];
        }

        return implode(" or ", $sqlSearchPart);
    }

    public function fromOwner(User $owner) : array
    {
        $sql = static::$selectSql." where user_id = :ownerId";
        return self::queryBooks($sql, ['ownerId' => $owner->id]);
    }

    /**
     * @param array|string $sql
     * @param array $params
     * @return array|BookCopy[]
     */
    protected static function queryBooks(array|string $sql, array $params = []): array
    {
        $stmt = static::$db->query($sql, $params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(BookCopy::fromArray(...), $result);
    }

    public function save(BookCopy $book) : void
    {
        if(!static::fromId($book->id)) {
            throw new \Exception('Book not found');
        }
        $sql = $this->updateSql();
        $stmt = static::$db->query($sql, $this->getBindValues($book));
        $stmt->execute();
    }

    /**
     * @throws \Exception
     */
    public function create(BookCopy $book) : void
    {
        try {
            $sql = $this->insertSql();
            $stmt = static::$db->query($sql, $this->getBindValues($book));
            $this->setNewId($book);
        } catch (\PDOException $e) {
            if ($this->isForeignKeyError($e, 'user_id')) {
                throw new \Exception('User not found');
            }
            throw $e;
        }
    }

    public function delete(BookCopy $book): void
    {
        $sql = "delete from book_copy where id = :id";
        static::$db->query($sql, ['id' => $book->id]);
    }

    /**
     * @param BookCopy $book
     * @return array
     */
    protected function getBindValues(BookCopy $book): array
    {
        $values = [
            'author' => $book->author,
            'title' => $book->title,
            'description' => $book->description,
            'created_at' => $book->createdAt->format("Y-m-d H:i:s"),
            'availability' => $book->availabilityStatus,
            'ownerId' => $book->userId,
            'image' => $book->image,
        ];
        if($book->id > 0) {
            $values['id'] = $book->id;
        }
        return $values;
    }

    /**
     * @param BookCopy $book
     * @return void
     */
    protected function setNewId(BookCopy $book): void
    {
        $book->id = (int)static::$db->getPDO()->lastInsertId();
    }

    /**
     * @return string
     */
    protected function updateSql(): string
    {
        $sql = "update book_copy 
                set author = :author, title = :title, description = :description, 
                    created_at = :created_at, availability_status = :availability, 
                    image = :image, user_id = :ownerId where id = :id";
        return $sql;
    }

    /**
     * @return string
     */
    protected function insertSql(): string
    {
        $sql = "insert into book_copy (author, title, description, created_at, availability_status, user_id, image) 
                values (:author, :title, :description, :created_at, :availability, :ownerId, :image)";
        return $sql;
    }
}