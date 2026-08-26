import './bootstrap';
import Alpine from 'alpinejs';
import 'preline';

// Sidebar state management with localStorage persistence
document.addEventListener('alpine:init', () => {
  Alpine.store('sidebar', {
    open: localStorage.getItem('sidebarOpen') !== 'false',
    mobileOpen: false,
    toggle() {
      this.open = !this.open;
      localStorage.setItem('sidebarOpen', this.open);
    },
    toggleMobile() {
      this.mobileOpen = !this.mobileOpen;
    },
    closeMobile() {
      this.mobileOpen = false;
    }
  });

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
