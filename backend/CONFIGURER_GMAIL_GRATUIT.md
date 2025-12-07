# 📧 Configurer Gmail SMTP (Gratuit)

## ✅ Gmail SMTP est 100% Gratuit !

Gmail permet d'envoyer jusqu'à **500 emails/jour gratuitement** - parfait pour commencer !

---

## 🔧 Étape 1 : Créer un App Password Google

### 1.1 Active la Validation en 2 étapes (si pas déjà fait)

1. Va sur : https://myaccount.google.com/security
2. Cherche **"Validation en 2 étapes"**
3. Si c'est **désactivé** :
   - Clique sur **"Activer"**
   - Suis les instructions pour l'activer
   - Tu auras besoin de ton téléphone

### 1.2 Crée un App Password

1. Toujours sur : https://myaccount.google.com/security
2. Cherche **"Mots de passe des applications"** (ou "App Passwords")
3. Si tu ne le vois pas :
   - Assure-toi que la validation en 2 étapes est activée
   - Cherche dans la barre de recherche Google : "app passwords"
4. Clique sur **"Mots de passe des applications"**
5. Sélectionne :
   - **Application** : "Mail"
   - **Appareil** : "Windows Computer" (ou autre)
6. Clique sur **"Générer"**
7. **Copie le mot de passe** (16 caractères, espaces inclus)
   - Exemple : `abcd efgh ijkl mnop`

---

## ⚙️ Étape 2 : Configurer le .env

### 2.1 Ouvre le fichier .env

Ouvre : `E:\Project_Voicy_Assistant\backend\.env`

### 2.2 Modifie les lignes MAIL

Remplace les lignes Mailtrap par :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ton_email@gmail.com
MAIL_PASSWORD=ton_app_password_ici
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ton_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**⚠️ Important** :
- `MAIL_USERNAME` : Ton adresse Gmail complète (ex: `barryyoussouf400@gmail.com`)
- `MAIL_PASSWORD` : L'App Password que tu viens de créer (16 caractères, **sans espaces**)
- `MAIL_FROM_ADDRESS` : La même adresse Gmail

### 2.3 Sauvegarde le fichier

---

## 🧪 Étape 3 : Tester

### 3.1 Vide le cache

```bash
php artisan config:clear
```

### 3.2 Teste l'inscription

1. Va sur : http://127.0.0.1:8000/register
2. Crée un compte avec un **vrai email** (pas Gmail, un autre email)
3. Vérifie la boîte de réception de cet email
4. Tu devrais recevoir l'email de vérification ! 📧

---

## ⚠️ Limitations Gmail Gratuit

- **500 emails/jour maximum** (gratuit)
- **100 destinataires par email maximum**
- Parfait pour commencer et tester !

---

## 🔍 Si ça ne fonctionne pas

### Problème : "Username and Password not accepted"

**Solution** :
- Vérifie que tu utilises bien l'**App Password**, pas ton mot de passe Gmail normal
- Vérifie qu'il n'y a **pas d'espaces** dans l'App Password
- Assure-toi que la validation en 2 étapes est activée

### Problème : "Connection timeout"

**Solution** :
- Vérifie que le port est bien `587`
- Vérifie que `MAIL_ENCRYPTION=tls`

### Problème : Email non reçu

**Solution** :
- Vérifie le dossier **Spam**
- Attends quelques minutes
- Vérifie que l'email de destination est valide

---

**Suis ces étapes et dis-moi quand tu as créé l'App Password !** 🚀

