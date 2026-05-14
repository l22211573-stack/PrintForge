<?php
// config.php
$host = 'localhost'; // Mejor 'localhost' en Linux para usar sockets
$db   = 'marketplace';
$user = 'mp_user';   // Tu usuario de la base de datos
$pass = 'gotzugg';   // Tu contraseña segura
$port = '3306';      // <-- EL PUERTO REAL DE MARIADB EN LA RASPBERRY
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
