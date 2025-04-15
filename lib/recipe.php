<?php

class recipe {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }
  
    public function selectRecipe($recipe_id = NULL) {

        $sql = "select * from recipe" ;

        if($recipe_id !== NULL){
            $sql1 = $sql;
            $sql2 =  " where id = '$recipe_id'";
            $sql = $sql1 . $sql2;
            
        }
        $recipes = [];

        $result = mysqli_query($this->connection, $sql);

        while($recipe = $result->fetch_assoc()) {


            $recipe['kitchen'] = $this->selectRecipeKitchenType($recipe["kitchen_id"])['description'];

            $recipe['type'] = $this->selectRecipeKitchenType($recipe["type_id"])['description'];

            $recipe['ingredients'] = $this->selectRecipeIngredients($recipe['id']);

            $recipe['sumTotalPrice'] = $this->calculateSumTotal($recipe['ingredients'], "price");

            $recipe['sumTotalCalories'] = $this->calculateSumTotal($recipe['ingredients'], 'calories');

            $recipe['preparation'] = $this->selectRecipeRecipeInfo($recipe['id'], 'P');

            $recipe['reviews'] = $this->selectRecipeRecipeInfo($recipe['id'], 'R');

            $recipe['reviewAverage'] = $this->calculateReviewAverage($recipe['reviews']);

            $recipe['comments'] = $this->selectRecipeRecipeInfo($recipe['id'], 'C');

            $recipe['favorites'] = $this->selectRecipeRecipeInfo($recipe['id'], 'F');

            $currentUserId = '1';
            
            $recipe['favorited'] = $this->determineFavorite($currentUserId, $recipe['favorites']);

            $recipes[] = $recipe;
        }

        return($recipes);

    }

    public function filterRecipes($term) {
        $fullData = $this->selectRecipe();
        $hits = [];
        foreach ($fullData as $record) {
            $string = json_encode($record);
            if (str_contains($string, $term)){
                $hits[] = $record;
            }
        }
        return($hits);
    }

    private function selectRecipeKitchenType($kitchenType_id) {

        $type = new kitchenType($this->connection);

        $typeInfo = $type->selectKitchenType($kitchenType_id);

        return($typeInfo);
    }

    private function selectRecipeIngredients($recipe_id){

        $ingredients = new ingredient($this->connection);

        $ingredientsInfo = $ingredients-> selectIngredient($recipe_id);

        return($ingredientsInfo);

    }

    private function calculateSumTotal($ingredients, $category) {
       
        $sumTotal = 0;

        foreach ($ingredients as $ingredient) { 

            $amount = floatval($ingredient["amount"]);
            $packaging = floatval($ingredient["packaging"]);
            $expenditure = floatval($ingredient[$category]);

            $total = ($expenditure*$amount)/$packaging;
        
            
            $sumTotal += $total;
        }

        return($sumTotal);

    }

    private function selectRecipeRecipeInfo($recipe_id, $record_type){

        $recipeInfo = new recipeinfo(($this->connection));

        $information = $recipeInfo->selectRecipeInfo($recipe_id, $record_type);


        return($information);

    }

    private function calculateReviewAverage($reviews) {

        $average = 'Niet genoeg reviews';
        $scoreslist = [];
        if(count($reviews) > 0) {
            foreach($reviews as $review){
                $scoreslist[] = $review['number'];
            }
            $average = array_sum($scoreslist)/count($scoreslist);
        }
        return($average);
    }

    private function determineFavorite($user_id, $favorites){

        $result = false;

        foreach($favorites as $favorite) {
            if($user_id = $favorite['user_id']){
                $result = true;
            }
        }

        return($result);
    }

}
