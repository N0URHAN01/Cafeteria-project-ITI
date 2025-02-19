<?php

require_once __DIR__ . "/../db/Database.php";


class Product{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create_product($name,$price,$category_id,$image_url,$stock_quantity,$status= 'available'){

        try{
           
            $insert_product = $this->db->connect()->prepare(
                "INSERT INTO products (name, price, category_id, image_url, stock_quantity, status) 
                 VALUES (:name, :price, :category_id, :image_url, :stock_quantity, :status)"
            );
    
            $insert_product->bindParam(':name', $name);
            $insert_product->bindParam(':price', $price);
            $insert_product->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $insert_product->bindParam(':image_url', $image_url);
            $insert_product->bindParam(':stock_quantity', $stock_quantity, PDO::PARAM_INT);
            $insert_product->bindParam(':status', $status);
    
                
            if ($insert_product->execute()) {
                return $this->db->connect()->lastInsertId(); 
            }
            return false;
    
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public function is_product_name_used($name): bool {
        try {
            $stmt = $this->db->connect()->prepare(
                "SELECT product_id FROM products WHERE name = :name LIMIT 1"
            );
            $stmt->bindParam(':name', $name);
            $stmt->execute();
    
            return $stmt->rowCount() > 0; 
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false; 
        }
    }
    

    public function get_all_products() {
        try {
            $query = "SELECT p.product_id, p.name, p.price, p.stock_quantity, p.status, 
                             p.image_url, c.name AS category_name
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.category_id";
    
            $stmt = $this->db->connect()->prepare($query);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false; 
    }
}
 
    public function get_product_by_id($product_id) {
        try {
            $query = "SELECT p.product_id, p.name, p.price, p.stock_quantity, p.status, 
                             p.image_url, c.category_id, c.name AS category_name
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.category_id
                      WHERE p.product_id = :product_id
                      LIMIT 1";
    
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC); 
    
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false; 
        }
    }
    
    public function update_product($product_id, $name, $price, $category_id, $image_url, $stock_quantity, $status) {
        try {
                      
            $query = "UPDATE products 
                      SET name = :name, price = :price, category_id = :category_id, 
                          image_url = :image_url, stock_quantity = :stock_quantity, status = :status 
                      WHERE product_id = :product_id";
    
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':image_url', $image_url);
            $stmt->bindParam(':stock_quantity', $stock_quantity, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status);
    
            return $stmt->execute() ? true : false;
    
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete_product($product_id){
        try{
            
            $query = "DELETE FROM products WHERE product_id = :product_id";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            return $stmt->execute() ? true : false;
        }catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }

    }



    public function get_products_by_category($category_id) {
        try {
            $query = "SELECT * FROM products WHERE category_id = :category_id";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return [];
        }
    }
    

}