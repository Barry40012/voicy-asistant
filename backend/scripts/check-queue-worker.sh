#!/bin/bash

# Script de vérification du worker de queue
# Usage: ./scripts/check-queue-worker.sh

echo "🔍 Vérification du worker de queue"
echo "===================================="
echo ""

# Vérifier si Supervisor est installé
if ! command -v supervisorctl &> /dev/null; then
    echo "❌ Supervisor n'est pas installé"
    echo "   Installez-le avec: sudo apt-get install supervisor"
    exit 1
fi

echo "✅ Supervisor est installé"
echo ""

# Vérifier le statut du worker
echo "📊 Statut du worker:"
if supervisorctl status voicy-queue-worker:* &> /dev/null; then
    supervisorctl status voicy-queue-worker:*
    echo ""
    
    # Compter les workers en cours d'exécution
    RUNNING=$(supervisorctl status voicy-queue-worker:* 2>/dev/null | grep -c "RUNNING" || echo "0")
    
    if [ "$RUNNING" -gt 0 ]; then
        echo "✅ $RUNNING worker(s) en cours d'exécution"
    else
        echo "⚠️  Aucun worker en cours d'exécution"
    fi
else
    echo "❌ Le worker n'est pas configuré"
    echo "   Configurez-le avec: sudo ./scripts/setup-queue-worker.sh"
fi

echo ""

# Vérifier les logs récents
if [ -f "storage/logs/queue-worker.log" ]; then
    echo "📝 Dernières lignes du log (10 dernières):"
    tail -n 10 storage/logs/queue-worker.log
    echo ""
fi

# Vérifier la queue Laravel
if command -v php &> /dev/null; then
    echo "📦 Vérification de la queue Laravel:"
    php artisan queue:work --once --timeout=1 2>&1 | head -n 5 || echo "   Aucun job en attente"
    echo ""
fi

echo "✅ Vérification terminée"

