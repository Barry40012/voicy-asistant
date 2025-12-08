#!/bin/bash

# Script de déploiement automatique pour Voicy Assistant
# Ce script configure automatiquement le worker de queue après le déploiement
# Usage: sudo ./scripts/deploy-production.sh

set -e

echo "🚀 Déploiement automatique de Voicy Assistant"
echo "=============================================="
echo ""

# Vérifier que le script est exécuté en root
if [ "$EUID" -ne 0 ]; then 
    echo "❌ Erreur: Ce script doit être exécuté avec sudo"
    exit 1
fi

# Détecter le chemin du projet (depuis le répertoire backend)
PROJECT_PATH=$(pwd)
echo "📁 Chemin du projet détecté: $PROJECT_PATH"

# Détecter l'utilisateur PHP
if [ -f "/etc/nginx/nginx.conf" ] || [ -d "/etc/nginx" ]; then
    PHP_USER="www-data"
elif [ -f "/etc/apache2/apache2.conf" ] || [ -d "/etc/apache2" ]; then
    PHP_USER="www-data"
else
    PHP_USER="www-data"
fi

echo "👤 Utilisateur PHP détecté: $PHP_USER"

# Vérifier si Supervisor est installé
if ! command -v supervisorctl &> /dev/null; then
    echo "⚠️  Supervisor n'est pas installé"
    echo "📦 Installation de Supervisor..."
    apt-get update
    apt-get install -y supervisor
    echo "✅ Supervisor installé"
fi

# Vérifier si le worker est déjà configuré
if [ -f "/etc/supervisor/conf.d/voicy-queue-worker.conf" ]; then
    echo "✅ Configuration du worker trouvée"
    
    # Mettre à jour le chemin dans la configuration si nécessaire
    CURRENT_PATH=$(grep "command=php" /etc/supervisor/conf.d/voicy-queue-worker.conf | sed 's/.*php \(.*\)\/artisan.*/\1/')
    
    if [ "$CURRENT_PATH" != "$PROJECT_PATH" ]; then
        echo "🔄 Mise à jour du chemin dans la configuration..."
        sed -i "s|command=php .*/artisan|command=php $PROJECT_PATH/artisan|g" /etc/supervisor/conf.d/voicy-queue-worker.conf
        sed -i "s|stdout_logfile=.*/storage/logs|stdout_logfile=$PROJECT_PATH/storage/logs|g" /etc/supervisor/conf.d/voicy-queue-worker.conf
    fi
else
    echo "📝 Création de la configuration du worker..."
    
    # Créer le fichier de configuration
    cat > /etc/supervisor/conf.d/voicy-queue-worker.conf << EOF
[program:voicy-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_PATH/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=$PHP_USER
numprocs=2
redirect_stderr=true
stdout_logfile=$PROJECT_PATH/storage/logs/queue-worker.log
stopwaitsecs=3600
EOF
    
    echo "✅ Configuration créée"
fi

# Créer le répertoire de logs s'il n'existe pas
echo "📁 Vérification du répertoire de logs..."
mkdir -p "$PROJECT_PATH/storage/logs"
chown -R "$PHP_USER:$PHP_USER" "$PROJECT_PATH/storage"

# Charger la configuration Supervisor
echo "🔄 Chargement de la configuration Supervisor..."
supervisorctl reread
supervisorctl update

# Redémarrer le worker
echo "🔄 Redémarrage du worker..."
supervisorctl restart voicy-queue-worker:* || supervisorctl start voicy-queue-worker:*

# Attendre un peu
sleep 2

# Vérifier le statut
echo ""
echo "📊 Statut du worker:"
supervisorctl status voicy-queue-worker:* || echo "⚠️  Le worker n'est pas démarré"

echo ""
echo "✅ Déploiement terminé !"
echo ""
echo "📝 Le worker de queue est maintenant configuré et démarré automatiquement."
echo "   Il redémarrera automatiquement à chaque boot du serveur."
echo ""

