<?php
// Configuration pour résoudre l'erreur HTTP 413 et gérer les uploads de vidéos

// Augmenter les limites d'upload pour les vidéos
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '52M');
ini_set('max_execution_time', 300); // 5 minutes
ini_set('max_input_time', 300);
ini_set('memory_limit', '256M');

// Headers pour éviter les timeouts
set_time_limit(300);

// Configuration CORS si nécessaire
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gérer les requêtes OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * Fonction pour vérifier et traiter les uploads de médias
 */
function handleMediaUpload($fileInput, $allowedTypes = [], $maxSize = 50 * 1024 * 1024) {
    $response = [
        'success' => false,
        'message' => '',
        'filename' => '',
        'url' => ''
    ];
    
    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] === UPLOAD_ERR_NO_FILE) {
        $response['message'] = 'Aucun fichier sélectionné';
        return $response;
    }
    
    $file = $_FILES[$fileInput];
    
    // Vérifier les erreurs d'upload
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $response['message'] = 'Le fichier est trop volumineux. Taille maximum: ' . formatBytes($maxSize);
            return $response;
        case UPLOAD_ERR_PARTIAL:
            $response['message'] = 'Le fichier n\'a été que partiellement téléchargé';
            return $response;
        case UPLOAD_ERR_NO_TMP_DIR:
            $response['message'] = 'Dossier temporaire manquant';
            return $response;
        case UPLOAD_ERR_CANT_WRITE:
            $response['message'] = 'Impossible d\'écrire le fichier sur le disque';
            return $response;
        default:
            $response['message'] = 'Erreur inconnue lors du téléchargement';
            return $response;
    }
    
    // Vérifier la taille du fichier
    if ($file['size'] > $maxSize) {
        $response['message'] = 'Le fichier est trop volumineux. Taille maximum: ' . formatBytes($maxSize);
        return $response;
    }
    
    // Vérifier le type MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
        $response['message'] = 'Type de fichier non autorisé. Types acceptés: ' . implode(', ', $allowedTypes);
        return $response;
    }
    
    // Générer un nom de fichier unique
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    
    // Définir le dossier de destination
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $destination = $uploadDir . $filename;
    
    // Déplacer le fichier
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $response['success'] = true;
        $response['message'] = 'Fichier téléchargé avec succès';
        $response['filename'] = $filename;
        $response['url'] = $destination;
    } else {
        $response['message'] = 'Erreur lors du déplacement du fichier';
    }
    
    return $response;
}

/**
 * Formater la taille en bytes
 */
function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

/**
 * API endpoint pour traiter les uploads de produits
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_product') {
    
    $response = [
        'success' => false,
        'message' => '',
        'data' => []
    ];
    
    try {
        // Types de fichiers autorisés
        $imageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $videoTypes = ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo'];
        
        $productData = [
            'name' => $_POST['name'] ?? '',
            'category' => $_POST['category'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0,
            'imageLink' => $_POST['imageLink'] ?? '',
            'videoLink' => $_POST['videoLink'] ?? ''
        ];
        
        // Traiter l'upload d'image
        if (isset($_FILES['productImage'])) {
            $imageResult = handleMediaUpload('productImage', $imageTypes, 5 * 1024 * 1024); // 5MB max
            if ($imageResult['success']) {
                $productData['imagePath'] = $imageResult['url'];
            } else if ($_FILES['productImage']['error'] !== UPLOAD_ERR_NO_FILE) {
                throw new Exception($imageResult['message']);
            }
        }
        
        // Traiter l'upload de vidéo
        if (isset($_FILES['productVideo'])) {
            $videoResult = handleMediaUpload('productVideo', $videoTypes, 50 * 1024 * 1024); // 50MB max
            if ($videoResult['success']) {
                $productData['videoPath'] = $videoResult['url'];
            } else if ($_FILES['productVideo']['error'] !== UPLOAD_ERR_NO_FILE) {
                throw new Exception($videoResult['message']);
            }
        }
        
        // Ici, vous pouvez ajouter la logique pour sauvegarder en base de données
        // Example: saveProductToDatabase($productData);
        
        $response['success'] = true;
        $response['message'] = 'Produit sauvegardé avec succès';
        $response['data'] = $productData;
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        http_response_code(400);
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

/**
 * Fonction de validation d'URL
 */
function validateMediaUrl($url, $type = 'image') {
    if (empty($url)) return true; // URL optionnelle
    
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    
    // Vérifications supplémentaires selon le type
    $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    
    if ($type === 'image') {
        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    } elseif ($type === 'video') {
        return in_array($extension, ['mp4', 'webm', 'mov', 'avi']);
    }
    
    return false;
}

/**
 * Créer un htaccess pour optimiser les uploads
 */
function createHtaccessForUploads() {
    $htaccessContent = '
# Configuration pour optimiser les uploads de vidéos
<IfModule mod_php.c>
    php_value upload_max_filesize 50M
    php_value post_max_size 52M
    php_value max_execution_time 300
    php_value max_input_time 300
    php_value memory_limit 256M
</IfModule>

# Protection du dossier uploads
<Files "*.php">
    Order Allow,Deny
    Deny from all
</Files>

# Optimisation du cache pour les médias
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/webp "access plus 1 month"
    ExpiresByType video/mp4 "access plus 1 week"
    ExpiresByType video/webm "access plus 1 week"
</IfModule>
';
    
    $uploadsDir = 'uploads/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }
    
    file_put_contents($uploadsDir . '.htaccess', $htaccessContent);
}

// Créer le fichier .htaccess si nécessaire
if (!file_exists('uploads/.htaccess')) {
    createHtaccessForUploads();
}

?>