# 🚀 Configuration Flutterwave pour Voicy Assistant

## 📋 Vue d'ensemble

Flutterwave est maintenant configuré comme **méthode de paiement par défaut** pour les abonnements. Il accepte les **cartes Visa, Mastercard** et autres méthodes de paiement populaires en Afrique.

---

## 🔧 Étape 1 : Créer un compte Flutterwave

1. **Va sur** : https://dashboard.flutterwave.com/signup
2. **Crée un compte** (gratuit)
3. **Vérifie ton email** et complète ton profil

---

## 🔑 Étape 2 : Récupérer les clés API

### En mode Test (Sandbox) :

1. **Connecte-toi** à ton dashboard Flutterwave
2. Va dans **Settings** > **API Keys**
3. Tu verras :
   - **Public Key** (commence par `FLWPUBK-...`)
   - **Secret Key** (commence par `FLWSECK-...`)
4. **Copie ces clés** (tu peux les révéler en cliquant sur l'icône œil)

### En mode Production :

1. **Active ton compte** (vérification d'identité requise)
2. **Bascule en mode Live** dans Settings
3. **Récupère les clés Live** (différentes des clés de test)

---

## ⚙️ Étape 3 : Configurer le Webhook

### Pour le développement local :

1. **Installe ngrok** (si pas déjà fait) : https://ngrok.com/download
2. **Démarre ngrok** :
   ```bash
   ngrok http 8000
   ```
3. **Copie l'URL** (ex: `https://abc123.ngrok.io`)

### Dans Flutterwave Dashboard :

1. Va dans **Settings** > **Webhooks**
2. **Ajoute un webhook** :
   - **URL** : `https://ton-domaine.com/api/webhooks/payments/flutterwave`
   - **Events** : Sélectionne `charge.completed`
3. **Copie le Secret Hash** (généré automatiquement)

---

## 📝 Étape 4 : Mettre à jour le fichier `.env`

Ouvre `backend/.env` et ajoute ces lignes :

```env
# Flutterwave Configuration
FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST_xxxxxxxxxxxxx
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST_xxxxxxxxxxxxx
FLUTTERWAVE_WEBHOOK_SECRET=ton_secret_hash_ici
FLUTTERWAVE_BASE_URL=https://api.flutterwave.com/v3
```

**Remplace** :
- `FLWSECK_TEST_xxxxxxxxxxxxx` par ta **Secret Key**
- `FLWPUBK_TEST_xxxxxxxxxxxxx` par ta **Public Key**
- `ton_secret_hash_ici` par le **Secret Hash** du webhook

---

## 🧪 Étape 5 : Tester le paiement

### Cartes de test Flutterwave :

**Carte Visa réussie** :
- **Numéro** : `5531886652142950`
- **CVV** : `564`
- **Date d'expiration** : N'importe quelle date future
- **PIN** : `3310`
- **OTP** : `123456`

**Carte Mastercard réussie** :
- **Numéro** : `5399838383838381`
- **CVV** : `883`
- **Date d'expiration** : N'importe quelle date future
- **PIN** : `3310`
- **OTP** : `123456`

### Tester dans l'application :

1. **Connecte-toi** à ton compte
2. Va dans **Dashboard** > **Mon Abonnement**
3. **Choisis un plan** et clique sur "S'abonner avec ma carte Visa"
4. Tu seras **redirigé vers Flutterwave**
5. **Utilise une carte de test** ci-dessus
6. Après le paiement, tu seras **redirigé vers le dashboard**
7. **Vérifie** que ton abonnement est actif

---

## 🌍 Étape 6 : Passer en Production

### Quand tu es prêt :

1. **Active ton compte Flutterwave** (vérification d'identité)
2. **Bascule en mode Live** dans Settings
3. **Récupère les clés Live**
4. **Mets à jour le `.env`** avec les clés Live
5. **Configure le webhook** avec ton domaine de production
6. **Teste avec une vraie carte** (petit montant)

---

## 💰 Devises supportées

Flutterwave supporte plusieurs devises africaines :
- **XOF** (Franc CFA Ouest-Africain) - Guinée, Sénégal, etc.
- **XAF** (Franc CFA Centrafricain)
- **NGN** (Naira Nigérien)
- **GHS** (Cedi Ghanéen)
- **KES** (Shilling Kenyan)
- **ZAR** (Rand Sud-Africain)
- **USD** (Dollar US)
- **EUR** (Euro)

**Par défaut**, le système utilise **XOF**. Tu peux changer dans les plans.

---

## 🔒 Sécurité

- **Ne partage JAMAIS** tes clés secrètes
- **Utilise les variables d'environnement** (`.env`)
- **Active 2FA** sur ton compte Flutterwave
- **Vérifie les webhooks** avec le secret hash

---

## 📞 Support

- **Documentation Flutterwave** : https://developer.flutterwave.com/docs
- **Support Flutterwave** : support@flutterwave.com
- **Dashboard** : https://dashboard.flutterwave.com

---

## ✅ Checklist

- [ ] Compte Flutterwave créé
- [ ] Clés API récupérées (Test ou Live)
- [ ] Webhook configuré
- [ ] Variables ajoutées dans `.env`
- [ ] Cache Laravel vidé : `php artisan config:clear`
- [ ] Test avec une carte de test réussi
- [ ] Abonnement activé automatiquement après paiement

---

**Une fois configuré, les utilisateurs pourront payer avec leur carte Visa UBA directement !** 🎉

