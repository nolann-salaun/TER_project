document.getElementById('signupForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const email = document.getElementById('email').value;
    const birthdate = document.getElementById('birthdate').value;

    // Vérification des champs du formulaire
    if (password !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas.');
        event.preventDefault(); // Empêcher la soumission du formulaire
    }

    if (!/^\w+@\w+\.\w+$/.test(email)) {
        alert('Adresse email non valide.');
        event.preventDefault();
    }

    if (!/^\d{8}$/.test(birthdate)) {
        alert('La date de naissance doit être au format AAAAMMJJ.');
        event.preventDefault();
    }
});
