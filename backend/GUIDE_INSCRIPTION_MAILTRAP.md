# 📧 Guide Inscription Mailtrap

## 📋 Formulaire d'Inscription

### 1. What is your full name? *
**Réponse** : Ton nom complet (ex: Youssouf Barry)

### 2. What do you intend to use Mailtrap for? *
**Réponse** : Sélectionne **"Development"** ou **"Testing"**
- C'est pour tester les emails en développement

### 3. What is your role? *
**Réponse** : Sélectionne **"Developer"** ou **"Software Engineer"**
- Ou choisis celui qui correspond le mieux à ton rôle

### 4. How did you hear about us? *
**Réponse** : Tu peux choisir :
- **"Google"** (si tu as trouvé via recherche)
- **"Friend/Colleague"** (si quelqu'un t'a recommandé)
- **"Other"** (si autre chose)

---

## ✅ Après l'inscription

Une fois inscrit, tu vas :

1. **Vérifier ton email** (si demandé)
2. **Accéder au Dashboard Mailtrap**
3. **Créer une "Demo Inbox"** (déjà créée par défaut)
4. **Récupérer les credentials SMTP**

---

## 🔑 Récupérer les Credentials SMTP

### Étapes :

1. **Dans le Dashboard Mailtrap** :
   - Clique sur **"Inboxes"** dans le menu de gauche
   - Tu verras une "Demo Inbox" (ou crée-en une nouvelle)

2. **Ouvre l'Inbox** :
   - Clique sur la "Demo Inbox"

3. **Va dans "SMTP Settings"** :
   - Cherche l'onglet ou le lien **"SMTP Settings"**
   - Ou clique sur **"Show Credentials"**

4. **Sélectionne "Laravel"** :
   - Dans la liste des frameworks, choisis **"Laravel"**
   - Les credentials seront formatés pour Laravel

5. **Copie les informations** :
   - **Host** : `smtp.mailtrap.io`
   - **Port** : `2525` (ou `465` pour SSL)
   - **Username** : (un long string)
   - **Password** : (un long string)

---

## ⚙️ Configurer dans Laravel

Une fois que tu as les credentials, ajoute-les dans ton `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=ton_username_ici
MAIL_PASSWORD=ton_password_ici
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.com
MAIL_FROM_NAME="${APP_NAME}"
```

Puis vide le cache :
```bash
php artisan config:clear
```

---

**Remplis le formulaire avec ces informations et continue !** 🚀

Une fois que tu as les credentials, donne-moi les valeurs et je t'aide à les configurer dans le `.env`.

