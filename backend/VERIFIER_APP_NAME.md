# ✅ Vérifier APP_NAME dans .env

## ⚙️ Configuration

Assure-toi que dans ton `.env`, tu as :

```env
APP_NAME="Voicy Assistant"
```

Si ce n'est pas le cas, ajoute ou modifie cette ligne.

## 🧪 Après modification

1. **Sauvegarde** le `.env`
2. **Vide le cache** :
```bash
php artisan config:clear
```

## ✅ Résultat

Les emails afficheront maintenant :
- **Header** : "Voicy Assistant" (au lieu de Laravel)
- **Footer** : "© 2025 Voicy Assistant. Tous droits réservés."

---

**Vérifie que APP_NAME="Voicy Assistant" est dans ton .env !** 🚀

