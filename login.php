<?php include 'include/header.php' ;
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php


// Vérification si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
	header('Location:index.php'); // Redirection vers la page d'accueil si l'utilisateur est déjà connecté

}

// Traitement de la soumission du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Récupération des données du formulaire en méthode POST
	$login = $_POST['login'];
	$password = $_POST['password'];


	$stmt = $pdo->prepare("SELECT * FROM t_d_user WHERE Login=:login");
	$stmt->bindValue(':login', $login);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user && password_verify($password, $user['Password'])) {
		try {
			// Connexion réussie, stockage de l'identifiant de l'utilisateur dans la variable de session
			$_SESSION['user_id'] = $user['Id_User'];


			//code...
			//recup le type d'utilisateur pour renseigner la variable de session user_type
			$stmt = $pdo->prepare("SELECT * FROM t_d_usertype WHERE Id_UserType=:typeuser");
			$stmt->bindValue(':typeuser', $user['Id_UserType']);
			$stmt->execute();
			$usert = $stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['user_type']	= $usert['Libelle'];
			$_SESSION['logged_in'] = true;
			//header('Location:index.php'); // Redirection vers la page d'accueil
			echo '<meta http-equiv="refresh" content="0;url=index.php">';
			exit();
		} catch (\Throwable $th) {
			//header('Location:index.php'); // Redirection vers la page d'accueil
			echo '<meta http-equiv="refresh" content="0;url=index.php">';
			exit();
		}
	} else {
		// Identifiants incorrects, affichage d'un message d'erreur
		$error_message = "Email ou mot de passe incorrect.";
	}
}
?>


<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width: 400px;">
        <h2 class="text-center mb-4">Connexion</h2>
<?php if (isset($error_message)) : ?>
	<div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
<?php endif; ?>
<form method="POST">
<div class="mb-3">
	<label for="login" class="form-label">Login :</label>
	<input type="login" id="login" name="login" class="form-control" required>
	</div>
	<div class="mb-3">
	<label for="password" class="form-label">Mot de passe :</label>
	<input type="password" id="password" name="password" class="form-control"  required>
	</div>
	<input type="submit" value="Se connecter" class="btn btn-primary w-100">
</form>
<p class="mt-3 text-center">Pas encore inscrit ? <a href="inscription.php">S'inscrire</a></p>
    </div>
</div>
<?php include 'include/footer.php' ?>