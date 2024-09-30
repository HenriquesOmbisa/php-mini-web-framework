<?php

require './autoloader.php';

use App\Schema\Schema;

$action = $argv[1] ?? null;

switch ($action) {
    case 'migrate':
        (new Schema())->up();
        break;
    case 'rollback':
        break;
    default:
        echo "Comandos disponíveis:\n";
        echo "  migrate   - Aplica novas migrações\n";
        echo "  rollback  - Reverte as migrações\n";
        break;
}
