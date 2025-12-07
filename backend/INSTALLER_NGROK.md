# 🔧 Installer ngrok pour Tester WhatsApp en Local

## 📥 Installation

### Windows

1. **Télécharge ngrok** : https://ngrok.com/download
2. **Extrais** le fichier `ngrok.exe` dans un dossier (ex: `C:\ngrok`)
3. **Ouvre PowerShell** en tant qu'administrateur
4. **Ajoute au PATH** (optionnel) ou utilise le chemin complet

### Utilisation

1. **Lance ton serveur Laravel** :
   ```bash
   php artisan serve
   ```

2. **Dans un autre terminal**, lance ngrok :
   ```bash
   C:\ngrok\ngrok.exe http 8000
   ```
   Ou si dans le PATH :
   ```bash
   ngrok http 8000
   ```

3. **Copie l'URL HTTPS** affichée (ex: `https://abc123.ngrok.io`)

4. **Utilise cette URL** pour configurer le webhook dans Meta :
   ```
   https://abc123.ngrok.io/api/webhooks/whatsapp
   ```

---

## ⚠️ Important

- **ngrok est gratuit** mais l'URL change à chaque redémarrage
- Pour la **production**, utilise un vrai domaine avec HTTPS
- **Garde ngrok ouvert** pendant tes tests

---

**Installe ngrok et configure le webhook !** 🚀

