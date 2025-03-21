"use strict";

window.onload = async () => {
    console.log("load");

    try {
        const response = await fetch('./php/dispach.php/get/posts', {
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
