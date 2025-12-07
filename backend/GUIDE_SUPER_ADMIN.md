# 🔐 Guide : Gérer les Super Administrateurs

## 📋 Vérifier les Super Admins

### Méthode 1 : Via la commande Artisan (Recommandé)

```bash
php artisan admin:check-super-admins
```

Cette commande affiche :
- ✅ Tous les super administrateurs
- 📋 Tous les administrateurs normaux
- Les informations : ID, Nom, Email, Rôle, Date de création

### Méthode 2 : Via Tinker

```bash
php artisan tinker
```

Puis dans tinker :
```php
$superAdmins = App\Models\User::where('role', 'super_admin')->get(['id', 'name', 'email']);
foreach($superAdmins as $admin) {
    echo $admin->name . ' - ' . $admin->email . PHP_EOL;
}
exit
```

### Méthode 3 : Via la Base de Données

1. Accédez à Supabase Dashboard
2. Allez dans **Table Editor** > **users**
3. Filtrez par `role = 'super_admin'`

---

## 🎯 Se Donner le Rôle Super Admin

### Méthode 1 : Via la commande Artisan (Recommandé)

```bash
php artisan admin:make-super-admin ton-email@example.com
```

**Remplace** `ton-email@example.com` par ton email.

Exemple :
```bash
php artisan admin:make-super-admin businesstraiding855@gmail.com
```

### Méthode 2 : Via Tinker

```bash
php artisan tinker
```

Puis dans tinker :
```php
$user = App\Models\User::where('email', 'ton-email@example.com')->first();
$user->role = 'super_admin';
$user->save();
echo "Rôle mis à jour !";
exit
```

### Méthode 3 : Via la Base de Données

1. Accédez à Supabase Dashboard
2. Allez dans **Table Editor** > **users**
3. Trouve ton utilisateur
4. Modifie le champ `role` : change en `super_admin`
5. Sauvegarde

---

## ✅ Vérifier que ça fonctionne

1. **Déconnecte-toi** de l'application
2. **Reconnecte-toi** avec ton compte
3. Va sur `/admin/admins` - Tu devrais voir le menu "Administrateurs"
4. Tu peux maintenant créer et gérer d'autres administrateurs

---

## 🔑 Différence entre Admin et Super Admin

### Admin (`admin`)
- Accès au panel admin
- Peut gérer : utilisateurs, plans, abonnements, paiements, audios, logs, paramètres
- **NE PEUT PAS** gérer les autres administrateurs

### Super Admin (`super_admin`)
- Accès au panel admin
- Peut gérer : utilisateurs, plans, abonnements, paiements, audios, logs, paramètres
- **PEUT** gérer les autres administrateurs (créer, modifier, supprimer)
- A automatiquement **toutes les permissions**

---

## 🚨 Important

- **Ne partage JAMAIS** les accès super admin
- **Utilise** `super_admin` seulement pour toi
- **Donne** `admin` aux autres administrateurs si besoin
- Un super admin ne peut pas se supprimer lui-même depuis l'interface

---

## 📝 Commandes Disponibles

```bash
# Vérifier tous les super admins
php artisan admin:check-super-admins

# Donner le rôle super_admin à un utilisateur
php artisan admin:make-super-admin email@example.com

# Vérifier le rôle d'un utilisateur spécifique
php artisan user:check-role email@example.com

# Donner le rôle admin à un utilisateur
php artisan user:make-admin email@example.com
```

