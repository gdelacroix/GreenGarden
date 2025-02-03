# GreenGarden

## Initialisation du projet

### Création de la base de données
Avant de lancer le projet, il est nécessaire d'initialiser la base de données. Pour cela, suivez les étapes ci-dessous :

1. Créez une base de données MySQL nommée `greengarden`.
2. Exécutez le script `greengarden (1).sql` pour initialiser la structure et les données de la base.

### Utilisateurs de la base de données
La base de données comporte plusieurs utilisateurs avec les identifiants suivants :

| Nom d'utilisateur | Mot de passe |
|------------------|-------------|
| TITI            | TITI        |
| TATA            | TATA        |
| TUTU            | TUTU        |
| TETE            | TETE        |
| TOTO            | TOTO        |
| TYTY            | TYTY        |

Chacun de ces utilisateurs a un mot de passe identique à son identifiant.

## Configuration et démarrage
1. Assurez-vous que votre serveur de base de données est actif et que la base `greengarden` est bien créée. Attention, l'utilisateur par défaut doit être `root` sans mot de passe.
2. Configurez votre projet pour utiliser les identifiants de connexion correspondants.
3. Lancez votre projet et vérifiez que tout fonctionne correctement.

## Historique du développement

### Partie 1 - Initialisation du projet
**Commit:** `b2a3cd7f3a50d20f1756094962b7003446729c75`
- Création de la page d'accueil avec affichage des produits.

### Partie 2 - Authentification et gestion des utilisateurs
**Commit:** `fb824bcd8995f3c15151b3b63d0cb8debe48f4fd`
- Création des fonctionnalités de login, logout et inscription avec hashage des mots de passe.
- Gestion des informations de connexion (utilisateur, type d'utilisateur) via des variables de session.
- Création de la page produit :
  - Mode lecture seule pour les utilisateurs "lambda".
  - Mode création/modification pour les administrateurs et commerciaux.

### Partie 3 - Gestion du panier et des commandes
**Commit:** `a89099ef66acb8575dbad6bf00597d5e1df2ef64`
- Mise en place du panier (stocké en session).
- Création du processus de commande finale.

### Partie 4 - Refactorisation en POO
**Commit:** `jkl1121`
- Implémentation d'une architecture orientée objet.
- Création d'une classe `dao` pour gérer les requêtes SQL.
- Création des classes `Produit`, `Categorie` et `Fournisseur`.
- Intégration de la POO dans les pages `index.php`, `Produit.php`, `Categorie.php` et `Commande.php`.

## Remarque
Si vous rencontrez des problèmes de connexion à la base de données, assurez-vous que les utilisateurs et leurs mots de passe sont bien configurés et ont les permissions nécessaires sur la base `greengarden`.

Have Fun ! 🚀

