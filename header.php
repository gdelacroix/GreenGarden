<?php
 session_start(); // Démarrer la session

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        // Récupération du nom du fichier PHP en cours sans extension
        echo ucfirst(basename($_SERVER['PHP_SELF'], '.php'));
        ?>
    </title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <?php
    if (!isset($_SESSION['Panier'])) {
        $_SESSION['Panier'] = [];
    }

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

    // Vérifier si l'utilisateur est commercial ou admin
    $isCommercialOrAdmin = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['Commercial', 'Admin']);
    $estConnecte = isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true ; // Vérifie si une session utilisateur existe
    // Initialiser le panier si nécessaire
    if (!isset($_SESSION['Panier'])) {
        $_SESSION['Panier'] = [];
    }

    // Obtenir le total des quantités
    $totalQuantity = array_sum(array_column($_SESSION['Panier'], 'quantity'));
    ?>
    <nav class="navbar navbar-expand-lg 
    <?php
    // Appliquer des classes spécifiques selon le type d'utilisateur
    if (isset($_SESSION['user_type'])) {
        switch ($_SESSION['user_type']) {
            case 'Commercial':
            case 'SAV':
                echo 'bg-success'; // Vert pour Commercial et SAV
                break;
            case 'Admin':
                echo 'bg-danger'; // Rouge pour Admin
                break;
            default:
                echo 'bg-body-tertiary'; // Couleur par défaut
                break;
        }
    } else {
        echo 'bg-body-tertiary'; // Couleur par défaut si non connecté
    }
    ?>">

        <div class="container-fluid">
            <a class="navbar-brand" href="#">GreenGarden</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <?php if ($isCommercialOrAdmin): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="Produit.php">Ajouter un produit</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="Categories.php">Toutes les catégories</a></li>
                                <li><a class="dropdown-item" href="Categorie.php">Ajouter une catégorie</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">

                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#cartModal">
                            Panier (<span id="cart-count"><?= isset($_SESSION['Panier']) ? array_sum(array_column($_SESSION['Panier'], 'quantite')) : 0 ?></span>)
                        </button>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if ($username): ?>
                        <span class="navbar-text me-3">Bienvenue, <?= htmlentities($username) ?>!</span>
                        <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                        <a href="inscription.php" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
                <!-- <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
            </div>
        </div>
    </nav>
    <!-- Modal -->
    <!-- Modal du panier -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Votre panier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($_SESSION['Panier'])): ?>
                        <ul class="list-group">
                            <?php foreach ($_SESSION['Panier'] as $produit): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong><?= htmlspecialchars($produit['libelle']) ?></strong><br>
                                        Prix TTC: <?= number_format($produit['prix'], 2) ?> €
                                    </span>
                                    <div>

                                        <!-- Formulaire pour décrémenter -->
                                        <form action="panier.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="decrementer">
                                            <input type="hidden" name="slug" value="<?= $produit['slug'] ?>">
                                            <button type="submit" class="btn btn-warning btn-sm">-</button>
                                        </form>

                                        <?= $produit['quantite'] ?>
                                        <!-- Formulaire pour incrémenter -->
                                        <form action="panier.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="incrementer">
                                            <input type="hidden" name="slug" value="<?= $produit['slug'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm">+</button>
                                        </form>

                                        <!-- Formulaire pour supprimer -->
                                        <form action="panier.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="supprimer">
                                            <input type="hidden" name="slug" value="<?= $produit['slug'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="mt-3 text-end">
                            <strong>Total: </strong>
                            <?= number_format(array_sum(array_map(fn($p) => $p['prix'] * $p['quantite'], $_SESSION['Panier'])), 2) ?> €
                        </p>

                        <!-- Formulaire pour vider le panier -->
                        <form action="panier.php" method="POST" class="text-end">
                            <input type="hidden" name="action" value="vider">
                            <button type="submit" class="btn btn-danger">Vider le panier</button>
                        </form>

                        <?php if ($estConnecte): ?>
                            <!-- Bouton pour passer la commande si l'utilisateur est connecté -->
                            <a href="commande.php" class="btn btn-primary">Passer la commande</a>
                        <?php else: ?>
                            <!-- Message si l'utilisateur n'est pas connecté -->
                            <p class="text-danger">Vous devez être connecté pour passer une commande.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Votre panier est vide.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>