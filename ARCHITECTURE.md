# Architecture du projet

## Vue d'ensemble

Ce projet implémente une solution complète de routage de trains avec statistiques, composée de:

- **Backend**: API REST PHP 8.4 (Symfony 7.1)
- **Frontend**: Application Vue.js 3 + TypeScript + Vuetify 3
- **Base de données**: PostgreSQL 15
- **Reverse Proxy**: Nginx avec HTTPS
- **Orchestration**: Docker Compose

## Structure du backend

```
backend/
├── config/          # Configuration Symfony
├── src/
│   ├── Controller/ # Contrôleurs API
│   ├── Entity/     # Entités Doctrine
│   ├── Repository/ # Repositories Doctrine
│   ├── Service/    # Services métier
│   └── Exception/  # Exceptions personnalisées
├── tests/          # Tests PHPUnit
└── migrations/     # Migrations Doctrine
```

### Services principaux

- **StationLoader**: Charge et gère les stations depuis `stations.json`
- **GraphBuilder**: Construit le graphe des connexions depuis `distances.json`
- **RoutingService**: Implémente l'algorithme de Dijkstra pour calculer les plus courts chemins

### Algorithme de routage

L'algorithme de Dijkstra est utilisé pour trouver le chemin le plus court entre deux stations. Le graphe est construit de manière bidirectionnelle à partir des données de `distances.json`.

## Structure du frontend

```
frontend/
├── src/
│   ├── views/      # Composants de page
│   ├── services/   # Services API
│   ├── stores/     # Stores Pinia
│   ├── router/     # Configuration Vue Router
│   └── plugins/    # Plugins (Vuetify)
└── public/         # Assets statiques
```

### Pages principales

- **RouteCalculator**: Interface pour calculer un trajet entre deux stations
- **Statistics**: Visualisation des statistiques par code analytique avec graphiques

## Sécurité

- **HTTPS**: Certificats SSL auto-signés pour le développement
- **JWT**: Authentification Bearer Token (configurée mais optionnelle pour ce défi)
- **CORS**: Configuration pour autoriser les requêtes depuis le frontend
- **Headers de sécurité**: X-Frame-Options, X-Content-Type-Options, X-XSS-Protection

## Base de données

### Table `routes`

- `id`: UUID (string, 36 caractères)
- `from_station_id`: Code de la station de départ
- `to_station_id`: Code de la station d'arrivée
- `analytic_code`: Code analytique du trajet
- `distance_km`: Distance totale en kilomètres
- `path`: Chemin sous forme de tableau JSON
- `created_at`: Date de création

Index sur `analytic_code` et `created_at` pour optimiser les requêtes de statistiques.

## API Endpoints

### POST /api/v1/routes

Calcule un trajet entre deux stations.

**Request:**
```json
{
  "fromStationId": "MX",
  "toStationId": "ZW",
  "analyticCode": "ANA-123"
}
```

**Response (201):**
```json
{
  "id": "uuid",
  "fromStationId": "MX",
  "toStationId": "ZW",
  "analyticCode": "ANA-123",
  "distanceKm": 50.5,
  "path": ["MX", "CGE", "ZW"],
  "createdAt": "2025-01-01T00:00:00Z"
}
```

### GET /api/v1/stats/distances

Récupère les statistiques agrégées par code analytique.

**Query Parameters:**
- `from` (optionnel): Date de début (format: YYYY-MM-DD)
- `to` (optionnel): Date de fin (format: YYYY-MM-DD)
- `groupBy` (optionnel): Grouper par 'day', 'month', 'year' ou 'none' (défaut)

**Response (200):**
```json
{
  "from": "2025-01-01",
  "to": "2025-01-31",
  "groupBy": "none",
  "items": [
    {
      "analyticCode": "ANA-123",
      "totalDistanceKm": 100.5
    }
  ]
}
```

## Tests

### Backend

- Tests unitaires avec PHPUnit
- Couverture de code configurée
- Tests d'intégration pour les services de routage

### Frontend

- Tests unitaires avec Vitest
- Tests des services API
- Couverture de code configurée

## CI/CD

Le pipeline GitHub Actions inclut:

1. **Backend**: Tests PHPUnit, analyse de code
2. **Frontend**: Tests Vitest, linting
3. **Sécurité**: Scan Trivy pour vulnérabilités
4. **Build**: Construction et publication des images Docker (sur main)

## Déploiement

Voir `DEPLOYMENT.md` pour les instructions détaillées.

Le déploiement se fait en une commande:
```bash
docker compose up -d
```

## Choix techniques

- **Symfony 7.1**: Framework moderne, bien documenté, écosystème riche
- **Vue.js 3**: Framework réactif, TypeScript natif, excellente DX
- **Vuetify 3**: Composants Material Design, responsive par défaut
- **PostgreSQL**: Base de données relationnelle robuste, support JSON natif
- **Docker Compose**: Orchestration simple, reproductible
- **Nginx**: Reverse proxy performant, configuration flexible

