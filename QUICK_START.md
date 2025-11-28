# 🚀 Démarrage rapide

## Installation et lancement en 3 étapes

### 1. Configuration initiale

```bash
./scripts/setup.sh
```

Génère automatiquement:
- Certificats SSL (HTTPS)
- Clés JWT (authentification)
- Copie les fichiers de données

### 2. Lancer l'application

```bash
docker compose up -d
```

### 3. Initialiser la base de données

```bash
docker compose exec backend php bin/console doctrine:database:create
docker compose exec backend php bin/console doctrine:schema:create
```

## Accès à l'application

- **Frontend**: https://localhost
- **API**: https://localhost/api/v1

⚠️ Acceptez le certificat auto-signé dans votre navigateur.

## Tests

```bash
# Backend
docker compose exec backend vendor/bin/phpunit

# Frontend  
docker compose exec frontend npm test
```

## Documentation complète

- [README_PROJET.md](README_PROJET.md) - Vue d'ensemble du projet
- [DEPLOYMENT.md](DEPLOYMENT.md) - Instructions détaillées
- [ARCHITECTURE.md](ARCHITECTURE.md) - Architecture technique

## Fonctionnalités

✅ Calcul de trajet entre deux stations (algorithme Dijkstra)  
✅ Statistiques par code analytique  
✅ Interface utilisateur moderne (Vue.js + Vuetify)  
✅ API REST conforme OpenAPI  
✅ Tests unitaires et d'intégration  
✅ CI/CD complet (GitHub Actions)  
✅ Sécurité HTTPS + JWT  

## Réinitialisation complète

Pour tout arrêter et réinstaller depuis zéro :

### Méthode rapide (script)

```bash
./scripts/reset.sh
```

### Méthode manuelle

```bash
# 1. Arrêter et supprimer tous les containers et volumes
docker compose down -v

# 2. (Optionnel) Supprimer les images également
docker compose down --rmi all

# 3. Reconstruire les images
docker compose build --no-cache

# 4. Relancer les services
docker compose up -d

# 5. Réinitialiser la base de données
docker compose exec backend php bin/console doctrine:database:create
docker compose exec backend php bin/console doctrine:schema:create
```

### Commandes utiles

```bash
# Voir l'état des containers
docker compose ps

# Voir les logs
docker compose logs -f

# Arrêter sans supprimer
docker compose stop

# Redémarrer
docker compose restart
```

## Support

Pour toute question, consultez la documentation ou les commentaires dans le code.

