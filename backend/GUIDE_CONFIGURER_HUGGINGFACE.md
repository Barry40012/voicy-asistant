# 🤗 Guide : Configurer HuggingFace comme Alternative à OpenAI

Ce guide vous explique comment configurer HuggingFace pour utiliser l'analyse IA de newsletter lorsque votre quota OpenAI est dépassé.

## 📋 Prérequis

1. Un compte HuggingFace (gratuit)
2. Accès à l'administration de votre plateforme

## 🚀 Étapes de Configuration

### Étape 1 : Créer un Compte HuggingFace

1. Allez sur [huggingface.co](https://huggingface.co)
2. Cliquez sur **"Sign Up"** (Inscription)
3. Créez un compte gratuit (email ou GitHub)
4. Confirmez votre email

### Étape 2 : Obtenir une Clé API HuggingFace

1. Connectez-vous à votre compte HuggingFace
2. Allez dans **Settings** (Paramètres) → **Access Tokens**
   - Ou directement : [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
3. Cliquez sur **"New token"** (Nouveau token)
4. Donnez un nom au token (ex: "Voicy Assistant")
5. Sélectionnez le type : **"Read"** (Lecture) est suffisant
6. Cliquez sur **"Generate token"**
7. **Copiez le token** (vous ne pourrez plus le voir après !)

### Étape 3 : Configurer HuggingFace dans l'Administration

1. **Connectez-vous à votre plateforme** en tant qu'administrateur
2. Allez sur **`/admin/ai-providers`**
3. Trouvez **"HuggingFace"** dans la liste
4. Cliquez sur **"Configurer"**

### Étape 4 : Entrer la Clé API

1. Dans le champ **"Clé API (Test)"**, collez votre token HuggingFace
2. Vérifiez que **"Environnement"** est sur **"Test"** (pour commencer)
3. Cliquez sur **"Enregistrer"**

### Étape 5 : Activer HuggingFace

1. Retournez à la liste des providers (`/admin/ai-providers`)
2. Trouvez **"HuggingFace"**
3. Cliquez sur **"Activer"** (ou basculez le statut actif)
4. **Important** : Cliquez sur **"Définir comme par défaut"** pour que HuggingFace soit utilisé automatiquement

### Étape 6 : Tester la Connexion

1. Cliquez sur **"Tester"** à côté de HuggingFace
2. Vous devriez voir : **"✅ Connexion réussie !"**

## ✅ Vérification

Pour vérifier que tout fonctionne :

1. Allez sur **`/admin/newsletter/create`**
2. Remplissez le sujet et le contenu
3. Cliquez sur **"Analyser avec l'IA"**
4. Si HuggingFace est bien configuré, l'analyse devrait fonctionner !

## 🔧 Modèles Recommandés

HuggingFace propose plusieurs modèles pour la génération de texte :

### Pour l'Amélioration de Newsletter (Recommandé)

- **Mistral-7B-Instruct** : Modèle performant et gratuit
  - URL : `https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2`
- **Llama-2-7B-Chat** : Alternative populaire
  - URL : `https://api-inference.huggingface.co/models/meta-llama/Llama-2-7b-chat-hf`

### Pour la Transcription Audio

- **Whisper Base** : Déjà configuré par défaut
  - URL : `https://api-inference.huggingface.co/models/openai/whisper-base`

## ⚙️ Configuration Avancée

Si vous voulez changer le modèle utilisé :

1. Allez sur `/admin/ai-providers`
2. Cliquez sur **"Configurer"** pour HuggingFace
3. Dans **"Configuration"**, modifiez :
   - **`llm_api_url`** : URL du modèle que vous voulez utiliser
   - **`llm_model`** : Nom du modèle
4. Enregistrez

## ⚠️ Limitations HuggingFace

1. **Temps de réponse** : Peut être plus lent qu'OpenAI (modèles gratuits)
2. **Quota gratuit** : Limite de requêtes par mois (généralement généreux)
3. **Première requête** : Le modèle peut prendre 10-30 secondes à démarrer (cold start)

## 💡 Conseils

1. **Pour le développement** : HuggingFace est parfait (gratuit)
2. **Pour la production** : Considérez un plan payant HuggingFace ou revenez à OpenAI
3. **Performance** : Les modèles plus grands sont plus lents mais meilleurs
4. **Quota** : Surveillez votre usage sur [huggingface.co/settings/billing](https://huggingface.co/settings/billing)

## 🆘 Dépannage

### Erreur "Model is currently loading"

- **Cause** : Le modèle est en train de démarrer (cold start)
- **Solution** : Attendez 30-60 secondes et réessayez

### Erreur "Invalid API key"

- **Cause** : La clé API est incorrecte
- **Solution** : Vérifiez que vous avez copié le token complet

### Erreur "Quota exceeded"

- **Cause** : Vous avez atteint la limite gratuite
- **Solution** : Attendez le renouvellement mensuel ou passez à un plan payant

### Le modèle ne répond pas en JSON

- **Cause** : Certains modèles HuggingFace ne suivent pas toujours les instructions JSON
- **Solution** : Le système essaie automatiquement de parser le texte, mais les résultats peuvent varier

## 📚 Ressources

- [Documentation HuggingFace Inference API](https://huggingface.co/docs/api-inference/index)
- [Liste des modèles disponibles](https://huggingface.co/models)
- [Guide des tokens d'accès](https://huggingface.co/docs/hub/security-tokens)

## ✅ Checklist

- [ ] Compte HuggingFace créé
- [ ] Token API généré et copié
- [ ] HuggingFace configuré dans `/admin/ai-providers`
- [ ] Clé API entrée et enregistrée
- [ ] HuggingFace activé
- [ ] HuggingFace défini comme provider par défaut
- [ ] Test de connexion réussi
- [ ] Test d'analyse de newsletter réussi

Une fois toutes ces étapes complétées, vous pouvez utiliser HuggingFace au lieu d'OpenAI ! 🎉

