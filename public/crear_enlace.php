<?php
// Carga el archivo de autoload de Composer
require __DIR__ . '/../vendor/autoload.php';

// Carga el archivo de bootstrap de Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Crea una instancia del Kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Ejecuta el comando 'storage:link'
$kernel->call('storage:link');

// Mensaje de éxito
echo "El enlace simbólico 'public/storage' ha sido creado exitosamente.";
?>
