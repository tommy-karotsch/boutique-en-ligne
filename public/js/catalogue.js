async function loadItems(){
    const apiUrl = '/boutique-en-ligne/public/api-item?top=1';
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

            carte.innerHTML = `
                <div class="item-card__thumb">
                    <span class="item-card__badge" style="background-color: ${item.rarity_color || '#000'};">
                        ${item.rarity ?? ''}
                    </span>
                    ${item.image ? `<a href="/boutique-en-ligne/public/item/show?id=${item.id}"><img src="${item.image}" alt="${item.name}" class="item-card__img"></a>` : ''}
                </div>

                <h2 class="item-card__name"><a href="/boutique-en-ligne/public/item/show?id=${item.id}">${item.name}</a></h2>
                <p class="item-card__info">${item.category ?? ''} · ${item.color ?? ''}</p>

                <div class="item-card__bottom">
                    <span class="item-card__price">${item.price} Crédits</span>
                    <a href="/boutique-en-ligne/public/cart/add?id=${item.id}" class="item-card__add">+ Panier</a>
                </div>
            `;

            conteneur.appendChild(carte);
        });

        
    } catch(error){
        conteneur.innerHTML = '<p>Impossible de charger les items.</p>';
        console.error("Erreur :", error.message);
    }
}

loadItems();