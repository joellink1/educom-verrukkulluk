<?php

class grocery {


private $connection;

public function __construct($connection) {
    $this->connection = $connection;
}

public function selectGrocery($user_id) {

    $sql = "select * from grocery where user_id = '$user_id'";
    $result = mysqli_query($this->connection, $sql);
    $groceries = [];

    while($row = $result->fetch_assoc()) {
        $product = new product($this->connection);
        $productinfo = $product->selectProduct($row["product_id"]);
        $ingredientInfo = [...$row,...$productinfo];

        $groceries[] = $ingredientInfo;
    }
    return($groceries);
}



public function addGroceries($recipe_id, $user_id){
    $ingredients = $this->selectRecipeIngredients($recipe_id);
    
    foreach($ingredients as $ingredient) {
        
        $newAmount = $ingredient['amount'];
        if($this->alreadyOnList($ingredient['product_id'], $user_id)){
            $product_id_to_delete = $ingredient['product_id'];
            $oldAmount = $this->alreadyOnList($product_id_to_delete, $user_id)['amount'];
            $newAmount += $oldAmount;
            $sql = "delete from grocery where user_id = $user_id and product_id = '$product_id_to_delete'";
            $this->connection->execute_query($sql);

        }
        
        $this->addToShoppingList($ingredient['product_id'], $ingredient['packaging'], $newAmount, $user_id);
    }

}

private function alreadyOnList($product_id, $user_id) {
    $groceries = $this->selectGrocery($user_id);

    foreach($groceries as $grocery){
        if($grocery['product_id'] == $product_id){
            return($grocery);
        }
    }
    return(false);

}



private function selectRecipeIngredients($recipe_id){

    $ingredients = new ingredient($this->connection);

    $ingredientsInfo = $ingredients-> selectIngredient($recipe_id);

    return($ingredientsInfo);

}

private function addToShoppingList($product_id, $packaging, $amount, $user_id){

    $connection = $this->connection;

    $sql = "insert into grocery (user_id, product_id, packaging, amount) values ('$user_id', '$product_id', '$packaging', '$amount')";
    
    $connection->execute_query($sql);
}
}