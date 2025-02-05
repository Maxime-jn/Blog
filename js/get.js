"use strict"

document.addEventListener('DOMContentLoaded ', function () {
    console.log("load")
    fetch('/2024-2025/AtWeb/Mercredi/2emeSemestre/Blog/php/dispach.php/get/posts', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer votre-token'
        }
    })
        .then(response => response.json())
        .then(posts => {
            const main = document.querySelector('main');
            posts.forEach(post => {
                const card = document.createElement('div');
                card.classList.add('card');

                const img = document.createElement('img');
                img.src = post.imageUrl;
                img.alt = 'img';
                card.appendChild(img);
                
                const title = document.createElement('h2');
                title.textContent = post.titre;
                card.appendChild(title);

                const link = document.createElement('a');
                link.href = 'detail.html';
                link.textContent = 'Lire plus';
                card.appendChild(link);

                main.appendChild(card);
            });
        })
        .catch(error => console.error('Error fetching posts:', error));
});