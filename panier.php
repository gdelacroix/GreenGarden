<?php
session_start();

if (!isset($_SESSION['Panier'])) {
    $_SESSION['Panier'] = [];
}

$action = $_POST['action'] ?? null;

switch ($action) {
    case 'ajouter':
        // Ajouter un produit au panier
        $slug = $_POST['slug'];
        $libelle = $_POST['libelle'];
        $prixTTC = (float) $_POST['prix'];


        // Vérifier si le produit existe déjà dans le panier
        $found = false;
        foreach ($_SESSION['Panier'] as &$produit) {
            if ($produit['slug'] === $slug) {
                $produit['quantite']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['Panier'][] = [
                'slug' => $slug,
                'libelle' => $libelle,
                'prix' => $prixTTC,
                'quantite' => 1,
            ];
        }
        break;

    case 'incrementer':
        // Incrémenter la quantité d'un produit
        $slug = $_POST['slug'];
        foreach ($_SESSION['Panier'] as &$produit) {
            if ($produit['slug'] === $slug) {
                $produit['quantite']++;
                break;
            }
        }
        break;

    case 'decrementer':
        // Décrémenter la quantité d'un produit
        $slug = $_POST['slug'];
        foreach ($_SESSION['Panier'] as $key => &$produit) {
            if ($produit['slug'] === $slug) {
                if ($produit['quantite'] > 1) {
                    $produit['quantite']--;
                } else {
                    // Si la quantité est 1, supprimer le produit
                    unset($_SESSION['Panier'][$key]);
                }
                break;
            }
        }
        // Réindexer le tableau pour éviter des trous dans les indices
        $_SESSION['Panier'] = array_values($_SESSION['Panier']);
        break;

    case 'supprimer':
        // Supprimer un produit du panier
        $slug = $_POST['slug'];
        foreach ($_SESSION['Panier'] as $key => $produit) {
            if ($produit['slug'] === $slug) {
                unset($_SESSION['Panier'][$key]);
                break;
            }
        }
        // Réindexer le tableau
        $_SESSION['Panier'] = array_values($_SESSION['Panier']);
        break;

    case 'vider':
        // Vider complètement le panier
        $_SESSION['Panier'] = [];
        break;

    default:
        // Action inconnue
        break;
}

header('Location:index.php');
exit;
