<?php
include('header.php');


// Initialisation des variables
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Requête SQL pour récupérer les catégories
$sql = "SELECT Id_Categorie, Libelle, Slug FROM t_d_categorie";

// Ajout du filtre de recherche si applicable
if (!empty($search)) {
    $sql .= " WHERE Libelle LIKE '%$search%'";
}

// Exécution de la requête
$categories = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Suppression d'une catégorie
if (isset($_GET['delete'])) {
    $deleteSlug = $_GET['delete'];
    
    // Requête pour récupérer l'ID en fonction du slug
    $stmt = $pdo->prepare("SELECT Id_Categorie FROM t_d_categorie WHERE Slug = :slug");
    $stmt->execute(['slug' => $deleteSlug]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        // Requête de suppression
        $deleteSql = "DELETE FROM t_d_categorie WHERE Slug = :slug";
        $stmt = $pdo->prepare($deleteSql);
        $stmt->execute(['slug' => $deleteSlug]);
    }

    // Redirection après suppression
    header('Location: categories.php');
    exit;
}
?>

<div class="container my-5">
    <h1 class="mb-4">Liste des catégories</h1>

    <!-- Formulaire de recherche -->
    <form method="get" action="categories.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher une catégorie"
                   value="<?= htmlentities($search) ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>

    <!-- Bouton Créer une catégorie -->
    <a href="categorie.php" class="btn btn-success mb-4">Créer une catégorie</a>

    <!-- Liste des catégories -->
    <div class="row">
        <?php if (count($categories) > 0): ?>
            <?php foreach ($categories as $category): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlentities($category['Libelle']) ?></h5>
                            <!-- Boutons Modifier et Supprimer -->
                            <a href="categorie.php?slug=<?= $category['Slug'] ?>"
                             class="btn btn-warning btn-sm">Modifier</a>
                            <a href="categories.php?delete=<?= $category['Slug'] ?>"
                             class="btn btn-danger btn-sm" 
                             onclick="return confirm(
                             'Êtes-vous sûr de vouloir supprimer cette catégorie ?\n (Sachez que si une commande existe sur ce produit, la suppression ne sera pas possible)')">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Aucune catégorie trouvée.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include('footer.php');
?>
