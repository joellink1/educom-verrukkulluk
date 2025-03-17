<?php

class recipeInfo {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }
  
    public function selectRecipeInfo($recipe_id, $record_type) {

        $sql = "select * from recipe_info where recipe_id = $recipe_id AND record_type = '$record_type'";

        $result = mysqli_query($this->connection, $sql);
        
        $allRecipeInfo = [];
        
        while($row = $result->fetch_assoc()) {
            $recipeInfo = $row;
            if ($row["record_type"] == "C" or $row["record_type"] == "F") {
            
                $data = $this->selectRecipeUser($row["user_id"]);
                $recipeInfo = [...$data,...$row];
            }
            $allRecipeInfo[] = $recipeInfo;
        }
        
        return($allRecipeInfo);

    }

    private function selectRecipeUser($user_id){

        $user = new user($this->connection);

        $userInfo = $user->selectUser($user_id);

        return($userInfo);
    }

    public function addFavorite($recipe_id, $user_id) {

        $this->removeFavorite($recipe_id, $user_id);

        $sql = "insert into recipe_info (recipe_id, user_id, record_type) values ($recipe_id, $user_id, 'F')";

        mysqli_query($this->connection, $sql);
    }


    public function removeFavorite($recipe_id, $user_id) {

        $sql = "delete from recipe_info where recipe_id=$recipe_id and user_id=$user_id.";

        
        mysqli_query($this->connection, $sql);
    }

}
