// FILTRE REPERTOIRE
console.log("Le JS est lu.");

document.querySelectorAll('.divCategorie button').forEach(button => {
    button.addEventListener('click', function() {
        const category = this.getAttribute('data-category');
        fetch(`/repertoire/filter?category=${category}`)
            .then(response => response.text())
            .then(html => {
                document.querySelector('#repertoire').innerHTML = html;
            });
    });
});

// FILTRE ENTRAIDE
// Fonction générique pour gérer le tri par texte
function sortItems(containerId, selector, attribute, isDescending = false) {
    const items = [...document.querySelectorAll(`#${containerId} ${selector}`)];
    items.sort((a, b) => {
        const valueA = a.querySelector(attribute).textContent.toLowerCase();
        const valueB = b.querySelector(attribute).textContent.toLowerCase();
        return isDescending ? valueB.localeCompare(valueA) : valueA.localeCompare(valueB);
    });
    updateDisplay(containerId, items);
}

// Fonction générique pour trier par nombre (likes, commentaires)
function sortByNumber(containerId, selector, attribute, isDescending = true) {
    const items = [...document.querySelectorAll(`#${containerId} ${selector}`)];
    items.sort((a, b) => {
        const numberA = parseInt(a.querySelector(attribute).textContent);
        const numberB = parseInt(b.querySelector(attribute).textContent);
        return isDescending ? numberB - numberA : numberA - numberB;
    });
    updateDisplay(containerId, items);
}

// Fonction générique pour trier par date
function sortByDate(containerId, selector, attribute, isDescending = true) {
    const items = [...document.querySelectorAll(`#${containerId} ${selector}`)];
    items.sort((a, b) => {
        const dateA = new Date(a.querySelector(attribute).getAttribute('data-date'));
        const dateB = new Date(b.querySelector(attribute).getAttribute('data-date'));
        return isDescending ? dateB - dateA : dateA - dateB;
    });
    updateDisplay(containerId, items);
}

// Fonction pour mettre à jour l'affichage après tri
function updateDisplay(containerId, sortedItems) {
    const section = document.getElementById(containerId);
    section.innerHTML = ''; // Efface les éléments actuels
    sortedItems.forEach(item => {
        section.appendChild(item); // Réaffiche les éléments triés
    });
}

// Gestion de l'affichage de la divTriAff (Tri et Afficher)
document.addEventListener("DOMContentLoaded", function () {
    const triBtn = document.getElementById('triBtn');
    const divTriAff = document.querySelector('.divTriAff');

    if (triBtn && divTriAff) {
        triBtn.addEventListener('click', function () {
            divTriAff.style.display = divTriAff.style.display === 'none' || divTriAff.style.display === '' ? 'flex' : 'none';
        });
    }

    // Ajout des écouteurs d'événements aux boutons
    const buttonEvents = [
        // Section Entraide
        { id: 'ordreCroissantBtn', action: () => sortItems('entraide', 'a', '.divTitreQ h4', false) },
        { id: 'ordreDecroissantBtn', action: () => sortItems('entraide', 'a', '.divTitreQ h4', true) },
        { id: 'plusCommentes', action: () => sortByNumber('entraide', 'a', '.divInfos p:first-child', true) },
        { id: 'moinsCommentes', action: () => sortByNumber('entraide', 'a', '.divInfos p:first-child', false) },
        { id: 'recent', action: () => sortByDate('entraide', 'a', '.divAuteur p span', true) },
        { id: 'ancien', action: () => sortByDate('entraide', 'a', '.divAuteur p span', false) },
        // Section Recette
        { id: 'ordreCroissantBtn', action: () => sortItems('recette', 'a', '.cardInfo h4', false) },
        { id: 'ordreDecroissantBtn', action: () => sortItems('recette', 'a', '.cardInfo h4', true) },
        { id: 'plusLikesBtn', action: () => sortByNumber('recette', 'a', '.divRight p', true) },
        { id: 'moinsLikesBtn', action: () => sortByNumber('recette', 'a', '.divRight p', false) }
    ];

    buttonEvents.forEach(event => {
        const button = document.getElementById(event.id);
        if (button) {
            button.addEventListener('click', event.action);
        }
    });


// RECETTE
    // Fonction pour ajouter un nouvel ingrédient
    function addIngredient() {
        const ingredientsContainer = document.querySelector('.ingredients');
        const index = ingredientsContainer.getAttribute('data-index');
        const prototype = ingredientsContainer.getAttribute('data-prototype');
        const newForm = prototype.replace(/__name__/g, index);

        const newIngredient = document.createElement('div');
        newIngredient.classList.add('ingredient-item');
        newIngredient.innerHTML = newForm + '<button type="button" class="remove-ingredient">🗑️</button>';
        ingredientsContainer.insertBefore(newIngredient, document.getElementById('add-ingredient'));

        ingredientsContainer.setAttribute('data-index', parseInt(index) + 1);

        // Ajouter la classe à l'input dans le nouveau formulaire
        const inputElement = newIngredient.querySelector('input');
        if (inputElement) {
            inputElement.classList.add('input'); // Ajoutez ici la classe souhaitée
        }

        // Ajouter l'événement de suppression
        newIngredient.querySelector('.remove-ingredient').addEventListener('click', function() {
            newIngredient.remove();
        });
    }

    // Fonction pour ajouter une nouvelle étape
    function addEtape() {
        const etapesContainer = document.querySelector('.etapes');
        const index = etapesContainer.getAttribute('data-index');
        const prototype = etapesContainer.getAttribute('data-prototype');
        const newForm = prototype.replace(/__name__/g, index);

        const newEtape = document.createElement('div');
        newEtape.classList.add('etape-item');
        newEtape.innerHTML = newForm + '<button type="button" class="remove-etape">🗑️</button>';
        etapesContainer.insertBefore(newEtape, document.getElementById('add-etape'));

        etapesContainer.setAttribute('data-index', parseInt(index) + 1);

        // Ajouter la classe à l'input dans le nouveau formulaire
        const inputElement = newEtape.querySelector('input');
        if (inputElement) {
            inputElement.classList.add('input'); // Ajoutez ici la classe souhaitée
        }

        // Ajouter l'événement de suppression
        newEtape.querySelector('.remove-etape').addEventListener('click', function() {
            newEtape.remove();
        });
    }

    // Ajouter des écouteurs pour les boutons "Ajouter"
    document.getElementById('add-ingredient').addEventListener('click', addIngredient);
    document.getElementById('add-etape').addEventListener('click', addEtape);

    
    // Ajouter des écouteurs pour les boutons "Supprimer" existants
    document.querySelectorAll('.remove-ingredient').forEach(button => {
        button.addEventListener('click', function() {
            button.closest('.ingredient-item').remove();
        });
    });

    document.querySelectorAll('.remove-etape').forEach(button => {
        button.addEventListener('click', function() {
            button.closest('.etape-item').remove();
        });
    });
});