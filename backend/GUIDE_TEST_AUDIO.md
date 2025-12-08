# 🧪 Guide Complet - Tester l'Analyse Audio

## 🎯 Objectif

Tester le système d'analyse audio **sans avoir besoin de WhatsApp Business** en développement local.

---

## 📋 Méthode 1 : Test via l'Interface Web (Recommandé)

### Étape 1 : Accéder à la Page de Test

1. **Connectez-vous** à votre compte
2. Allez dans **Dashboard > Mes Audios**
3. Cliquez sur **"Tester un Audio"** (bouton vert en haut à droite)
   - Ou accédez directement : `http://127.0.0.1:8000/dashboard/test-audio`

### Étape 2 : Uploader un Audio

1. **Choisissez un fichier audio** :
   - Formats acceptés : MP3, WAV, OGG, M4A
   - Taille max : 10 MB
   - Vous pouvez utiliser un enregistrement vocal de votre téléphone

2. **Numéro expéditeur** (optionnel) :
   - Par défaut : `+221000000000`
   - Changez-le pour simuler différents expéditeurs

3. **Cliquez sur "Analyser l'Audio"**

### Étape 3 : Voir les Résultats

1. **Redirection automatique** vers la page de détails de l'audio
2. **Attendez le traitement** (10-30 secondes selon la longueur)
3. **Rafraîchissez la page** pour voir les résultats

---

## 📋 Méthode 2 : Test avec WhatsApp Business (Production)

### Prérequis

- Application Meta créée
- WhatsApp Business Account configuré
- Webhook configuré avec ngrok (pour le local)

### Étapes

1. **Connecter WhatsApp** :
   - Dashboard > WhatsApp
   - Suivez l'assistant de connexion
   - Configurez le webhook dans Meta

2. **Envoyer un audio** :
   - Depuis votre téléphone, envoyez un message vocal au numéro WhatsApp Business connecté

3. **Voir les résultats** :
   - Dashboard > Mes Audios
   - L'audio apparaîtra automatiquement
   - Cliquez dessus pour voir l'analyse

---

## 🔄 Flux Complet du Système

### 1. Réception de l'Audio

```
Audio reçu (WhatsApp ou Upload)
    ↓
Upload vers Supabase Storage
    ↓
Création du record "audios" (status: uploaded)
    ↓
Dispatch du Job "ProcessAudioJob"
```

### 2. Traitement de l'Audio

```
ProcessAudioJob démarre
    ↓
Téléchargement audio depuis Supabase
    ↓
Transcription avec Whisper (OpenAI)
    ├─> Détection automatique de la langue
    ├─> Transcription du texte
    └─> Métadonnées (langue, confiance)
    ↓
Analyse avec GPT (OpenAI)
    ├─> Génération du résumé (3 lignes)
    ├─> Extraction des actions
    └─> Génération de la réponse suggérée
    ↓
Sauvegarde dans "audio_analyses"
    ↓
Mise à jour "audios" (status: done)
```

### 3. Affichage des Résultats

**Où voir les résultats ?**

- **Dashboard > Mes Audios** : Liste de tous les audios
- **Cliquez sur un audio** : Détails complets avec :
  - ✅ Transcription complète
  - ✅ Résumé intelligent
  - ✅ Actions identifiées
  - ✅ Réponse suggérée

---

## 📊 Ce que Vous Verrez

### Dans la Liste (Mes Audios)

- **Statut** : En attente / En cours / Traité / Erreur
- **Expéditeur** : Numéro de téléphone
- **Date** : Date de réception
- **Aperçu** : Résumé (si disponible)

### Dans les Détails

1. **Transcription** :
   - Texte complet de l'audio
   - Langue détectée automatiquement

2. **Résumé** :
   - 3 lignes maximum
   - Synthèse intelligente du contenu

3. **Actions identifiées** :
   - Liste des actions à effectuer
   - Exemple : "Rappeler le client", "Envoyer un devis", etc.

4. **Réponse suggérée** :
   - Réponse générée automatiquement
   - Adaptée à la langue détectée
   - Vous pouvez l'envoyer manuellement (bouton "Envoyer la réponse")

---

## ❓ Questions Fréquentes

### Le système répond-il automatiquement ?

**Non, pas en mode test.**

- En mode test (upload manuel) : Le système génère une réponse suggérée que vous pouvez voir et envoyer manuellement
- En production (WhatsApp) : Vous pouvez activer la réponse automatique dans les paramètres (à implémenter)

### Où sont stockés les audios ?

- **Supabase Storage** : Fichiers audio (bucket privé "audios")
- **Base de données** : Métadonnées et analyses

### Combien de temps prend le traitement ?

- **Court audio (< 30s)** : 10-15 secondes
- **Audio moyen (1-2 min)** : 20-30 secondes
- **Long audio (> 2 min)** : 30-60 secondes

### Puis-je tester avec plusieurs langues ?

**Oui !** Le système détecte automatiquement :
- Français
- Anglais
- Langues mixtes (français + anglais)

### Que faire si l'analyse échoue ?

1. **Vérifiez les logs** :
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Vérifiez la configuration IA** :
   - Admin > Providers IA
   - Vérifiez que OpenAI est actif et configuré

3. **Vérifiez la queue** :
   ```bash
   php artisan queue:work
   ```

---

## 🚀 Prochaines Étapes

1. **Tester avec différents types d'audios** :
   - Audio en français
   - Audio en anglais
   - Audio mixte (français + anglais)

2. **Vérifier la qualité** :
   - Transcription précise ?
   - Résumé pertinent ?
   - Réponse adaptée ?

3. **Configurer WhatsApp Business** :
   - Pour tester en conditions réelles
   - Voir le guide : `GUIDE_WHATSAPP_BUSINESS.md`

---

## ⚙️ Configuration Requise

### 1. Queue Worker

Le traitement audio se fait en arrière-plan. Assurez-vous que le worker tourne :

```bash
php artisan queue:work
```

### 2. Configuration IA

- **OpenAI** doit être configuré et actif
- **Clé API** valide dans Admin > Providers IA
- Voir : `GUIDE_IA_AUDIO_ANALYSIS.md`

### 3. Supabase Storage

- **Bucket "audios"** créé
- **Service Key** configurée dans `.env`
- Voir : `GUIDE_SUPABASE.md`

---

**Prêt à tester ? Allez sur Dashboard > Mes Audios > Tester un Audio !** 🚀

