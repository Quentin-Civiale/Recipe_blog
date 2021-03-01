window.onload = () => {
    // on va chercher toutes les étoiles
    const stars = document.querySelectorAll(".bi-star");

    // on va chercher la note (l'id de l'input donné par le ScoreType form)
    const note = document.querySelector("#note");

    // on va boucler sur les étoiles pour leur ajouter des écouteurs d'évènements
    for (star of stars) {
        // on écoute le survol
        star.addEventListener("mouseover", function () {
            resetStars();
            this.className = "bi-star-fill";
            this.style.color = "#FF8A65";

            // l'élément précédent dans le DOM (de même niveau, balise soeur)
            let previousStar = this.previousElementSibling;

            while (previousStar) {
                // on passe l'étoile qui précède en étoile pleine
                previousStar.className = "bi-star-fill";
                previousStar.style.color = "#FF8A65";
                // on récupère l'étoile qui la précède
                previousStar = previousStar.previousElementSibling;
            }
        });

        // on écoute le clic
        star.addEventListener("click", function () {
            note.value = this.dataset.value;
            $('#countRatingStars').html(note.value);
            $('.starsMessage').removeClass('hideStarsMessage').show().delay(1000).fadeOut(3500);
        })

        star.addEventListener("mouseout", function () {
            resetStars(note.value);
        })
    }

    /**
     * Reset des étoiles en vérifiant la note dans l'input caché
     * @param {number} note
     */
    function resetStars(note = 0) {
        for (star of stars) {
            if (star.dataset.value > note) {
                star.className = "bi-star";
                star.style.color = "#6c757d";
            }
            else {
                star.className = "bi-star-fill";
                star.style.color = "#FF8A65";
            }

        }
    }
}