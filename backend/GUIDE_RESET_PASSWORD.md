# 🔐 Guide : Réinitialisation de Mot de Passe

## ✅ Ce qui a été fait

1. **Notification personnalisée** : `ResetPasswordNotification` créée avec le design de la plateforme
2. **Modèle User** : Méthode `sendPasswordResetNotification()` ajoutée pour utiliser la notification personnalisée
3. **Email personnalisé** : Email avec le nom de l'utilisateur et le design de Voicy Assistant

---

## 🔍 Diagnostic : Pourquoi l'email n'arrive pas ?

### 1. Vérifier la configuration email

Vérifie que ton `.env` contient bien les paramètres email :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # ou smtp.gmail.com
MAIL_PORT=2525              # ou 587 pour Gmail
MAIL_USERNAME=ton_username
MAIL_PASSWORD=ton_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Vérifier les logs

Regarde les logs Laravel pour voir les erreurs :

```bash
tail -f storage/logs/laravel.log
```

Ou regarde directement dans `storage/logs/laravel.log` pour voir les erreurs d'envoi.

### 3. Tester l'envoi d'email

#### Option A : Via Tinker

```bash
php artisan tinker
```

Puis dans Tinker :

```php
$user = App\Models\User::where('email', 'ton_email@example.com')->first();
$user->sendPasswordResetNotification('test-token-123');
```

#### Option B : Via la page web

1. Va sur `/forgot-password`
2. Entre ton email
3. Soumets le formulaire
4. Vérifie les logs pour voir si l'email est envoyé

### 4. Vérifier que l'utilisateur existe

Assure-toi que l'email existe dans la base de données :

```bash
php artisan tinker
```

```php
App\Models\User::where('email', 'ton_email@example.com')->exists();
```

### 5. Vérifier Mailtrap (si utilisé)

Si tu utilises Mailtrap pour les tests :
1. Va sur https://mailtrap.io
2. Va dans "Inboxes" > "My Inbox"
3. Vérifie si l'email est arrivé (peut prendre quelques secondes)

### 6. Vérifier Gmail (si utilisé)

Si tu utilises Gmail :
- Vérifie les **spams**
- Vérifie que tu as bien créé un **App Password** (pas ton mot de passe normal)
- Vérifie que l'**authentification à 2 facteurs** est activée (requis pour App Password)

---

## 🚨 Problèmes courants

### Erreur : "Connection timeout"

**Cause** : Mauvaise configuration SMTP ou serveur inaccessible

**Solution** :
- Vérifie `MAIL_HOST` et `MAIL_PORT`
- Vérifie que le firewall n'bloque pas le port
- Teste avec Mailtrap d'abord (plus fiable pour les tests)

### Erreur : "Authentication failed"

**Cause** : Mauvais username/password

**Solution** :
- Pour Gmail : utilise un **App Password**, pas ton mot de passe normal
- Pour Mailtrap : vérifie que tu as copié les bonnes credentials

### L'email n'arrive pas mais pas d'erreur

**Causes possibles** :
1. L'email est dans les spams
2. Le serveur email met du temps à envoyer
3. La queue n'est pas traitée (si tu utilises les queues)

**Solution** :
- Vérifie les spams
- Attends quelques minutes
- Si tu utilises les queues : `php artisan queue:work`

---

## 🔧 Commandes utiles

```bash
# Vider le cache de configuration
php artisan config:clear
php artisan cache:clear

# Vérifier les routes
php artisan route:list | grep password

# Voir les logs en temps réel
tail -f storage/logs/laravel.log

# Tester l'envoi d'email (si commande créée)
php artisan email:test ton_email@example.com
```

---

## 📝 Vérification finale

Avant de tester, vérifie que :

- [ ] Le `.env` contient les bonnes valeurs `MAIL_*`
- [ ] L'utilisateur existe dans la base de données
- [ ] Les logs ne montrent pas d'erreur
- [ ] Le serveur email est accessible (Mailtrap ou Gmail)
- [ ] Tu as vérifié les spams

---

## 🎯 Test rapide

1. Va sur `/forgot-password`
2. Entre ton email
3. Soumets
4. Vérifie les logs : `tail -f storage/logs/laravel.log`
5. Vérifie Mailtrap ou ta boîte email (et les spams)

Si tu vois une erreur dans les logs, partage-la moi et je t'aiderai à la résoudre ! 🚀

