<?php include('include/header.php');
// Récupérer les informations du client si connecté
$Utilisateur = $_SESSION['user_id'] ?? null;

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$clientExistant = null;
$adressesExistantes = [];
$erreurs = [];
$success = "";

function insererAdresse($pdo, $ligne1, $ligne2, $ligne3, $cp, $ville, $id_client)
{
    $stmt = $pdo->prepare('INSERT INTO t_d_adresse (Ligne1_Adresse, Ligne2_Adresse, Ligne3_Adresse, CP_Adresse, Ville_Adresse,Id_Client) VALUES (?, ?, ?, ?, ?,?)');
    $stmt->execute([$ligne1, $ligne2, $ligne3, $cp, $ville, $id_client]);
    return $pdo->lastInsertId();
}



// Récupérer les types de paiement
$typesPaiement = $pdo->query("SELECT * FROM t_d_type_paiement")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les types de clients
$typesClient = $pdo->query("SELECT * FROM t_d_type_client")->fetchAll(PDO::FETCH_ASSOC);

if ($Utilisateur) {
    $stmtClient = $pdo->prepare("SELECT * FROM t_d_client WHERE Id_User = :iduser");
    $stmtClient->execute([':iduser' => $_SESSION['user_id']]);
    $clientExistant = $stmtClient->fetch(PDO::FETCH_ASSOC);

    if ($clientExistant) {
        $client_id = $clientExistant['Id_Client'];
        $stmtAdresses = $pdo->prepare("SELECT * FROM t_d_adresse 
         WHERE Id_Client = :idClient");
        $stmtAdresses->execute([':idClient' => $clientExistant['Id_Client']]);
        $adressesExistantes = $stmtAdresses->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($adressesExistantes);
    }
}

// Gestion du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_client = $_POST['nom_client'] ?? null;
    $prenom_client = $_POST['prenom_client'] ?? null;
    $tel_client = $_POST['tel_client'] ?? null;
    $nom_societe = $_POST['nom_societe'] ?? null;
    $type_client = $_POST['type_client'] ?? null;

    // Récupération des données du formulaire
    $commandeId=0;
    $adresse_facturation = [];
    $adresse_livraison = [];
    $adresseFacturationId = null;
    $adresseLivraisonId = null;

    // Initialisation des adresses
    // Gestion de l'adresse de facturation
    if ($_POST['adresse_facturation'] === "Nouvelle") {
        // Nouvelle adresse sélectionnée
        $adresse_facturation = [
            'ligne1' => $_POST['facturation_ligne1'] ?? '',
            'ligne2' => $_POST['facturation_ligne2'] ?? '',
            'ligne3' => $_POST['facturation_ligne3'] ?? '',
            'cp' => $_POST['facturation_cp'] ?? '',
            'ville' => $_POST['facturation_ville'] ?? ''
        ];

        // Validation
        if (empty($adresse_facturation['ligne1']) || empty($adresse_facturation['cp']) || empty($adresse_facturation['ville'])) {
            $erreurs[] = "Les champs Ligne 1, CP et Ville sont obligatoires pour la nouvelle adresse de facturation.";
        }
    } elseif (!empty($_POST['adresse_facturation'])) {
        // Adresse existante sélectionnée
        $id = (int)$_POST['adresse_facturation'];
        $adresseFacturationId = $id;
        foreach ($adressesExistantes as $adresse) {
            if ((int)$adresse['Id_Adresse'] === $id) {
                $adresse_facturation = $adresse; // Affecter l'adresse correspondante
                break; // Sortir de la boucle une fois que l'adresse est trouvée
            }
        }
    }
    $memeAdresse = isset($_POST['meme_adresse']) ? true : false;
    if (!$memeAdresse) {
        // Gestion de l'adresse de livraison
        if ($_POST['adresse_livraison'] === "Nouvelle") {
            // Nouvelle adresse sélectionnée
            $adresse_livraison = [
                'ligne1' => $_POST['livraison_ligne1'] ?? '',
                'ligne2' => $_POST['livraison_ligne2'] ?? '',
                'ligne3' => $_POST['livraison_ligne3'] ?? '',
                'cp' => $_POST['livraison_cp'] ?? '',
                'ville' => $_POST['livraison_ville'] ?? ''
            ];


            // Validation
            if (empty($adresse_livraison['ligne1']) || empty($adresse_livraison['cp']) || empty($adresse_livraison['ville'])) {
                $erreurs[] = "Les champs Ligne 1, CP et Ville sont obligatoires pour la nouvelle adresse de livraison.";
            }
        } elseif (!empty($_POST['adresse_livraison'])) {
            // Adresse existante sélectionnée
            $id = (int)$_POST['adresse_livraison'];
            $adresseLivraisonId = $id;

            foreach ($adressesExistantes as $adresse) {
                if ((int)$adresse['Id_Adresse'] === $id) {
                    $adresse_livraison = $adresse; // Affecter l'adresse correspondante
                    break; // Sortir de la boucle une fois que l'adresse est trouvée
                }
            }
        }
    } else {
        $adresse_livraison = $adresse_facturation;
    }



    $type_paiement = $_POST['type_paiement'] ?? null;

    // Validation des champs client
    if (!$clientExistant) {
        if (empty($nom_client) || empty($prenom_client) || empty($tel_client)) {
            $erreurs[] = "Les champs Nom, Prénom et Téléphone sont obligatoires.";
        }
        if ($type_client == 'Professionnel' && empty($nom_societe)) {
            $erreurs[] = "Le champ Nom de la société est obligatoire pour un client professionnel.";
        }
    }

    // Si pas d'erreurs, insérer ou mettre à jour les données
    if (empty($erreurs)) {
        try {

            // on démarre une transcaction : on fait plusieurs insert d'un seul coup ou rien
            $pdo->beginTransaction();

            // Insérer le client si non existant
            if (!$clientExistant) {
                $stmt = $pdo->prepare("
                    INSERT INTO t_d_client (Nom_Client, Prenom_Client, Tel_Client, Nom_Societe_Client, Id_Type_Client,Id_Commercial,Id_User)
                    VALUES (:nom, :prenom, :tel, :societe, :type_client,1, :id_user)
                ");
                $stmt->execute([
                    'nom' => $nom_client,
                    'prenom' => $prenom_client,
                    'tel' => $tel_client,
                    'societe' => $type_client == 'professionnel' ? $nom_societe : null,
                    'type_client' => $type_client,
                    'id_user' => $Utilisateur,
                ]);
                $client_id = $pdo->lastInsertId();
            }
            // Gestion de l'adresse de facturation
            if (is_null($adresseFacturationId)) {
                // Insérer une nouvelle adresse dans la table t_d_adresse
                $adresseFacturationId = insererAdresse($pdo, $adresse_facturation['ligne1'], $adresse_facturation['ligne2'], $adresse_facturation['ligne3'], $adresse_facturation['cp'], $adresse_facturation['ville'], $client_id);;
            }
            // Gestion de l'adresse de livraison
            if ($memeAdresse) {
                $adresseLivraisonId = $adresseFacturationId;
            } elseif (is_null($adresseLivraisonId)) {
                // Insérer une nouvelle adresse dans la table t_d_adresse
                $adresseLivraisonId = insererAdresse($pdo, $adresse_livraison['ligne1'], $adresse_livraison['ligne2'], $adresse_livraison['ligne3'], $adresse_livraison['cp'], $adresse_livraison['ville'], $client_id);
            }


            // Insérer la commande
            $stmt = $pdo->prepare("
                INSERT INTO t_d_commande (Id_Client,  Id_Statut,Date_Commande, Id_TypePaiement, Remise_Commande)
                VALUES (:client_id, 1 , NOW(), :paiement, :remise)
            ");
            $remise = 0;
            if ($type_client == 'professionnel') {
                $remise = 10; // Remise 10% pour professionnels
            }
            $stmt->execute([
                'client_id' => $client_id,
                'paiement' => $type_paiement,
                'remise' => $remise,
            ]);
            $commandeId = $pdo->lastInsertId();

            //insérer les adresses pour la commande
            $stmt = $pdo->prepare('INSERT INTO t_d_adressecommande (Id_Commande, Id_Adresse, Id_Type) VALUES (?, ?, ?)');
            $stmt->execute([$commandeId, $adresseFacturationId, 2]); // Type 2 = Facturation
            $stmt->execute([$commandeId, $adresseLivraisonId, 1]); // Type 1 = Livraison



            //créer une ligne dans t_d_expedition
            $stmt = $pdo->prepare('INSERT INTO t_d_expedition (Date_Expedition) VALUES (NULL)');
            $stmt->execute();
            $expeditionId = $pdo->lastInsertId();
            //insérer les lignes de commandes
            foreach ($_SESSION['Panier'] as $produit) {
                // Récupérer l'Id_Product à partir du slug
                $stmt = $pdo->prepare('SELECT Id_Produit FROM t_d_produit WHERE Slug = ?');
                $stmt->execute([$produit['slug']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $productId = $product['Id_Produit']; //info de la BDD
                    $quantite = $produit['quantite']; // info du panier

                    // Insérer la ligne de commande
                    $stmt = $pdo->prepare('
                        INSERT INTO t_d_lignecommande (Id_Commande, Id_Produit, Quantite, Id_Expedition) 
                        VALUES (?, ?, ?, ?)
                    ');
                    $stmt->execute([$commandeId, $productId, $quantite, $expeditionId]);
                } else {
                    echo "Produit non trouvé pour le slug : $produit[slug]<br>";
                }
            }

            // Message de confirmation
            echo "Toutes les lignes de commande ont été insérées.";


            $pdo->commit();
            $success = "Commande enregistrée avec succès.";
            $_SESSION['Panier'] = []; // Vider le panier après la commande
            $_SESSION['commandeId'] = $commandeId;
            // Redirection vers la page CommandeValide.php
            echo '<meta http-equiv="refresh" content="0;url=CommandeValide.php">';
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $erreurs[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer une commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1>Passer une commande</h1>

        <?php if (!empty($erreurs)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlentities($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?= htmlentities($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <h3>Informations client</h3>
            <div class="mb-3" style="<?= $clientExistant ? 'display: none;' : '' ?>">
                <label for="nom_client" class="form-label">Nom *</label>
                <input type="text" name="nom_client" id="nom_client" class="form-control" <?= $clientExistant ? '' : 'required' ?>>
            </div>
            <div class="mb-3" style="<?= $clientExistant ? 'display: none;' : '' ?>">
                <label for="prenom_client" class="form-label">Prénom *</label>
                <input type="text" name="prenom_client" id="prenom_client" class="form-control" <?= $clientExistant ? '' : 'required' ?>>
            </div>
            <div class="mb-3" style="<?= $clientExistant ? 'display: none;' : '' ?>">
                <label for="tel_client" class="form-label">Téléphone *</label>
                <input type="text" name="tel_client" id="tel_client" class="form-control" <?= $clientExistant ? '' : 'required' ?>>
            </div>
            <div class="mb-3" style="<?= $clientExistant ? 'display: none;' : '' ?>">
                <label for="type_client" class="form-label">Type de client *</label>
                <select name="type_client" id="type_client" class="form-select" <?= $clientExistant ? '' : 'required' ?>>
                    <?php foreach ($typesClient as $type): ?>
                        <option value="<?= $type['Id_Type_Client'] ?>"><?= htmlentities($type['Libelle_Type_Client']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3" id="societe_field" style="<?= $clientExistant ? 'display: none;' : '' ?>">
                <label for="nom_societe" class="form-label">Nom de la société *</label>
                <input type="text" name="nom_societe" id="nom_societe" class="form-control">
            </div>
            <?php if ($clientExistant): ?>

                <p>Vous êtes connecté en tant que <strong><?= htmlentities($clientExistant['Nom_Client'] . ' ' . $clientExistant['Prenom_Client']) ?></strong>.</p>
            <?php endif; ?>

            <h3>Adresses</h3>
            <!-- Adresse de facturation -->
            <div class="mb-3">
                <label for="adresse_facturation" class="form-label">Adresse de facturation existante</label>
                <select name="adresse_facturation" id="adresse_facturation" class="form-select">
                    <option value="Nouvelle">Nouvelle adresse</option>
                    <?php

                    foreach ($adressesExistantes as $adresse): ?>
                        <option value="<?= $adresse['Id_Adresse'] ?>"><?= htmlentities($adresse['Ligne1_Adresse'] . ', ' . $adresse['Ville_Adresse']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="nouvelle_adresse_facturation">
                <div class="mb-3">
                    <label for="facturation_ligne1" class="form-label">Ligne 1 *</label>
                    <input type="text" name="facturation_ligne1" id="facturation_ligne1" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="facturation_ligne2" class="form-label">Ligne 2</label>
                    <input type="text" name="facturation_ligne2" id="facturation_ligne2" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="facturation_ligne3" class="form-label">Ligne 3</label>
                    <input type="text" name="facturation_ligne3" id="facturation_ligne3" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="facturation_cp" class="form-label">Code postal *</label>
                    <input type="text" name="facturation_cp" id="facturation_cp" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="facturation_ville" class="form-label">Ville *</label>
                    <input type="text" name="facturation_ville" id="facturation_ville" class="form-control">
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="meme_adresse" name="meme_adresse">
                <label class="form-check-label" for="meme_adresse">
                    Utiliser l'adresse de facturation comme adresse de livraison
                </label>
            </div>
            <!-- Adresse de livraison -->
            <div class="mb-3">
                <label for="adresse_livraison" class="form-label">Adresse de livraison existante</label>
                <select name="adresse_livraison" id="adresse_livraison" class="form-select">
                    <option value="Nouvelle">Nouvelle adresse</option>
                    <?php foreach ($adressesExistantes as $adresse): ?>
                        <option value="<?= $adresse['Id_Adresse'] ?>"><?= htmlentities($adresse['Ligne1_Adresse'] . ', ' . $adresse['Ville_Adresse']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="nouvelle_adresse_livraison">
                <div class="mb-3">
                    <label for="livraison_ligne1" class="form-label">Ligne 1 *</label>
                    <input type="text" name="livraison_ligne1" id="livraison_ligne1" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="livraison_ligne2" class="form-label">Ligne 2</label>
                    <input type="text" name="livraison_ligne2" id="livraison_ligne2" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="livraison_ligne3" class="form-label">Ligne 3</label>
                    <input type="text" name="livraison_ligne3" id="livraison_ligne3" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="livraison_cp" class="form-label">Code postal *</label>
                    <input type="text" name="livraison_cp" id="livraison_cp" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="livraison_ville" class="form-label">Ville *</label>
                    <input type="text" name="livraison_ville" id="livraison_ville" class="form-control">
                </div>
            </div>

            <h3>Paiement</h3>
            <div class="mb-3">
                <label for="type_paiement" class="form-label">Mode de paiement *</label>
                <select name="type_paiement" id="type_paiement" class="form-select" required>
                    <?php foreach ($typesPaiement as $type): ?>
                        <option value="<?= $type['Id_TypePaiement'] ?>"><?= htmlentities($type['Libelle_TypePaiement']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Passer commande</button>
        </form>
    </div>


    <?php
    include('include/footer.php');
    ?>