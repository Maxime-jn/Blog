"use strict"

document.getElementById('ajouterPostForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const titre = document.getElementById('titre').value;
    const commentaire = document.getElementById('commentaire').value;

    if (!titre || !commentaire) {
        alert('Veuillez remplir tous les champs.');
        return;
    }

    const formData = new FormData();
    formData.append('Titre', titre);
    formData.append('commentaire', commentaire);
    formData.append('iduser', 1);

    const files = document.getElementById('fichier').files;
    for (let i = 0; i < files.length; i++) {
        formData.append('fichier[]', files[i]);
    }

    fetch('http://localhost/2024-2025/AtWeb/Mercredi/2emeSemestre/Blog/php/dispach.php/post/create', {
        method: 'POST',
        body: formData,
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            alert('Post ajouté avec succès!');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de l\'ajout du post.');
        });

});
