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