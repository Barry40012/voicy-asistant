# 📧 Configuration Email - Vérification d'Inscription

## ✅ Ce qui a été fait

1. **Modèle User** : Activation de `MustVerifyEmail`
2. **Vue verify-email** : Page améliorée avec design moderne
3. **Routes** : Déjà configurées par Breeze

## 🔧 Configuration Email

### Option 1 : Mailtrap (Développement - Recommandé)

Mailtrap est parfait pour tester les emails en développement sans envoyer de vrais emails.

#### Étapes :

1. **Créer un compte Mailtrap** : https://mailtrap.io (gratuit)

2. **Récupérer les credentials** :
   - Va dans "Inboxes" > "SMTP Settings"
   - Choisis "Laravel" dans la liste
   - Copie les credentials

3. **Configurer le .env** :

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

### Option 2 : Gmail SMTP (Production)

Pour utiliser Gmail en production :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ton_email@gmail.com
MAIL_PASSWORD=ton_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ton_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**⚠️ Important pour Gmail** :
- Active "App Passwords" dans ton compte Google
- Ne utilise PAS ton mot de passe normal, mais un "App Password"

### Option 3 : SendGrid / Mailgun (Production - Recommandé)

Pour la production, utilise un service professionnel :

#### SendGrid :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=ton_api_key_sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Mailgun :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=ton_username_mailgun
MAIL_PASSWORD=ton_password_mailgun
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🧪 Tester

1. **Configure le .env** avec les credentials Mailtrap (ou autre)

2. **Vide le cache** :
```bash
php artisan config:clear
```

3. **Teste l'inscription** :
   - Va sur http://127.0.0.1:8000/register
   - Crée un compte
   - Tu seras redirigé vers la page de vérification
   - Vérifie ta boîte Mailtrap (ou ton email)

4. **Clique sur le lien** dans l'email pour vérifier

## 📋 Fonctionnement

1. **Inscription** → Email de vérification envoyé automatiquement
2. **Page de vérification** → S'affiche si l'email n'est pas vérifié
3. **Lien dans l'email** → Clique dessus pour vérifier
4. **Après vérification** → Accès au dashboard

## 🔍 Vérifier que ça fonctionne

### Test rapide avec Mailtrap :

1. Crée un compte sur https://mailtrap.io
2. Récupère les credentials SMTP
3. Ajoute-les dans le `.env`
4. Vide le cache : `php artisan config:clear`
5. Inscris-toi avec un nouvel email
6. Vérifie ta boîte Mailtrap → Tu devrais voir l'email !

---

**Configure Mailtrap pour tester, puis on passe à la production !** 🚀

