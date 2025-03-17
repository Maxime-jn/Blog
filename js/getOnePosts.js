"use strict";

window.onload = async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');

    if (!postId) {
        console.error('Post ID is missing in the URL');
        return;
    }

    try {
        const response = await fetch(`http://localhost/2024-2025/AtWeb/Mercredi/2emeSemestre/Blog/php/dispach.php/get/post?id=${postId}`, {
            headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
        });

        const post = await response.json();
        const main = document.querySelector('main');
        const token = sessionStorage.getItem('token');

        let multimediaContent = '';
        post.multimedia.forEach(media => {
            if (media.type === 'image') {
                multimediaContent += `<img src="${media.path_ficher}" alt="Image">`;
            } else if (media.type === 'video') {
                multimediaContent += `<video controls src="${media.path_ficher}"></video>`;
            } else if (media.type === 'audio') {
                multimediaContent += `<audio controls src="${media.path_ficher}"></audio>`;
            }
        });

        main.innerHTML = `
        <article>
            <h2 id="post-title">${post.Titre}</h2>
            <p id="post-comment">${post.commentaire}</p>
            <section id="multimedia-section">
                <div id="multimedia-content">
                    ${multimediaContent}
                    ${token ? `<button onclick="deletePost(${postId})">Supprimer</button>` : ''}
                </div>
            </section>
        </article>
        `;
    } catch (error) {
        console.error('Error fetching post details:', error);
    }
};
