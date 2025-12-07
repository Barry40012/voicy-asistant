# 🔐 Guide du Système de Permissions Administrateur

## 📋 Vue d'ensemble

Le système de permissions permet au **super administrateur** de créer des administrateurs avec des permissions spécifiques. Chaque administrateur ne voit et n'a accès qu'aux sections pour lesquelles il a reçu des permissions.

## ✅ Fonctionnalités

### 1. **Super Administrateur**
- Accès complet à toutes les fonctionnalités
- Peut créer et gérer d'autres administrateurs
- Peut attribuer des permissions spécifiques à chaque admin
- Voit tous les menus dans le panel admin

### 2. **Administrateur avec Permissions**
- Accès uniquement aux sections autorisées
- Menu dynamique : seules les sections autorisées sont visibles
- Routes sécurisées : accès refusé si pas de permission
- Cache des permissions pour optimiser les performances

## 🚀 Initialisation

### Étape 1 : Exécuter les migrations
```bash
php artisan migrate
```

### Étape 2 : Initialiser les permissions
```bash
php artisan permissions:init
```

Cette commande crée toutes les permissions disponibles dans la base de données.

## 📝 Permissions Disponibles

| Permission | Description |
|------------|-------------|
| `view_dashboard` | Accès au tableau de bord administrateur |
| `manage_users` | Gérer les utilisateurs (créer, modifier, supprimer) |
| `manage_plans` | Gérer les plans d'abonnement |
| `manage_subscriptions` | Gérer les abonnements des utilisateurs |
| `manage_payments` | Gérer les transactions de paiement |
| `manage_audios` | Gérer les audios des utilisateurs |
| `view_logs` | Accès aux logs système |
| `manage_comments` | Modérer et gérer les commentaires |
| `manage_newsletter` | Gérer les abonnés à la newsletter |
| `manage_contact_messages` | Gérer les messages de contact |
| `manage_admins` | Gérer les administrateurs et leurs permissions |
| `manage_payment_providers` | Configurer les providers de paiement |

## 👥 Gérer les Administrateurs

### Accéder à la gestion des admins
1. Connectez-vous en tant que **super administrateur**
2. Allez sur `/admin/admins`
3. Vous verrez la liste de tous les administrateurs

### Créer un nouvel administrateur
1. Cliquez sur **"Créer un administrateur"**
2. Remplissez le formulaire :
   - Nom
   - Email
   - Mot de passe
   - Rôle (admin ou super_admin)
   - **Sélectionnez les permissions** (cochez les cases)
3. Cliquez sur **"Créer"**

### Modifier les permissions d'un admin
1. Cliquez sur **"Modifier"** à côté d'un administrateur
2. Cochez/décochez les permissions souhaitées
3. Cliquez sur **"Mettre à jour"**

**Note** : Les permissions sont mises en cache pendant 30 minutes pour optimiser les performances. Elles sont automatiquement invalidées lors des modifications.

## 🔒 Sécurité

### Middleware de Permission
Toutes les routes admin sont protégées par le middleware `permission:nom_permission`.

Exemple :
```php
Route::get('/users', [AdminController::class, 'users'])
    ->middleware('permission:manage_users');
```

### Vérification dans les Vues
Le menu admin vérifie automatiquement les permissions :
```blade
@if($isSuperAdmin || $user->hasPermission('manage_users'))
    <a href="{{ route('admin.users') }}">Utilisateurs</a>
@endif
```

### Vérification dans les Contrôleurs
```php
if (!auth()->user()->hasPermission('manage_users')) {
    abort(403, 'Accès refusé.');
}
```

## 🎯 Exemples d'Utilisation

### Exemple 1 : Admin avec accès utilisateurs et abonnements
1. Créez un admin avec les permissions :
   - `view_dashboard`
   - `manage_users`
   - `manage_subscriptions`
2. Cet admin verra uniquement :
   - Dashboard
   - Utilisateurs
   - Abonnements

### Exemple 2 : Admin modérateur
1. Créez un admin avec les permissions :
   - `view_dashboard`
   - `manage_comments`
   - `manage_contact_messages`
   - `manage_newsletter`
2. Cet admin verra uniquement :
   - Dashboard
   - Commentaires
   - Messages de contact
   - Newsletter

## 🔧 Commandes Utiles

### Réinitialiser les permissions
```bash
php artisan permissions:init
```

### Vider le cache des permissions
```bash
php artisan cache:clear
```

## ⚠️ Important

1. **Super Admin** : Ne peut pas modifier ses propres permissions (il a toujours tout)
2. **Cache** : Les permissions sont mises en cache pendant 30 minutes
3. **Sécurité** : Toujours vérifier les permissions dans les contrôleurs ET les vues
4. **Routes** : Toutes les routes admin doivent avoir le middleware `permission`

## 📊 Structure de la Base de Données

### Table `permissions`
- `id` : Identifiant unique
- `key` : Clé unique de la permission (ex: `manage_users`)
- `name` : Nom affiché (ex: `Gérer les utilisateurs`)
- `description` : Description de la permission

### Table `admin_permissions`
- `user_id` : ID de l'utilisateur (admin)
- `permission_id` : ID de la permission
- Relation many-to-many entre users et permissions

## 🎉 C'est prêt !

Le système est maintenant complètement fonctionnel. Le super administrateur peut créer des administrateurs avec des permissions spécifiques, et chaque admin ne verra que les sections auxquelles il a accès.

