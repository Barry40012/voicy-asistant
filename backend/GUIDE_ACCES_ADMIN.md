# Guide d'accès au Panel Admin

## ✅ Vérification de l'accès

### 1. Vérifier ton rôle dans la base de données

Exécute cette commande pour vérifier ton rôle :

```bash
php artisan user:check-role businesstraiding855@gmail.com
```

Tu devrais voir :
- **Rôle: admin**
- **Is Admin: OUI**

### 2. Si le rôle n'est pas "admin"

Définis-toi comme admin :

```bash
php artisan user:make-admin businesstraiding855@gmail.com
```

### 3. Accéder au panel admin

1. **Déconnecte-toi** de ton compte (Log Out)
2. **Reconnecte-toi** avec ton email et mot de passe
3. Va sur : `http://127.0.0.1:8000/admin`

### 4. Si tu reçois toujours une erreur 403

#### Option A : Vérifier via la route de test

Va sur : `http://127.0.0.1:8000/test-role`

Cela affichera :
- Ton rôle dans la session
- Ton rôle dans la base de données
- Si tu es reconnu comme admin

#### Option B : Vider le cache

```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
```

Puis reconnecte-toi.

#### Option C : Vérifier directement dans la base de données

```bash
php artisan tinker
```

Puis dans tinker :
```php
$user = App\Models\User::where('email', 'businesstraiding855@gmail.com')->first();
echo $user->role; // Doit afficher "admin"
```

## 🔧 Fonctionnalités du Panel Admin

### Accès aux sections

- **Dashboard** : `/admin` - Vue d'ensemble avec statistiques
- **Utilisateurs** : `/admin/users` - Liste de tous les utilisateurs
- **Plans** : `/admin/plans` - Gestion des plans d'abonnement
- **Abonnements** : `/admin/subscriptions` - Liste des abonnements
- **Paiements** : `/admin/payments` - Historique des paiements
- **Audios** : `/admin/audios` - Liste de tous les audios
- **Logs** : `/admin/logs` - Logs système

### Modifier les identifiants de connexion

1. Va sur `/admin/users`
2. Clique sur un utilisateur pour voir ses détails
3. Dans la section **"Modifier les identifiants de connexion"** :
   - **Modifier l'email** : Change l'email de l'utilisateur
   - **Modifier le mot de passe** : Change le mot de passe de l'utilisateur

⚠️ **Note** : Quand tu modifies l'email, l'utilisateur devra vérifier son nouveau email.

## 🐛 Dépannage

### Erreur 403 Forbidden

1. Vérifie que ton rôle est bien "admin" dans la DB
2. Déconnecte-toi et reconnecte-toi
3. Vide le cache (voir Option B ci-dessus)
4. Vérifie les logs : `storage/logs/laravel.log`

### Le middleware ne reconnaît pas le rôle

Le middleware charge maintenant directement depuis la base de données, donc il devrait toujours avoir le rôle à jour. Si ça ne fonctionne toujours pas :

1. Vérifie que le middleware est bien enregistré dans `app/Http/Kernel.php`
2. Vérifie que la route utilise bien le middleware `admin`
3. Vérifie les logs Laravel pour voir les détails

## 📝 Commandes utiles

```bash
# Vérifier le rôle d'un utilisateur
php artisan user:check-role {email}

# Définir un utilisateur comme admin
php artisan user:make-admin {email}

# Vider tous les caches
php artisan optimize:clear
```

