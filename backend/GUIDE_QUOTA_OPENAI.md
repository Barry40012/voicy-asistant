# 🔧 Guide : Gérer le Quota OpenAI Dépassé

## 🚨 Problème

Vous recevez l'erreur : **"Quota API dépassé. Vérifiez votre abonnement OpenAI."**

Cela signifie que vous avez atteint la limite de votre abonnement OpenAI (gratuit ou payant).

## ✅ Solutions

### Option 1 : Vérifier et Augmenter votre Quota OpenAI

1. **Connectez-vous à votre compte OpenAI** :
   - Allez sur [platform.openai.com](https://platform.openai.com)
   - Connectez-vous avec votre compte

2. **Vérifiez votre quota** :
   - Allez dans "Usage" ou "Billing"
   - Vérifiez votre limite mensuelle
   - Vérifiez votre crédit restant

3. **Augmentez votre quota** :
   - Si vous êtes sur le plan gratuit, vous avez une limite très basse
   - Passez à un plan payant pour augmenter la limite
   - Ajoutez des crédits à votre compte

### Option 2 : Utiliser HuggingFace (Alternative Gratuite)

HuggingFace offre des modèles gratuits pour l'analyse de texte :

1. **Configurer HuggingFace** :
   - Allez sur `/admin/ai-providers`
   - Cliquez sur "Configurer" pour HuggingFace
   - Obtenez une clé API gratuite sur [huggingface.co](https://huggingface.co)
   - Configurez la clé API
   - Activez HuggingFace comme provider par défaut

2. **Avantages** :
   - Gratuit (avec certaines limites)
   - Pas besoin de carte bancaire
   - Modèles open-source

### Option 3 : Attendre le Renouvellement du Quota

Si vous êtes sur un plan avec quota mensuel :
- Attendez le renouvellement mensuel
- Le quota se renouvelle automatiquement chaque mois

### Option 4 : Utiliser une Autre Clé API OpenAI

Si vous avez accès à un autre compte OpenAI :
1. Allez sur `/admin/ai-providers`
2. Cliquez sur "Configurer" pour OpenAI
3. Entrez la nouvelle clé API
4. Testez la connexion

## 🔍 Vérifier votre Quota Actuel

### Via l'Interface OpenAI

1. Allez sur [platform.openai.com/usage](https://platform.openai.com/usage)
2. Connectez-vous
3. Vérifiez :
   - **Usage actuel** : Combien vous avez utilisé ce mois
   - **Limite** : Votre limite mensuelle
   - **Crédit restant** : Combien il vous reste

### Via l'API (si vous avez encore un peu de quota)

```bash
curl https://api.openai.com/v1/usage \
  -H "Authorization: Bearer YOUR_API_KEY"
```

## 💡 Conseils pour Économiser le Quota

1. **Réduire la longueur des prompts** :
   - Utilisez des textes plus courts
   - Évitez les répétitions

2. **Réduire `max_tokens`** :
   - Dans `/admin/ai-providers`, configurez un `max_tokens` plus bas
   - Par défaut : 2000 tokens
   - Vous pouvez réduire à 1000 ou 1500

3. **Utiliser un modèle moins cher** :
   - `gpt-3.5-turbo` est moins cher que `gpt-4`
   - Vérifiez que vous utilisez `gpt-3.5-turbo` dans la configuration

4. **Limiter l'utilisation** :
   - N'utilisez l'analyse IA que pour les newsletters importantes
   - Éditez manuellement les newsletters simples

## 🎯 Solution Recommandée

**Pour un usage en production** :
1. Passez à un plan OpenAI payant (à partir de $5/mois)
2. Configurez des alertes de quota dans OpenAI
3. Surveillez votre usage régulièrement

**Pour le développement/test** :
1. Utilisez HuggingFace (gratuit)
2. Ou utilisez un compte OpenAI avec crédits de test

## 📞 Support

Si vous avez des questions :
- Documentation OpenAI : [platform.openai.com/docs](https://platform.openai.com/docs)
- Support HuggingFace : [huggingface.co/support](https://huggingface.co/support)

