<?php
declare(strict_types=1);
namespace model;

use DateTime;
use DateTimeInterface;
use PDO;
use services\DBManager;

abstract class AbstractEntityManager
{
    public static DBManager $db;

    /**
     * Base for SQL select, without where clause. This is to avoid select fields clause repetitions
     * @var string
     */
    protected static string $selectSql;

    public function __construct()
    {
    }

    public static function fromArray(array $fieldVals) : static
    {
        return new static($fieldVals);
    }

    public function fromId(int $id) : ?Thread
    {
        $sql = static::$selectSql . " where id = :id";
        $stmt = static::$db->query($sql, ['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['id']))
            return null;
        return Thread::fromArray($result);
    }

    /**
     * @return static[]
     */
    public static function all(): array
    {
        $stmt = static::$db->query(static::$selectSql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(static::fromArray(...), $result);
    }

    /**
     * Converts a camelCase property name into a snake_case field name.
     * Useful for mapping entity property names to database column names.
     * @param string $property The property name in camelCase format.
     * @return string The corresponding field name in snake_case format.
     */
    protected static function propertyToField(string $property) : string
    {
        $fieldName = preg_replace_callback('/([[:upper:]])/', fn($matches) => '_'.strtolower($matches[0]), $property);
        return $fieldName;
    }
}