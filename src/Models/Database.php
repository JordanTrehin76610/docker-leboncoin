<?php 
// namespace App\Models;

// use PDO;

// class Database
// {
//     public static function getConnection(): PDO {
//         $host = 'db';         // Nom du service MySQL dans Docker
//         $port = 3306;         // Port MySQL
//         $db   = 'leboncoin';  // Nom de la base
//         $user = 'root';       // Utilisateur
//         $pass = 'root';       // Mot de passe

//         $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; //Génère le chemin de connection
//         $pdo = new PDO($dsn, $user, $pass); //Fais la connection   
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Gère les erreurs

//         return $pdo;
//     }
// }
namespace App\Models;
use PDO;
use PDOException;
use Dotenv\Dotenv;


class Database
{
    public static function createInstancePDO(): PDO|null
    {
        try {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $dbhost = $_ENV['DB_HOST'];
        $db_user = $_ENV['DB_USER'];
        $db_password = $_ENV['DB_PASSWORD'];
        $dbname = $_ENV['APP_ENV'] === 'test' ? $_ENV['DB_NAME_TEST'] : $_ENV['DB_NAME_DEV'];
        $pdo = new PDO(
            "mysql:host=$dbhost;dbname=$dbname;charset=utf8",
            $db_user,
            $db_password
        );
        // Mode ERRMODE_EXCEPTION uniquement en dev
        if ($_ENV['APP_ENV'] === 'dev') {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        return $pdo;

        } catch (PDOException $e) {
        return null;
        }
    }
}
?>