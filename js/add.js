"use strict"

document.getElementById('ajouterPostForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const titre = document.getElementById('titre').value;
    const commentaire = document.getElementById('commentaire').value;

    if (!titre || !commentaire) {
        alert('Veuillez remplir tous les champs.');
        return;
    }

    const token = localStorage.getItem('token');
    if (!token) {
        alert('Vous devez être connecté pour ajouter un post.');
        return;
    }

    fetch('./php/dispach.php/check/token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ token: token })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const userId = data.userId;

            const formData = new FormData();
            formData.append('Titre', titre);
            formData.append('commentaire', commentaire);
            formData.append('iduser', userId);

            const files = document.getElementById('fichier').files;
            for (let i = 0; i < files.length; i++) {
                formData.append('fichier[]', files[i]);
            }

            return fetch('./php/dispach.php/post/create', {
                method: 'POST',
                body: formData,
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
        } else {
            throw new Error('Erreur: ' + data.error);
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text(); // Get the response as text
    })
    .then(text => {
        try {
            const data = JSON.parse(text); // Try to parse the text as JSON
            console.log(data);
            if (data.success) {
                alert('Post ajouté avec succès!');
                window.location.href = 'index.html'; // Redirect to the desired page
            } else {
                alert('Erreur: ' + data.error);
            }
        } catch (error) {
            console.error('Invalid JSON:', text);
            alert('Une erreur est survenue lors de l\'ajout du post.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de l\'ajout du post.');
    });
});