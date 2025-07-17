<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database credentials
$host = 'localhost';
$db   = 'ecommerce';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search = isset($_GET['search']) ? $_GET['search'] : '';

switch($method) {
    case 'GET':
        if ($id) {
            // Récupérer un produit spécifique
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Produit non trouvé']);
            }
        } else {
            // Récupérer tous les produits avec pagination et recherche
            $offset = ($page - 1) * $limit;
            
            if ($search) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE product_name LIKE ? OR description LIKE ? LIMIT ? OFFSET ?");
                $searchTerm = "%$search%";
                $stmt->execute([$searchTerm, $searchTerm, $limit, $offset]);
            } else {
                $stmt = $pdo->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
                $stmt->execute([$limit, $offset]);
            }
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Compter le total pour la pagination
            if ($search) {
                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE product_name LIKE ? OR description LIKE ?");
                $countStmt->execute([$searchTerm, $searchTerm]);
            } else {
                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products");
                $countStmt->execute();
            }
            $total = $countStmt->fetchColumn();
            
            echo json_encode([
                'products' => $products,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ]);
        }
        break;
        
    case 'POST':
        // Créer un nouveau produit
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['product_name']) || !isset($input['price'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Données manquantes']);
            break;
        }
        
        $stmt = $pdo->prepare("INSERT INTO products (product_name, price, description, category, country, weight) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $input['product_name'],
            $input['price'],
            $input['description'] ?? '',
            $input['category'] ?? '',
            $input['country'] ?? '',
            $input['weight'] ?? ''
        ]);
        
        $newId = $pdo->lastInsertId();
        echo json_encode(['id' => $newId, 'message' => 'Produit créé avec succès']);
        break;
        
    case 'PUT':
        // Mettre à jour un produit
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID du produit requis']);
            break;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['error' => 'Données manquantes']);
            break;
        }
        
        $stmt = $pdo->prepare("UPDATE products SET product_name = ?, price = ?, description = ?, category = ?, country = ?, weight = ? WHERE id = ?");
        $stmt->execute([
            $input['product_name'],
            $input['price'],
            $input['description'] ?? '',
            $input['category'] ?? '',
            $input['country'] ?? '',
            $input['weight'] ?? '',
            $id
        ]);
        
        echo json_encode(['message' => 'Produit mis à jour avec succès']);
        break;
        
    case 'DELETE':
        // Supprimer un produit
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID du produit requis']);
            break;
        }
        
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['message' => 'Produit supprimé avec succès']);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
        break;
}
?>