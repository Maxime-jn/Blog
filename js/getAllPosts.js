// "use strict";

// window.onload = async () => {
//     try {
//         const response = await fetch('./php/dispach.php/get/posts', {
//             headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
//         });

//         const posts = await response.json();
//         const main = document.querySelector('main');
//         const token = sessionStorage.getItem('token');

//         posts.forEach(({ Titre, commentaire, path_ficher, idPosts }) => {
//             let mediaElement = '';
//             const fileExtension = path_ficher.split('.').pop().toLowerCase();

//             if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
//                 mediaElement = `<img src="${path_ficher}" alt="img">`;
//             } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
//                 mediaElement = `<video controls><source src="${path_ficher}" type="video/${fileExtension}"></video>`;
//             } else if (['mp3', 'wav', 'ogg'].includes(fileExtension)) {
//                 mediaElement = `<audio controls><source src="${path_ficher}" type="audio/${fileExtension}"></audio>`;
//             }

//             main.innerHTML += `
//                 <div class="card">
//                     ${mediaElement}
//                     <h2>${Titre}</h2>
//                     <p>${commentaire}</p>
//                     <a href="detail.html?id=${idPosts}">Lire plus</a>
//                     ${token ? `<button onclick="deletePost(${idPosts})">Supprimer</button>` : ''}
//                 </div>
//             `;
//         });

//     } catch (error) {
//         console.error('Error fetching posts:', error);
//     }
// };































// "use strict";

// window.onload = async () => {
//     try {
//         // Vérifier le token et récupérer l'id de l'utilisateur connecté
//         const token = localStorage.getItem('token');
//         let currentUserId = null;

//         if (token) {
//             const userResponse = await fetch('./php/dispach.php/check/token', {
//                 method: 'POST',
//                 headers: {
//                     'Authorization': `Bearer ${token}`,
//                     'Content-Type': 'application/json'
//                 }
//             });

//             const userData = await userResponse.json();
//             if (userData.success) {
//                 currentUserId = userData.userId;
//             }
//         }

//         // Récupérer les posts
//         const response = await fetch('./php/dispach.php/get/posts', {
//             headers: { 'Authorization': `Bearer ${token}` }
//         });

//         const posts = await response.json();
//         const main = document.querySelector('main');

//         posts.forEach(({ Titre, commentaire, path_ficher, idPosts, iduser }) => {
//             let mediaElement = '';
//             const fileExtension = path_ficher.split('.').pop().toLowerCase();

//             if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
//                 mediaElement = `<img src="${path_ficher}" alt="img">`;
//             } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
//                 mediaElement = `<video controls><source src="${path_ficher}" type="video/${fileExtension}"></video>`;
//             } else if (['mp3', 'wav', 'ogg'].includes(fileExtension)) {
//                 mediaElement = `<audio controls><source src="${path_ficher}" type="audio/${fileExtension}"></audio>`;
//             }

//             // Vérifier si l'utilisateur connecté est le créateur du post
//             const isOwner = currentUserId && currentUserId == iduser;
//             const editButtons = isOwner
//                 ? `<button class="buttonForm" onclick="editPost(${idPosts})">Modifier</button>
//                    <button class="buttonForm" onclick="deletePost(${idPosts})">Supprimer</button>`
//                 : '';

//             main.innerHTML += `
//                 <div class="card">
//                     ${mediaElement}
//                     <h2>${Titre}</h2>
//                     <p>${commentaire}</p>
//                     <a href="detail.html?id=${idPosts}">Lire plus</a>
//                     ${editButtons}
//                 </div>
//             `;
//         });

//     } catch (error) {
//         console.error('Error fetching posts:', error);
//     }
// };

// // Fonction de suppression de post
// async function deletePost(idPosts) {
//     if (!confirm("Voulez-vous vraiment supprimer ce post ?")) return;

//     const token = localStorage.getItem('token');

//     try {
//         const response = await fetch('./php/dispach.php/posts', {
//             method: 'DELETE',
//             headers: {
//                 'Authorization': `Bearer ${token}`,
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({ idPosts })
//         });

//         const result = await response.json();
//         if (result.error) {
//             alert("Erreur lors de la suppression : " + result.error);
//         } else {
//             alert("Post supprimé avec succès !");
//             location.reload();
//         }
//     } catch (error) {
//         console.error('Erreur suppression post:', error);
//     }
// }

// // Fonction de modification (redirige vers la page d'édition)
// function editPost(idPosts) {
//     window.location.href = `edit.html?id=${idPosts}`;
// }
























"use strict";

window.onload = async () => {
    try {
        // Récupérer le token et vérifier le token pour obtenir l'ID utilisateur connecté
        const token = localStorage.getItem('token');
        let currentUserId = null;

        if (token) {
            const userResponse = await fetch('./php/dispach.php/check/token', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });
            const userData = await userResponse.json();
            if (userData.success) {
                currentUserId = userData.userId;
            }
        }

        // Récupérer les posts
        const response = await fetch('./php/dispach.php/get/posts', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const posts = await response.json();
        const main = document.querySelector('main');

        posts.forEach(({ Titre, commentaire, path_ficher, idPosts, iduser }) => {
            let mediaElement = '';
            const fileExtension = path_ficher.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                mediaElement = `<img src="${path_ficher}" alt="img">`;
            } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
                mediaElement = `<video controls><source src="${path_ficher}" type="video/${fileExtension}"></video>`;
            } else if (['mp3', 'wav', 'ogg'].includes(fileExtension)) {
                mediaElement = `<audio controls><source src="${path_ficher}" type="audio/${fileExtension}"></audio>`;
            }

            // Affichage conditionnel des boutons pour l'utilisateur propriétaire
            const isOwner = currentUserId && currentUserId == iduser;
            const editButtons = isOwner
                ? `<button class="buttonForm" onclick="editPost(${idPosts})">Modifier</button>
                   <button class="buttonForm" onclick="deletePost(${idPosts})">Supprimer</button>`
                : '';

            main.innerHTML += `
                <div class="card">
                    ${mediaElement}
                    <h2>${Titre}</h2>
                    <p>${commentaire}</p>
                    <a href="detail.html?id=${idPosts}">Lire plus</a>
                    ${editButtons}
                </div>
            `;
        });

    } catch (error) {
        console.error('Error fetching posts:', error);
    }
};

// Fonction pour rediriger vers la page d'édition
function editPost(idPosts) {
    window.location.href = `edit.html?id=${idPosts}`;
}

// Fonction pour supprimer un post
async function deletePost(idPosts) {
    if (!confirm("Voulez-vous vraiment supprimer ce post ?")) return;

    const token = localStorage.getItem("token");

    try {
        const response = await fetch("./php/dispach.php/posts", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify({ idPosts: idPosts })
        });

        const result = await response.json();

        if (result.error) {
            alert("Erreur lors de la suppression : " + result.error);
        } else {
            alert("Post supprimé avec succès !");
            location.reload();
        }
    } catch (error) {
        console.error('Erreur suppression post:', error);
    }
}




// "use strict";

// window.onload = async () => {
//     try {
//         const response = await fetch('./php/dispach.php/get/posts', {
//             headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
//         });

//         const posts = await response.json();
//         const main = document.querySelector('main');
//         const token = sessionStorage.getItem('token');
//         const currentUserId = localStorage.getItem('userId');  // Assurez-vous que l'ID de l'utilisateur est stocké dans localStorage lors de la connexion

//         posts.forEach(({ Titre, commentaire, path_ficher, idPosts, iduser }) => {
//             let mediaElement = '';
//             const fileExtension = path_ficher.split('.').pop().toLowerCase();

//             if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
//                 mediaElement = `<img src="${path_ficher}" alt="img">`;
//             } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
//                 mediaElement = `<video controls><source src="${path_ficher}" type="video/${fileExtension}"></video>`;
//             } else if (['mp3', 'wav', 'ogg'].includes(fileExtension)) {
//                 mediaElement = `<audio controls><source src="${path_ficher}" type="audio/${fileExtension}"></audio>`;
//             }

//             // Affichage des posts
//             let editDeleteButtons = '';
//             if (currentUserId && currentUserId === iduser) {
//                 // Si l'utilisateur est le créateur du post, afficher les boutons
//                 editDeleteButtons = `
//                     <button onclick="deletePost(${idPosts})">Supprimer</button>
//                     <button onclick="editPost(${idPosts})">Modifier</button>
//                 `;
//             }

//             main.innerHTML += `
//                 <div class="card">
//                     ${mediaElement}
//                     <h2>${Titre}</h2>
//                     <p>${commentaire}</p>
//                     <a href="detail.html?id=${idPosts}">Lire plus</a>
//                     ${editDeleteButtons}
//                 </div>
//             `;
//         });

//     } catch (error) {
//         console.error('Error fetching posts:', error);
//     }
// };

// function deletePost(postId) {
//     fetch('./php/dispach.php/deletePost', {
//         method: 'POST',
//         headers: {
//             'Authorization': `Bearer ${localStorage.getItem('token')}`,
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify({ idPosts: postId })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             alert('Post supprimé');
//             location.reload();  // Recharger la page après suppression
//         } else {
//             alert('Erreur lors de la suppression');
//         }
//     });
// }

// function editPost(postId) {
//     // Rediriger vers une page d'édition, en utilisant l'ID du post
//     window.location.href = `edit.html?id=${postId}`;
// }






















