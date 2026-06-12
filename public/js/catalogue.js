async function loadItems(){
    const apiUrl = '/boutique-en-ligne/public/api-item';
    const conteneur = document.getElementById('catalogue');

    try{
        const response = await fetch(apiUrl);

        if(!response.ok){
            throw new Error(`Erreur HTTP ! Statut : ${response.status}`);
        }

        const items = await response.json();

        conteneur.innerHTML = '';

        items.forEach(item =>{
            const carte = document.createElement('div');
            carte.style.border = ' 2px solid ' + (item.rarity_color || '#000');
            carte.style.padding = '10px';
            carte.style.width = '250px';

            const titre = document.createElement('h2');
            titre.textContent = item.name;

            const categorie = document.createElement('p');
            categorie.textContent = 'Catégorie : ' + (item.category ?? 'N/A');

            const couleur = document.createElement('p');
            couleur.textContent = 'Couleur : ' + (item.color ?? 'Standard');

            const prix = document.createElement('p');
            const prixFort = document.createElement('strong');
            prixFort.textContent = 'Prix : ' + item.price + ' Crédits';
            prix.appendChild(prixFort);

            const lien = document.createElement('a');
            lien.href = '/boutique-en-ligne/public/item/show?id=' + encodeURIComponent(item.id);
            lien.textContent = 'Voir les détails';

            carte.appendChild(titre);
            carte.appendChild(categorie);
            carte.appendChild(couleur);
            carte.appendChild(prix);
            carte.appendChild(lien);

            conteneur.appendChild(carte);
        });
    } catch(error){
        conteneur.innerHTML = '<p>Impossible de charger les items.</p>';
        console.error("Erreur :", error.message);
    }
}

loadItems();