<?php
require_once '../app/models/ProduitClass.php';
require_once '../app/models/CategorieClass.php';
require_once '../app/models/FournisseurClass.php';
class ProduitController
{
    private $produit;

    public function __construct()
    {
        $this->produit = new Produit();
    }

    // Affiche tous les produits
    public function getAllProduits()
    {
        $produits = $this->produit->getAllProduits();

        // Création des gestionnaires de catégories et fournisseurs
        $categorieManager = new Categorie();
        $fournisseurManager = new Fournisseur();

        // Appel de la vue avec le terme de recherche et les produits
        include '../app/views/produit/allProduits.php';  // Assure-toi que le chemin correspond
    }


    // Affiche un produit par son slug
    public function getProduitBySlug($slug)
    {
        $produit = $this->produit->getProduitBySlug($slug);

        // Création des gestionnaires de catégories et fournisseurs
        $categorieManager = new Categorie();
        $fournisseurManager = new Fournisseur();

        return $produit;
    }


    // Affiche un produit par son id
    public function getProduitById($id)
    {
        $produit = $this->produit->getProduitById($id);
        return $produit;
    }

    // Recherche des produits
    public function getProduitsForSearch($searchTerm)
    {
        $produits = $this->produit->getProduitsForSearch($searchTerm);

        // Création des gestionnaires de catégories et fournisseurs
        $categorieManager = new Categorie();
        $fournisseurManager = new Fournisseur();

        // Appel de la vue avec le terme de recherche et les produits
        include '../app/views/produit/allProduits.php';  // Assure-toi que le chemin correspond
    }



    public function saveProduit()
    {
        // Vérification si c'est une mise à jour ou une création
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Mise à jour d'un produit existant
            $id = $_POST['id'];
            $data = $_POST;  // Récupère toutes les données du formulaire
            $this->update($id, $data);  // Appelle la méthode de mise à jour
            $_SESSION['message'] = "Le produit a été mis à jour avec succès.";
            header("Location: index.php?action=manageProduit&slug=" . $data['Slug']);  // Redirige après mise à jour
        } else {
            // Création d'un nouveau produit
            $data = $_POST;
            $this->create($data);  // Appelle la méthode de création
            $_SESSION['message'] = "Le produit a été ajouté avec succès.";
            header("Location: index.php?action=allProduits");  // Redirige après création
        }
    }

    // Ajoute un produit
    public function create($data)
    {
        $this->produit->setTaux_TVA($data['Taux_TVA']);
        $this->produit->setNom_Long($data['Nom_Long']);
        $this->produit->setNom_Court($data['Nom_court']);
        $this->produit->setRef_fournisseur($data['Ref_fournisseur']);
        $this->produit->setPhoto($data['Photo']);
        $this->produit->setPrix_Achat($data['Prix_Achat']);
        $this->produit->setId_Fournisseur($data['Id_Fournisseur']);
        $this->produit->setId_Categorie($data['Id_Categorie']);
        $this->produit->setSlug($data['Slug']);
        $this->produit->insertProduit();
    }

    // Met à jour un produit
    public function update($id, $data)
    {
        $this->produit->setId_Produit($id);
        $this->produit->setTaux_TVA($data['Taux_TVA']);
        $this->produit->setNom_Long($data['Nom_Long']);
        $this->produit->setNom_Court($data['Nom_court']);
        $this->produit->setRef_fournisseur($data['Ref_fournisseur']);
        $this->produit->setPhoto($data['Photo']);
        $this->produit->setPrix_Achat($data['Prix_Achat']);
        $this->produit->setId_Fournisseur($data['Id_Fournisseur']);
        $this->produit->setId_Categorie($data['Id_Categorie']);
        $this->produit->setSlug($data['Slug']);
        $this->produit->updateProduit();
    }

    // Supprime un produit
    public function delete($id)
    {
        $this->produit->setId_Produit($id);
        $this->produit->deleteProduit();
        header("Location: index.php?action=allProduits");  // Redirige vers la liste des produits après la suppression

    }

    // Gère la création la vue ou la modification d'un produit
    public function manageProduit($slug = null)
    {

        // Récupérer les catégories et les fournisseurs
        $categorieController = new CategorieController();
        $categories = Categorie::getAllCategories();
        $fournisseurController = new FournisseurController();
        $fournisseurs = Fournisseur::getAllFournisseurs();
        // Vérification des droits d'accès (par exemple, pour restreindre l'accès à la création/modification)
        $isCommercialOrAdmin = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['Commercial', 'Admin']);


        // Si un slug est passé, il s'agit de la modification d'un produit existant
        if ($slug) {
            $produitData = $this->getProduitBySlug($slug);
            if ($isCommercialOrAdmin) {
                $viewTitle = "Modifier un produit";
            } else {
                $viewTitle = "Détail d'un produit";
            }
        } else {
            $produitData = new Produit();  // Nouvel objet pour un produit à créer
            $viewTitle = "Créer un produit";
        }

        // Récupérer et effacer le message de session
    $message = $_SESSION['message'] ?? '';
    unset($_SESSION['message']);

        // Passer les données à la vue
        include '../app/views/produit/produitForm.php';
    }
}
