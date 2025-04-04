<?php
class Artwork {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer toutes les œuvres
    public function getAllArtworks() {
        $query = "SELECT a.*, w.name as warehouse_name 
                 FROM artworks a 
                 LEFT JOIN warehouses w ON a.warehouse_id = w.id 
                 ORDER BY a.title";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Récupérer une œuvre par son ID
    public function getArtworkById($id) {
        $query = "SELECT * FROM artworks WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Récupérer les œuvres d'un entrepôt spécifique
    public function getArtworksByWarehouse($warehouseId) {
        $query = "SELECT * FROM artworks WHERE warehouse_id = :warehouse_id ORDER BY title";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':warehouse_id', $warehouseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Ajouter une nouvelle œuvre
    public function addArtwork($title, $year, $artist, $width, $height, $warehouseId = null) {
        $query = "INSERT INTO artworks (title, year, artist, width, height, warehouse_id) 
                 VALUES (:title, :year, :artist, :width, :height, :warehouse_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':artist', $artist, PDO::PARAM_STR);
        $stmt->bindParam(':width', $width, PDO::PARAM_INT);
        $stmt->bindParam(':height', $height, PDO::PARAM_INT);
        $stmt->bindParam(':warehouse_id', $warehouseId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Mettre à jour une œuvre existante
    public function updateArtwork($id, $title, $year, $artist, $width, $height, $warehouseId = null) {
        $query = "UPDATE artworks 
                 SET title = :title, year = :year, artist = :artist, 
                     width = :width, height = :height, warehouse_id = :warehouse_id 
                 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':artist', $artist, PDO::PARAM_STR);
        $stmt->bindParam(':width', $width, PDO::PARAM_INT);
        $stmt->bindParam(':height', $height, PDO::PARAM_INT);
        $stmt->bindParam(':warehouse_id', $warehouseId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Assigner une œuvre à un entrepôt
    public function assignToWarehouse($id, $warehouseId) {
        $query = "UPDATE artworks SET warehouse_id = :warehouse_id WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':warehouse_id', $warehouseId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Supprimer une œuvre
    public function deleteArtwork($id) {
        $query = "DELETE FROM artworks WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

