# 📱 Guide Complet - Tester avec WhatsApp Business

## 🎯 Objectif

Connecter ton WhatsApp Business à Voicy Assistant et tester la réception de messages vocaux.

---

## 📋 Étape 1 : Créer une Application Meta

### 1.1 Aller sur Meta Developers

1. Va sur : **https://developers.facebook.com**
2. **Connecte-toi** avec ton compte Facebook
3. Clique sur **"Mes applications"** (en haut à droite)
4. Clique sur **"Créer une application"**

### 1.2 Choisir le Type

1. Sélectionne **"Business"** comme type
2. Clique sur **"Suivant"**
3. Remplis :
   - **Nom** : Voicy Assistant
   - **Email** : ton email
4. Clique sur **"Créer"**

---

## 📋 Étape 2 : Ajouter WhatsApp

### 2.1 Ajouter le Produit

1. Dans le dashboard, cherche **"Ajouter un produit"**
2. Trouve **"WhatsApp"**
3. Clique sur **"Configurer"**

### 2.2 Accéder à WhatsApp

1. Clique sur **"Commencer"** ou **"Get Started"**

---

## 📋 Étape 3 : Récupérer les Credentials

### 3.1 Phone Number ID

1. Menu gauche > **"API Setup"**
2. **Copie "Phone number ID"** (ex: `123456789012345`)

### 3.2 WhatsApp Business Account ID

1. Toujours dans **"API Setup"**
2. **Copie "WhatsApp Business Account ID"**

### 3.3 Access Token (Temporaire)

1. Dans **"API Setup"**, cherche **"Temporary access token"**
2. **Copie ce token** (commence par `EAA...`)
3. ⚠️ **Expire après 24h** - pour la production, crée un token permanent

### 3.4 App Secret (Optionnel)

1. **Paramètres** > **"Paramètres de base"**
2. Cherche **"Secret de l'application"**
3. Clique sur **"Afficher"** et copie

---

## 📋 Étape 4 : Configurer le Webhook (Local avec ngrok)

### 4.1 Installer ngrok

1. **Télécharge ngrok** : https://ngrok.com/download
2. **Extrais** le fichier dans un dossier (ex: `C:\ngrok`)
3. **Ajoute au PATH** ou utilise le chemin complet

### 4.2 Lancer ngrok

1. **Lance ton serveur Laravel** :
   ```bash
   php artisan serve
   ```

2. **Dans un autre terminal**, lance ngrok :
   ```bash
   ngrok http 8000
   ```

3. **Copie l'URL HTTPS** (ex: `https://abc123.ngrok.io`)
   - C'est ton URL publique temporaire

### 4.3 Configure le Verify Token

1. **Génère un token** (ex: `voicy_webhook_2024_secret`)

2. **Ajoute dans le .env** :
   ```env
   WHATSAPP_VERIFY_TOKEN=voicy_webhook_2024_secret
   WHATSAPP_APP_SECRET=ton_app_secret_ici
   ```

3. **Vide le cache** :
   ```bash
   php artisan config:clear
   ```

### 4.4 Configurer dans Meta

1. Meta Developers > WhatsApp > **"Configuration"**
2. Cherche **"Webhooks"**
3. Clique sur **"Configurer le webhook"**
4. Remplis :
   - **URL du callback** : `https://abc123.ngrok.io/api/webhooks/whatsapp`
   - **Token de vérification** : `voicy_webhook_2024_secret`
5. Clique sur **"Vérifier et enregistrer"**
6. Si tout est OK, tu verras ✅

### 4.5 S'abonner aux Événements

1. Clique sur **"Gérer"** à côté du webhook
2. **Sélectionne** :
   - ☑️ **messages**
3. Clique sur **"Enregistrer"**

---

## 📋 Étape 5 : Configurer dans Voicy Assistant

### 5.1 Ajouter dans .env

Ouvre `.env` et ajoute :

```env
WHATSAPP_VERIFY_TOKEN=voicy_webhook_2024_secret
WHATSAPP_APP_SECRET=ton_app_secret_ici
```

### 5.2 Se Connecter dans l'App

1. **Connecte-toi** à Voicy Assistant
2. Va dans **Dashboard** > **WhatsApp**
3. **Remplis le formulaire** :
   - **Phone Number ID** : (celui copié)
   - **WhatsApp Business Account ID** : (celui copié)
   - **Access Token** : (le token temporaire)
   - **Numéro de téléphone** : (optionnel)
4. Clique sur **"Enregistrer la connexion"**

---

## 🧪 Étape 6 : Tester

### 6.1 Envoyer un Message Vocal

1. **Ouvre WhatsApp** sur ton téléphone
2. **Envoie un message vocal** au numéro WhatsApp Business connecté
3. **Vérifie dans Voicy Assistant** :
   - Va dans **Dashboard** > **Audios**
   - Tu devrais voir le message vocal apparaître
   - Il sera automatiquement traité

### 6.2 Vérifier les Logs

Si ça ne fonctionne pas :

```bash
tail -f storage/logs/laravel.log
```

---

## ⚠️ Points Importants

- **Token temporaire** : Expire après 24h
- **ngrok** : Nécessaire pour tester en local
- **Numéro de test** : Meta fournit un numéro gratuit pour tester

---

**Suis ces étapes et teste !** 🚀

