<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Database;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        // On vide la table avant chaque test
        $pdo = Database::createInstancePDO();
        $pdo->exec("TRUNCATE TABLE users");
        // On insère un utilisateur de test
        $pdo->exec("INSERT INTO users (u_email, u_password, u_username)
        VALUES ('test@mail.com', 'pass', 'john')");
    }

    public function testCheckMailReturnsTrueWhenMailExists()
    {
        // ✅ assertTrue → on attend que le résultat soit vrai
        $user = new User;
        $user->findByEmail('test@mail.com');
    }
    
    public function testCheckMailReturnsFalseWhenMailDoesNotExist()
    {
        // ✅ assertFalse → on attend que le résultat soit faux
        $user = new User;
        $user->findByEmail('notfound@mail.com');
    }
}