# 🔐 Guide : Configuration Gmail pour Réinitialisation de Mot de Passe

## ⚠️ Problème courant avec Gmail

Gmail bloque souvent les connexions SMTP si tu utilises ton mot de passe normal. Tu dois utiliser un **App Password** (mot de passe d'application).

---

## ✅ Solution : Créer un App Password Gmail

### Étape 1 : Activer l'authentification à 2 facteurs

1. Va sur https://myaccount.google.com/security
2. Active **"Validation en deux étapes"** si ce n'est pas déjà fait
3. Suis les instructions pour configurer (SMS ou application d'authentification)

### Étape 2 : Créer un App Password

1. Va sur https://myaccount.google.com/apppasswords
   - Ou : Google Account > Sécurité > Validation en deux étapes > Mots de passe des applications
2. Sélectionne **"Application"** : `Mail`
3. Sélectionne **"Appareil"** : `Autre (nom personnalisé)` → entre "Voicy Assistant"
4. Clique sur **"Générer"**
5. **Copie le mot de passe** (16 caractères, sans espaces)
   - Exemple : `abcd efgh ijkl mnop` → utilise `abcdefghijklmnop`

### Étape 3 : Configurer le .env

Remplace dans ton `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ton_email@gmail.com
MAIL_PASSWORD=abcdefghijklmnop  # ← Le App Password (16 caractères)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ton_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Étape 4 : Vider le cache

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🧪 Tester l'envoi

### Option 1 : Via la page web

1. Va sur `/forgot-password`
2. Entre ton email
3. Soumets
4. Regarde les logs : `tail -f storage/logs/laravel.log`

### Option 2 : Via Tinker

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'ton_email@gmail.com')->first();
if ($user) {
    Password::sendResetLink(['email' => $user->email]);
    echo "Email envoyé !";
} else {
    echo "Utilisateur non trouvé";
}
```

---

## 🔍 Vérifier les logs

Regarde les logs pour voir les erreurs :

```bash
tail -f storage/logs/laravel.log
```

Ou ouvre directement le fichier :
```bash
notepad storage/logs/laravel.log
```

Cherche les lignes avec :
- `Password reset requested`
- `Password reset link status`
- `Error sending password reset link`

---

## 🚨 Erreurs courantes

### Erreur : "Authentication failed"

**Cause** : Mauvais App Password ou mot de passe normal utilisé

**Solution** :
- Vérifie que tu utilises bien un **App Password** (16 caractères)
- Vérifie que l'authentification à 2 facteurs est activée
- Recrée un App Password si nécessaire

### Erreur : "Connection timeout"

**Cause** : Firewall ou réseau bloque le port 587

**Solution** :
- Vérifie que le port 587 n'est pas bloqué
- Essaie avec le port 465 et `MAIL_ENCRYPTION=ssl`

### L'email n'arrive pas

**Causes possibles** :
1. L'email est dans les **spams**
2. Gmail met du temps à envoyer
3. Le lien a expiré (60 minutes par défaut)

**Solution** :
- Vérifie les **spams** dans Gmail
- Attends quelques minutes
- Vérifie les logs pour confirmer l'envoi

---

## 🔄 Alternative : Utiliser Mailtrap (pour les tests)

Si Gmail pose problème, utilise Mailtrap pour tester :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=ton_username_mailtrap
MAIL_PASSWORD=ton_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.com
MAIL_FROM_NAME="${APP_NAME}"
```

Avec Mailtrap, tous les emails arrivent dans ton inbox Mailtrap (pas dans ta vraie boîte email).

---

## ✅ Checklist

Avant de tester, vérifie que :

- [ ] L'authentification à 2 facteurs est activée sur Gmail
- [ ] Un App Password a été créé (16 caractères)
- [ ] Le `.env` contient le bon App Password (pas le mot de passe normal)
- [ ] `MAIL_HOST=smtp.gmail.com`
- [ ] `MAIL_PORT=587`
- [ ] `MAIL_ENCRYPTION=tls`
- [ ] Le cache a été vidé (`php artisan config:clear`)
- [ ] Les logs sont vérifiés pour voir les erreurs

---

## 📝 Notes importantes

- **Ne partage JAMAIS ton App Password**
- **Un App Password est différent de ton mot de passe Gmail**
- **Tu peux créer plusieurs App Passwords** (un par application)
- **Si tu changes ton mot de passe Gmail**, tu dois recréer les App Passwords

---

**Une fois configuré, teste la réinitialisation de mot de passe et vérifie les logs !** 🚀

