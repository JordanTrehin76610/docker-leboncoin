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

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Database
{
    public static function createInstancePDO(): ?PDO
    {
    try {
        $dbhost = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $pdo = new PDO(
            "mysql:host=$dbhost;dbname=$dbname;charset=utf8",
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
        [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
        );
        return $pdo;
        } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();

        return null;
        }
    }
}
?>