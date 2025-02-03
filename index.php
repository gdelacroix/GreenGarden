<?php
include('include/header.php');
require 'Classes/ProduitClass.php';
require 'Classes/CategorieClass.php';
require 'Classes/FournisseurClass.php';
?>
<?php

// Initialisation des variables
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Création d'une instance de la classe Produit
$produitManager = new Produit();
// Création d'une instance de la classe Categorie
$categorieManager = new Categorie();
// Création d'une instance de la classe Fournisseur
$fournisseurManager = new Fournisseur();

// // Requête SQL pour récupérer les produits
// $sql = "SELECT 
//             p.Id_Produit, p.Nom_court, p.Photo, 
//             c.Libelle AS Categorie, 
//             f.Nom_fournisseur AS Fournisseur,
//             p.Prix_Achat, p.Taux_TVA,
//             (p.Prix_Achat + (p.Prix_Achat * p.Taux_TVA / 100)) AS Prix_TTC,
//             p.Slug
//         FROM t_d_produit p
//         INNER JOIN t_d_categorie c ON p.Id_Categorie = c.Id_Categorie
//         INNER JOIN t_d_fournisseur f ON p.Id_Fournisseur = f.Id_Fournisseur";

// // Ajout du filtre de recherche si applicable
// if (!empty($search)) {
//     $sql .= " WHERE p.Nom_court LIKE '%$search%'";
// }



// Récupération des produits avec ou sans recherche
if (empty($search)) {
    $products = $produitManager->getAllProduits(); // Si nécessaire, implémentez une méthode de recherche dans ProduitClass
} else {
    $products = $produitManager->getProduitsForSearch($search);
}



?>


<div class="container my-5">
    <h1 class="mb-4">Liste des produits</h1>

    <!-- Formulaire de recherche -->
    <form method="get" action="index.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher un produit"
                value="<?= htmlentities($search) ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>

    <!-- Liste des produits -->
    <div class="row">
          <?php
        if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <?php  $product->getSlug()  ?>
                <div class="col-md-4 mb-4">
                    <a href="produit.php?slug=<?= urlencode($product->getSlug()) ?>" class="text-decoration-none">
                        <div class="card">
                            <img src="images/<?= htmlentities($product->getPhoto()) ?>"
                                class="card-img-top" alt="<?= htmlentities($product->getNom_court()) ?>"
                                style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='images/erreur.webp';">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlentities($product->getNom_court()) ?></h5>
                                <p class="card-text"><strong>Catégorie :</strong>
                                    <?php
                                    $categorie = $categorieManager->getCategorieById($product->getId_Categorie());
                                    if (is_object($categorie[0])) {
                                        echo htmlentities($categorie[0]->getLibelle());
                                    } else {
                                        echo 'Catégorie inconnue';
                                    }
                                    ?>
                                <p class="card-text"><strong>Fournisseur :</strong>
                                    <?php
                                    
                                    $fournisseur = $fournisseurManager->getFournisseurById($product->getId_Fournisseur());
                                   // echo $fournisseur->getNom_Fournisseur();
                                    if (is_object($fournisseur[0])) {
                                        echo htmlentities($fournisseur[0]->getNom_Fournisseur());
                                    } else {
                                        echo 'Fournisseur inconnu';
                                    }
                                    
                                    ?></p>
                                <p class="card-text"><strong>Prix TTC :</strong>
                                    <?= number_format($product->getPrixTTC(), 2, ',', ' ') ?> €</p>

                                <form method='POST' action='panier.php' class='add-to-cart-form' data-slug="<?= $product->getSlug() ?>"
                                    data-libelle="<?= htmlentities($product->getNom_court()) ?>"
                                    data-prix="<?=$product->getPrixTTC() ?>">
                                    <input type='hidden' name='action' value='ajouter'>
                                    <input type='hidden' name='slug' value="<?= $product->getSlug() ?>">
                                    <input type='hidden' name='libelle' value="<?= $product->getNom_court() ?>">
                                    <input type='hidden' name='prix' value="<?= $product->getPrixTTC() ?>">
                                    <button type='submit' class='btn btn-primary'>Ajouter au panier</button>
                                </form>


                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Aucun produit trouvé.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include('include/footer.php');
?>