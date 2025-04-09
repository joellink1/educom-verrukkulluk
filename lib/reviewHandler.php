<?php

class reviewHandler {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function addReview($recipe_id, $review_score){
        //add review score to recipeInfo for recipe_id
        $sql = "insert into recipe_info (recipe_id, number, record_type) values ('$recipe_id', '$review_score', 'R')";
        $this->connection->execute_query($sql);
    }

    public function giveAverage($recipe_id){
        //takes all review scores for recipe_id from recipeInfo
        $sql = "select * from recipe_info where record_type = 'R' AND recipe_id = $recipe_id";
        $result = mysqli_query($this->connection, $sql);
        $scores = [];
        while($row=$result->fetch_array()){
            $scores[]=$row["number"];
        }
        $average = 0;
        if (count($scores) > 0){
            $average = array_sum($scores)/count($scores);
        }
        return($average);
    }
}