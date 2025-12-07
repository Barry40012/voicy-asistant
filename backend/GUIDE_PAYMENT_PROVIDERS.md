# 💳 Guide de Configuration des Providers de Paiement

## 📋 Vue d'ensemble

Ce guide explique comment configurer les différents providers de paiement (Flutterwave, Stripe, Orange Money, MTN, Paycard) en mode **Test** et **Production**.

---

## 🔧 Configuration Générale

### Accès à la Configuration

1. **Connectez-vous** en tant qu'administrateur
2. Allez dans **Administration** > **Providers de Paiement**
3. Cliquez sur **Modifier** pour le provider que vous souhaitez configurer

### Modes Disponibles

- **Test / Sandbox** : Pour tester les paiements sans utiliser de vrais fonds
- **Production / Live** : Pour les paiements réels en production

⚠️ **Important** : Les clés API de test et de production sont différentes. Assurez-vous d'utiliser les bonnes clés selon l'environnement sélectionné.

---

## 🚀 Flutterwave

### Mode Test

1. **Créez un compte** : https://dashboard.flutterwave.com/signup
2. **Récupérez les clés de test** :
   - Allez dans **Settings** > **API Keys**
   - Copiez la **Public Key** (commence par `FLWPUBK_TEST_...`)
   - Copiez la **Secret Key** (commence par `FLWSECK_TEST_...`)
3. **Configurez le webhook** :
   - URL : `https://votre-domaine.com/api/webhooks/payments/flutterwave`
   - Événements : `charge.completed`
   - Copiez le **Secret Hash** généré

### Mode Production

1. **Activez votre compte** (vérification d'identité requise)
2. **Basculez en mode Live** dans Settings
3. **Récupérez les clés Live** :
   - Public Key (commence par `FLWPUBK_...`)
   - Secret Key (commence par `FLWSECK_...`)
4. **Configurez le webhook de production** avec la même URL

### Champs à Remplir

- **Clé secrète** : `FLWSECK_TEST_...` (test) ou `FLWSECK_...` (production)
- **Clé publique** : `FLWPUBK_TEST_...` (test) ou `FLWPUBK_...` (production)
- **Secret webhook** : Le hash généré par Flutterwave
- **URL de base** : `https://api.flutterwave.com/v3` (par défaut)

---

## 💳 Stripe

### Mode Test

1. **Créez un compte** : https://dashboard.stripe.com/register
2. **Récupérez les clés de test** :
   - Allez dans **Developers** > **API keys**
   - Copiez la **Publishable key** (commence par `pk_test_...`)
   - Copiez la **Secret key** (commence par `sk_test_...`)
3. **Configurez le webhook** :
   - URL : `https://votre-domaine.com/api/webhooks/payments/stripe`
   - Événements : `payment_intent.succeeded`, `charge.succeeded`
   - Copiez le **Signing secret**

### Mode Production

1. **Activez votre compte** (vérification d'entreprise requise)
2. **Récupérez les clés Live** :
   - Publishable key (commence par `pk_live_...`)
   - Secret key (commence par `sk_live_...`)
3. **Configurez le webhook de production**

### Champs à Remplir

- **Clé secrète** : `sk_test_...` (test) ou `sk_live_...` (production)
- **Clé publique** : `pk_test_...` (test) ou `pk_live_...` (production)
- **Secret webhook** : Le signing secret de Stripe

---

## 🟠 Orange Money

### Mode Test

1. **Contactez Orange Money** pour obtenir un compte développeur
2. **Récupérez les identifiants** :
   - **ID Marchand** (Merchant ID)
   - **Clé API** (API Key)
3. **Configurez le webhook** :
   - URL : `https://votre-domaine.com/api/webhooks/payments/orange`
   - Copiez le **Secret webhook**

### Mode Production

1. **Soumettez votre demande** pour un compte de production
2. **Récupérez les identifiants de production**
3. **Configurez le webhook de production**

### Champs à Remplir

- **ID Marchand** : Votre identifiant marchand Orange Money
- **Clé API** : Votre clé API Orange Money
- **Secret webhook** : Le secret pour vérifier les webhooks

---

## 📱 MTN Mobile Money

### Mode Test

1. **Contactez MTN** pour obtenir un compte développeur
2. **Récupérez les identifiants** :
   - **Clé d'abonnement** (Subscription Key)
   - **Clé API** (API Key)
3. **Configurez le webhook** :
   - URL : `https://votre-domaine.com/api/webhooks/payments/mtn`
   - Copiez le **Secret webhook**

### Mode Production

1. **Soumettez votre demande** pour un compte de production
2. **Récupérez les identifiants de production**
3. **Configurez le webhook de production**

### Champs à Remplir

- **Clé d'abonnement** : Votre clé d'abonnement MTN
- **Clé API** : Votre clé API MTN
- **Secret webhook** : Le secret pour vérifier les webhooks

---

## 🎴 Paycard (Guinée)

### À Propos de Paycard

Paycard est une application de paiement guinéenne qui permet :
- ✅ Créer un compte utilisateur
- ✅ Acheter des cartes Visa virtuelles
- ✅ Lier un compte Orange Money
- ✅ Effectuer des transactions entre Orange Money et Paycard
- ✅ Payer des abonnements et services

### Mode Test

1. **Contactez Paycard** pour obtenir :
   - Un compte développeur/test
   - Les clés API de test
   - L'accès à l'API sandbox

2. **Récupérez les identifiants** :
   - **ID Marchand** (Merchant ID)
   - **Clé API** (API Key)
   - **Clé secrète** (Secret Key)

3. **Configurez le webhook** :
   - URL : `https://votre-domaine.com/api/webhooks/payments/paycard`
   - Copiez le **Secret webhook**

### Mode Production

1. **Soumettez votre demande** pour un compte de production auprès de Paycard
2. **Récupérez les identifiants de production**
3. **Configurez le webhook de production**

### Champs à Remplir

- **ID Marchand** : Votre identifiant marchand Paycard
- **Clé API** : Votre clé API Paycard
- **Clé secrète** : Votre clé secrète Paycard
- **Secret webhook** : Le secret pour vérifier les webhooks
- **URL de base** : 
  - Test : `https://api.paycard.gn/sandbox`
  - Production : `https://api.paycard.gn/v1`

### Intégration Paycard

L'adaptateur Paycard est déjà intégré dans le système. Pour l'utiliser :

1. **Activez Paycard** dans la liste des providers
2. **Configurez les clés API** selon l'environnement (test ou production)
3. **Définissez Paycard comme provider par défaut** (optionnel)
4. Les utilisateurs pourront alors payer leurs abonnements via Paycard

### Fonctionnalités

- ✅ Paiement par carte Visa virtuelle (via Paycard)
- ✅ Paiement via Orange Money (lié à Paycard)
- ✅ Vérification automatique des paiements
- ✅ Activation automatique des abonnements après paiement réussi

---

## 🔄 Basculer entre Test et Production

### Étapes

1. **Allez dans** Administration > Providers de Paiement
2. **Cliquez sur Modifier** pour le provider souhaité
3. **Changez l'environnement** :
   - Sélectionnez **Test** pour le développement
   - Sélectionnez **Production** pour la mise en ligne
4. **Mettez à jour les clés API** selon l'environnement sélectionné
5. **Enregistrez** les modifications

⚠️ **Attention** : Les clés de test et de production sont stockées séparément. Assurez-vous d'avoir configuré les deux si vous voulez basculer facilement.

---

## ✅ Vérification

### Comment Vérifier que ça Fonctionne

1. **Mode Test** :
   - Créez un paiement de test
   - Vérifiez que le lien de paiement est généré
   - Testez le paiement avec les cartes de test du provider

2. **Mode Production** :
   - Vérifiez que les clés de production sont bien configurées
   - Testez avec un petit montant réel
   - Vérifiez que le webhook fonctionne et active l'abonnement

### Logs

Les logs de paiement sont disponibles dans :
- **Administration** > **Logs**
- Recherchez les entrées avec `PaymentAdapter` ou le nom du provider

---

## 🆘 Support

### Problèmes Courants

1. **Erreur "API key required"** :
   - Vérifiez que les clés sont bien enregistrées
   - Vérifiez que vous utilisez les bonnes clés selon l'environnement

2. **Webhook ne fonctionne pas** :
   - Vérifiez que l'URL du webhook est correcte
   - Vérifiez que le secret webhook est bien configuré
   - Vérifiez les logs pour voir les erreurs

3. **Paiement en attente** :
   - Le système vérifie automatiquement les paiements en attente
   - Si le problème persiste, vérifiez les logs et contactez le support du provider

---

## 📝 Notes Importantes

- ⚠️ **Ne partagez jamais vos clés API** avec qui que ce soit
- 🔒 **Les clés sont stockées de manière sécurisée** dans la base de données
- 🔄 **Les clés test et production sont séparées** pour éviter les erreurs
- ✅ **Toujours tester en mode test** avant de passer en production
- 📧 **Configurez les webhooks** pour l'activation automatique des abonnements

---

## 🔗 Liens Utiles

- **Flutterwave** : https://dashboard.flutterwave.com
- **Stripe** : https://dashboard.stripe.com
- **Orange Money** : Contactez votre représentant Orange
- **MTN Mobile Money** : Contactez votre représentant MTN
- **Paycard** : Contactez Paycard pour l'accès à l'API

---

**Dernière mise à jour** : Décembre 2025
