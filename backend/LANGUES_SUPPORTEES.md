# 🌍 Langues Supportées par Voicy Assistant

## ✅ Langues Actuellement Supportées

Le système **Voicy Assistant** détecte automatiquement la langue des messages vocaux et génère des réponses dans la même langue.

### 1. 🇫🇷 **Français**
- **Code** : `fr`
- **Détection** : Automatique
- **Support complet** : ✅
  - Transcription (Whisper)
  - Analyse et résumé (LLM)
  - Génération de réponses
  - Détection de confiance : ~95%

### 2. 🇬🇧 **Anglais**
- **Code** : `en`
- **Détection** : Automatique
- **Support complet** : ✅
  - Transcription (Whisper)
  - Analyse et résumé (LLM)
  - Génération de réponses
  - Détection de confiance : ~95%

### 3. 🔀 **Langues Mixtes**
- **Code** : `mixed`
- **Détection** : Automatique
- **Support** : ✅
  - Le système peut détecter et traiter les messages contenant à la fois du français et de l'anglais
  - Les réponses sont générées dans la langue dominante détectée

---

## 🔧 Configuration Technique

### Service IA (`AIService.php`)
```php
private array $supportedLanguages = [
    'fr' => 'Français',
    'en' => 'Anglais',
];
```

### Détection Automatique
- **OpenAI Whisper** : Détecte automatiquement la langue (paramètre `language: null`)
- **HuggingFace** : Détection basée sur l'analyse du texte transcrit
- **Confiance** : Score de confiance fourni pour chaque langue détectée

---

## 📝 Notes

- La détection de langue est **automatique** - aucun paramètre à configurer
- Le système peut gérer les **langues mixtes** (français + anglais dans le même message)
- Les réponses sont générées dans la **même langue** que le message original
- La **confiance** de détection est généralement très élevée (>90%) pour le français et l'anglais
