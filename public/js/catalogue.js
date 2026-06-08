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

            carte.innerHTML = `
            <h2>${item.name}</h2>
            <p>Catégorie : ${item.category ?? 'N/A'}</p>
            <p>Couleur : ${item.color ?? 'Standard'}</p>
            <p><strong>Prix : ${item.price} Crédits</strong></p>
            <a href="/boutique-en-ligne/public/item/show?id=${item.id}">Voir les détails</a>
            `;

            conteneur.appendChild(carte);
        });
    } catch(error){
        conteneur.innerHTML = '<p>Impossible de charger les items.</p>';
        console.error("Erreur :", error.message);
    }
}

loadItems();