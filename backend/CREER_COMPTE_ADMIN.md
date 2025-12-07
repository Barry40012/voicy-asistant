# 🔐 Créer un Compte Administrateur

## 📋 Méthode 1 : Via la Base de Données (Recommandé)

### Étape 1 : Accéder à la base de données

1. **Ouvre** Supabase Dashboard : https://supabase.com/dashboard
2. **Sélectionne** ton projet
3. Va dans **Table Editor** > **users**

### Étape 2 : Modifier ton utilisateur

1. **Trouve** ton utilisateur dans la table `users`
2. **Clique** sur la ligne pour l'éditer
3. **Modifie** le champ `role` :
   - Change `user` (ou `null`) en `admin` ou `super_admin`
4. **Sauvegarde**

---

## 📋 Méthode 2 : Via Tinker (Ligne de commande)

### Étape 1 : Ouvrir Tinker

```bash
cd backend
php artisan tinker
```

### Étape 2 : Modifier ton utilisateur

Dans Tinker, exécute :

```php
$user = App\Models\User::where('email', 'ton-email@example.com')->first();
$user->role = 'admin';
$user->save();
exit
```

**Remplace** `ton-email@example.com` par ton email.

---

## 📋 Méthode 3 : Via SQL Direct

### Étape 1 : Accéder à SQL Editor

1. **Ouvre** Supabase Dashboard
2. Va dans **SQL Editor**

### Étape 2 : Exécuter la requête

```sql
UPDATE users 
SET role = 'admin' 
WHERE email = 'ton-email@example.com';
```

**Remplace** `ton-email@example.com` par ton email.

---

## ✅ Vérifier l'accès

1. **Déconnecte-toi** de l'application
2. **Reconnecte-toi**
3. **Va sur** : `/admin`
4. Tu devrais voir le **Dashboard Administrateur**

---

## 🔒 Rôles disponibles

- **`admin`** : Accès complet au panel admin
- **`super_admin`** : Accès complet + permissions spéciales (futur)
- **`user`** ou **`null`** : Utilisateur normal (pas d'accès admin)

---

## 🚨 Important

- **Ne partage JAMAIS** les accès admin
- **Utilise** `super_admin` seulement pour toi
- **Donne** `admin` aux autres administrateurs si besoin

---

**Une fois le rôle défini, tu auras accès à tout le panel admin !** 🎉

