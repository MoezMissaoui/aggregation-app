# Système d'Agrégation API

## À propos

Le Système d'Agrégation est une API RESTful conçue pour gérer les abonnements et les services entre partenaires. Cette API utilise l'authentification OAuth2 avec le flux "client credentials" pour sécuriser les points d'accès.

## Configuration Requise

- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Extension PHP PDO MySQL
- Extension PHP OpenSSL

## Installation

1. Cloner le dépôt :
```bash
git clone [url-du-depot]
cd aggregation_ws
```

2. Installer les dépendances :
```bash
composer install
```

3. Configurer l'environnement :
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurer la base de données dans le fichier `.env`

5. Exécuter les migrations et les seeders :
```bash
php artisan migrate
php artisan db:seed
```

## Authentification

L'API utilise OAuth2 avec le flux "client credentials". Pour accéder aux endpoints protégés, vous devez d'abord obtenir un token d'accès.

### Obtenir un Token d'Accès

```http
POST /api/v1/auth/oauth2/token
Content-Type: application/json

{
    "grant_type": "client_credentials",
    "client_id": "votre-client-id",
    "client_secret": "votre-client-secret"
}
```

### Utiliser le Token

Incluez le token dans l'en-tête Authorization de vos requêtes :

```http
Authorization: Bearer votre-token-access
```

## Endpoints API

### Gestion des Abonnements

#### Optin
```http
POST /api/v1/subscription/optin/{partner_id}
```

#### Optout
```http
POST /api/v1/subscription/optout/{partner_id}
```

#### Vérifier le Statut
```http
GET /api/v1/subscription/status/{partner_id}
```

### Vérification de Santé

```http
GET /api/health
```

## Gestion des Erreurs

L'API retourne des réponses d'erreur cohérentes au format JSON :

```json
{
    "message": "Message d'erreur",
    "data": {
        "errors": ["Description détaillée de l'erreur"]
    },
    "status_code": 4xx/5xx
}
```

### Codes d'État HTTP

- 200 : Succès
- 400 : Requête invalide
- 401 : Non authentifié
- 403 : Non autorisé
- 404 : Ressource non trouvée
- 422 : Erreur de validation
- 500 : Erreur serveur

## Sécurité

- Toutes les requêtes doivent être effectuées via HTTPS
- Les tokens d'accès expirent après une période définie
- Les requêtes sont limitées par rate limiting
- Chaque requête nécessite un ID de corrélation pour le traçage

## Journalisation

L'API enregistre automatiquement :
- Les requêtes entrantes
- Les réponses sortantes
- Les erreurs d'authentification
- Les exceptions système

## Support

Pour toute assistance technique ou question, contactez l'équipe de support :
- Email : [adresse-email-support]
- Documentation API complète : [lien-vers-documentation]

## Licence

[Type de Licence] - voir le fichier LICENSE pour plus de détails.
