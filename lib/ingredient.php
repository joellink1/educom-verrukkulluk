<?php

class ingredient {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }
  
    private function selectIngredientProduct($ingredient_id) {

        $sql = "select * from ingredient where id = $ingredient_id";
        
        $result = mysqli_query($this->connection, $sql);
        $ingredient = mysqli_fetch_array($result, MYSQLI_ASSOC);

        
        $product_id = $ingredient["product_id"] ;

        $prod = new product($this->connection);

        $data = $prod->selectProduct($product_id);

        return($data);
    }

    public function selectIngredient($recipe_id) {

        $sql = "select * from ingredient where recipe_id = $recipe_id";
        
        $result = mysqli_query($this->connection, $sql);

        $ingredients = [];

        while($row = $result->fetch_assoc()) {
            $product = $this->selectIngredientProduct($row["id"]);
            $ingredientInfo = [...$row,...$product];

            $ingredients[] = $ingredientInfo;
        }


        return($ingredients);

    }


}