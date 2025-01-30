<?php
include('header.php');
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
$produit = [
    'Id_Produit' => '',
    'nom_long' => '',
    'nom_court' => '',
    'prix_achat' => '',
    'taux_tva' => '',
    'id_categorie' => '',
    'id_fournisseur' => '',
    'ref_fournisseur' => '',
    'photo' => ''
];
$categories = [];
$fournisseurs = [];
$message = "";

// Vérifier si l'utilisateur est commercial ou admin: renvoie un booléen
$isCommercialOrAdmin = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['Commercial', 'Admin']);

//


// Récupérer les catégories et fournisseurs pour les listes déroulantes
$categories = $pdo->query("SELECT Id_Categorie, Libelle FROM t_d_categorie")->fetchAll();
$fournisseurs = $pdo->query("SELECT Id_Fournisseur, Nom_Fournisseur FROM t_d_fournisseur")->fetchAll();




// Si un produit spécifique est en cours de modification ou d'affichage
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $stmt = $pdo->prepare("SELECT * FROM t_d_produit WHERE slug = ?");
    $stmt->execute([$slug]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);
   
}

// Gestion du formulaire uniquement si l'utilisateur est commercial
if ($isCommercialOrAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $nom_court = $_POST['nom_court'];
    $prix = floatval($_POST['prix']);
    $categorie_id = intval($_POST['categorie']);
    $fournisseur_id = intval($_POST['fournisseur']);
    $taux_tva = floatval($_POST['taux_tva']);
    $ref_fournisseur = $_POST['ref_fournisseur'];
    $photo = $produit['photo'];

    // Gestion de l'upload de fichier
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = 'images/';
        $tmp_name = $_FILES['photo']['tmp_name'];
        $filename = uniqid() . '_' . basename($_FILES['photo']['name']);
        $photo_path = $uploads_dir . $filename;

        if (move_uploaded_file($tmp_name, $photo_path)) {
            $photo = $filename;
        } else {
            $message = "Erreur lors de l'upload de la photo.";
        }
    }

    // Insertion ou mise à jour en base de données
    if (!empty($_POST['id'])) {
        // Mise à jour
        $stmt = $pdo->prepare("UPDATE t_d_produit SET nom_long = ?, nom_court = ?, prix_achat = ?, id_categorie = ?, id_fournisseur = ?,taux_tva = ?, ref_fournisseur = ?, photo = ? WHERE id_produit = ?");
        $stmt->execute([$nom, $nom_court, $prix, $categorie_id, $fournisseur_id, $taux_tva, $ref_fournisseur,$photo, $produit['Id_Produit']]);
        $message = "Produit mis à jour avec succès !";
    } else {
        // Création
        $stmt = $pdo->prepare("INSERT INTO t_d_produit (nom_long, nom_court, prix_achat, id_categorie, id_fournisseur,taux_tva,ref_fournisseur, photo) VALUES (?, ?, ?, ?, ?,?,?, ?)");
        $stmt->execute([$nom, $nom_court, $prix, $categorie_id, $fournisseur_id, $taux_tva, $ref_fournisseur,$photo]);
        $message = "Produit créé avec succès !";
    }
}
// Récupération des noms de la catégorie et du fournisseur pour l'affichage en mode lecture
$categoriesMap = array_column($categories, 'Libelle', 'Id_Categorie');
$categorieNom = isset($produit['Id_Categorie'], $categoriesMap[$produit['Id_Categorie']])
    ? $categoriesMap[$produit['Id_Categorie']]
    : '';

$fournisseursMap = array_column($fournisseurs, 'Nom_Fournisseur', 'Id_Fournisseur');
$fournisseurNom = isset($produit['Id_Fournisseur'], $fournisseursMap[$produit['Id_Fournisseur']])
    ? $fournisseursMap[$produit['Id_Fournisseur']]
    : '';
?>


<h1><?= isset($_GET['slug']) ? "Détails du produit" : "Créer un produit" ?></h1>

<?php if ($message): ?>
    <p style="color: green;"><?= htmlentities($message) ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlentities($produit['Id_Produit']) ?>">

    <div>
        <label for="nom">Nom :</label>
       
    <textarea name="nom" id="nom" <?= $isCommercialOrAdmin ? '' : 'readonly' ?> required><?= isset($produit['Nom_Long']) ? htmlentities($produit['Nom_Long']) : '' ?></textarea>
 </div>

    <div>
        <label for="nom_court">Nom court :</label>
        <input type="text" name="nom_court" id="nom_court" value="<?= isset($produit['Nom_court']) ? htmlentities($produit['Nom_court']) : '' ?>" <?= !$isCommercialOrAdmin ? 'readonly' : ''  ?>" <?= $isCommercialOrAdmin ? '' : 'readonly' ?> required>
    </div>

    <div>
        <label for="ref_fournisseur">Référence fournisseur :</label>
        <input type="text" name="ref_fournisseur" id="ref_fournisseur" value="<?= isset($produit['Ref_Fournisseur']) ? htmlentities($produit['Ref_Fournisseur']) : '' ?>" <?= !$isCommercialOrAdmin ? 'readonly' : '' ?> required>
    </div>

    <div>
        <label for="prix">Prix :</label>
        <input type="number" step="0.01" name="prix" id="prix" value="<?= isset($produit['Prix_Achat']) ? htmlentities($produit['Prix_Achat']) : '' ?>" <?= $isCommercialOrAdmin ? '' : 'readonly' ?> required>
    </div>

    <div>
        <label for="taux_tva">Taux de TVA :</label>
        <input type="number" step="0.01" name="taux_tva" id="taux_tva" value="<?= isset($produit['Taux_TVA']) ? htmlentities($produit['Taux_TVA']) : '' ?>" <?= !$isCommercialOrAdmin ? 'readonly' : '' ?> required>
    </div>

    <div class="mb-3">
        <label for="id_categorie" class="form-label">Catégorie</label>
        <select class="form-select" id="id_categorie" name="categorie" <?= !$isCommercialOrAdmin ? 'disabled' : '' ?>>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?= $categorie['Id_Categorie'] ?>" <?= (isset($produit['id_categorie']) && $produit['id_categorie'] == $categorie['Id_Categorie']) ? 'selected' : '' ?>>
                    <?= htmlentities($categorie['Libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="id_fournisseur" class="form-label">Fournisseur</label>
        <select class="form-select" id="id_fournisseur" name="fournisseur" <?= !$isCommercialOrAdmin ? 'disabled' : '' ?>>
            <?php foreach ($fournisseurs as $fournisseur): ?>
                <option value="<?= $fournisseur['Id_Fournisseur'] ?>" <?= (isset($produit['id_fournisseur']) && $produit['id_fournisseur'] == $fournisseur['Id_Fournisseur']) ? 'selected' : '' ?>>
                    <?= htmlentities($fournisseur['Nom_Fournisseur']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="photo">Photo :</label>
        <?php if ($isCommercialOrAdmin): ?>
            <input type="file" name="photo" id="photo">
        <?php endif; ?>
        <?php if ( isset($produit['Photo'])): ?>
            <p>Photo actuelle : <img src="images/<?= htmlentities($produit['Photo']) ?>" alt="Photo" style="width: 100px;"></p>
        <?php endif; ?>
    </div>

    <?php if ($isCommercialOrAdmin): ?>
        <button type="submit">Enregistrer</button>
    <?php endif; ?>
</form>




<?php
include('footer.php');
?>