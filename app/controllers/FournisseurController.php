<?php

require_once '../app/models/FournisseurClass.php';  

class FournisseurController
{
    // Afficher tous les fournisseurs
    public function index()
    {
        $fournisseurs = Fournisseur::getAllFournisseurs();
        include 'views/fournisseur/index.php'; // Ici tu incluras la vue qui affichera tous les fournisseurs
    }

    // Afficher un fournisseur par ID
    public function showById($id)
    {
        $fournisseur = new Fournisseur();
        $fournisseur = $fournisseur->getFournisseurById($id);
        include 'views/fournisseur/show.php'; // Vue qui affiche les détails du fournisseur
    }

   
}
?>
