# 🔧 Correction Migration - Table audios

## ❌ Problème

La migration `create_audio_analyses_table` essaie de référencer une table `audio` (singulier) alors que la table s'appelle `audios` (pluriel).

## ✅ Solution appliquée

J'ai corrigé la migration pour spécifier explicitement la table `audios` :

```php
$table->foreignId('audio_id')->constrained('audios')->onDelete('cascade');
```

## 🧪 Réessayer les migrations

### Étape 1 : Rollback des migrations partiellement exécutées

```bash
cd E:\Project_Voicy_Assistant\backend
php artisan migrate:rollback
```

### Étape 2 : Réexécuter les migrations

```bash
php artisan migrate
```

**Résultat attendu** :
- ✅ Toutes les migrations s'exécutent sans erreur
- ✅ Toutes les tables sont créées dans Supabase

### Étape 3 : Créer les plans (Seeder)

```bash
php artisan db:seed --class=PlanSeeder
```

---

**Exécute `php artisan migrate:rollback` puis `php artisan migrate` !** 🚀

