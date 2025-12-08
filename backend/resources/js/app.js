import './bootstrap';

// Alpine.js pour l'interactivité légère
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// React sera chargé de manière sélective via des composants
// Les composants React seront montés sur des divs spécifiques avec data-react-component
