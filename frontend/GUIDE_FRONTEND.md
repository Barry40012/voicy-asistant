# 🎨 Guide Frontend - Voicy Assistant

## ✅ Choix Technique : Blade + Tailwind CSS

### Pourquoi Blade + Tailwind ?

1. **Déjà installé** : Laravel Breeze est déjà configuré avec Tailwind
2. **Rapide pour MVP** : Pas besoin de SPA complexe
3. **Intégration native** : Fonctionne directement avec Laravel
4. **Tailwind CSS** : Framework CSS moderne et rapide
5. **Alpine.js** : Inclus avec Breeze pour l'interactivité

### Stack Frontend

```
Blade Templates
├── Tailwind CSS (styling)
├── Alpine.js (interactivité)
└── Structure : Layouts + Components
```

## 📁 Structure des Vues

```
resources/views/
├── layouts/
│   ├── app.blade.php          # Layout principal
│   └── guest.blade.php        # Layout pour login/register
├── components/                # Composants réutilisables
│   ├── button.blade.php
│   ├── card.blade.php
│   └── status-badge.blade.php
├── dashboard/
│   ├── index.blade.php        # Dashboard principal
│   ├── audios/
│   │   ├── index.blade.php    # Liste des audios
│   │   └── show.blade.php     # Détail d'un audio
│   ├── subscription/
│   │   └── index.blade.php    # Gestion abonnement
│   └── whatsapp/
│       └── index.blade.php    # Connexion WhatsApp
└── admin/
    ├── index.blade.php        # Dashboard admin
    └── users/
        └── index.blade.php    # Liste utilisateurs
```

## 🎨 Palette de Couleurs

Voir `PALETTE_COULEURS.md` pour la palette complète.

**Couleurs principales** :
- **Primaire** : `#0ea5e9` (Bleu ciel - tech, communication)
- **Secondaire** : `#a855f7` (Violet - innovation, IA)
- **Neutres** : Gris (Tailwind gray)

## 🚀 Prochaines Étapes

1. **Configurer Tailwind** avec la palette personnalisée
2. **Créer les layouts** (app.blade.php, guest.blade.php)
3. **Créer les composants** réutilisables
4. **Créer les vues dashboard** (audios, subscription, whatsapp)
5. **Créer les vues admin**

---

**On commence par configurer Tailwind avec la palette, puis on crée les layouts !** 🚀

