<?php
class ArtworkController {
    private $artworkModel;
    private $warehouseModel;
    
    public function __construct() {
        $this->artworkModel = new Artwork();
        $this->warehouseModel = new Warehouse();
    }
    
    // Afficher la liste des œuvres
    public function index() {
        $artworks = $this->artworkModel->getAllArtworks();
        require_once 'views/artwork/index.php';
    }
    
    // Afficher le formulaire d'ajout d'une œuvre
    public function add() {
        $warehouses = $this->warehouseModel->getAllWarehouses();
        require_once 'views/artwork/add.php';
    }
    
    // Traiter l'ajout d'une œuvre
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
                header('Location: index.php?controller=artwork&action=add');
                exit;
            }
            
            if ($this->artworkModel->addArtwork($title, $year, $artist, $width, $height, $warehouseId)) {
                $_SESSION['success'] = "Artwork added successfully.";
                header('Location: index.php?controller=artwork&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Failed to add artwork.";
                header('Location: index.php?controller=artwork&action=add');
                exit;
            }
        }
    }
    
    // Afficher le formulaire de modification d'une œuvre
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=artwork&action=index');
            exit;
        }
        
        $id = (int)$_GET['id'];
        $artwork = $this->artworkModel->getArtworkById($id);
        
        if (!$artwork) {
            $_SESSION['error'] = "Artwork not found.";
            header('Location: index.php?controller=artwork&action=index');
            exit;
        }
        
        $warehouses = $this->warehouseModel->getAllWarehouses();
        require_once 'views/artwork/edit.php';
    }
    
    // Traiter la modification d'une œuvre
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
                header("Location: index.php?controller=artwork&action=edit&id=$id");
                exit;
            }
            
            if ($this->artworkModel->updateArtwork($id, $title, $year, $artist, $width, $height, $warehouseId)) {
                $_SESSION['success'] = "Artwork updated successfully.";
                header('Location: index.php?controller=artwork&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Failed to update artwork.";
                header("Location: index.php?controller=artwork&action=edit&id=$id");
                exit;
            }
        }
    }
    
    // Traiter l'assignation d'une œuvre à un entrepôt
    public function assign() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['artwork_id'];
            $warehouseId = !empty($_POST['warehouse_id']) ? (int)$_POST['warehouse_id'] : null;
            
            if ($this->artworkModel->assignToWarehouse($id, $warehouseId)) {
                $_SESSION['success'] = "Artwork assigned successfully.";
            } else {
                $_SESSION['error'] = "Failed to assign artwork.";
            }
            
            // Rediriger vers la page précédente ou la liste des œuvres
            $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?controller=artwork&action=index';
            header("Location: $referer");
            exit;
        }
    }
    
    // Traiter la suppression d'une œuvre
    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            if ($this->artworkModel->deleteArtwork($id)) {
                $_SESSION['success'] = "Artwork deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete artwork.";
            }
        }
        
        header('Location: index.php?controller=artwork&action=index');
        exit;
    }
}

