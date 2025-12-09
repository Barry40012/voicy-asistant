# 🔐 Guide : Gestion des Sessions et Cookies

## 📋 Table des Matières
1. [Sessions avec Timeout Automatique](#sessions-avec-timeout-automatique)
2. [Bandeau de Cookies](#bandeau-de-cookies)
3. [Configuration](#configuration)
4. [Importance des Cookies](#importance-des-cookies)

---

## 🔄 Sessions avec Timeout Automatique

### Fonctionnement

Le système de timeout automatique fonctionne de la manière suivante :

1. **Détection d'inactivité** : Le système surveille l'activité de l'utilisateur (mouvements de souris, clics, frappe au clavier, scroll).

2. **Avertissement avant expiration** : 
   - Si l'utilisateur est inactif pendant **5 minutes**, un avertissement apparaît
   - L'avertissement s'affiche **60 secondes avant l'expiration** de la session
   - Un compte à rebours visuel indique le temps restant

3. **Options disponibles** :
   - **"Rester connecté"** : Prolonge la session en envoyant une requête au serveur
   - **"Se déconnecter"** : Déconnecte immédiatement l'utilisateur
   - **Fermer** : Ferme l'avertissement (mais la session expirera quand même)

4. **Expiration automatique** : Si aucune action n'est prise, la session expire et l'utilisateur est redirigé vers la page de connexion.

### Configuration

**Fichier** : `config/session.php`

```php
'lifetime' => env('SESSION_LIFETIME', 30), // 30 minutes
```

**Fichier** : `.env`

```env
SESSION_LIFETIME=30  # Durée en minutes (30 minutes par défaut)
```

### Personnalisation

Vous pouvez modifier les délais dans `resources/views/components/session-timeout.blade.php` :

```javascript
sessionLifetime: {{ config('session.lifetime', 30) * 60 }}, // Durée totale de la session (en secondes)
warningTime: 60, // Temps d'avertissement avant expiration (en secondes)
inactivityThreshold: 5 * 60 * 1000, // Seuil d'inactivité (5 minutes en millisecondes)
```

---

## 🍪 Bandeau de Cookies

### Fonctionnement

Le bandeau de cookies s'affiche automatiquement lors de la première visite sur le site.

### Types de Cookies

#### 1. **Cookies Essentiels** (Toujours actifs)
- **Session** : Authentification de l'utilisateur
- **CSRF** : Protection contre les attaques CSRF
- **Préférences** : Langue, thème, etc.

Ces cookies sont **nécessaires** et **ne peuvent pas être désactivés**.

#### 2. **Cookies Analytics** (Optionnel)
- Utilisés pour analyser l'utilisation de la plateforme
- Aident à améliorer l'expérience utilisateur
- Peuvent être activés/désactivés par l'utilisateur

### Acceptation

Lorsque l'utilisateur clique sur **"J'accepte"** :
- Les préférences sont enregistrées dans `localStorage`
- Le bandeau ne s'affiche plus lors des prochaines visites
- Les cookies analytics sont activés si l'utilisateur les a acceptés

### Paramètres

L'utilisateur peut :
- Voir les détails des cookies utilisés
- Activer/désactiver les cookies analytics
- Modifier ses préférences à tout moment

---

## ⚙️ Configuration

### Sessions

**Durée par défaut** : 30 minutes d'inactivité

**Modifier la durée** :
1. Éditer `.env` :
   ```env
   SESSION_LIFETIME=60  # 60 minutes
   ```
2. Ou modifier directement `config/session.php`

### Cookies

**Localisation des préférences** : `localStorage` du navigateur

**Clé** : `cookie_consent`

**Format** :
```json
{
  "accepted": true,
  "date": "2025-12-08T10:30:00.000Z",
  "analytics": false
}
```

---

## 🎯 Importance des Cookies

### Pourquoi les Cookies sont Importants ?

#### 1. **Sécurité**
- **Protection CSRF** : Les cookies CSRF protègent contre les attaques Cross-Site Request Forgery
- **Authentification sécurisée** : Les cookies de session permettent de maintenir l'utilisateur connecté de manière sécurisée

#### 2. **Fonctionnalité**
- **Authentification** : Sans cookies de session, l'utilisateur devrait se reconnecter à chaque page
- **Préférences utilisateur** : Langue, thème, paramètres personnalisés

#### 3. **Conformité RGPD**
- **Transparence** : Le bandeau informe l'utilisateur des cookies utilisés
- **Consentement** : L'utilisateur peut accepter ou refuser les cookies non essentiels
- **Contrôle** : L'utilisateur peut modifier ses préférences à tout moment

### Cookies Utilisés sur cette Plateforme

| Cookie | Type | Durée | Description |
|--------|------|-------|-------------|
| `voicy_assistant_session` | Essentiel | Session | Identifie l'utilisateur connecté |
| `XSRF-TOKEN` | Essentiel | Session | Protection CSRF |
| `cookie_consent` | Essentiel | 1 an | Préférences de cookies de l'utilisateur |

### Recommandation

**Pour cette plateforme** :
- ✅ **Cookies essentiels** : **OBLIGATOIRES** - Sans eux, la plateforme ne peut pas fonctionner
- ⚠️ **Cookies analytics** : **OPTIONNELS** - Utiles pour améliorer la plateforme, mais pas essentiels

**Conclusion** : Le bandeau de cookies est **recommandé** pour :
1. **Conformité RGPD** (si vous avez des utilisateurs européens)
2. **Transparence** avec vos utilisateurs
3. **Préparation** pour l'ajout futur d'outils analytics (Google Analytics, etc.)

---

## 🔧 Dépannage

### La session expire trop rapidement

**Solution** : Augmenter `SESSION_LIFETIME` dans `.env`

```env
SESSION_LIFETIME=60  # 60 minutes au lieu de 30
```

### L'avertissement ne s'affiche pas

**Vérifications** :
1. Vérifier que le composant `<x-session-timeout />` est inclus dans le layout
2. Vérifier que l'utilisateur est authentifié (`@auth`)
3. Vérifier la console JavaScript pour les erreurs

### Les cookies ne sont pas acceptés

**Vérifications** :
1. Vérifier `localStorage` dans la console du navigateur
2. Vérifier que le bandeau est bien inclus dans les layouts (`app.blade.php` et `guest.blade.php`)

---

## 📝 Notes

- Le timeout de session est basé sur l'**inactivité**, pas sur le temps total de connexion
- L'avertissement apparaît **60 secondes avant l'expiration**
- Les cookies essentiels sont **toujours actifs** et ne peuvent pas être désactivés
- Les préférences de cookies sont stockées dans `localStorage` (persistent même après fermeture du navigateur)

---

## 🚀 Prochaines Étapes

1. **Tester le timeout** : Se connecter et attendre 5 minutes sans activité
2. **Tester le bandeau** : Supprimer `cookie_consent` de `localStorage` et recharger la page
3. **Personnaliser** : Ajuster les délais selon vos besoins

