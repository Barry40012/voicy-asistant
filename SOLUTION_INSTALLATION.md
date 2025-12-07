# 🔧 Solution : Installation Laravel avec fichiers existants

Le dossier `backend` contient déjà les fichiers que j'ai créés (migrations, modèles, etc.).
Il faut créer Laravel dans un dossier temporaire, puis fusionner.

## 📋 Solution 1 : Créer Laravel ailleurs puis fusionner (RECOMMANDÉ)

### Étape 1 : Créer Laravel dans un dossier temporaire

```bash
cd E:\Project_Voicy_Assistant
composer create-project laravel/laravel:^10.0 backend_temp --prefer-dist
```

### Étape 2 : Déplacer les fichiers Laravel dans backend

```bash
# Copier tous les fichiers Laravel dans backend
xcopy /E /I /Y backend_temp\* backend\
```

### Étape 3 : Supprimer le dossier temporaire

```bash
rmdir /S /Q backend_temp
```

### Étape 4 : Vérifier que tout est OK

```bash
cd E:\Project_Voicy_Assistant\backend
php artisan --version
```

---

## 📋 Solution 2 : Vider backend temporairement (si Solution 1 ne marche pas)

### ⚠️ ATTENTION : Cette solution supprime temporairement les fichiers créés

### Étape 1 : Sauvegarder les fichiers créés

```bash
cd E:\Project_Voicy_Assistant
mkdir backend_backup
xcopy /E /I /Y backend\* backend_backup\
```

### Étape 2 : Vider backend (garder seulement le dossier)

```bash
cd E:\Project_Voicy_Assistant\backend
del /Q /S *.*
for /d %d in (*) do rmdir /S /Q "%d"
```

### Étape 3 : Créer Laravel

```bash
cd E:\Project_Voicy_Assistant\backend
composer create-project laravel/laravel:^10.0 . --prefer-dist
```

### Étape 4 : Restaurer les fichiers sauvegardés

```bash
cd E:\Project_Voicy_Assistant
xcopy /E /I /Y backend_backup\* backend\
```

### Étape 5 : Nettoyer

```bash
rmdir /S /Q backend_backup
```

---

## ✅ Après l'installation

Une fois Laravel installé, continue avec les autres commandes :

```bash
cd E:\Project_Voicy_Assistant\backend
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require supabase/supabase-php guzzlehttp/guzzle predis/predis
npm install
npm run build
```

Puis configure `.env` et exécute les migrations.

