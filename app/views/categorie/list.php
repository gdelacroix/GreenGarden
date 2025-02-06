

<div class="container my-5">
    <h1 class="mb-4">Liste des catégories</h1>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlentities($message) ?></div>
    <?php endif; ?>

    <form method="get" action="index.php">
        <input type="hidden" name="action" value="categorie">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher une catégorie"
                   value="<?= htmlentities($search) ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>
  

    <a href="index.php?action=manageCategorie" class="btn btn-success my-4">Créer une catégorie</a>

    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5><?= htmlentities($category->getLibelle()) ?></h5>
                        <a href="index.php?action=manageCategorie&slug=<?= $category->getSlug() ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="index.php?action=deleteCategorie&slug=<?= $category->getSlug()  ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
