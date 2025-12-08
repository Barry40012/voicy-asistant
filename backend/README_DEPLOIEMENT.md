# 📦 Guide Complet de Déploiement

## 🎯 Vue d'ensemble

Ce guide vous explique comment déployer Voicy Assistant en production avec **configuration automatique** du worker de queue. Plus besoin de se souvenir des commandes !

## 🚀 Déploiement en 3 étapes

### Étape 1 : Déployer le code

Déployez votre code sur le serveur (Git, FTP, rsync, etc.)

### Étape 2 : Exécuter le script de déploiement

```bash
cd /chemin/vers/votre/projet/backend
sudo ./scripts/deploy-production.sh
```

**C'est tout !** Le script configure automatiquement :
- ✅ Supervisor
- ✅ Worker de queue
- ✅ Redémarrage automatique
- ✅ Logs

### Étape 3 : Vérifier

```bash
sudo supervisorctl status
```

Vous devriez voir le worker actif.

## 📝 Scripts disponibles

### `scripts/deploy-production.sh`
**Script principal de déploiement**
- Configure automatiquement le worker
- Détecte le chemin et l'utilisateur
- Démarre le worker automatiquement

### `scripts/post-deploy.sh`
**Script post-déploiement**
- Redémarre le worker
- Vide les caches
- Vérifie que tout fonctionne

### `scripts/check-queue-worker.sh`
**Script de vérification**
- Vérifie le statut du worker
- Affiche les logs récents
- Teste la queue

## 🔄 Intégration dans votre workflow

### Avec Git

Ajoutez à votre script de déploiement :

```bash
#!/bin/bash
# Votre script de déploiement Git

git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configuration automatique du worker
sudo ./scripts/deploy-production.sh
```

### Avec CI/CD (GitHub Actions, GitLab CI, etc.)

Ajoutez cette étape :

```yaml
- name: Setup Queue Worker
  run: |
    ssh user@server "cd /path/to/project/backend && sudo ./scripts/deploy-production.sh"
```

## ✅ Vérification automatique

Le système vérifie automatiquement le worker toutes les 5 minutes en production.

Pour vérifier manuellement :

```bash
php artisan queue:check-worker
```

## 🧪 Test en local

Avant de déployer, testez en local :

1. Configurez l'email dans `.env` (voir `GUIDE_TEST_NEWSLETTER_LOCAL.md`)
2. Démarrez le worker : `php artisan queue:work`
3. Testez l'envoi d'une newsletter

## 📚 Documentation complémentaire

- **`DEPLOY.md`** : Guide de déploiement détaillé
- **`GUIDE_QUEUE_WORKER_PRODUCTION.md`** : Configuration manuelle du worker
- **`GUIDE_TEST_NEWSLETTER_LOCAL.md`** : Tester en local
- **`README_NEWSLETTER.md`** : Documentation du système de newsletter

## 🎉 Résultat

Une fois configuré, **tout est automatique** :

✅ Worker démarre au boot  
✅ Worker redémarre après chaque déploiement  
✅ Vérification automatique toutes les 5 minutes  
✅ Plus besoin de commandes manuelles  

**Profitez de votre déploiement sans stress !** 🚀

