<?php
include('include/header.php');
require 'Classes/ProduitClass.php';
require 'Classes/FournisseurClass.php';
require 'Classes/CategorieClass.php';
?>
<?php



// Vérifiez si l'utilisateur est connecté
$username = null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Récupération des informations de l'utilisateur
    $sqlUser = "SELECT Login FROM t_d_user WHERE Id_User = :userId";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute(['userId' => $userId]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $username = $user['Login'];
    }
}



// Initialisation des variables


$produit = new Produit();
$produitData = new Produit();
$categories = Categorie::getAllCategories();
$fournisseurs = Fournisseur::getAllFournisseurs();
$message = "";

// Vérifier si l'utilisateur est commercial ou admin: renvoie un booléen
$isCommercialOrAdmin = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['Commercial', 'Admin']);



// Si un produit spécifique est en cours de modification ou d'affichage
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    // $stmt = $pdo->prepare("SELECT * FROM t_d_produit WHERE slug = ?");
    // $stmt->execute([$slug]);
    // $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    $produitData = $produit->getProduitBySlug($slug);
}

// Gestion du formulaire uniquement si l'utilisateur est commercial
if ($isCommercialOrAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {

   

    $produitData->setNom_Long($_POST['nom'] ?? '');
    $produitData->setNom_court($_POST['nom_court'] ?? '');
    $produitData->setPrix_Achat(floatval($_POST['prix'] ?? 0));
    $produitData->setTaux_TVA(floatval($_POST['taux_tva'] ?? 0));
    $produitData->setRef_Fournisseur($_POST['ref_fournisseur'] ?? '');
    $produitData->setId_Categorie(intval($_POST['categorie'] ?? 0));
    $produitData->setId_Fournisseur(intval($_POST['fournisseur'] ?? 0));

    // Gestion de l'upload de fichier
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = 'images/';
        $tmp_name = $_FILES['photo']['tmp_name'];
        $filename = uniqid() . '_' . basename($_FILES['photo']['name']);
        if (move_uploaded_file($tmp_name, $uploads_dir . $filename)) {
            $produitData->setPhoto($filename);
        } else {
            $message = "Erreur lors de l'upload de la photo.";
        }
    }

    // Insertion ou mise à jour en base de données
    if (!empty($_POST['id'])) {
        // Mise à jour
        $produitData->updateProduit();
        $message = "Produit mis à jour avec succès !";
    } else {
        // Création
        // $stmt = $pdo->prepare("INSERT INTO t_d_produit (nom_long, nom_court, prix_achat, id_categorie, id_fournisseur,taux_tva,ref_fournisseur, photo) VALUES (?, ?, ?, ?, ?,?,?, ?)");
        // $stmt->execute([$nom, $nom_court, $prix, $categorie_id, $fournisseur_id, $taux_tva, $ref_fournisseur,$photo]);
        $produitData->insertProduit();
        $message = "Produit créé avec succès !";
    }
}
// Récupération des noms de la catégorie et du fournisseur pour l'affichage en mode lecture
$categoriesMap = array_column($categories, 'Libelle', 'Id_Categorie');
$categorieNom = isset($categoriesMap[$produitData->getId_Categorie()]) ? $categoriesMap[$produitData->getId_Categorie()] : 'Inconnu';

$fournisseursMap = array_column($fournisseurs, 'Nom_Fournisseur', 'Id_Fournisseur');
$fournisseurNom = isset($fournisseursMap[$produitData->getId_Fournisseur()]) ? $fournisseursMap[$produitData->getId_Fournisseur()] : 'Inconnu';
?>


<h1><?= isset($_GET['slug']) ? "Détails du produit" : "Créer un produit" ?></h1>

<?php if ($message): ?>
    <div class="alert alert-success" role="alert">
        <p style="color: green;"><?= htmlentities($message) ?></p>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="p-4 border rounded bg-light">
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
            <p class="mt-2">Photo actuelle : <img src="images/<?= htmlentities($produitData->getPhoto()) ?>" class="img-thumbnail" alt="Photo" style="width: 100px;"></p>
        <?php endif; ?>
    </div>

    <?php if ($isCommercialOrAdmin): ?>
        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
    <?php endif; ?>
</form>




<?php
include('include/footer.php');
?>