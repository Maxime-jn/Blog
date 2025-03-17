// fetch("login.php", {
//     method: "POST",
//     body: new URLSearchParams({
//         username: "utilisateur",
//         password: "motdepasse"
//     })
// })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             localStorage.setItem("token", data.token); // Stocker le token
//             window.location.href = "../index.html"; // Rediriger vers la page d'accueil
//         } else {
//             alert("Échec de la connexion");
//         }
//     });


// fetch("check_token.php", {
//     method: "GET",
//     headers: {
//         "Authorization": "Bearer " + localStorage.getItem("token")
//     }
// })
//     .then(response => response.json())
//     .then(data => {
//         if (!data.success) {
//             alert("Session expirée. Veuillez vous reconnecter.");
//             localStorage.removeItem("token");
//             window.location.href = "../connexion.html";
//         } else {
//             console.log("Accès autorisé :", data.user);
//         }
//     });


// fetch("logout.php", {
//     method: "POST",
//     headers: {
//         "Authorization": "Bearer " + localStorage.getItem("token")
//     }
// })
//     .then(response => response.json())
//     .then(data => {
//         localStorage.removeItem("token"); // Supprimer le token
//         window.location.href = "../connexion.html"; // Rediriger vers la connexion
//     });


// document.addEventListener("DOMContentLoaded", function () {
//     const authButton = document.getElementById("authButton");
//     const token = localStorage.getItem("token");

//     if (token) {
//         // Utilisateur connecté -> Afficher Déconnexion
//         authButton.innerHTML = '<a href="#" id="logoutLink">Déconnexion</a>';

//         // Gestion de la déconnexion
//         document.getElementById("logoutLink").addEventListener("click", function (event) {
//             event.preventDefault();
//             fetch("php/logout.php", {
//                 method: "POST",
//                 headers: {
//                     "Authorization": "Bearer " + token
//                 }
//             })
//                 .then(response => response.json())
//                 .then(data => {
//                     localStorage.removeItem("token"); // Supprimer le token
//                     window.location.href = "connexion.html"; // Rediriger vers la connexion
//                 });
//         });
//     } else {
//         // Utilisateur non connecté -> Afficher Connexion
//         authButton.innerHTML = '<a href="connexion.html">Connexion</a>';
//     }
// });


// // // Écouteur pour la soumission du formulaire de connexion
// // document.getElementById("loginForm").addEventListener("submit", function (event) {
// //     event.preventDefault(); // Empêcher la soumission classique du formulaire

// //     // Récupérer les données du formulaire
// //     const username = document.getElementById("username").value;
// //     const password = document.getElementById("password").value;

// //     // Envoyer la requête de connexion via AJAX
// //     fetch("login.php", {
// //         method: "POST",
// //         body: new URLSearchParams({
// //             username: username,
// //             password: password
// //         })
// //     })
// //     .then(response => response.json())
// //     .then(data => {
// //         if (data.success) {
// //             // Si la connexion réussit, stocker le token dans localStorage
// //             localStorage.setItem("token", data.token);

// //             // Rediriger vers index.html
// //             window.location.href = "index.html";
// //         } else {
// //             // Si la connexion échoue, afficher un message d'erreur
// //             alert("Nom d'utilisateur ou mot de passe incorrect.");
// //         }
// //     });
// // });


document.addEventListener("DOMContentLoaded", function () {
    const authButton = document.getElementById("authButton");
    const token = localStorage.getItem("token");

    if (authButton) {
        if (token) {
            // Si l'utilisateur est connecté, afficher Déconnexion
            authButton.innerHTML = '<a href="#" id="logoutLink">Déconnexion</a>';

            // Gestion de la déconnexion
            document.getElementById("logoutLink").addEventListener("click", function (event) {
                event.preventDefault();
                fetch("php/logout.php", {
                    method: "POST",
                    headers: {
                        "Authorization": "Bearer " + token
                    }
                })
                .then(response => response.json())
                .then(() => {
                    localStorage.removeItem("token"); // Supprimer le token local
                    window.location.href = "connexion.html"; // Rediriger vers la connexion
                });
            });
        } else {
            // Si l'utilisateur n'est pas connecté, afficher Connexion et Inscription
            authButton.innerHTML = '<a href="connexion.html">Connexion</a> | <a href="inscription.html">Inscription</a>';
        }
    }

    // Gestion du formulaire de connexion
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();

            // Récupérer les données du formulaire
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            // Envoyer la requête AJAX pour la connexion
            fetch("php/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    username: username,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem("token", data.token); // Stocker le token
                    window.location.href = "index.html"; // Redirection après connexion
                } else {
                    alert("Nom d'utilisateur ou mot de passe incorrect.");
                }
            });
        });
    }

    // Gestion du formulaire d'inscription
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            event.preventDefault();

            // Récupération des données
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                alert("Les mots de passe ne correspondent pas.");
                return;
            }

            // Envoyer la requête d'inscription
            fetch("php/register.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    username: username,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Inscription réussie ! Vous pouvez maintenant vous connecter.");
                    window.location.href = "connexion.html";
                } else {
                    alert("Erreur : " + data.error);
                }
            });
        });
    }
});
