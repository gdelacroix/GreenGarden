
<!-- showProduits.php -->
<div class="container my-5">
    <h1 class="mb-4">Liste des produits</h1>

    <!-- Formulaire de recherche -->
    <form method="get" action="index.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher un produit"
                value="<?= isset($searchTerm) ? htmlentities($searchTerm) : '' ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>

    <!-- Liste des produits -->
    <div class="row">
        <?php if (count($produits) > 0): ?>
            <?php foreach ($produits as $product): ?>
                <div class="col-md-4 mb-4">
                    <a href="index.php?action=manageProduit&slug=<?= urlencode($product->getSlug()) ?>" class="text-decoration-none">
                        <div class="card">
                            <img src="../../../images/<?= htmlentities($product->getPhoto()) ?>"
                                class="card-img-top" alt="<?= htmlentities($product->getNom_court()) ?>"
                                style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='../../../images/erreur.webp';">
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
                                </p>
                                <p class="card-text"><strong>Fournisseur :</strong>
                                    <?php
                                    $fournisseur = $fournisseurManager->getFournisseurById($product->getId_Fournisseur());
                                    if (is_object($fournisseur[0])) {
                                        echo htmlentities($fournisseur[0]->getNom_Fournisseur());
                                    } else {
                                        echo 'Fournisseur inconnu';
                                    }
                                    ?>
                                </p>
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
