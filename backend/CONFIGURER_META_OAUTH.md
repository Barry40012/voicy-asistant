# 🔧 Configurer Meta OAuth pour Connexion en un Clic

## 🎯 Objectif

Permettre aux utilisateurs de se connecter à WhatsApp Business en un seul clic via OAuth Meta.

---

## 📋 Étape 1 : Créer une Application Meta

1. Va sur : **https://developers.facebook.com**
2. Crée une application de type **"Business"**
3. Ajoute le produit **"WhatsApp"**

---

## 📋 Étape 2 : Récupérer App ID et App Secret

1. Dans Meta Developers > **Paramètres** > **Paramètres de base**
2. **Copie** :
   - **ID de l'application** (App ID)
   - **Secret de l'application** (App Secret)

---

## 📋 Étape 3 : Configurer les URLs de Redirection

1. Dans Meta Developers > **Paramètres** > **Paramètres de base**
2. Cherche **"URL de redirection OAuth valides"**
3. **Ajoute** :
   ```
   http://127.0.0.1:8000/dashboard/whatsapp/callback
   ```
   (Pour la production, ajoute aussi ton domaine)

---

## 📋 Étape 4 : Configurer dans .env

Ouvre `.env` et ajoute :

```env
# Meta OAuth Configuration
META_APP_ID=ton_app_id_ici
META_APP_SECRET=ton_app_secret_ici
```

---

## 📋 Étape 5 : Vider le Cache

```bash
php artisan config:clear
```

---

## 🧪 Tester

1. Va dans **Dashboard** > **WhatsApp**
2. Clique sur **"Connecter avec Meta"**
3. Autorise l'accès
4. Tu seras redirigé et connecté automatiquement ! 🎉

---

## ⚠️ Important

- **App ID et App Secret** : Garde-les secrets
- **URL de redirection** : Doit correspondre exactement
- **Permissions** : L'app doit avoir les permissions WhatsApp Business

---

**Configure Meta OAuth maintenant pour activer la connexion en un clic !** 🚀

