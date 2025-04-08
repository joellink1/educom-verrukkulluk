<?php

class favoriteHandler {

    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function checkFavorite($user_id, $recipe_id) {
        //check whether favoritelink in recipeinfo
        //return bool
    }

    public function swapFavorites($user_id, $recipe_id) {
        //if no DB entry, create one
        //if available, delete+

    }
}