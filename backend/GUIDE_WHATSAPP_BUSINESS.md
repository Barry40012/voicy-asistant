# 📱 Guide Complet - Connecter WhatsApp Business

## 🎯 Objectif

Connecter ton WhatsApp Business à Voicy Assistant pour recevoir et traiter automatiquement les messages vocaux.

---

## 📋 Étape 1 : Créer une Application Meta

### 1.1 Aller sur Meta Developers

1. Va sur : https://developers.facebook.com
2. **Connecte-toi** avec ton compte Facebook
3. Clique sur **"Mes applications"** (en haut à droite)
4. Clique sur **"Créer une application"**

### 1.2 Choisir le Type d'Application

1. Sélectionne **"Business"** comme type d'application
2. Clique sur **"Suivant"**
3. Remplis les informations :
   - **Nom de l'application** : Voicy Assistant (ou autre)
   - **Email de contact** : ton email
   - **Objectif commercial** : Gérer les conversations clients
4. Clique sur **"Créer une application"**

---

## 📋 Étape 2 : Ajouter WhatsApp

### 2.1 Ajouter le Produit WhatsApp

1. Dans le dashboard de ton application, cherche **"Ajouter un produit"**
2. Trouve **"WhatsApp"** dans la liste
3. Clique sur **"Configurer"** à côté de WhatsApp

### 2.2 Accéder à WhatsApp

1. Tu seras redirigé vers la page WhatsApp
2. Clique sur **"Commencer"** ou **"Get Started"**

---

## 📋 Étape 3 : Récupérer les Credentials

### 3.1 Phone Number ID

1. Dans le menu de gauche, clique sur **"API Setup"** ou **"Configuration API"**
2. Tu verras **"Phone number ID"**
3. **Copie cette valeur** (ex: `123456789012345`)

### 3.2 WhatsApp Business Account ID

1. Toujours dans **"API Setup"**
2. Tu verras **"WhatsApp Business Account ID"**
3. **Copie cette valeur**

### 3.3 Access Token (Temporaire pour Test)

1. Dans **"API Setup"**, cherche **"Temporary access token"**
2. **Copie ce token** (commence par `EAA...`)
3. ⚠️ **Ce token expire après 24h** - pour la production, tu devras créer un token permanent

### 3.4 App Secret (Optionnel mais Recommandé)

1. Va dans **"Paramètres"** > **"Paramètres de base"**
2. Cherche **"Secret de l'application"**
3. Clique sur **"Afficher"** et copie le secret
4. ⚠️ **Garde-le secret** - ne le partage jamais

---

## 📋 Étape 4 : Configurer le Webhook

### 4.1 URL du Webhook

Ton URL de webhook est :
```
http://ton-domaine.com/api/webhooks/whatsapp
```

**Pour le développement local**, utilise un service comme **ngrok** :

1. **Installe ngrok** : https://ngrok.com/download
2. **Lance ton serveur Laravel** :
   ```bash
   php artisan serve
   ```
3. **Dans un autre terminal**, lance ngrok :
   ```bash
   ngrok http 8000
   ```
4. **Copie l'URL HTTPS** (ex: `https://abc123.ngrok.io`)
5. **Ton webhook URL** sera : `https://abc123.ngrok.io/api/webhooks/whatsapp`

### 4.2 Verify Token

1. **Génère un token aléatoire** (ex: `voicy_webhook_2024_secret`)
2. **Configure-le dans ton .env** :
   ```env
   WHATSAPP_VERIFY_TOKEN=voicy_webhook_2024_secret
   ```
3. **Vide le cache** :
   ```bash
   php artisan config:clear
   ```

### 4.3 Configurer dans Meta

1. Dans Meta Developers > WhatsApp > **"Configuration"**
2. Cherche **"Webhooks"** ou **"Configuration du webhook"**
3. Clique sur **"Configurer le webhook"** ou **"Modifier"**
4. Remplis :
   - **URL du callback** : `https://abc123.ngrok.io/api/webhooks/whatsapp`
   - **Token de vérification** : `voicy_webhook_2024_secret` (celui que tu as mis dans .env)
5. Clique sur **"Vérifier et enregistrer"**
6. Meta va envoyer une requête de vérification - si tout est OK, tu verras ✅

### 4.4 S'abonner aux Événements

1. Une fois le webhook vérifié, clique sur **"Gérer"** à côté du webhook
2. **Sélectionne les événements** :
   - ☑️ **messages** (obligatoire)
3. Clique sur **"Enregistrer"**

---

## 📋 Étape 5 : Configurer dans Voicy Assistant

### 5.1 Ajouter les Credentials dans .env

Ouvre ton `.env` et ajoute :

```env
# WhatsApp Configuration
WHATSAPP_VERIFY_TOKEN=voicy_webhook_2024_secret
WHATSAPP_APP_SECRET=ton_app_secret_ici
```

### 5.2 Se Connecter dans l'Application

1. **Connecte-toi** à ton compte Voicy Assistant
2. Va dans **Dashboard** > **WhatsApp**
3. **Remplis le formulaire** avec :
   - **Phone Number ID** : (celui que tu as copié)
   - **WhatsApp Business Account ID** : (celui que tu as copié)
   - **Access Token** : (le token temporaire)
   - **Numéro de téléphone** : (optionnel, pour info)
4. Clique sur **"Enregistrer la connexion"**

---

## 🧪 Étape 6 : Tester

### 6.1 Envoyer un Message Vocal

1. **Ouvre WhatsApp** sur ton téléphone
2. **Envoie un message vocal** au numéro WhatsApp Business connecté
3. **Vérifie dans Voicy Assistant** :
   - Va dans **Dashboard** > **Audios**
   - Tu devrais voir le message vocal apparaître
   - Il sera automatiquement traité (transcription, résumé, etc.)

### 6.2 Vérifier les Logs

Si ça ne fonctionne pas, vérifie les logs :

1. **Logs Laravel** :
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Logs dans l'application** :
   - Va dans **Admin** > **Logs** (si tu es admin)
   - Cherche les entrées de type `webhook_whatsapp`

---

## ⚠️ Points Importants

### Token Temporaire vs Permanent

- **Token temporaire** : Expire après 24h, parfait pour tester
- **Token permanent** : Pour la production, tu dois créer un système d'app (System User) dans Meta Business

### Numéro de Téléphone de Test

Meta fournit un **numéro de test** gratuit pour développer. Tu peux l'utiliser pour tester sans coût.

### Coûts WhatsApp

- **Période de test** : Gratuit (jusqu'à 1000 conversations/mois)
- **Production** : Payant selon le nombre de conversations

---

## 🔧 Dépannage

### Webhook non vérifié

- Vérifie que l'URL est accessible (utilise ngrok pour le local)
- Vérifie que le `WHATSAPP_VERIFY_TOKEN` correspond
- Vérifie les logs Laravel

### Messages non reçus

- Vérifie que le webhook est bien configuré dans Meta
- Vérifie que tu es abonné aux événements "messages"
- Vérifie que le numéro de test est bien activé

---

**Suis ces étapes et teste avec ton WhatsApp Business !** 🚀

