<?php
include('include/header.php');
?>
<?php


if (!isset($_SESSION['commandeId'])) {
    header('Location: index.php');
    exit;
}

$commandeId = $_SESSION['commandeId'];

// Récupérer les informations de la commande
$stmtCommande = $pdo->prepare("
    SELECT c.Id_Commande, c.Num_Commande, c.Date_Commande, 
           cl.Nom_Client, cl.Prenom_Client
    FROM t_d_commande c
    INNER JOIN t_d_client cl ON cl.Id_Client = c.Id_Client
        WHERE c.Id_Commande = :commandeId
");
$stmtCommande->execute([':commandeId' => $commandeId]);
$commande = $stmtCommande->fetch(PDO::FETCH_ASSOC);

// Récupérer les produits de la commande
$stmtProduits = $pdo->prepare("
    SELECT pc.Quantite,  p.Nom_Long,(p.Prix_Achat + (p.Prix_Achat * p.Taux_TVA / 100)) AS Prix_TTC
    FROM t_d_lignecommande pc
    INNER JOIN t_d_produit p ON p.Id_Produit = pc.Id_Produit
    WHERE pc.Id_Commande = :commandeId
");
$stmtProduits->execute([':commandeId' => $commandeId]);
$produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

// Supprimer l'ID de commande de la session (si vous ne voulez plus le conserver)
unset($_SESSION['commandeId']);
?>


<div class="container mt-5">
<div class="text-center">
        <h1 class="text-success">Commande Validée avec Succès !</h1>
        <p class="lead">Merci pour votre achat. Voici les détails de votre commande :</p>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Détails de la commande</h5>
            <ul class="list-group">
                <li class="list-group-item">
                    <strong>Numéro Commande :</strong> <?= htmlentities($commande['Num_Commande']) ?>
                </li>
                <li class="list-group-item">
                    <strong>Date :</strong> <?= htmlentities($commande['Date_Commande']) ?>
                </li>
            </ul>
        </div>
    </div>
    <h2  class="mb-3">Détail des produits</h2>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td><?= htmlentities($produit['Nom_Long']) ?></td>
                    <td><?= htmlentities($produit['Quantite']) ?></td>
                    <td><?= htmlentities(number_format($produit['Prix_TTC'], 2)) ?> €</td>
                    <td><?= htmlentities(number_format($produit['Prix_TTC'] * $produit['Quantite'], 2)) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary btn-lg">Retour à l'accueil</a>
    </div>
</div>



<?php
include('include/footer.php');

?>