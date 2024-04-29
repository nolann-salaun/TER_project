
<?php
session_start(); // Démarre une nouvelle session ou reprend une session existante

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";  // Mot de passe de la base de données, souvent vide par défaut sous XAMPP
    $dbname = "mydatabase";

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Préparation de la requête SQL pour vérifier les identifiants
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Vérifiez si le mot de passe dans la base de données correspond au mot de passe saisi
        if (password_verify($password, $hashed_password)) {
            // Mot de passe correct, démarrer la session
            $_SESSION['user_email'] = $email;
            header('Location: Homepage.html'); // Redirige vers la page d'accueil
            exit();
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Invalid email or password";
    }

    $stmt->close();
    $conn->close();
}
?>