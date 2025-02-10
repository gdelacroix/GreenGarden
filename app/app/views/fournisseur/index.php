<!-- views/fournisseur/index.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Fournisseurs</title>
</head>
<body>
    <h1>Liste des Fournisseurs</h1>
    <a href="index.php?controller=fournisseur&action=create">Ajouter un nouveau fournisseur</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom du Fournisseur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fournisseurs as $fournisseur): ?>
                <tr>
                    <td><?php echo $fournisseur->getId_Fournisseur(); ?></td>
                    <td><?php echo $fournisseur->getNom_Fournisseur(); ?></td>
                    <td>
                        <a href="index.php?controller=fournisseur&action=showById&id=<?php echo $fournisseur->getId_Fournisseur(); ?>">Voir</a>
                        <a href="index.php?controller=fournisseur&action=update&id=<?php echo $fournisseur->getId_Fournisseur(); ?>">Modifier</a>
                        <a href="index.php?controller=fournisseur&action=delete&id=<?php echo $fournisseur->getId_Fournisseur(); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
