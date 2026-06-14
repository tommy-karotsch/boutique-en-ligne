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
            carte.className = 'item-card';
            carte.style.borderColor = item.rarity_color || '#000';

            carte.innerHTML = `
            <h2 class="item-card__name">${item.name}</h2>
            <p class="item-card__info">Catégorie : ${item.category ?? 'N/A'}</p>
            <p class="item-card__info">Couleur : ${item.color ?? 'Standard'}</p>
            <p class="item-card__price">${item.price} Crédits</p>
            <a href="/boutique-en-ligne/public/item/show?id=${item.id}" class="item-card__link">Voir les détails</a>
            `;

            conteneur.appendChild(carte);
        });

        
    } catch(error){
        conteneur.innerHTML = '<p>Impossible de charger les items.</p>';
        console.error("Erreur :", error.message);
    }
}

loadItems();