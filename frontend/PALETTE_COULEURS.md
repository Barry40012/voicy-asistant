# 🎨 Palette de Couleurs - Voicy Assistant

## 🎯 Identité Visuelle

**Voicy Assistant** : SaaS professionnel pour l'automatisation WhatsApp
- **Ton** : Moderne, professionnel, tech, fiable
- **Cible** : Professionnels, entreprises

## 🎨 Palette Principale

### Couleur Primaire (Brand)
```css
--primary-50: #f0f9ff;
--primary-100: #e0f2fe;
--primary-200: #bae6fd;
--primary-300: #7dd3fc;
--primary-400: #38bdf8;
--primary-500: #0ea5e9;  /* Couleur principale */
--primary-600: #0284c7;
--primary-700: #0369a1;
--primary-800: #075985;
--primary-900: #0c4a6e;
```

**Couleur principale** : `#0ea5e9` (Bleu ciel moderne, tech, communication)

### Couleur Secondaire (Accent)
```css
--secondary-50: #faf5ff;
--secondary-100: #f3e8ff;
--secondary-200: #e9d5ff;
--secondary-300: #d8b4fe;
--secondary-400: #c084fc;
--secondary-500: #a855f7;  /* Couleur secondaire */
--secondary-600: #9333ea;
--secondary-700: #7e22ce;
--secondary-800: #6b21a8;
--secondary-900: #581c87;
```

**Couleur secondaire** : `#a855f7` (Violet moderne, innovation, IA)

### Couleurs Neutres
```css
--gray-50: #f9fafb;
--gray-100: #f3f4f6;
--gray-200: #e5e7eb;
--gray-300: #d1d5db;
--gray-400: #9ca3af;
--gray-500: #6b7280;
--gray-600: #4b5563;
--gray-700: #374151;
--gray-800: #1f2937;
--gray-900: #111827;
```

### Couleurs de Statut
```css
--success: #10b981;  /* Vert - Succès */
--warning: #f59e0b;  /* Orange - Avertissement */
--error: #ef4444;    /* Rouge - Erreur */
--info: #3b82f6;     /* Bleu - Information */
```

## 🎨 Application dans Tailwind

### Configuration Tailwind (tailwind.config.js)

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',  // Principal
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
        },
        secondary: {
          50: '#faf5ff',
          100: '#f3e8ff',
          200: '#e9d5ff',
          300: '#d8b4fe',
          400: '#c084fc',
          500: '#a855f7',  // Secondaire
          600: '#9333ea',
          700: '#7e22ce',
          800: '#6b21a8',
          900: '#581c87',
        },
      },
    },
  },
}
```

## 🎨 Utilisation dans les Vues

### Boutons
- **Primaire** : `bg-primary-500 hover:bg-primary-600`
- **Secondaire** : `bg-secondary-500 hover:bg-secondary-600`
- **Succès** : `bg-green-500 hover:bg-green-600`
- **Danger** : `bg-red-500 hover:bg-red-600`

### Textes
- **Titres** : `text-gray-900`
- **Sous-titres** : `text-gray-700`
- **Corps** : `text-gray-600`
- **Légendes** : `text-gray-500`

### Arrière-plans
- **Principal** : `bg-white`
- **Secondaire** : `bg-gray-50`
- **Accent** : `bg-primary-50`

### Bordures
- **Légères** : `border-gray-200`
- **Moyennes** : `border-gray-300`
- **Fondues** : `border-primary-200`

## 🎨 Exemples d'Application

### Header/Navbar
- Fond : `bg-white border-b border-gray-200`
- Logo : `text-primary-600`
- Liens actifs : `text-primary-600`
- Liens inactifs : `text-gray-600`

### Cards
- Fond : `bg-white border border-gray-200 shadow-sm`
- Titre : `text-gray-900`
- Hover : `hover:shadow-md hover:border-primary-200`

### Boutons CTA
- Principal : `bg-primary-500 text-white hover:bg-primary-600`
- Secondaire : `bg-secondary-500 text-white hover:bg-secondary-600`

### Badges/Status
- Succès : `bg-green-100 text-green-800`
- Warning : `bg-yellow-100 text-yellow-800`
- Erreur : `bg-red-100 text-red-800`
- Info : `bg-blue-100 text-blue-800`

## 🎨 Thème Sombre (Optionnel - Futur)

```css
--dark-bg: #0f172a;
--dark-surface: #1e293b;
--dark-text: #f1f5f9;
```

---

**Cette palette est moderne, professionnelle et parfaite pour un SaaS tech !** 🎨

