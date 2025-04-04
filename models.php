<?php
// Modèle pour les œuvres d'art
class Artwork {
    // Récupérer toutes les œuvres
    public function getAllArtworks() {
        $db = getDbConnection();
        $query = "SELECT a.*, w.name as warehouse_name 
                 FROM artworks a 
                 LEFT JOIN warehouses w ON a.warehouse_id = w.id 
                 ORDER BY a.title";
        $stmt = $db->query($query);
        return $stmt->fetchAll();
    }
    
    // Récupérer une œuvre par son ID
    public function getArtworkById($id) {
        $db = getDbConnection();
        $query = "SELECT * FROM artworks WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Récupérer les œuvres d'un entrepôt
    public function getArtworksByWarehouse($warehouseId) {
        $db = getDbConnection();
        $query = "SELECT * FROM artworks WHERE warehouse_id = :warehouse_id ORDER BY title";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':warehouse_id', $warehouseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Ajouter une œuvre
    public function addArtwork($title, $year, $artist, $width, $height, $warehouseId = null) {
        $db = getDbConnection();
        $query = "INSERT INTO artworks (title, year, artist, width, height, warehouse_id) 
                 VALUES (:title, :year, :artist, :width, :height, :warehouse_id)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':artist', $artist, PDO::PARAM_STR);
        $stmt->bindParam(':width', $width, PDO::PARAM_INT);
        $stmt->bindParam(':height', $height, PDO::PARAM_INT);
        $stmt->bindParam(':warehouse_id', $warehouseId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Mettre à jour une œuvre
    public function updateArtwork($id, $title, $year, $artist, $width, $height, $warehouseId = null) {
        $db = getDbConnection();
        $query = "UPDATE artworks 
                 SET title = :title, year = :year, artist = :artist, 
                     width = :width, height = :height, warehouse_id = :warehouse_id 
                 WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':artist', $artist, PDO::PARAM_STR);
        $stmt->bindParam(':width', $width, PDO::PARAM_INT);
        $stmt->bindParam(':height', $height, PDO::PARAM_INT);
        $stmt->bindParam(':warehouse_id', $warehouseId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Supprimer une œuvre
    public function deleteArtwork($id) {
        $db = getDbConnection();
        $query = "DELETE FROM artworks WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

// Modèle pour les entrepôts
class Warehouse {
    // Récupérer tous les entrepôts
    public function getAllWarehouses() {
        $db = getDbConnection();
        $query = "SELECT w.*, COUNT(a.id) as artwork_count 
                 FROM warehouses w 
                 LEFT JOIN artworks a ON w.id = a.warehouse_id 
                 GROUP BY w.id 
                 ORDER BY w.name";
        $stmt = $db->query($query);
        return $stmt->fetchAll();
    }
    
    // Récupérer un entrepôt par son ID
    public function getWarehouseById($id) {
        $db = getDbConnection();
        $query = "SELECT * FROM warehouses WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Ajouter un entrepôt
    public function addWarehouse($name, $address) {
        $db = getDbConnection();
        $query = "INSERT INTO warehouses (name, address) VALUES (:name, :address)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    // Mettre à jour un entrepôt
    public function updateWarehouse($id, $name, $address) {
        $db = getDbConnection();
        $query = "UPDATE warehouses SET name = :name, address = :address WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    // Supprimer un entrepôt
    public function deleteWarehouse($id) {
        $db = getDbConnection();
        // D'abord, mettre à null les références dans les œuvres
        $query1 = "UPDATE artworks SET warehouse_id = NULL WHERE warehouse_id = :id";
        $stmt1 = $db->prepare($query1);
        $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();
        
        // Ensuite, supprimer l'entrepôt
        $query2 = "DELETE FROM warehouses WHERE id = :id";
        $stmt2 = $db->prepare($query2);
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt2->execute();
    }
}

