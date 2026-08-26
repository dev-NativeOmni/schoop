import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import 'preline';

Alpine.plugin(collapse);

// Determine initial state (default to true / open on desktop)
const initialSidebarOpen = localStorage.getItem('sidebarOpen') !== 'false';

// Define Alpine stores directly on Alpine instance before start()
Alpine.store('sidebar', {
  open: initialSidebarOpen,
  mobileOpen: false,
  toggle() {
    this.open = !this.open;
    localStorage.setItem('sidebarOpen', this.open ? 'true' : 'false');
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

window.Alpine = Alpine;
Alpine.start();
