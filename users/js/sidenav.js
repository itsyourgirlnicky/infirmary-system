const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

dropdownToggles.forEach(toggle => {
  toggle.addEventListener('click', () => {
    const dropdownMenu = toggle.nextElementSibling;
    const isExpanded = dropdownMenu.getAttribute('aria-expanded') === 'true';

    dropdownMenu.setAttribute('aria-expanded', !isExpanded);
    toggle.classList.toggle('active');
  });
});
