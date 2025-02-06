
<?php $categorie = $categorie ?? null; ?>
<div class="container my-5">
    <h1 class="mb-4"><?= $categorie ? "Modifier" : "Créer" ?> une catégorie</h1>
  


    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlentities($message) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?action=saveCategorie">
        <input type="hidden" name="id" value="<?= $categorie ? $categorie->getId_Categorie() : '' ?>">
        <div class="mb-3">
            <label for="libelle" class="form-label">Libellé</label>
            <input type="text" class="form-control" id="libelle" name="libelle" value="<?= htmlentities($categorie ? $categorie->getLibelle() : '') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><?= $categorie ? "Mettre à jour" : "Créer" ?></button>
        <a href="index.php?action=categorie" class="btn btn-secondary">Annuler</a>
    </form>
</div>