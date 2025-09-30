<?php
use PHPUnit\Framework\TestCase;
use App\Models\Annonce;
use App\Models\Database;

class AnnonceTest extends TestCase
{

    protected function setUp(): void
    {
        
        $pdo = Database::createInstancePDO();
        if ($pdo === null) {
            throw new Exception("La connexion PDO n'a pas été créée.");
        }
        $pdo->exec("TRUNCATE TABLE annonces");
        $pdo->exec("TRUNCATE TABLE users");

        $pdo->exec("INSERT INTO users (u_email, u_password, u_username)
        VALUES ('user@mail.com', 'pass', 'alice')");
    }

    public function testCreateAnnonceInsertsAnnonce()
    {
        $pdo = Database::createInstancePDO();
        $userId = $pdo->lastInsertId();
        $annonce = new Annonce();
        $result = $annonce->createAnnonce("Vélo route", "Très bon état", 150.0, null, $userId, "A vendre");
        // ✅ assertTrue → vérifie que la méthode retourne bien true
        $this->assertTrue($result);
        // ✅ assertEquals → vérifie qu’il y a bien 1 annonce en BDD
        $stmt = $pdo->query("SELECT COUNT(*) FROM annonces");
        $this->assertEquals(1, $stmt->fetchColumn());
    }

    public function testFindByIdReturnsAnnonce()
    {
        $pdo = Database::createInstancePDO();
        $userId = $pdo->lastInsertId();
        $annonce = new Annonce();
        $annonce->createAnnonce("PC portable", "Occasion", 500.0, null, $userId, "A vendre");
        $id = $pdo->lastInsertId();
        $result = $annonce->findById($id);
        // ✅ assertNotFalse → doit retourner un tableau, pas false
        $this->assertNotFalse($result);
        // ✅ assertEquals → titre attendu
        $this->assertEquals("PC portable", $result['a_title']);
    }
}