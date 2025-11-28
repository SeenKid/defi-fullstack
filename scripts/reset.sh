#!/bin/bash

set -e

echo "🛑 Arrêt et suppression de tous les containers..."
docker compose down -v

echo "🗑️  Suppression des images (optionnel)..."
read -p "Voulez-vous supprimer les images Docker également ? (o/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Oo]$ ]]; then
    docker compose down --rmi all
    echo "✅ Images supprimées"
else
    echo "ℹ️  Images conservées"
fi

echo ""
echo "🔨 Reconstruction des images..."
docker compose build --no-cache

echo ""
echo "🚀 Démarrage des services..."
docker compose up -d

echo ""
echo "✅ Réinstallation terminée !"
echo ""
echo "Prochaines étapes:"
echo "1. docker compose exec backend php bin/console doctrine:database:create"
echo "2. docker compose exec backend php bin/console doctrine:schema:create"
echo ""
echo "Vérifier l'état: docker compose ps"

