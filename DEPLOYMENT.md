# Instructions de déploiement

## Prérequis

- Docker Engine 25 ou supérieur
- Docker Compose 2.0 ou supérieur

## Déploiement local

### 1. Générer les certificats SSL (pour HTTPS)

```bash
mkdir -p docker/nginx/ssl
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/nginx/ssl/key.pem \
  -out docker/nginx/ssl/cert.pem \
  -subj "/C=CH/ST=VD/L=Montreux/O=MOB/CN=localhost"
```

### 2. Générer les clés JWT

```bash
cd backend
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

Lors de la génération de la clé privée, vous devrez entrer un passphrase. Notez-le et utilisez-le dans la variable d'environnement `JWT_PASSPHRASE`.

### 3. Lancer l'application

```bash
docker compose up -d
```

### 4. Initialiser la base de données

```bash
docker compose exec backend php bin/console doctrine:database:create
docker compose exec backend php bin/console doctrine:schema:create
```

### 5. Accéder à l'application

- Frontend: https://localhost
- API: https://localhost/api/v1

## Variables d'environnement

Les variables d'environnement peuvent être configurées dans un fichier `.env` à la racine du projet ou directement dans `docker-compose.yml`.

### Backend

- `APP_ENV`: Environnement (dev, prod, test)
- `APP_SECRET`: Clé secrète Symfony
- `DATABASE_URL`: URL de connexion PostgreSQL
- `JWT_SECRET_KEY`: Chemin vers la clé privée JWT
- `JWT_PUBLIC_KEY`: Chemin vers la clé publique JWT
- `JWT_PASSPHRASE`: Passphrase pour la clé privée JWT

### Frontend

- `VITE_API_URL`: URL de l'API backend

## Tests

### Backend

```bash
docker compose exec backend vendor/bin/phpunit
docker compose exec backend vendor/bin/phpunit --coverage-html var/coverage
```

### Frontend

```bash
docker compose exec frontend npm test
docker compose exec frontend npm run test:coverage
```

## Arrêter l'application

```bash
docker compose down
```

Pour supprimer également les volumes (base de données):

```bash
docker compose down -v
```

## Dépannage

### Vérifier les logs

```bash
docker compose logs -f backend
docker compose logs -f frontend
docker compose logs -f nginx
```

### Réinitialiser la base de données

```bash
docker compose exec backend php bin/console doctrine:database:drop --force
docker compose exec backend php bin/console doctrine:database:create
docker compose exec backend php bin/console doctrine:schema:create
```

