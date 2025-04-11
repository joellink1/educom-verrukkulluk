<?php

class favoriteHandler {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function checkFavorite($user_id, $recipe_id) {
        $match = true;
        
        $sql = "select * from recipe_info where record_type = 'F' AND recipe_id = $recipe_id AND user_id = $user_id";
        $result = mysqli_query($this->connection, $sql);

        $hits = [];
        while($row = $result->fetch_assoc()){
            $hits[]=$row;
        }
        
        if ($hits == []){
            $match = false;
        }

        return($match);
    }

    public function swapFavorites($user_id, $recipe_id) {
        $sql = "DELETE from recipe_info where record_type = 'F' AND recipe_id = $recipe_id AND user_id = $user_id";
        $method = "delete";
        if ($this->checkFavorite($user_id, $recipe_id) == false){
            $sql = "insert into recipe_info (user_id, recipe_id, record_type) values ('$user_id', '$recipe_id', 'F')";
            $method = "insert";
        }
        $this->connection->execute_query($sql);
        return($method);
    }
}