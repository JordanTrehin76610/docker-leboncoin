<?php 
namespace App\Models;

use PDO;

class Database
{

    public static function getConnection(): PDO {
        $host = 'db';         // Nom du service MySQL dans Docker
        $port = 3306;         // Port MySQL
        $db   = 'leboncoin';  // Nom de la base
        $user = 'root';       // Utilisateur
        $pass = 'root';       // Mot de passe

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; //Génère le chemin de connection
        $pdo = new PDO($dsn, $user, $pass); //Fais la connection   
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Gère les erreurs
    }
   
}

?>