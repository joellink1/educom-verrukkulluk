<?php

require_once("lib/database.php");
require_once("lib/product.php");
require_once("lib/ingredient.php");
require_once("lib/user.php");
require_once("lib/kitchenType.php");
require_once("lib/recipeInfo.php");
require_once("lib/recipe.php");
require_once("lib/grocery.php");

/// INIT
$db = new database();
$ingr = new ingredient($db->getConnection());
$user = new user($db->getConnection());
$info = new recipeInfo($db->getConnection());
$dish = new recipe($db->getConnection());
$groc = new grocery($db->getConnection());

/// VERWERK 
$data = $groc->addGroceries('2', '3');

/// RETURN
echo'<pre>';
    var_dump($data);
echo'</pre>';
