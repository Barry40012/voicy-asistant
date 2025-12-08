#!/bin/bash

# Script exécuté automatiquement après chaque déploiement
# Ce script s'assure que le worker de queue est toujours actif
# À ajouter à votre script de déploiement

set -e

echo "🔄 Post-déploiement : Vérification du worker de queue"
echo "===================================================="

# Vider les caches Laravel
echo "🧹 Nettoyage des caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Vérifier si Supervisor est disponible
if command -v supervisorctl &> /dev/null; then
    # Vérifier si le worker est configuré
    if supervisorctl status voicy-queue-worker:* &> /dev/null 2>&1; then
        echo "✅ Worker trouvé, redémarrage..."
        supervisorctl restart voicy-queue-worker:* || supervisorctl start voicy-queue-worker:*
        sleep 2
        echo "📊 Statut du worker:"
        supervisorctl status voicy-queue-worker:* || true
        echo "✅ Worker redémarré"
    else
        echo "⚠️  Worker non configuré"
        echo "💡 Pour configurer automatiquement, exécutez: sudo ./scripts/deploy-production.sh"
    fi
else
    echo "⚠️  Supervisor non installé"
    echo "💡 Le worker ne peut pas être géré automatiquement"
    echo "💡 Pour tester en local, exécutez: php artisan queue:work"
fi

# Vérifier via la commande Artisan (si disponible)
if php artisan list | grep -q "queue:check-worker"; then
    echo "🔍 Vérification via Artisan..."
    php artisan queue:check-worker || true
fi

echo ""
echo "✅ Post-déploiement terminé"
