# 📝 Configuration HuggingFace - Guide Étape par Étape

## ✅ Étape 1 : Vérifier le Token Actuel

Dans l'interface `/admin/ai-providers` → Configurer HuggingFace :

1. Le champ "Token API (Test)" affiche des points (••••••••••)
2. Cela signifie qu'un token est déjà configuré
3. **Mais il peut être incomplet ou invalide**

## 🔧 Étape 2 : Remplacer le Token

### Option A : Si vous avez encore accès au token original

1. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Trouvez votre token "Voicy Assistant"
3. **Cliquez sur l'icône de copie** (pas sur le token)
4. Dans `/admin/ai-providers` → Configurer HuggingFace :
   - **Effacez complètement** le champ "Token API (Test)"
   - **Collez le nouveau token** (sans espaces)
   - Vérifiez qu'il commence par `hf_` et fait 40-50 caractères
5. Cliquez sur **"Enregistrer"**

### Option B : Créer un Nouveau Token

1. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Cliquez sur **"New token"**
3. Nom : **"Voicy Assistant"** (ou un autre nom)
4. Type : **"Read"** (minimum) ou **"Write"**
5. Cliquez sur **"Generate token"**
6. **Copiez immédiatement** le token (vous ne pourrez plus le voir !)
7. Dans `/admin/ai-providers` → Configurer HuggingFace :
   - **Effacez** l'ancien token
   - **Collez le nouveau token**
   - Vérifiez qu'il n'y a **pas d'espaces** avant ou après
8. Cliquez sur **"Enregistrer"**

## ⚙️ Étape 3 : Vérifier la Configuration LLM

Pour que l'amélioration de newsletter fonctionne, vous devez aussi configurer l'URL LLM :

1. Dans `/admin/ai-providers` → Configurer HuggingFace
2. Section **"Configuration"**
3. Vérifiez que **"URL API LLM"** est configurée :
   - Valeur recommandée : `https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2`
   - Ou : `https://api-inference.huggingface.co/models/meta-llama/Llama-2-7b-chat-hf`
4. **"Modèle LLM"** : `mistralai/Mistral-7B-Instruct-v0.2` (ou le modèle choisi)
5. Cliquez sur **"Enregistrer"**

## ✅ Étape 4 : Activer HuggingFace

1. Retournez à la liste des providers (`/admin/ai-providers`)
2. Trouvez **"HuggingFace"**
3. **Activez** HuggingFace (basculez le statut actif)
4. **Important** : Cliquez sur **"Définir comme par défaut"**

## 🧪 Étape 5 : Tester

1. Cliquez sur **"Tester"** à côté de HuggingFace
2. Vous devriez voir : **"✅ Connexion réussie !"**

Si vous voyez encore une erreur :
- Vérifiez les logs : `tail -f storage/logs/laravel.log`
- Consultez `TROUBLESHOOTING_HUGGINGFACE.md`

## 📋 Checklist Complète

- [ ] Token copié complètement (40-50 caractères)
- [ ] Token commence par `hf_`
- [ ] Pas d'espaces avant/après le token
- [ ] Token a la permission "Read" minimum
- [ ] URL LLM configurée (pour newsletter)
- [ ] Modèle LLM configuré
- [ ] HuggingFace activé
- [ ] HuggingFace défini comme provider par défaut
- [ ] Test de connexion réussi

## 🎯 Configuration Recommandée

### Pour l'Amélioration de Newsletter

**URL API LLM** :
```
https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2
```

**Modèle LLM** :
```
mistralai/Mistral-7B-Instruct-v0.2
```

### Alternatives (si Mistral ne fonctionne pas)

**Option 1 - Llama 2** :
- URL : `https://api-inference.huggingface.co/models/meta-llama/Llama-2-7b-chat-hf`
- Modèle : `meta-llama/Llama-2-7b-chat-hf`

**Option 2 - Falcon** :
- URL : `https://api-inference.huggingface.co/models/tiiuae/falcon-7b-instruct`
- Modèle : `tiiuae/falcon-7b-instruct`

## ⚠️ Important

1. **Le token doit être copié complètement** - pas seulement les premiers caractères
2. **Pas d'espaces** - vérifiez avant et après le token
3. **URL LLM requise** - sans ça, l'amélioration de newsletter ne fonctionnera pas
4. **Première requête lente** - HuggingFace peut prendre 10-30 secondes pour démarrer le modèle (cold start)

## 🆘 Si ça ne fonctionne toujours pas

1. Vérifiez les logs : `tail -f storage/logs/laravel.log`
2. Testez le token manuellement :
   ```bash
   curl https://huggingface.co/api/whoami -H "Authorization: Bearer VOTRE_TOKEN"
   ```
3. Consultez `TROUBLESHOOTING_HUGGINGFACE.md` pour plus de détails

