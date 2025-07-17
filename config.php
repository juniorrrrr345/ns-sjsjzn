<?php
/**
 * Configuration de base de données - Multi-environnement
 * Adapte automatiquement selon la plateforme de déploiement
 */

// Détection automatique de l'environnement
if (isset($_ENV['DATABASE_URL'])) {
    // Configuration Heroku (ClearDB)
    $url = parse_url($_ENV['DATABASE_URL']);
    $host = $url["host"];
    $user = $url["user"];
    $pass = $url["pass"];
    $db = substr($url["path"], 1);
    
} elseif (isset($_ENV['MYSQL_URL'])) {
    // Configuration Railway
    $url = parse_url($_ENV['MYSQL_URL']);
    $host = $url["host"];
    $user = $url["user"];
    $pass = $url["pass"];
    $db = substr($url["path"], 1);
    
} elseif (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], '000webhostapp.com') !== false) {
    // Configuration 000WebHost
    // ⚠️ IMPORTANT: Remplacez ces valeurs par celles fournies par 000WebHost
    $host = 'localhost';
    $db   = 'id123456_ecommerce';  // Remplacer par votre nom de DB
    $user = 'id123456_user';       // Remplacer par votre username
    $pass = 'votre_mot_de_passe';  // Remplacer par votre password
    
} else {
    // Configuration locale (développement)
    $host = 'localhost';
    $db   = 'ecommerce';
    $user = 'root';
    $pass = '';
}

// Test de connexion et gestion d'erreur
try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        throw new Exception('Erreur de connexion: ' . $conn->connect_error);
    }
    
    // Configuration UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Log l'erreur (en production, loguer dans un fichier)
    error_log("Erreur DB: " . $e->getMessage());
    
    // Affichage d'erreur pour le développement
    if (isset($_ENV['ENVIRONMENT']) && $_ENV['ENVIRONMENT'] === 'development') {
        die("Erreur de base de données: " . $e->getMessage());
    } else {
        die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
    }
}

// Variables globales pour utilisation dans les autres fichiers
$GLOBALS['db_connection'] = $conn;
$GLOBALS['db_config'] = [
    'host' => $host,
    'database' => $db,
    'username' => $user,
    'password' => $pass
];

/**
 * Fonction helper pour obtenir la connexion DB
 */
function getDbConnection() {
    return $GLOBALS['db_connection'];
}

/**
 * Fonction helper pour exécuter des requêtes préparées
 */
function executeQuery($sql, $params = []) {
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if ($params) {
        $types = str_repeat('s', count($params)); // Assume all strings for simplicity
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt;
}
?>