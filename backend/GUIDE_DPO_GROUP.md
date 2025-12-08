# 💳 Guide d'Intégration - DPO Group (Direct Pay Online)

## 🎯 Présentation

DPO Group (Direct Pay Online) est un service de paiement spécialisé pour l'Afrique de l'Ouest, acceptant les cartes Visa et Mastercard. Il est particulièrement adapté pour les paiements en Guinée et dans la région.

---

## 📋 Configuration dans l'Administration

### Étape 1 : Accéder à la Configuration

1. **Connectez-vous** en tant qu'administrateur
2. Allez dans **Administration > Providers de Paiement**
3. Trouvez **DPO Group** dans la liste
4. Cliquez sur **"Configurer"**

### Étape 2 : Récupérer vos Credentials DPO Group

Pour obtenir vos identifiants DPO Group :

1. **Créez un compte** sur https://secure.3gdirectpay.com
2. **Connectez-vous** à votre compte DPO Group
3. Dans votre **dashboard**, récupérez :
   - **Company Token** : Identifiant unique de votre entreprise
   - **Service Type** : Type de service (par défaut : `5525` pour les abonnements)
   - **API Key** : Clé API pour les requêtes (si disponible)

### Étape 3 : Configurer dans l'Admin

1. **Environnement** : Choisissez **Test** ou **Production**
2. **Remplissez les champs** :
   - **Company Token (Test)** : Votre Company Token pour le mode test
   - **Company Token (Production)** : Votre Company Token pour le mode production
   - **Service Type** : `5525` (par défaut) ou celui fourni par DPO Group
   - **Clé API** : Si DPO Group vous en fournit une
   - **Secret webhook** : Pour vérifier les webhooks (optionnel)

3. **URL de base** :
   - **Test** : `https://secure1.sandbox.directpay.online`
   - **Production** : `https://secure.3gdirectpay.com`

4. **Activez** le provider et marquez-le comme **Par défaut** si souhaité

5. Cliquez sur **"Enregistrer"**

---

## 🔄 Flux de Paiement

### 1. Création du Paiement

```
Utilisateur choisit un plan
    ↓
Système crée un paiement via DPO Group
    ↓
DPO Group génère un token de transaction
    ↓
Redirection vers la page de paiement DPO Group
```

### 2. Paiement par l'Utilisateur

```
Utilisateur entre ses informations de carte
    ↓
DPO Group traite le paiement
    ↓
Redirection vers votre callback
    ↓
Système vérifie le statut
    ↓
Activation automatique de l'abonnement
```

---

## 🔧 Configuration des Webhooks (Optionnel)

DPO Group peut envoyer des notifications de paiement via webhook :

1. **Dans votre compte DPO Group**, configurez l'URL du webhook :
   ```
   https://votre-domaine.com/api/webhooks/payments/dpogroup
   ```

2. **Configurez le secret webhook** dans l'admin pour sécuriser les webhooks

---

## 📊 Statuts de Transaction DPO Group

| Code DPO | Statut | Description |
|----------|--------|-------------|
| `000` | Succès | Transaction réussie |
| `001` | En attente | Transaction en cours de traitement |
| `002` | Échec | Transaction échouée |
| `3` | Payé | Paiement confirmé |
| `2` | Échoué | Paiement échoué |

---

## ⚙️ Champs de Configuration

### Credentials

- **Company Token** : Identifiant unique de votre entreprise DPO Group
- **Service Type** : Type de service (par défaut : `5525`)
- **Clé API** : Clé API DPO Group (si disponible)
- **Secret webhook** : Secret pour vérifier les webhooks

### Config

- **Base URL** : URL de base de l'API DPO Group
  - Test : `https://secure1.sandbox.directpay.online`
  - Production : `https://secure.3gdirectpay.com`

---

## 🧪 Tester en Mode Test

1. **Configurez DPO Group** avec vos credentials de test
2. **Activez** le provider en mode **Test**
3. **Testez un paiement** avec une carte de test DPO Group
4. **Vérifiez** que le callback fonctionne correctement

---

## 📝 Notes Importantes

- **Service Type** : Le service type `5525` est généralement utilisé pour les abonnements récurrents
- **Devises supportées** : DPO Group supporte principalement les devises locales (GNF, XOF, etc.) et USD/EUR
- **Timeout** : Les paiements ont un timeout de 5 minutes par défaut (PTL=5)
- **Callbacks** : DPO Group redirige vers votre URL de callback avec `TransactionToken` et `CompanyRef`

---

## 🔍 Dépannage

### Le paiement ne se crée pas

- Vérifiez que le **Company Token** est correct
- Vérifiez que l'**URL de base** correspond à l'environnement (test/production)
- Consultez les logs Laravel pour voir les erreurs XML

### Le callback ne fonctionne pas

- Vérifiez que l'URL de callback est accessible publiquement
- Vérifiez que `TransactionToken` et `CompanyRef` sont bien reçus
- Consultez les logs pour voir les paramètres reçus

### La vérification de transaction échoue

- Vérifiez que le **Company Token** est correct
- Vérifiez que le **TransactionToken** est valide
- Assurez-vous que la transaction existe dans DPO Group

---

## 📚 Documentation DPO Group

Pour plus d'informations, consultez la documentation officielle DPO Group :
- Site web : https://secure.3gdirectpay.com
- Support : Contactez le support DPO Group pour obtenir vos credentials

---

**DPO Group est maintenant intégré et prêt à être utilisé !** 🚀

