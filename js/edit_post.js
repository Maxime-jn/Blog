document.getElementById("editPostForm").addEventListener("submit", async(e) => {
    e.preventDefault();

    const updatedPost = {
        idPosts: postId,
        Titre: document.getElementById("titre").value,
        commentaire: document.getElementById("commentaire").value
    };
    console.log("Données envoyées pour la mise à jour :", updatedPost);

    try {
        const updateResponse = await fetch("./php/dispach.php/posts", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(updatedPost)
        });

        const result = await updateResponse.json();
        console.log("Réponse du serveur :", result);

        if (result.error) {
            alert("Erreur: " + result.error);
        } else {
            alert("Post modifié avec succès !");
            window.location.href = "index.html";
        }
    } catch (error) {
        console.error("Erreur lors de la mise à jour du post:", error);
        alert("Erreur lors de la mise à jour du post.");
    }
});