import './bootstrap';
import Alpine from 'alpinejs';
import 'preline';

// Dark mode persistence using Alpine store
document.addEventListener('alpine:init', () => {
  Alpine.store('darkMode', localStorage.getItem('darkMode') === 'true');
  Alpine.effect(() => {
    if (Alpine.store('darkMode')) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  });
});

window.Alpine = Alpine;
Alpine.start();
