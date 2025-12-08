import React from 'react';
import { createRoot } from 'react-dom/client';
import axios from 'axios';

// Configuration axios pour Laravel
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Composants React
import AnimatedCard from './components/AnimatedCard';
import InteractiveButton from './components/InteractiveButton';
import AudioPlayer from './components/AudioPlayer';
import SubscriptionCard from './components/SubscriptionCard';
import DashboardStats from './components/DashboardStats';
import HomepageHero from './components/HomepageHero';
import FeatureCard from './components/FeatureCard';

// Fonction pour initialiser React de manière sélective
export function initReactComponents() {
    // Initialiser les composants React sur les éléments avec data-react-component
    document.querySelectorAll('[data-react-component]').forEach((element) => {
        const componentName = element.getAttribute('data-react-component');
        const props = JSON.parse(element.getAttribute('data-props') || '{}');
        
        const root = createRoot(element);
        
        switch (componentName) {
            case 'AnimatedCard':
                root.render(<AnimatedCard {...props} />);
                break;
            case 'InteractiveButton':
                root.render(<InteractiveButton {...props} />);
                break;
            case 'AudioPlayer':
                root.render(<AudioPlayer {...props} />);
                break;
            case 'SubscriptionCard':
                root.render(<SubscriptionCard {...props} />);
                break;
            case 'DashboardStats':
                root.render(<DashboardStats {...props} />);
                break;
            case 'HomepageHero':
                root.render(<HomepageHero {...props} />);
                break;
            case 'FeatureCard':
                root.render(<FeatureCard {...props} />);
                break;
            default:
                console.warn(`Component ${componentName} not found`);
        }
    });
}

// Initialiser quand le DOM est prêt
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initReactComponents);
} else {
    initReactComponents();
}

// Réinitialiser après les navigations AJAX (pour les SPAs partielles)
window.addEventListener('react:reinit', initReactComponents);

