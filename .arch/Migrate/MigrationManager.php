<?php

namespace App\Migrate;

use App\Database\Database;
use App\Schema\Schema;
use PDO;

class MigrationManager extends Database
{
    protected static function createMigrationTable(): void
    {
        $db = Database::connection();
        $query = "CREATE TABLE IF NOT EXISTS migrations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            table_name VARCHAR(255) NOT NULL,
            hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($query);
    }
    protected static function existTable($table_name): bool
    {
        $db = Database::connection();
        $stm = $db->prepare("SELECT hash FROM migrations WHERE table_name = :table_name limit 1");
        $stm->execute(['table_name' => $table_name]);
        return $stm->rowCount() > 0;
    }
    protected static function hasChanges(string $table, string $currentDefinition): bool
    {
        $db = Database::connection();
        $stm = $db->prepare("SELECT hash FROM migrations WHERE table_name = :table");
        $stm->execute([':table' => $table]);

        $previousDefinition = $stm->fetchColumn();
        $currentDefinition = self::generateHash($currentDefinition);

        // Verifica se a definição mudou
        if($previousDefinition !== $currentDefinition) return true;
        return false;
    }
    private static function generateHash($data): string
    {
        return md5(serialize($data));
    }
    protected static function recordMigration(string $table_name, string $definitions): void
    {
        $db = Database::connection();
        $stm = $db->prepare("REPLACE INTO migrations (table_name, hash) VALUES (:table_name, :hash)");
        $stm->execute([':table_name' => $table_name, ':hash' => self::generateHash($definitions)]);
    }
    protected static function addMigration(string $table_name, string $definitions)
    {
        $db = Database::connection();
        $stm = $db->prepare("INSERT INTO migrations (table_name, hash) VALUES (:table_name, :hash)");
        $stm->execute([':table_name' => $table_name, ':hash' => self::generateHash($definitions)]);
    }
    protected static function createClass(string $table_name)
    {
        $upper = ucfirst($table_name);
        $file = "Orm/Models/$upper.php";
        $lines = [
            "<?php",
            "namespace App\Orm\Models;",
            "use App\Orm\Model;",
            "class $upper extends Model",
            "{",
            "   protected static string ".'$table'." = \"". $table_name ."\";",
            "}",
        ];
        $handle = fopen($file,"w");
        foreach ($lines as $line)
        { fwrite($handle, $line . "\n");}
        fclose($handle);
    }

    public static function rollback(): void
    {
        // Implementar o rollback se necessário
    }
}
