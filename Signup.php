<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $userPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];

    // Vérification que le mot de passe et la confirmation sont identiques
    if ($userPassword !== $confirmPassword) {
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
        header('Location: Inscription.html'); // Redirection vers la page HTML
        exit();
    }

    // Paramètres de la base de données
    $servername = "localhost";
    $username = "root"; // Nom d'utilisateur de la base de données
    $dbPassword = ""; // Mot de passe de la base de données
    $dbname = "mydatabase";

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Vérification de l'unicité de l'email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Cet email est déjà utilisé.";
        header('Location: Inscription.html'); // Redirection vers la page HTML
        exit();
    }
    $stmt->close();

    // Insertion des données dans la table 'users'
    $stmt = $conn->prepare("INSERT INTO users (email, password, firstname, lastname, birthdate) VALUES (?, ?, ?, ?, ?)");
    // Hachage du mot de passe de manière sécurisée
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
    $stmt->bind_param("sssss", $email, $hashedPassword, $firstname, $lastname, $birthdate);

    // Exécution de la requête préparée
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Inscription réussie. Vous pouvez vous connecter.";
        header('Location: Inscription.html'); // Redirection vers la page HTML pour se connecter
    } else {
        $_SESSION['error_message'] = "Erreur lors de l'inscription : " . $stmt->error;
        header('Location: Inscription.html'); // Redirection vers la page HTML
    }

    // Fermeture de la requête et de la connexion
    $stmt->close();
    $conn->close();
}
?>
