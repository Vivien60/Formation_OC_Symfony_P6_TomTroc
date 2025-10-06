<?php

namespace model;

use DateTime;
use PDO;
use services\DBManager;

abstract class AbstractEntity
{
    public int $id = -1; // Par défaut l'id vaut -1, ce qui permet de vérifier
    // facilement si l'entité est nouvelle ou pas.
    public DateTime|string|null $createdAt {
        set {
            if(!is_a($value, ' DateTimeInterface') && $value !== null) {
                $this->createdAt = new DateTime($value);
            } else {
                $this->createdAt = $value;
            }
        }
    }
    public static DBManager $db;

    /**
     * Base for SQL select, without where clause. This is to avoid select fields clause repetitions
     * @var string
     */
    protected static string $selectSql;

    public function __construct($data)
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    /**
     * Système d'hydratation de l'entité.
     * Permet de transformer les données d'un tableau associatif.
     * Les noms de champs de la table doivent correspondre aux noms des attributs de l'entité.
     * Les underscore sont transformés en camelCase (ex: date_creation devient setDateCreation).
     * @return void
     */
    public function hydrate(array $data) : void
    {
        foreach ($data as $key => $value) {
            $fieldName = str_replace('_', '', ucwords($key, '_'));
            $method = 'set' . $fieldName;
            $property = strtolower($fieldName[0]).substr($fieldName, 1);
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else if(property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    public static function fromArray(array $fieldVals) : static
    {
        return new static($fieldVals);
    }

    public static function fromId(int $id) : ?static
    {
        $sql = static::$selectSql . " where id = :id";
        $stmt = static::$db->query($sql, ['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result['id']))
            return null;
        return static::fromArray($result);
    }
}