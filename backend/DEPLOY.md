# 🚀 Guide de Déploiement Automatique

Ce guide explique comment déployer Voicy Assistant en production avec configuration automatique du worker de queue.

## 📋 Avant le déploiement

### 1. Préparer le serveur

```bash
# Installer Supervisor
sudo apt-get update
sudo apt-get install -y supervisor

# Vérifier l'installation
sudo supervisorctl status
```

### 2. Configurer les permissions

```bash
# Donner les permissions d'exécution aux scripts
chmod +x scripts/*.sh
```

## 🎯 Déploiement Automatique

### Option 1 : Script de déploiement complet (Recommandé)

Après avoir déployé votre code sur le serveur :

```bash
cd /chemin/vers/votre/projet/backend
sudo ./scripts/deploy-production.sh
```

Ce script :
- ✅ Détecte automatiquement le chemin du projet
- ✅ Détecte l'utilisateur PHP
- ✅ Configure Supervisor automatiquement
- ✅ Démarre le worker
- ✅ Configure le redémarrage automatique au boot

### Option 2 : Intégration dans votre script de déploiement

Ajoutez cette ligne à la fin de votre script de déploiement existant :

```bash
# À la fin de votre script de déploiement
sudo ./scripts/deploy-production.sh
```

### Option 3 : GitHub Actions / CI/CD

Si vous utilisez GitHub Actions, ajoutez cette étape :

```yaml
- name: Setup Queue Worker
  run: |
    ssh user@your-server "cd /path/to/project/backend && sudo ./scripts/deploy-production.sh"
```

## ✅ Vérification après déploiement

### Vérifier que le worker est actif

```bash
sudo supervisorctl status
```

Vous devriez voir :
```
voicy-queue-worker:voicy-queue-worker_00   RUNNING   pid 12345, uptime 0:05:00
voicy-queue-worker:voicy-queue-worker_01   RUNNING   pid 12346, uptime 0:05:00
```

### Tester l'envoi d'une newsletter

1. Allez sur `/admin/newsletter/create`
2. Créez une newsletter de test
3. Envoyez-la
4. Vérifiez les logs : `tail -f storage/logs/queue-worker.log`

## 🔄 Après chaque déploiement

Le script `post-deploy.sh` peut être ajouté à votre processus de déploiement :

```bash
# Dans votre script de déploiement
./scripts/post-deploy.sh
```

Ce script :
- ✅ Redémarre le worker automatiquement
- ✅ Vide les caches Laravel
- ✅ Vérifie que tout est à jour

## 🛡️ Vérification automatique

Le système vérifie automatiquement le worker toutes les 5 minutes en production via une tâche planifiée.

Pour vérifier manuellement :

```bash
php artisan queue:check-worker
```

## 📝 Checklist de déploiement

- [ ] Code déployé sur le serveur
- [ ] `.env` configuré avec les bonnes valeurs
- [ ] Migrations exécutées : `php artisan migrate`
- [ ] Script `deploy-production.sh` exécuté
- [ ] Worker vérifié : `sudo supervisorctl status`
- [ ] Test d'envoi de newsletter réussi
- [ ] Logs vérifiés : `tail -f storage/logs/queue-worker.log`

## 🎯 Résumé

**Une fois le script `deploy-production.sh` exécuté :**

✅ Le worker démarre automatiquement  
✅ Le worker redémarre automatiquement au boot  
✅ Le worker redémarre automatiquement après chaque déploiement (via `post-deploy.sh`)  
✅ Vérification automatique toutes les 5 minutes  

**Plus besoin de se souvenir des commandes !** 🎉

## 🆘 En cas de problème

### Le worker ne démarre pas

```bash
# Vérifier les logs de Supervisor
sudo tail -f /var/log/supervisor/supervisord.log

# Vérifier la configuration
sudo supervisorctl reread
sudo supervisorctl update

# Redémarrer manuellement
sudo supervisorctl restart voicy-queue-worker:*
```

### Le worker s'arrête fréquemment

```bash
# Vérifier les logs du worker
tail -f storage/logs/queue-worker.log

# Vérifier la mémoire
free -h
```

### Besoin d'aide

Consultez :
- `GUIDE_QUEUE_WORKER_PRODUCTION.md` : Guide détaillé
- `README_NEWSLETTER.md` : Documentation du système de newsletter

