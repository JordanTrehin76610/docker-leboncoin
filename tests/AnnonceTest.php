<?php
use PHPUnit\Framework\TestCase;
use App\Models\Annonce;
use App\Models\Database;

class AnnonceTest extends TestCase
{
    private $userId;

    protected function setUp(): void
    {
        $this->resetTable('annonces');
        $this->resetTable('users');
        $pdo = Database::createInstancePDO();
        if ($pdo === null) {
            throw new Exception("La connexion PDO n'a pas été créée.");
        }
        
        $pdo->exec("INSERT INTO users (u_email, u_password, u_username)
        VALUES ('user@mail.com', 'pass', 'alice')");
        $this->userId = $pdo->lastInsertId();
    }
    
    protected function resetTable(string $table): void
    {
        $pdo = Database::createInstancePDO();
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
        $pdo->exec("DELETE FROM $table");
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    }

    public function testCreateAnnonceInsertsAnnonce()
    {
        $pdo = Database::createInstancePDO();
        $userId = $this->userId;
        $annonce = new Annonce();
        $result = $annonce->createAnnonce("Vélo route", "Très bon état", 150.0, "uploads/default.png", $userId, "A vendre");
        // ✅ assertTrue → vérifie que la méthode retourne bien true
        $this->assertTrue($result);
        // ✅ assertEquals → vérifie qu’il y a bien 1 annonce en BDD
        $stmt = $pdo->query("SELECT COUNT(*) FROM annonces");
        $this->assertEquals(1, $stmt->fetchColumn());
    }

    public function testFindByIdReturnsAnnonce()
    {
        $pdo = Database::createInstancePDO();
        $userId = $this->userId;
        $annonce = new Annonce();
        $annonce->createAnnonce("PC portable", "Occasion", 500.0, null, $userId, "A vendre");
        // Récupère l'id de la dernière annonce insérée pour ce user
        $stmt = $pdo->query("SELECT a_id FROM annonces WHERE u_id = $userId ORDER BY a_id DESC LIMIT 1");
        $id = $stmt->fetchColumn();
        $result = $annonce->findById($id);
        // ✅ assertNotFalse → doit retourner un tableau, pas false
        $this->assertNotFalse($result);
        // ✅ assertEquals → titre attendu
        $this->assertEquals("PC portable", $result['a_title']);
    }
}