<?php

class product {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }
  
    public function selectProduct($product_id) {

        $sql = "select * from product where id = $product_id";
        
        $result = mysqli_query($this->connection, $sql);
        $product = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        return($product);

    }


}
