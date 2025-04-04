<?php
class DashboardController {
    private $artworkModel;
    private $warehouseModel;
    
    public function __construct() {
        $this->artworkModel = new Artwork();
        $this->warehouseModel = new Warehouse();
    }
    
    public function index() {
        // Récupérer les statistiques pour le tableau de bord
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
        
        // Charger la vue du tableau de bord
        require_once 'views/dashboard/index.php';
    }
}

