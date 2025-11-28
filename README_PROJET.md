# 🚆 Train Routing & Analytics - Solution complète

## Résumé

Cette solution implémente un système complet de routage de trains avec calcul de distances et statistiques analytiques, conforme aux spécifications du défi.

## Fonctionnalités implémentées

### ✅ Objectifs principaux

- [x] Backend PHP 8.4 avec Symfony 7.1
- [x] API REST conforme à la spécification OpenAPI
- [x] Frontend TypeScript avec Vue.js 3 + Vuetify 3
- [x] Tests unitaires et d'intégration (PHPUnit, Vitest)
- [x] Couverture de code configurée
- [x] Docker Compose pour le déploiement
- [x] Pipeline CI/CD complet (GitHub Actions)
- [x] Sécurité HTTPS et JWT configurée
- [x] Versioning Git structuré

### 🎁 Points Bonus

- [x] Algorithme de routage (Dijkstra) pour calculer la distance optimale
- [x] Endpoint de statistiques agrégées par code analytique
- [x] Visualisation des statistiques avec graphiques (Chart.js)

## Structure du projet

```
defi-fullstack/
├── backend/              # API Symfony
│   ├── src/
│   │   ├── Controller/   # Contrôleurs API
│   │   ├── Service/      # Services métier (routage, graph)
│   │   ├── Entity/       # Entités Doctrine
│   │   └── Repository/    # Repositories
│   ├── tests/            # Tests PHPUnit
│   └── migrations/       # Migrations DB
├── frontend/             # Application Vue.js
│   ├── src/
│   │   ├── views/        # Pages (RouteCalculator, Statistics)
│   │   ├── services/     # Services API
│   │   └── stores/       # Stores Pinia
│   └── public/
├── docker/               # Configuration Docker
│   └── nginx/            # Configuration Nginx
├── scripts/              # Scripts utilitaires
└── .github/workflows/    # CI/CD
```

## Démarrage rapide

### 1. Configuration initiale

```bash
./scripts/setup.sh
```

Ce script génère:
- Certificats SSL pour HTTPS
- Clés JWT pour l'authentification
- Copie les fichiers de données nécessaires

### 2. Lancer l'application

```bash
docker compose up -d
```

### 3. Initialiser la base de données

```bash
docker compose exec backend php bin/console doctrine:database:create
docker compose exec backend php bin/console doctrine:schema:create
```

### 4. Accéder à l'application

- **Frontend**: https://localhost
- **API**: https://localhost/api/v1

⚠️ **Note**: Acceptez le certificat auto-signé dans votre navigateur.

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

## Documentation

- [DEPLOYMENT.md](DEPLOYMENT.md) - Instructions de déploiement détaillées
- [ARCHITECTURE.md](ARCHITECTURE.md) - Architecture et choix techniques

## Conformité OpenAPI

L'API est strictement conforme à la spécification `openapi.yml`:

- ✅ POST `/api/v1/routes` - Calcul de trajet
- ✅ GET `/api/v1/stats/distances` - Statistiques (bonus)
- ✅ Schémas de réponse conformes
- ✅ Gestion des erreurs (400, 422)

## Technologies utilisées

### Backend
- PHP 8.4
- Symfony 7.1
- Doctrine ORM
- PostgreSQL 15
- PHPUnit 11

### Frontend
- TypeScript 5
- Vue.js 3
- Vuetify 3
- Pinia
- Chart.js
- Vitest

### Infrastructure
- Docker Engine 25
- Docker Compose
- Nginx
- GitHub Actions

## Hypothèses et choix

1. **Authentification JWT**: Configurée mais non obligatoire pour les endpoints publics (peut être activée)
2. **Graphe bidirectionnel**: Les connexions sont bidirectionnelles (un train peut aller dans les deux sens)
3. **Distance en kilomètres**: Les distances sont stockées et retournées en kilomètres (float)
4. **Codes analytiques**: Format libre (string, max 50 caractères)
5. **Groupement statistiques**: Implémenté de base, peut être étendu pour un groupement temporel plus fin

## Améliorations possibles

- [ ] Cache Redis pour les calculs de routage fréquents
- [ ] Authentification JWT complète avec endpoints de login
- [ ] Pagination pour les statistiques
- [ ] Export des statistiques (CSV, PDF)
- [ ] WebSockets pour les mises à jour en temps réel
- [ ] Tests E2E avec Playwright/Cypress
- [ ] Monitoring avec Prometheus/Grafana

## Auteur

Solution complète pour le défi fullstack MOB.

