
(() => {
  const menuButton = document.querySelector('.menu-button');
  const mobileMenu = document.querySelector('.mobile-menu');
  if (menuButton && mobileMenu) {
    menuButton.addEventListener('click', () => {
      const open = menuButton.getAttribute('aria-expanded') === 'true';
      menuButton.setAttribute('aria-expanded', String(!open));
      mobileMenu.hidden = open;
    });
    mobileMenu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
      mobileMenu.hidden = true;
      menuButton.setAttribute('aria-expanded', 'false');
    }));
  }

  const form = document.getElementById('contactForm');
  if (form) {
    const params = new URLSearchParams(window.location.search);
    const requestedSubject = params.get('subject');
    if (requestedSubject) {
      const subject = form.elements.subject;
      const aliases = {
        'privacy-request': 'Privacy or data rights request',
        'privacy-opt-out': 'Privacy opt-out request',
        'legal-notice': 'Legal notice',
        'agency': 'Agency registration or platform presentation'
      };
      const target = aliases[requestedSubject] || requestedSubject;
      [...subject.options].forEach((option) => {
        if (option.value === target) subject.value = target;
      });
    }
    form.addEventListener('submit', (event) => {
      if (!form.reportValidity()) event.preventDefault();
    });
  }
})();
