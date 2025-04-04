<?php
class WarehouseController {
    private $warehouseModel;
    private $artworkModel;
    
    public function __construct() {
        $this->warehouseModel = new Warehouse();
        $this->artworkModel = new Artwork();
    }
    
    // Afficher la liste des entrepôts
    public function index() {
        $warehouses = $this->warehouseModel->getAllWarehouses();
        require_once 'views/warehouse/index.php';
    }
    
    // Afficher le formulaire d'ajout d'un entrepôt
    public function add() {
        require_once 'views/warehouse/add.php';
    }
    
    // Traiter l'ajout d'un entrepôt
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $address = trim($_POST['address']);
            
            // Validation simple
            if (empty($name) || empty($address)) {
                $_SESSION['error'] = "All fields are required.";
                header('Location: index.php?controller=warehouse&action=add');
                exit;
            }
            
            if ($this->warehouseModel->addWarehouse($name, $address)) {
                $_SESSION['success'] = "Warehouse added successfully.";
                header('Location: index.php?controller=warehouse&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Failed to add warehouse.";
                header('Location: index.php?controller=warehouse&action=add');
                exit;
            }
        }
    }
    
    // Afficher le formulaire de modification d'un entrepôt
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=warehouse&action=index');
            exit;
        }
        
        $id = (int)$_GET['id'];
        $warehouse = $this->warehouseModel->getWarehouseById($id);
        
        if (!$warehouse) {
            $_SESSION['error'] = "Warehouse not found.";
            header('Location: index.php?controller=warehouse&action=index');
            exit;
        }
        
        require_once 'views/warehouse/edit.php';
    }
    
    // Traiter la modification d'un entrepôt
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $name = trim($_POST['name']);
            $address = trim($_POST['address']);
            
            // Validation simple
            if (empty($name) || empty($address)) {
                $_SESSION['error'] = "All fields are required.";
                header("Location: index.php?controller=warehouse&action=edit&id=$id");
                exit;
            }
            
            if ($this->warehouseModel->updateWarehouse($id, $name, $address)) {
                $_SESSION['success'] = "Warehouse updated successfully.";
                header('Location: index.php?controller=warehouse&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Failed to update warehouse.";
                header("Location: index.php?controller=warehouse&action=edit&id=$id");
                exit;
            }
        }
    }
    
    // Afficher les œuvres d'un entrepôt spécifique
    public function view() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=warehouse&action=index');
            exit;
        }
        
        $id = (int)$_GET['id'];
        $warehouse = $this->warehouseModel->getWarehouseById($id);
        
        if (!$warehouse) {
            $_SESSION['error'] = "Warehouse not found.";
            header('Location: index.php?controller=warehouse&action=index');
            exit;
        }
        
        $artworks = $this->artworkModel->getArtworksByWarehouse($id);
        require_once 'views/warehouse/view.php';
    }
    
    // Traiter la suppression d'un entrepôt
    public function delete() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            
            if ($this->warehouseModel->deleteWarehouse($id)) {
                $_SESSION['success'] = "Warehouse deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete warehouse.";
            }
        }
        
        header('Location: index.php?controller=warehouse&action=index');
        exit;
    }
}

