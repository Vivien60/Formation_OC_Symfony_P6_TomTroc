<?php

namespace model;

use services\DBManager;

abstract class AbstractEntity
{
    protected int $id = -1;

    public static DBManager $db;   // Par défaut l'id vaut -1, ce qui permet de vérifier facilement si l'entité est nouvelle ou pas.

    /**
     * Constructeur de la classe.
     * Si un tableau associatif est passé en paramètre, on hydrate l'entité.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
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
    protected function hydrate(array $data) : void
    {
        foreach ($data as $key => $value) {
            $fieldName = str_replace('_', '', ucwords($key, '_'));
            $method = 'set' . $fieldName;
            $property = strtolower($fieldName[0]).substr($fieldName, 1);
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else if(property_exists($this, $method)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * Setter pour l'id.
     * @param int $id
     * @return void
     */
    public function setId(int $id) : void
    {
        $this->id = $id;
    }


    /**
     * Getter pour l'id.
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    abstract public static function fromId(int $id) : ?static;
    abstract public static function fromArray(array $fieldVals) : static;
}