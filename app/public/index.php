<?php
include('../include/header.php');

// Inclusion des contrôleurs
require_once '../app/controllers/ProduitController.php';
require_once '../app/controllers/CategorieController.php';
require_once '../app/controllers/FournisseurController.php';

// Vérification de l'action dans l'URL
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'manageProduit':
            // Affiche la vue de gestion du produit (création ou modification)
            $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
            $produitController = new ProduitController();
            $produitController->manageProduit($slug);
            break;

        case 'saveProduit':
            // Sauvegarde un produit (création ou mise à jour)
            $produitController = new ProduitController();
            $produitController->saveProduit();
            break;

        case 'deleteProduit':
            // Supprime un produit
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $produitController = new ProduitController();
            $produitController->delete($id);
            break;

        case 'searchProduits':
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
            $produitController = new ProduitController();
            $produitController->getProduitsForSearch($searchTerm);
            break;

        case 'allProduits':
            // Affiche tous les produits
            $produitController = new ProduitController();
            if (empty($search)) {
                $produitController->getallProduits();
            } else {
                $produitController->getProduitsForSearch($search);
            }
            break;

            // Actions Categorie
        case 'manageCategorie':
            // Affiche la vue de gestion de la catégorie (création ou modification)
            $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
            $categorieController = new CategorieController();
            $categorieController->manageCategorie($slug);
            break;

        case 'saveCategorie':
            // Sauvegarde une catégorie (création ou mise à jour)
            $categorieController = new CategorieController();
            $categorieController->saveCategorie();
            break;

        case 'deleteCategorie':
            // Supprime une catégorie
            $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
            $categorieController = new CategorieController();
            $categorieController->delete($slug);
            break;

        case 'allCategories':
            // Affiche toutes les catégories
            $categorieController = new CategorieController();
            $categorieController->index();
            break;



        default:
            // Action par défaut (peut-être une page d'accueil ou une page d'erreur)
            echo "Action non définie.";
            break;
    }
} else {
    // Si aucune action n'est spécifiée, afficher la liste des produits par défaut
    $produitController = new ProduitController();
    $produitController->getallProduits();
}
?>



<?php
include('../include/footer.php');
?>