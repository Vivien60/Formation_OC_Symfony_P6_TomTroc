<?php
declare(strict_types=1);
namespace lib;

use PDO;
use PDOStatement;

/**
 * Classe qui permet de se connecter à la base de données.
 * Cette classe est un singleton. Cela signifie qu'il n'est pas possible de créer plusieurs instances de cette classe.
 * Pour récupérer une instance de cette classe, il faut utiliser la méthode getInstance().
 */
class DBManager
{
    // Création d'une classe singleton qui permet de se connecter à la base de données.
    // On crée une instance de la classe DBConnect qui permet de se connecter à la base de données.
    private static ?DBManager $instance = null;

    private PDO $db;

    public static array $bddConfig;

    /**
     * Constructeur de la classe DBManager.
     * Initialise la connexion à la base de données.
     * Ce constructeur est privé. Pour récupérer une instance de la classe, il faut utiliser la méthode getInstance().
     * @see DBManager::getInstance()
     */
    private function __construct()
    {
        $conf = self::$bddConfig;
        // On se connecte à la base de données.
        $this->db = new PDO($conf['dsn'], $conf['user'], $conf['password']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * Méthode qui permet de récupérer l'instance de la classe DBManager.
     * @return DBManager
     */
    public static function getInstance(): DBManager
    {
        if (!self::$instance) {
            self::$instance = new DBManager();
        }
        return self::$instance;
    }

    /**
     * Méthode qui permet de récupérer l'objet PDO qui permet de se connecter à la base de données.
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->db;
    }

    /**
     * Méthode qui permet d'exécuter une requête SQL.
     * Si des paramètres sont passés, on utilise une requête préparée.
     * @param string $sql : la requête SQL à exécuter.
     * @param array|null $params : les paramètres de la requête SQL.
     * @return PDOStatement : le résultat de la requête SQL.
     */
    public function query(string $sql, ?array $params = null): PDOStatement
    {
        if ($params == null) {
            return $this->db->query($sql);
        }
        return $this->executeQueryWithParams($sql, $params);
    }

    /**
     * Exécute une requête SQL préparée
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    protected function executeQueryWithParams(string $sql, array $params): PDOStatement
    {
        $query = $this->db->prepare($sql);
        if (isset($params['order'])) {
            $query->bindValue(':order', $params['order'], PDO::PARAM_INT);
            unset($params['order']);
        }
        foreach ($params as $key => $value) {
            $query->bindValue(":$key", $value);
        }
        $query->execute();
        return $query;
    }
}