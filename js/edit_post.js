document.addEventListener("DOMContentLoaded", async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get("id");
    const token = localStorage.getItem("token");

    if (!postId || !token) {
        alert("Accès refusé.");
        window.location.href = "index.html";
        return;
    }

    // Récupérer les infos du post
    const response = await fetch(`./php/dispach.php/get/post?id=${postId}`, {
        headers: { "Authorization": `Bearer ${token}` }
    });

    const post = await response.json();
    document.getElementById("postId").value = post.idPosts;
    document.getElementById("titre").value = post.Titre;
    document.getElementById("commentaire").value = post.commentaire;

    // Gérer la soumission du formulaire
    document.getElementById("editPostForm").addEventListener("submit", async (event) => {
        event.preventDefault();

        const updatedPost = {
            idPosts: postId,
            Titre: document.getElementById("titre").value,
            commentaire: document.getElementById("commentaire").value
        };

        const updateResponse = await fetch("./php/dispach.php/posts", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(updatedPost)
        });

        const result = await updateResponse.json();
        if (result.error) {
            alert("Erreur: " + result.error);
        } else {
            alert("Post modifié avec succès !");
            window.location.href = "index.html";
        }
    });
});
