#!/bin/bash

set -e

echo "🚀 Configuration du projet Train Routing..."

# Créer les répertoires nécessaires
mkdir -p docker/nginx/ssl
mkdir -p backend/config/jwt

# Générer les certificats SSL
echo "📜 Génération des certificats SSL..."
if [ ! -f docker/nginx/ssl/cert.pem ]; then
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout docker/nginx/ssl/key.pem \
        -out docker/nginx/ssl/cert.pem \
        -subj "/C=CH/ST=VD/L=Montreux/O=MOB/CN=localhost"
    echo "✅ Certificats SSL générés"
else
    echo "ℹ️  Certificats SSL déjà présents"
fi

# Générer les clés JWT
echo "🔑 Génération des clés JWT..."
if [ ! -f backend/config/jwt/private.pem ]; then
    echo "Entrez un passphrase pour la clé privée JWT (ou appuyez sur Entrée pour utiliser la valeur par défaut):"
    read -s JWT_PASSPHRASE
    if [ -z "$JWT_PASSPHRASE" ]; then
        JWT_PASSPHRASE="train_secret_passphrase"
    fi
    
    openssl genpkey -out backend/config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass pass:"$JWT_PASSPHRASE"
    openssl pkey -in backend/config/jwt/private.pem -out backend/config/jwt/public.pem -pubout -passin pass:"$JWT_PASSPHRASE"
    
    echo "✅ Clés JWT générées"
    echo "⚠️  N'oubliez pas de configurer JWT_PASSPHRASE=$JWT_PASSPHRASE dans votre docker-compose.yml"
else
    echo "ℹ️  Clés JWT déjà présentes"
fi

# Copier les fichiers de données
echo "📋 Copie des fichiers de données..."
cp stations.json backend/data/stations.json 2>/dev/null || true
cp distances.json backend/data/distances.json 2>/dev/null || true
cp stations.json frontend/public/stations.json 2>/dev/null || true

echo "✅ Configuration terminée!"
echo ""
echo "Prochaines étapes:"
echo "1. docker compose up -d"
echo "2. docker compose exec backend php bin/console doctrine:database:create"
echo "3. docker compose exec backend php bin/console doctrine:schema:create"
echo ""
echo "L'application sera accessible sur https://localhost"

