# Guide : Configuration du Worker de Queue en Production

Ce guide explique comment configurer le worker de queue Laravel pour qu'il fonctionne automatiquement en production, sans intervention manuelle.

## 📋 Prérequis

- Serveur Linux (Ubuntu/Debian recommandé)
- Supervisor installé
- Accès root ou sudo

## 🚀 Installation de Supervisor

### Ubuntu/Debian

```bash
sudo apt-get update
sudo apt-get install supervisor
```

### Vérifier l'installation

```bash
sudo supervisorctl status
```

## ⚙️ Configuration du Worker

### 1. Créer le fichier de configuration

Copiez le fichier `supervisor/voicy-queue-worker.conf` vers le répertoire de configuration de Supervisor :

```bash
sudo cp backend/supervisor/voicy-queue-worker.conf /etc/supervisor/conf.d/voicy-queue-worker.conf
```

### 2. Modifier le fichier selon votre environnement

Éditez le fichier :

```bash
sudo nano /etc/supervisor/conf.d/voicy-queue-worker.conf
```

**Points importants à modifier :**

- `command=php /var/www/voicy-assistant/backend/artisan queue:work` : Remplacez `/var/www/voicy-assistant/backend` par le chemin absolu de votre projet
- `user=www-data` : Remplacez par l'utilisateur qui exécute PHP (généralement `www-data` ou `nginx`)
- `stdout_logfile` : Chemin vers le fichier de log (assurez-vous que le répertoire existe)

### 3. Exemple de configuration complète

```ini
[program:voicy-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /chemin/vers/votre/projet/backend/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/chemin/vers/votre/projet/backend/storage/logs/queue-worker.log
stopwaitsecs=3600
```

**Explications des paramètres :**

- `numprocs=2` : Nombre de workers en parallèle (ajustez selon votre serveur)
- `--sleep=3` : Temps d'attente entre chaque vérification de la queue (secondes)
- `--tries=3` : Nombre de tentatives en cas d'échec
- `--max-time=3600` : Temps maximum d'exécution d'un worker avant redémarrage (1 heure)
- `autostart=true` : Démarrer automatiquement au boot
- `autorestart=true` : Redémarrer automatiquement en cas de crash

### 4. Créer le répertoire de logs

```bash
sudo mkdir -p /chemin/vers/votre/projet/backend/storage/logs
sudo chown -R www-data:www-data /chemin/vers/votre/projet/backend/storage
```

### 5. Charger la configuration

```bash
sudo supervisorctl reread
sudo supervisorctl update
```

### 6. Démarrer le worker

```bash
sudo supervisorctl start voicy-queue-worker:*
```

### 7. Vérifier le statut

```bash
sudo supervisorctl status
```

Vous devriez voir quelque chose comme :

```
voicy-queue-worker:voicy-queue-worker_00   RUNNING   pid 12345, uptime 0:00:05
voicy-queue-worker:voicy-queue-worker_01   RUNNING   pid 12346, uptime 0:00:05
```

## 🔧 Commandes utiles

### Redémarrer le worker

```bash
sudo supervisorctl restart voicy-queue-worker:*
```

### Arrêter le worker

```bash
sudo supervisorctl stop voicy-queue-worker:*
```

### Voir les logs

```bash
tail -f /chemin/vers/votre/projet/backend/storage/logs/queue-worker.log
```

### Voir les logs de Supervisor

```bash
sudo tail -f /var/log/supervisor/supervisord.log
```

## 🔄 Après chaque déploiement

Après chaque déploiement de code, redémarrez les workers pour charger le nouveau code :

```bash
sudo supervisorctl restart voicy-queue-worker:*
```

Ou ajoutez cette commande à votre script de déploiement.

## 🐛 Dépannage

### Le worker ne démarre pas

1. Vérifiez les logs de Supervisor :
```bash
sudo tail -f /var/log/supervisor/supervisord.log
```

2. Vérifiez la syntaxe du fichier de configuration :
```bash
sudo supervisorctl reread
```

3. Vérifiez les permissions :
```bash
ls -la /chemin/vers/votre/projet/backend/storage/logs
```

### Le worker s'arrête fréquemment

1. Vérifiez les logs du worker :
```bash
tail -f /chemin/vers/votre/projet/backend/storage/logs/queue-worker.log
```

2. Augmentez `--max-time` si nécessaire
3. Vérifiez la mémoire disponible :
```bash
free -h
```

### Les emails ne sont pas envoyés

1. Vérifiez que le worker tourne :
```bash
sudo supervisorctl status
```

2. Vérifiez la configuration email dans `.env` :
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.com
MAIL_FROM_NAME="${APP_NAME}"
```

3. Testez l'envoi d'email :
```bash
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('your-email@example.com')->subject('Test'); });
```

## 📝 Alternative : Systemd (si Supervisor n'est pas disponible)

Si vous ne pouvez pas utiliser Supervisor, vous pouvez créer un service systemd :

### Créer le service

```bash
sudo nano /etc/systemd/system/voicy-queue-worker.service
```

### Contenu du fichier

```ini
[Unit]
Description=Voicy Assistant Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /chemin/vers/votre/projet/backend/artisan queue:work --sleep=3 --tries=3

[Install]
WantedBy=multi-user.target
```

### Activer et démarrer

```bash
sudo systemctl daemon-reload
sudo systemctl enable voicy-queue-worker
sudo systemctl start voicy-queue-worker
```

## ✅ Vérification finale

1. Envoyez une newsletter depuis l'admin
2. Vérifiez que les jobs sont traités :
```bash
php artisan queue:work --once
```
3. Vérifiez les logs pour confirmer l'envoi

## 🎯 Résumé

Une fois configuré, le worker de queue fonctionnera automatiquement :
- ✅ Démarre automatiquement au boot
- ✅ Redémarre automatiquement en cas de crash
- ✅ Traite les emails de newsletter en arrière-plan
- ✅ Logs disponibles pour le débogage

Plus besoin d'exécuter manuellement `php artisan queue:work` ! 🎉

