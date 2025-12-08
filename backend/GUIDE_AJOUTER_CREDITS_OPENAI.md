# 💳 Guide : Ajouter des Crédits OpenAI

Ce guide vous explique comment ajouter des crédits à votre compte OpenAI pour utiliser l'analyse IA de newsletter.

## 📋 Prérequis

1. Un compte OpenAI (si vous n'en avez pas, créez-en un sur [platform.openai.com](https://platform.openai.com))
2. Une carte bancaire ou un moyen de paiement

## 🚀 Étapes pour Ajouter des Crédits

### Étape 1 : Se Connecter à OpenAI

1. Allez sur [platform.openai.com](https://platform.openai.com)
2. Connectez-vous avec votre compte OpenAI
3. Si vous n'avez pas de compte, créez-en un (gratuit)

### Étape 2 : Accéder aux Paramètres de Facturation

1. Cliquez sur votre **photo de profil** en haut à droite
2. Sélectionnez **"Settings"** (Paramètres)
3. Cliquez sur **"Billing"** (Facturation) dans le menu de gauche
   - Ou allez directement sur [platform.openai.com/account/billing](https://platform.openai.com/account/billing)

### Étape 3 : Ajouter un Moyen de Paiement

1. Dans la section **"Payment method"** (Méthode de paiement)
2. Cliquez sur **"Add payment method"** (Ajouter un moyen de paiement)
3. Entrez les informations de votre carte bancaire :
   - Numéro de carte
   - Date d'expiration
   - CVV
   - Nom sur la carte
   - Adresse de facturation
4. Cliquez sur **"Save"** (Enregistrer)

### Étape 4 : Ajouter des Crédits

1. Dans la section **"Credits"** ou **"Add credits"**
2. Choisissez le montant à ajouter :
   - **Minimum recommandé** : $5 (environ 5€)
   - **Pour un usage régulier** : $10-20
   - **Pour un usage intensif** : $50+
3. Cliquez sur **"Add credits"** ou **"Purchase"**
4. Confirmez le paiement

### Étape 5 : Vérifier les Crédits

1. Après le paiement, vous verrez vos crédits dans la section **"Credits"**
2. Les crédits sont disponibles immédiatement
3. Vous pouvez voir votre usage dans **"Usage"** (Utilisation)

## 💰 Coûts Approximatifs

### Pour l'Amélioration de Newsletter

- **GPT-3.5-turbo** : ~$0.001-0.002 par newsletter (très économique)
- **GPT-4** : ~$0.01-0.02 par newsletter (plus cher mais meilleur)

**Avec $5**, vous pouvez améliorer environ **2500-5000 newsletters** avec GPT-3.5-turbo !

### Pour la Transcription Audio

- **Whisper** : ~$0.006 par minute d'audio
- **Avec $5**, vous pouvez transcrire environ **800 minutes** d'audio

## ✅ Après Avoir Ajouté les Crédits

### Vérifier la Configuration OpenAI

1. Allez sur `/admin/ai-providers` dans votre plateforme
2. Vérifiez que **OpenAI** est configuré :
   - Cliquez sur "Configurer"
   - Vérifiez que votre clé API est bien entrée
   - Cliquez sur "Tester" pour vérifier la connexion

### Activer OpenAI

1. Dans `/admin/ai-providers`
2. Trouvez **"OpenAI"**
3. **Activez** OpenAI
4. Cliquez sur **"Définir comme par défaut"**

### Tester l'Analyse de Newsletter

1. Allez sur `/admin/newsletter/create`
2. Remplissez le sujet et le contenu
3. Cliquez sur **"Analyser avec l'IA"**
4. Ça devrait fonctionner maintenant ! 🎉

## 🔍 Vérifier votre Usage

Pour surveiller votre consommation :

1. Allez sur [platform.openai.com/usage](https://platform.openai.com/usage)
2. Vous verrez :
   - Votre usage actuel
   - Les coûts par jour/mois
   - Les prévisions de consommation

## ⚠️ Important

- Les crédits sont débités au fur et à mesure de l'utilisation
- Vous recevrez des alertes quand vos crédits sont faibles
- Vous pouvez ajouter des crédits à tout moment
- Les crédits ne expirent pas (sauf si votre compte est inactif pendant longtemps)

## 💡 Conseils pour Économiser

1. **Utilisez GPT-3.5-turbo** au lieu de GPT-4 (suffisant pour les newsletters)
2. **Limitez `max_tokens`** dans la configuration (1000-1500 au lieu de 2000)
3. **Réduisez la température** (0.3 au lieu de 0.7) pour plus de cohérence
4. **N'utilisez l'IA que pour les newsletters importantes**

## 🆘 Problèmes Courants

### "Payment method declined"
- Vérifiez que votre carte est valide
- Vérifiez que vous avez suffisamment de fonds
- Essayez une autre carte

### "Credits not showing"
- Attendez quelques minutes (le traitement peut prendre du temps)
- Rafraîchissez la page
- Vérifiez vos emails pour une confirmation

### "API key invalid"
- Vérifiez que votre clé API est correcte dans `/admin/ai-providers`
- Testez la connexion avec le bouton "Tester"

## ✅ Checklist

- [ ] Compte OpenAI créé
- [ ] Carte bancaire ajoutée
- [ ] Crédits ajoutés ($5 minimum recommandé)
- [ ] Clé API configurée dans `/admin/ai-providers`
- [ ] OpenAI activé et défini comme provider par défaut
- [ ] Test de connexion réussi
- [ ] Test d'analyse de newsletter réussi

Une fois ces étapes complétées, l'analyse IA de newsletter fonctionnera parfaitement ! 🚀

