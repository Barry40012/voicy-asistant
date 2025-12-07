# ✅ Correction - Table "audios"

## ❌ Problème

Laravel cherchait la table `audio` (singulier) alors que la table s'appelle `audios` (pluriel).

## ✅ Solution appliquée

1. **Modèle Audio** : Ajout de `protected $table = 'audios';` pour spécifier explicitement le nom de la table
2. **Vue dashboard** : Correction de la requête pour utiliser `where('status', '=', 'done')` au lieu de `where('status', 'done')`

## 🧪 Tester

Maintenant, rafraîchis la page dans ton navigateur. Ça devrait fonctionner !

Si tu as encore une erreur, vide le cache :

```bash
php artisan config:clear
php artisan cache:clear
```

---

**Le problème est corrigé. Rafraîchis la page !** 🚀

