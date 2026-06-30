const toggle = document.querySelector('.nav-toggle');
const nav = document.querySelector('.nav');

toggle.addEventListener('click', () => {
    nav.classList.toggle('nav--open');

    const opended = nav.classList.contains('nav--open');
    toggle.setAttribute('aria-expanded', opended);
});