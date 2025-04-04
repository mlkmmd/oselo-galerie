<?php
// Contrôleur pour le tableau de bord
class DashboardController {
    private $artworkModel;
    private $warehouseModel;
    
    public function __construct() {
        $this->artworkModel = new Artwork();
        $this->warehouseModel = new Warehouse();
    }
    
    public function index() {
        // Récupérer les statistiques
        $artworks = $this->artworkModel->getAllArtworks();
        $warehouses = $this->warehouseModel->getAllWarehouses();
        
        $artworkCount = count($artworks);
        $warehouseCount = count($warehouses);
        
        // Compter les œuvres sans entrepôt
        $unassignedCount = 0;
        foreach ($artworks as $artwork) {
            if (empty($artwork['warehouse_id'])) {
                $unassignedCount++;
            }
        }
        
        // Afficher la vue
        include 'views/dashboard.php';
    }
}

// Contrôleur pour les œuvres
class ArtworkController {
    private $artworkModel;
    private $warehouseModel;
    
    public function __construct() {
        $this->artworkModel = new Artwork();
        $this->warehouseModel = new Warehouse();
    }
    
    // Liste des œuvres
    public function index() {
        $artworks = $this->artworkModel->getAllArtworks();
        include 'views/artwork_list.php';
    }
    
    // Formulaire d'ajout
    public function add() {
        $warehouses = $this->warehouseModel->getAllWarehouses();
        include 'views/artwork_form.php';
    }
    
    // Traitement de l'ajout
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $year = (int)$_POST['year'];
            $artist = trim($_POST['artist']);
            $width = (int)$_POST['width'];
            $height = (int)$_POST['height'];
            $warehouseId = !empty($_POST['warehouse_id']) ? (int)$_POST['warehouse_id'] : null;
            
            // Validation simple
            if (empty($title) || $year <= 0 || empty($artist) || $width <= 0 || $height <= 0) {
                $_SESSION['error'] = "All fields are required and dimensions must be positive numbers.";
                header('Location: index.php?page=artwork&action=add');
                exit;
            }
            
            if ($this->artworkModel->addArtwork($title, $year, $artist, $width, $height, $warehouseId)) {
                $_SESSION['success'] = "Artwork added successfully.";
                header('Location: index.php?page=artwork&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Failed to add artwork.";
                header('Location: index.php?page=artwork&action=add');
                exit;
            }
        }
    }
    
    // Formulaire de modification
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?page=artwork&action=index');
            exit;
        }
        
        $id = (int)$_GET['id'];
        $artwork = $this->artworkModel->getArtworkById($id);
        
        if (!$artwork) {
            $_SESSION['error'] = "Artwork not found.";
            header('Location: index.php?page=artwork&action=index');
            exit;
        }
        
        $warehouses = $this->warehouseModel->getAllWarehouses();
        include 'views/artwork_form.php';
    }
    
    // Traitement de la modification
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $title = trim($_POST['title']);
            $year = (int)$_POST['year'];
            $artist = trim($_POST['artist']);
            $width = (int)$_POST['width'];
            $height = (int)$_POST['height'];
            $warehouseId = !empty($_POST['warehouse_id']) ? (int)$_POST['warehouse_id'] : null;
            
            // Validation simple
            if (empty($title) || $year <= 0 || empty($artist) || $width <= 0 || $height <= 0) {
                $_SESSION['error'] = "All fields are required and dimensions must be positive numbers.";
                header("Location: index.php?page=artwork&action=edit&id=$id");
                exit;
            }
            
            if ($this->artworkModel->updateArtwork($id, $title, $year, $artist, $width, $height, $warehouseId)) {
                $_SESSION['success'] = "Artwork updated successfully.";
                header('Location: index.php?page=artwork&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Failed to update artwork.";
                header("Location: index.php?page=artwork&action=edit&id=$id");
                exit;
            }
        }
    }
    
    // Suppression
    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            if ($this->artworkModel->deleteArtwork($id)) {
                $_SESSION['success'] = "Artwork deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete artwork.";
            }
        }
        
        header('Location: index.php?page=artwork&action=index');
        exit;
    }
}

// Contrôleur pour les entrepôts
class WarehouseController {
    private $warehouseModel;
    private $artworkModel;
    
    public function __construct() {
        $this->warehouseModel = new Warehouse();
        $this->artworkModel = new Artwork();
    }
    
    // Liste des entrepôts
    public function index() {
        $warehouses = $this->warehouseModel->getAllWarehouses();
        include 'views/warehouse_list.php';
    }
    
    // Formulaire d'ajout
    public function add() {
        include 'views/warehouse_form.php';
    }
    
    // Traitement de l'ajout
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $address = trim($_POST['address']);
            
            // Validation simple
            if (empty($name) || empty($address)) {
                $_SESSION['error'] = "All fields are required.";
                header('Location: index.php?page=warehouse&action=add');
                exit;
            }
            
            if ($this->warehouseModel->addWarehouse($name, $address)) {
                $_SESSION['success'] = "Warehouse added successfully.";
                header('Location: index.php?page=warehouse&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Failed to add warehouse.";
                header('Location: index.php?page=warehouse&action=add');
                exit;
            }
        }
    }
    
    // Formulaire de modification
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?page=warehouse&action=index');
            exit;
        }
        
        $id = (int)$_GET['id'];
        $warehouse = $this->warehouseModel->getWarehouseById($id);
        
        if (!$warehouse) {
            $_SESSION['error'] = "Warehouse not found.";
            header('Location: index.php?page=warehouse&action=index');
            exit;
        }
        
        include 'views/warehouse_form.php';
    }
    
    // Traitement de la modification
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $name = trim($_POST['name']);
            $address = trim($_POST['address']);
            
            // Validation simple
            if (empty($name) || empty($address)) {
                $_SESSION['error'] = "All fields are required.";
                header("Location: index.php?page=warehouse&action=edit&id=$id");
                exit;
            }
            
            if ($this->warehouseModel->updateWarehouse($id, $name, $address)) {
                $_SESSION['success'] = "Warehouse updated successfully.";
                header('Location: index.php?page=warehouse&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Failed to update warehouse.";
                header("Location: index.php?page=warehouse&action=edit&id=$id");
                exit;
            }
        }
    }
    
    // Voir les œuvres d'un entrepôt
    public function view() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?page=warehouse&action=index');
            exit;
        }
        
        $id = (int)$_GET['id'];
        $warehouse = $this->warehouseModel->getWarehouseById($id);
        
        if (!$warehouse) {
            $_SESSION['error'] = "Warehouse not found.";
            header('Location: index.php?page=warehouse&action=index');
            exit;
        }
        
        $artworks = $this->artworkModel->getArtworksByWarehouse($id);
        include 'views/warehouse_view.php';
    }
    
    // Suppression
    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            if ($this->warehouseModel->deleteWarehouse($id)) {
                $_SESSION['success'] = "Warehouse deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete warehouse.";
            }
        }
        
        header('Location: index.php?page=warehouse&action=index');
        exit;
    }
}

