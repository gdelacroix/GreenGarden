

<h1><?= $viewTitle ?></h1>

<?php if ($message): ?>
    <div class="alert alert-success" role="alert">
        <p style="color: green;"><?= htmlentities($message) ?></p>
    </div>
<?php endif; ?>

<form action="index.php?action=saveProduit" method="post" enctype="multipart/form-data" class="p-4 border rounded bg-light">
    <input type="hidden" name="id" value="<?= htmlentities($produitData->getId_Produit()) ?>">

    <div class="mb-3">
        <label for="nom" class="form-label">Nom :</label>

        <textarea  class="form-control" name="nom" id="nom" <?= $isCommercialOrAdmin ? '' : 'readonly' ?> required><?= htmlentities($produitData->getNom_Long()) ?></textarea>
    </div>

    <div class="mb-3">
        <label for="nom_court" class="form-label">Nom court :</label>
        <input type="text"  class="form-control" name="nom_court" id="nom_court" value="<?= htmlentities($produitData->getNom_court())  ?>" <?= !$isCommercialOrAdmin ? 'readonly' : ''  ?>" <?= $isCommercialOrAdmin ? '' : 'readonly' ?> required>
    </div>

    <div class="mb-3">
        <label for="ref_fournisseur" class="form-label">Référence fournisseur :</label>
        <input type="text"  class="form-control" name="ref_fournisseur" id="ref_fournisseur" value="<?= htmlentities($produitData->getRef_Fournisseur())  ?>" <?= !$isCommercialOrAdmin ? 'readonly' : '' ?> required>
    </div>

    <div class="mb-3">
        <label for="prix" class="form-label">Prix :</label>
        <input type="number"  class="form-control" step="0.01" name="prix" id="prix" value="<?= htmlentities($produitData->getPrix_Achat())  ?>" <?= $isCommercialOrAdmin ? '' : 'readonly' ?> required>
    </div>

    <div class="mb-3">
        <label for="taux_tva" class="form-label">Taux de TVA :</label>
        <input type="number"  class="form-control" step="0.01" name="taux_tva" id="taux_tva" value="<?= $produitData->getTaux_TVA() ?>" <?= !$isCommercialOrAdmin ? 'readonly' : '' ?> required>
    </div>

    <div class="mb-3">
        <label for="id_categorie" class="form-label">Catégorie</label>
        <select class="form-select" id="id_categorie" name="categorie" <?= !$isCommercialOrAdmin ? 'disabled' : '' ?>>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?= $categorie->getId_Categorie() ?>" <?= ($produitData->getId_Categorie() == $categorie->getId_Categorie()) ? 'selected' : '' ?>>
                    <?= htmlentities($categorie->getLibelle()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="id_fournisseur" class="form-label">Fournisseur</label>
        <select class="form-select" id="id_fournisseur" name="fournisseur" <?= !$isCommercialOrAdmin ? 'disabled' : '' ?>>
            <?php foreach ($fournisseurs as $fournisseur): ?>
                <option value="<?= $fournisseur->getId_Fournisseur() ?>" <?= ($produitData->getId_Fournisseur() == $fournisseur->getId_Fournisseur()) ? 'selected' : '' ?>>
                    <?= htmlentities($fournisseur->getNom_Fournisseur()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="photo" class="form-label">Photo :</label>
        <?php if ($isCommercialOrAdmin): ?>
            <input type="file"  class="form-control" name="photo" id="photo">
        <?php endif; ?>
        <?php if (null !== $produitData->getPhoto()): ?>
            <p class="mt-2">Photo actuelle : <img src="../images/<?= htmlentities($produitData->getPhoto()) ?>" class="img-thumbnail" alt="Photo" style="width: 100px;"  onerror="this.onerror=null; this.src='../images/erreur.webp';"></p>
        <?php endif; ?>
    </div>

    <?php if ($isCommercialOrAdmin): ?>
        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
    <?php endif; ?>
</form>




