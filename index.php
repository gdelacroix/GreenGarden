<?php
include('header.php');
?>
<?php
// Configuration de la base de données
$host = '127.0.0.1';
$dbname = 'greengarden';
$username = 'root';
$password = '';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Initialisation des variables
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Requête SQL pour récupérer les produits
$sql = "SELECT 
            p.Id_Produit, p.Nom_court, p.Photo, 
            c.Libelle AS Categorie, 
            f.Nom_fournisseur AS Fournisseur,
            p.Prix_Achat, p.Taux_TVA,
            (p.Prix_Achat + (p.Prix_Achat * p.Taux_TVA / 100)) AS Prix_TTC
        FROM t_d_produit p
        INNER JOIN t_d_categorie c ON p.Id_Categorie = c.Id_Categorie
        INNER JOIN t_d_fournisseur f ON p.Id_Fournisseur = f.Id_Fournisseur";

// Ajout du filtre de recherche si applicable
if (!empty($search)) {
    $sql .= " WHERE p.Nom_court LIKE '%$search%'";
}

// Exécution de la requête
$products = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images/<?= htmlentities($product['Photo']) ?>"
                            class="card-img-top" alt="<?= htmlentities($product['Nom_court']) ?>" 
                            style="height: 200px; object-fit: cover;"  onerror="this.onerror=null; this.src='images/erreur.webp';">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlentities($product['Nom_court']) ?></h5>
                            <p class="card-text"><strong>Catégorie :</strong>
                                <?= htmlentities($product['Categorie']) ?></p>
                            <p class="card-text"><strong>Fournisseur :</strong>
                                <?= htmlentities($product['Fournisseur']) ?></p>
                            <p class="card-text"><strong>Prix TTC :</strong>
                                <?= number_format($product['Prix_TTC'], 2, ',', ' ') ?> €</p>
                        </div>
                    </div>
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
include('footer.php');
?>