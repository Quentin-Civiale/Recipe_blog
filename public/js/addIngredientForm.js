var $collectionHolder;

// configure le lien "Ajouter un ingrédient"
var $addNewIngredient = $('<br/><a href="#" id="addIngredientButton" class="btn btn-secondary"> Ajouter un ingrédient </a>');
var $newLinkLi = $('<div></div>').append($addNewIngredient);

// configure le numéro du premier ingrédient à 1
var ingredientNumber = 1;

function addIngredientForm($collectionHolder, $newLinkLi) {
    // Récupère le prototype de données
    var prototype = $collectionHolder.data('prototype');

    // obtention du nouvel index
    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    // augmente l'index avec un pour l'élément suivant
    // $collectionHolder.data('index', index + 1);

    // Ajoute +1 à chaque nouvel ingrédient de la vue
    // ingredientNumber++;

    // Affiche le formulaire dans un div, avant le lien et après chaque nouvel ajout d'ingrédient
    // var $newFormLi = $('<div><hr/><div class="ingredientForm"><h5>Ingrédient N°'+ingredientNumber+'</h5></div></div>').append(newForm);
    var $newFormLi = $('<div class="ingredientForm"></div>').append(newForm);

    $newLinkLi.before($newFormLi);

    // Ajoute un lien de suppression au nouveau formulaire
    addIngredientFormDeleteLink($newFormLi);
}


jQuery(document).ready(function() {
    // Récupère l'ingrédient qui contient la collection
    $collectionHolder = $('#recipe_ingredients');

    // ajoute "Ajouter un ingredient" et le div
    $collectionHolder.append($newLinkLi);

    // compte les entrées actuelles du formulaire
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addNewIngredient.on('click', function(e) {
        // empêche le lien de créer un "#" sur l'URL
        e.preventDefault();

        // ajoute un nouveau formulaire
        addIngredientForm($collectionHolder, $newLinkLi);
    });
});


function addIngredientFormDeleteLink($ingredientFormLi) {
    var $removeIngredientLink = $('<a href="#" id="removeIngredientButtonWeb" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>');
    $ingredientFormLi.append($removeIngredientLink);

    $removeIngredientLink.on('click', function(e) {
        // empêche le lien de créer un # sur l'URL
        e.preventDefault();

        // supprimer le formulaire ingrédient
        $ingredientFormLi.remove();

        // Reduit de 1 à chaque suppression d'ingrédient sur la recette
        // ingredientNumber--;
        //
        // var ingredients = document.getElementsByClassName("ingredientForm");
        // for (var i = 0; i < ingredients.length; i++)
        // {
        //     ingredients[i].innerHTML = "<h5>Ingrédient N°"+(i + 1)+"</h5>";
        // }
    });
}