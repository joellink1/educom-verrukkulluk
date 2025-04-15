<?php
//// Allereerst zorgen dat de "Autoloader" uit vendor opgenomen wordt:
require_once("./vendor/autoload.php");

/// Twig koppelen:
$loader = new \Twig\Loader\FilesystemLoader("./templates");
/// VOOR PRODUCTIE:
/// $twig = new \Twig\Environment($loader), ["cache" => "./cache/cc"]);

/// VOOR DEVELOPMENT:
$twig = new \Twig\Environment($loader, ["debug" => true ]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
/******************************/

/// Next step, iets met je data doen. Ophalen of zo

require_once("lib/database.php");
require_once("lib/product.php");
require_once("lib/ingredient.php");
require_once("lib/user.php");
require_once("lib/kitchenType.php");
require_once("lib/recipeInfo.php");
require_once("lib/recipe.php");
require_once("lib/grocery.php");
require_once("lib/favoriteHandler.php");
require_once("lib/reviewHandler.php");

$db = new database;
$recipe = new recipe($db->getConnection());
$grocery = new grocery($db->getConnection());
$fh = new favoriteHandler($db->getConnection());
$rh = new reviewHandler($db->getConnection());

/*
URL:
http://localhost/index.php?recipe_id=4&action=detail
*/

$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : "";
$recipe_id = isset($_GET["recipe_id"]) ? $_GET["recipe_id"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "homepage";
$review_score = isset($_GET["review_score"]) ? $_GET["review_score"] : "";
$product_id = isset($_GET["product_id"]) ? $_GET["product_id"] : "";
$query = isset($_POST["query"]) ? $_POST["query"]: "";
$user_id = 4;

switch($action) {

        case "homepage": {
            $data = $recipe->selectRecipe();
            $template = 'homepage.html.twig';
            $title = "homepage";
            break;
        }

        case "detail": {
            $data = $recipe->selectRecipe($recipe_id);
            $data = $data[0];
            $template = 'detail.html.twig';
            $title = "detail pagina";
            break;
        }

        case "grocery": {
            $data = $grocery->selectGrocery($user_id);

            $template = 'grocery.html.twig';
            $title = "boodschappenlijst";
            break;
        }

        case "addGroceries": {
            $result=["status"=>"success"];

            $grocery->addGroceries($recipe_id, $user_id);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            die();
        }

        case "averageRating": {
            $average = $rh->giveAverage($recipe_id);
            $result=["average"=>$average];
            echo json_encode($result);
            die();
        }

        case "giveRating": {
            $rh->addReview($recipe_id, $review_score);
            $average = $rh->giveAverage($recipe_id);
            $result=["average"=>$average];
            echo json_encode($result);
            die();
        }

        case "checkFavorite": {
            $result = ["match"=>$fh->checkFavorite($user_id, $recipe_id)];
            echo json_encode($result);
            die();
        }

        case "changeFavorite": {
            $method = $fh->swapFavorites($user_id, $recipe_id);
            $result = ["method"=> $method];
            echo json_encode($result);
            die();
        }

        case "deleteProduct": {
            $grocery->deleteProduct($product_id, $user_id);
            $data = $grocery->selectGrocery($user_id);
            $result = ["status"=>"success"];
            echo json_encode($result);
            die();
        }

        case "addPackage": {
            $grocery->addPackage($product_id, $user_id);
            $data = $grocery->selectGrocery($user_id);
            $result = ["result"=>"success"];
            echo json_encode($result);
            die();

        }

        case "removePackage": {
            $grocery->removePackage($product_id, $user_id);
            $data = $grocery->selectGrocery($user_id);
            $result = ["result"=>"success"];
            echo json_encode($result);
            die();
        }

        case "searchSite": {
            $terms = explode("%20", $query, 1);
            $term = $terms[0];
            $data = $recipe->filterRecipes($term);
            $template = 'homepage.html.twig';
            $title = "homepage";

            break;
        }
}

/// Juiste template laden, in dit geval "homepage" 
$template = $twig->load($template);



/// En tonen die handel!
echo $template->render(["title" => $title, "data" => $data]);


