<?php
declare(strict_types=1);
namespace model;

use PDO;
use lib\DBManager;
use lib\Utils;
use PDOException;

abstract class AbstractEntityManager
{
    public static DBManager $db;

    /**
     * Base for SQL select, without where clause. This is to avoid select fields clause repetitions
     * @var string
     */
    protected static string $selectSql;

    public function __construct(protected string $entityClass)
    {
    }

    public function fromArray(array $fieldVals) : AbstractEntity
    {
        return $this->entityClass::fromArray($fieldVals);
    }

    public function fromId(int $id) : ?AbstractEntity
    {
        $sql = static::$selectSql . " where id = :id";
        $stmt = static::$db->query($sql, ['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['id']))
            return null;
        return $this->fromArray($result);
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

    protected function isForeignKeyError(PDOException $e, string $column): bool {
        // Détecte l'erreur FK (dépend de ton SGBD)
        return str_contains($e->getMessage(), 'FOREIGN KEY')
            && str_contains($e->getMessage(), $column);
    }
}