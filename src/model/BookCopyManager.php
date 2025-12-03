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
        parent::__construct(BookCopy::class);;
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
    public function listAvailableBookCopies(int $limit = 0): array
    {
        $searchBook = new BookCopySearch();
        $sql = $this->buildAvailableBooksQuery($searchBook, $limit);
        return self::queryBooks($sql);
    }

    /**
     * @param mixed $searchTerm
     * @param int $limit
     * @return BookCopy[]
     */
    public function searchBooksForExchange(mixed $searchTerm, int $limit = 0) : array
    {
        $searchBook = new BookCopySearch($searchTerm);
        $sql = $this->buildAvailableBooksQuery($searchBook, $limit);
        return self::queryBooks($sql, $searchBook->getSearchParams());
    }

    private function buildAvailableBooksQuery(BookCopySearch $search, int $limit = 0) : string
    {
        $sqlBase = static::$selectSql." where availability_status = %s and (%s)";
        $limit = intval($limit);
        $sqlBase .= $limit > 0 ? " limit $limit" : "";
        if(empty($searchParams)) {
            $sqlSearchPart = ["1=1"];
        } else {

            foreach($searchParams as $key => $value) {
                if(!in_array($key, $search->getSearchFieldsAllowed())) {
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
    //TODO Vivien : check if we can make generic query, queryBuilder or something else
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
        $sql = "update book_copy 
                set author = :author, title = :title, description = :description, 
                    created_at = :created_at, availability_status = :availability, 
                    image = :image, user_id = :ownerId where id = :id";
        $stmt = static::$db->query($sql, [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'description' => $book->description,
            'created_at' => $book->createdAt->format("Y-m-d H:i:s"),
            'availability' => $book->availabilityStatus,
            'ownerId' => $book->userId,
            'image' => $book->image,
        ]);
        $stmt->execute();
    }

    /**
     * @throws \Exception
     */
    public function create(BookCopy $book) : void
    {
        if(User::fromId($book->userId) === null) {
            throw new \Exception('User not found');
        }
        $sql = "insert into book_copy (author, title, description, created_at, availability_status, user_id, image) 
                values (:author, :title, :description, :created_at, :availability, :ownerId, :image)";
        $stmt = static::$db->query($sql, [
            'author' => $book->author,
            'title' => $book->title,
            'description' => $book->description,
            'created_at' => $book->createdAt->format("Y-m-d H:i:s"),
            'availability' => $book->availabilityStatus,
            'ownerId' => $book->userId,
            'image' => $book->image,
        ]);
        $book->id = (int)static::$db->getPDO()->lastInsertId();
    }

    public function delete(BookCopy $book): void
    {
        $sql = "delete from book_copy where id = :id";
        static::$db->query($sql, ['id' => $book->id]);
    }

    public function withUser(BookCopy $bookCopy) : void
    {
        Utils::trace("BookCopyManager::withUser");
        Utils::trace($bookCopy->userId);
        Utils::trace("BookCopyManager::withUser : User");
        Utils::trace(User::fromId($bookCopy->userId));
        $bookCopy->owner = User::fromId($bookCopy->userId);
    }
}