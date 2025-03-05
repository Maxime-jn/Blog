"use strict";

window.onload = async () => {
    try {
        const response = await fetch('php/dispach.php/get/posts', {
            headers: { 'Authorization': 'Bearer votre-token' }
        });

        const posts = await response.json();
        const main = document.querySelector('main');

        posts.forEach(({ Titre, commentaire, path_ficher, idPosts}) => {
            main.innerHTML += `
                <div class="card">
                    <img src="${path_ficher}" alt="img">
                    <h2>${Titre}</h2>
                    <p>${commentaire}</p>
                    <a href="detail.html?id=${idPosts}">Lire plus</a>
                </div>
            `;
        });

    } catch (error) {
        console.error('Error fetching posts:', error);
    }
};


// fetch('getPosts.php')
//     .then(response => response.json())
//     .then(posts => {
//         let postsContainer = document.getElementById('posts');
//         posts.forEach(post => {
//             let postElement = document.createElement('div');
//             postElement.classList.add('post');
//             postElement.innerHTML = `
//                 <h2>${post.title}</h2>
//                 <p>${post.content}</p>
//                 <button onclick="deletePost(${post.id})">Supprimer</button>
//             `;
//             postsContainer.appendChild(postElement);
//         });
//     })
//     .catch(error => console.error('Error:', error));

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
