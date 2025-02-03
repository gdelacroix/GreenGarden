<?php

include("include/header.php");
$isCommercialOrAdmin = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['Commercial', 'Admin']);
if (!$isCommercialOrAdmin){
    header('Location: index.php'); // Redirection vers la page d'accueil
    exit();
}
// Initialisation des variables
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$libelle = $slugValue = '';
$idCategorie = null;

// Si un slug est fourni, on récupère les informations de la catégorie à modifier
if ($slug !== '') {
    $sql = "SELECT Id_Categorie, Libelle, Slug FROM t_d_categorie WHERE Slug = '$slug'";
    $result = $pdo->query($sql);
    $category = $result->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        $libelle = $category['Libelle'];
        $slugValue = $category['Slug'];
        $idCategorie = $category['Id_Categorie']; // On récupère l'ID de la catégorie
    } else {
        echo "Catégorie introuvable.";
            exit;
        }
  


    }
    // Traitement du formulaire de création/modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $libelle = $_POST['libelle'];
   
    
    // Si un slug est fourni (modification), on effectue une mise à jour, sinon une insertion
    if ($idCategorie !== null) {
        // Mise à jour d'une catégorie existante (sur l'ID de la catégorie)
        $sql = "UPDATE t_d_categorie SET Libelle = '$libelle' WHERE Id_Categorie = $idCategorie";
        $pdo->exec($sql);
    } else {
        // Création d'une nouvelle catégorie
        $sql = "INSERT INTO t_d_categorie (Libelle) VALUES ('$libelle')";
        $pdo->exec($sql);
    }

    // Redirection après la soumission du formulaire
    header('Location: categories.php');
    exit;
}
?>

<div class="container my-5">
    <h1 class="mb-4"><?= $slug ? "Modifier" : "Créer" ?> une catégorie</h1>

    <form method="post">
        <div class="mb-3">
            <label for="libelle" class="form-label">Libellé</label>
            <input type="text" class="form-control" id="libelle" name="libelle" value="<?= htmlentities($libelle) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><?= $slug ? "Mettre à jour" : "Créer" ?></button>
        <a href="categories.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<?php

include("include/footer.php");
?>