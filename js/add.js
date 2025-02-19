"use strict"

document.getElementById('ajouterPostForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData();
    formData.append('Titre', document.getElementById('titre').value);
    formData.append('commentaire', document.getElementById('commentaire').value);
    formData.append('iduser', 1);

    const files = document.getElementById('fichier').files;
    for (let i = 0; i < files.length; i++) {
        formData.append('fichier[]', files[i]);
    }

    fetch('http://localhost/2024-2025/AtWeb/Mercredi/2emeSemestre/Blog/php/dispach.php/post/create', {
        method: 'POST',
        body: formData,
        headers: {
            'Authorization': 'Bearer votre-token'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            alert('Post ajouté avec succès!');
        })

});
