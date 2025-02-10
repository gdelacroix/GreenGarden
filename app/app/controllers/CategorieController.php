<?php
require_once '../app/models/CategorieClass.php';

class CategorieController
{

    private $categorie;

    public function __construct()
    {
        $this->categorie = new Categorie();
    }
    // Afficher toutes les catégories
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $categories = $this->categorie->getAllCategories($search);

        // Message de session
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);

        include '../app/views/categorie/list.php';
    }

    public function manageCategorie($slug = null)
    {
        
        if ($slug) {

            $categorieArray = $this->categorie->getCategorieBySlug($slug);
            $categorie = $categorieArray[0] ?? null; // On récupère directement l'objet
            if (!$categorie) {
                $_SESSION['message'] = "Catégorie introuvable.";
                header("Location: index.php?action=categorie");
                exit;
            }
        }else{
            $categorie=null;
        }

        // Message de session
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);

        include '../app/views/categorie/form.php';
    }


   

    // Afficher une catégorie par ID
    public function showById($id)
    {
        $categorie = new Categorie();
        $categorie = $categorie->getCategorieById($id);
        include '../app/views/categorie/show.php'; // Vue qui affiche les détails de la catégorie
    }

    // Afficher une catégorie par Slug
    public function showBySlug($slug)
    {
        $categorie = new Categorie();
        $categorie = $categorie->getCategorieBySlug($slug);
        return $categorie[0];
    }


    public function saveCategorie()
    {
        $id = $_POST['id'] ?? null;
        $libelle = trim($_POST['libelle']);

        if (!$libelle) {
            $_SESSION['message'] = "Le libellé est obligatoire.";
            header("Location: index.php?action=categorie");
            exit;
        }

        if ($id) {
            $this->update($id, $libelle);
            $_SESSION['message'] = "Catégorie mise à jour avec succès.";
        } else {
            $this->create($libelle);
            $_SESSION['message'] = "Catégorie créée avec succès.";
        }

        echo '<meta http-equiv="refresh" content="0;url=index.php?action=allCategories">';
     
        exit;
    }

    // Ajouter une nouvelle catégorie
    public function create($libelle)
    {
        var_dump($libelle); // Vérifie si $libelle contient bien une valeur
        $categorie = new Categorie(null, $libelle);
        $categorie->insertCategorie();
        //  header("Location: index.php?controller=categorie&action=index");
    }

    // Mettre à jour une catégorie
    public function update($id, $libelle)
    {
        $categorie = new Categorie($id, $libelle);
        $categorie->updateCategorie();
        //  header("Location: index.php?controller=categorie&action=showById&id=$id");
    }

    // Supprimer une catégorie
    public function delete($slug)
    {
        $slug = $_GET['slug'] ?? null;
        $categorie = $this->showBySlug($slug);

        if (!$slug || !$categorie->deleteCategorie($slug)) {
            $_SESSION['message'] = "Impossible de supprimer cette catégorie.";
        } else {
            $_SESSION['message'] = "Catégorie supprimée avec succès.";
        }

        echo '<meta http-equiv="refresh" content="0;url=index.php?action=allCategories">';
        exit;
    }
}
