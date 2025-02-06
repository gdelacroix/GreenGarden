<!-- views/fournisseur/show.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Fournisseur</title>
</head>
<body>
    <h1>Détails du Fournisseur</h1>
    <p><strong>ID :</strong> <?php echo $fournisseur->getId_Fournisseur(); ?></p>
    <p><strong>Nom du Fournisseur :</strong> <?php echo $fournisseur->getNom_Fournisseur(); ?></p>
    <a href="index.php?controller=fournisseur&action=index">Retour à la liste</a>
</body>
</html>
