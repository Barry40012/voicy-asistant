# 🎙️ Guide de Configuration - Analyse Audio avec IA

## 📋 Vue d'ensemble

Ce guide explique comment configurer le système d'analyse audio de Voicy Assistant, qui permet de :
- ✅ **Transcrire** les messages vocaux WhatsApp automatiquement
- ✅ **Détecter la langue** (français, anglais, langues mixtes)
- ✅ **Analyser** le contenu (résumé, actions, réponse)
- ✅ **Gagner du temps** en ne plus avoir à écouter les audios

---

## 🎯 Fonctionnalités

### 1. Transcription Automatique

Le système utilise **Whisper** (OpenAI ou HuggingFace) pour transcrire les audios avec une précision de **89-99%**.

**Langues supportées actuellement** :
- 🇫🇷 **Français** (priorité)
- 🇬🇧 **Anglais** (priorité)
- 🔄 **Langues mixtes** (français + anglais dans le même audio)

**Langues locales** (prévues pour futures mises à jour) :
- Swahili
- Wolof
- Fulfulde
- Dyula
- Et autres langues guinéennes

### 2. Détection Automatique de Langue

Le système détecte automatiquement la langue parlée dans l'audio :
- **Détection primaire** : Whisper détecte la langue principale
- **Détection secondaire** : Analyse du transcript pour détecter les langues mixtes
- **Confiance** : Score de confiance (0.00 à 1.00) pour chaque langue détectée

### 3. Analyse Intelligente

Après transcription, le système :
1. **Résume** le message en 3 lignes maximum
2. **Identifie les actions** à entreprendre
3. **Génère une réponse** professionnelle adaptée à la langue détectée

---

## 🔧 Configuration

### Étape 1 : Choisir un Provider IA

Vous avez deux options :

#### Option A : OpenAI (Recommandé - Meilleure précision)

**Avantages** :
- ✅ Précision de transcription : **95-99%**
- ✅ Détection de langue automatique très précise
- ✅ Support multilingue natif
- ✅ Gestion des langues mixtes

**Configuration** :
1. Créez un compte sur https://platform.openai.com
2. Générez une clé API dans **API Keys**
3. Ajoutez des crédits à votre compte

#### Option B : HuggingFace (Alternative gratuite)

**Avantages** :
- ✅ Gratuit (avec limitations)
- ✅ Open source
- ✅ Bonne précision (85-95%)

**Configuration** :
1. Créez un compte sur https://huggingface.co
2. Générez un token dans **Settings** > **Access Tokens**
3. Utilisez un modèle Whisper disponible

### Étape 2 : Configurer le fichier `.env`

Ouvrez `backend/.env` et ajoutez :

```env
# Provider IA (openai ou huggingface)
AI_PROVIDER=openai

# Clé API
AI_API_KEY=sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# URLs des APIs (par défaut OpenAI)
AI_WHISPER_API_URL=https://api.openai.com/v1/audio/transcriptions
AI_LLM_API_URL=https://api.openai.com/v1/chat/completions

# Modèle LLM pour l'analyse
AI_LLM_MODEL=gpt-3.5-turbo

# Précision de transcription (0.0 = très précis, 1.0 = plus créatif)
AI_TRANSCRIPTION_TEMPERATURE=0.0

# Format de réponse Whisper (verbose_json pour obtenir les métadonnées)
AI_WHISPER_RESPONSE_FORMAT=verbose_json
```

### Étape 3 : Tester la Configuration

1. **Envoyez un audio** sur WhatsApp connecté
2. **Vérifiez les logs** dans Administration > Logs
3. **Consultez l'analyse** dans Dashboard > Mes Audios

---

## 🌍 Support Multilingue

### Langues Actuellement Supportées

| Langue | Code | Statut | Précision |
|--------|------|--------|-----------|
| Français | `fr` | ✅ Actif | 95-99% |
| Anglais | `en` | ✅ Actif | 95-99% |
| Mixte (fr+en) | `mixed` | ✅ Actif | 90-95% |

### Langues Locales (Futures Mises à Jour)

Pour ajouter le support d'une langue locale :

1. **Modifiez** `backend/config/ai.php` :
```php
'local_languages' => [
    'sw' => 'Swahili',
    'wo' => 'Wolof',
    // Ajoutez votre langue ici
],
```

2. **Modifiez** `backend/app/Services/AIService.php` :
```php
private array $supportedLanguages = [
    'fr' => 'Français',
    'en' => 'Anglais',
    'sw' => 'Swahili', // Ajoutez ici
];
```

3. **Testez** avec des audios dans cette langue

---

## 🔍 Comment ça Fonctionne

### Flux de Traitement

```
1. Audio reçu sur WhatsApp
   ↓
2. Téléchargement et stockage (Supabase)
   ↓
3. Job de traitement (Queue)
   ↓
4. Transcription avec Whisper
   ├─ Détection automatique de langue
   ├─ Transcription du texte
   └─ Score de confiance
   ↓
5. Détection de langues mixtes (si nécessaire)
   ↓
6. Analyse avec LLM
   ├─ Résumé
   ├─ Actions identifiées
   └─ Réponse générée (dans la langue détectée)
   ↓
7. Sauvegarde dans la base de données
   ↓
8. Affichage dans le dashboard
```

### Détection de Langue

1. **Whisper détecte** automatiquement la langue principale
2. **Analyse du transcript** pour détecter les mots-clés français/anglais
3. **Si plusieurs langues détectées** → marqué comme `mixed`
4. **Score de confiance** calculé pour chaque langue

### Gestion des Langues Mixtes

Quand quelqu'un mélange français et anglais dans le même audio :

- **Détection** : Le système détecte les deux langues
- **Code langue** : `mixed`
- **Détails** : `[{'lang': 'fr', 'confidence': 0.7}, {'lang': 'en', 'confidence': 0.3}]`
- **Réponse** : Générée en français par défaut (configurable)

---

## 📊 Précision et Performance

### Précision de Transcription

| Condition | Précision |
|-----------|-----------|
| Audio clair, français | 95-99% |
| Audio clair, anglais | 95-99% |
| Audio avec bruit | 85-95% |
| Langues mixtes | 90-95% |
| Langues locales (futur) | À tester |

### Temps de Traitement

- **Transcription** : 5-15 secondes (selon la durée de l'audio)
- **Analyse** : 3-8 secondes
- **Total** : 8-23 secondes pour un audio de 1 minute

---

## 🛠️ Dépannage

### Problème : Transcription échoue

**Solutions** :
1. Vérifiez que `AI_API_KEY` est correcte
2. Vérifiez que vous avez des crédits (OpenAI) ou un token valide (HuggingFace)
3. Vérifiez les logs dans Administration > Logs
4. Vérifiez que le format audio est supporté (OGG, MP3, WAV)

### Problème : Langue mal détectée

**Solutions** :
1. Whisper détecte automatiquement, mais peut se tromper sur des audios très courts
2. Pour forcer une langue, modifiez `AIService.php` (non recommandé)
3. Vérifiez le score de confiance dans les analyses

### Problème : Analyse de mauvaise qualité

**Solutions** :
1. Utilisez un modèle plus puissant : `gpt-4` au lieu de `gpt-3.5-turbo`
2. Ajustez la température : `AI_TRANSCRIPTION_TEMPERATURE=0.0` (plus précis)
3. Vérifiez que le transcript est correct avant l'analyse

---

## 💡 Recommandations

### Pour une Précision Maximale

1. **Utilisez OpenAI Whisper** (meilleure précision)
2. **Température à 0.0** (plus précis, moins créatif)
3. **Modèle GPT-4** pour l'analyse (si budget permet)
4. **Audios de bonne qualité** (évitez les bruits de fond)

### Pour Réduire les Coûts

1. **Utilisez HuggingFace** (gratuit avec limitations)
2. **Modèle GPT-3.5-turbo** pour l'analyse (moins cher)
3. **Limitez la durée** des audios traités (dans les plans)

---

## 🔮 Évolutions Futures

### Langues Locales

Le système est conçu pour être extensible. Pour ajouter une langue locale :

1. **Formation** : Entraîner un modèle Whisper sur des données locales (optionnel)
2. **Configuration** : Ajouter la langue dans `config/ai.php`
3. **Test** : Tester avec des audios réels
4. **Déploiement** : Activer progressivement

### Améliorations Prévues

- 🔄 **Détection de sentiment** (positif, négatif, neutre)
- 🔄 **Extraction d'entités** (noms, dates, montants)
- 🔄 **Classification automatique** (urgent, normal, spam)
- 🔄 **Réponses automatiques** selon le contexte

---

## 📝 Exemples

### Exemple 1 : Audio en Français

**Audio** : "Bonjour, j'aimerais savoir si vous avez des disponibilités pour demain après-midi."

**Résultat** :
- **Langue détectée** : `fr` (confiance: 0.98)
- **Transcript** : "Bonjour, j'aimerais savoir si vous avez des disponibilités pour demain après-midi."
- **Résumé** : "Le client demande des disponibilités pour demain après-midi."
- **Actions** : `["Vérifier le calendrier", "Répondre avec les créneaux disponibles"]`
- **Réponse** : "Bonjour, je vais vérifier nos disponibilités pour demain après-midi et vous recontacter rapidement."

### Exemple 2 : Audio Mixte (Français + Anglais)

**Audio** : "Hello, je voulais te dire que le meeting est reporté à demain. Merci!"

**Résultat** :
- **Langue détectée** : `mixed`
- **Langues détectées** : `[{'lang': 'en', 'confidence': 0.4}, {'lang': 'fr', 'confidence': 0.6}]`
- **Transcript** : "Hello, je voulais te dire que le meeting est reporté à demain. Merci!"
- **Résumé** : "Le meeting est reporté à demain."
- **Réponse** : "D'accord, j'ai noté que le meeting est reporté à demain. Merci pour l'information."

---

## 🔗 Liens Utiles

- **OpenAI Whisper** : https://platform.openai.com/docs/guides/speech-to-text
- **HuggingFace Whisper** : https://huggingface.co/docs/transformers/model_doc/whisper
- **Documentation OpenAI** : https://platform.openai.com/docs
- **Documentation HuggingFace** : https://huggingface.co/docs

---

**Dernière mise à jour** : Décembre 2025

