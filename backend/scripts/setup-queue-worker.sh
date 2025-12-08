#!/bin/bash

# Script d'installation du worker de queue pour Voicy Assistant
# Usage: sudo ./scripts/setup-queue-worker.sh

set -e

echo "🚀 Configuration du worker de queue pour Voicy Assistant"
echo "=================================================="

# Vérifier que le script est exécuté en root
if [ "$EUID" -ne 0 ]; then 
    echo "❌ Erreur: Ce script doit être exécuté avec sudo"
    exit 1
fi

# Demander le chemin du projet
read -p "📁 Entrez le chemin absolu du projet backend (ex: /var/www/voicy-assistant/backend): " PROJECT_PATH

if [ ! -d "$PROJECT_PATH" ]; then
    echo "❌ Erreur: Le répertoire $PROJECT_PATH n'existe pas"
    exit 1
fi

# Demander l'utilisateur
read -p "👤 Entrez l'utilisateur qui exécute PHP (généralement www-data ou nginx): " PHP_USER

# Vérifier que l'utilisateur existe
if ! id "$PHP_USER" &>/dev/null; then
    echo "❌ Erreur: L'utilisateur $PHP_USER n'existe pas"
    exit 1
fi

# Demander le nombre de workers
read -p "⚙️  Nombre de workers en parallèle (recommandé: 2): " NUM_PROCS
NUM_PROCS=${NUM_PROCS:-2}

# Créer le fichier de configuration Supervisor
CONFIG_FILE="/etc/supervisor/conf.d/voicy-queue-worker.conf"

echo "📝 Création du fichier de configuration..."

cat > "$CONFIG_FILE" << EOF
[program:voicy-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_PATH/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=$PHP_USER
numprocs=$NUM_PROCS
redirect_stderr=true
stdout_logfile=$PROJECT_PATH/storage/logs/queue-worker.log
stopwaitsecs=3600
EOF

echo "✅ Fichier de configuration créé: $CONFIG_FILE"

# Créer le répertoire de logs s'il n'existe pas
echo "📁 Création du répertoire de logs..."
mkdir -p "$PROJECT_PATH/storage/logs"
chown -R "$PHP_USER:$PHP_USER" "$PROJECT_PATH/storage"

# Charger la configuration Supervisor
echo "🔄 Chargement de la configuration Supervisor..."
supervisorctl reread
supervisorctl update

# Démarrer le worker
echo "▶️  Démarrage du worker..."
supervisorctl start voicy-queue-worker:*

# Attendre un peu
sleep 2

# Vérifier le statut
echo ""
echo "📊 Statut du worker:"
supervisorctl status voicy-queue-worker:*

echo ""
echo "✅ Configuration terminée !"
echo ""
echo "📝 Commandes utiles:"
echo "   - Voir le statut: sudo supervisorctl status"
echo "   - Redémarrer: sudo supervisorctl restart voicy-queue-worker:*"
echo "   - Arrêter: sudo supervisorctl stop voicy-queue-worker:*"
echo "   - Voir les logs: tail -f $PROJECT_PATH/storage/logs/queue-worker.log"
echo ""

