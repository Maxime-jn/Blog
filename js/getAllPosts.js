"use strict";

window.onload = async () => {
    try {
        const response = await fetch('php/dispach.php/get/posts', {
            headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
        });

        const posts = await response.json();
        const main = document.querySelector('main');
        const token = sessionStorage.getItem('token');

        posts.forEach(({ Titre, commentaire, path_ficher, idPosts }) => {
            let mediaElement = '';
            const fileExtension = path_ficher.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                mediaElement = `<img src="${path_ficher}" alt="img">`;
            } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
                mediaElement = `<video controls><source src="${path_ficher}" type="video/${fileExtension}"></video>`;
            } else if (['mp3', 'wav', 'ogg'].includes(fileExtension)) {
                mediaElement = `<audio controls><source src="${path_ficher}" type="audio/${fileExtension}"></audio>`;
            }

            main.innerHTML += `
                <div class="card">
                    ${mediaElement}
                    <h2>${Titre}</h2>
                    <p>${commentaire}</p>
                    <a href="detail.html?id=${idPosts}">Lire plus</a>
                    ${token ? `<button onclick="deletePost(${idPosts})">Supprimer</button>` : ''}
                </div>
            `;
        });

    } catch (error) {
        console.error('Error fetching posts:', error);
    }
};



// function deletePost(postId) {
//     let token = localStorage.getItem('auth_token');
//     if (!token) {
//         alert('Vous devez être connecté pour supprimer un post.');
//         return;
//     }

//     fetch(`deletePost.php?id=${postId}`, {
//         method: 'DELETE',
//         headers: {
//             'Authorization': `Bearer ${token}`
//         }
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             alert('Post supprimé.');
//             location.reload();
//         } else {
//             alert('Vous ne pouvez pas supprimer ce post.');
//         }
//     })
//     .catch(error => console.error('Error:', error));
// }
