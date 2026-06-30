const formFiltres = document.getElementById('filters');

if (formFiltres){
    const selects = formFiltres.querySelectorAll('select');

    selects.forEach(select => {
        select.addEventListener('change', () => {
            formFiltres.submit();
        });
    });
}