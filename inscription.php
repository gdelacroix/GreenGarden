<?php include 'include/header.php' ?>
<?php


// Vérification si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
	header('Location: index.php'); // Redirection vers la page d'accueil si l'utilisateur est déjà connecté
	exit();
}

// Traitement de la soumission du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Récupération des données du formulaire en méthode POST
	$login = $_POST['login'];
	$password = $_POST['password'];

	
	$stmt = $pdo->prepare("SELECT * FROM t_d_user WHERE login=:login");
	$stmt->bindValue(':login', $login);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user) {
		// L'utilisateur existe déjà, affichage d'un message d'erreur
		$error_message = "Ce login est déjà utilisé par un autre utilisateur.";
	} else {
		// Insertion de l'utilisateur dans la base de données
		$password_hash = password_hash($password, PASSWORD_DEFAULT); // Hashage du mot de passe
		$stmt = $pdo->prepare("INSERT INTO t_d_user (Login, Password,Id_UserType) 
		VALUES (:login, :mot_de_passe,1)"); //on force le type utilisateur à client
		$stmt->bindValue(':login', $login);
		$stmt->bindValue(':mot_de_passe', $password_hash);
		$stmt->execute();

		// Récupération de l'identifiant de l'utilisateur inséré
		$user_id = $pdo->lastInsertId();

		// Connexion automatique de l'utilisateur après son inscription
		$_SESSION['user_id'] = $user_id;

		header('Location: index.php'); // Redirection vers la page d'accueil
		exit();
	}
}
?>


<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width: 400px;">
        <h2 class="text-center mb-4">Inscription</h2>
	<?php if (isset($error_message)) : ?>
		<div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
	<?php endif; ?>
	<form method="POST">
	<div class="mb-3">
		<label for="login"  class="form-label">Votre Login :</label>
		<input type="login" id="login" name="login"  class="form-control" required>
		</div>
		<div class="mb-3">
		<label for="password"  class="form-label">Mot de passe :</label>
		<input type="password" id="password" name="password"  class="form-control" required>
		</div>
		<input type="submit"  class="btn btn-success w-100" value="S'inscrire">
	</form>
	<p class="mt-3 text-center">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </div>
</div>
<?php include 'include/footer.php' ?>