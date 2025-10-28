# Gestion-Banque

Un système de gestion bancaire développé avec Laravel 10 pour gérer les comptes bancaires et les transactions avec une API REST complète.

## Fonctionnalités

- **API REST complète** : Endpoint GET /api/v1/comptes pour lister les comptes
- **Authentification Bearer Token** : Utilisation de Laravel Passport
- **Filtrage et recherche avancés** : Par type, statut, numéro, nom du titulaire
- **Tri et pagination** : Tri personnalisable et pagination automatique
- **Middleware de rating** : Blocage des utilisateurs avec rating élevé
- **Service cloud externe** : Récupération des comptes épargne archivés
- **Gestion des comptes bancaires** : Création, modification et suppression de comptes
- **Gestion des transactions** : Enregistrement des débits et crédits
- **Gestion des clients** : Association des comptes aux clients
- **UUID comme clés primaires** : Sécurité et unicité renforcée
- **Validation des données** : Requêtes de validation personnalisées
- **Données de test** : Factories et seeders pour générer des données fictives

## Architecture

### Modèles

#### Compte
- **Clé primaire** : UUID
- **Champs** :
  - `numero` : Numéro unique généré automatiquement (format : CPT-XXXXXX)
  - `type` : Type de compte (epargne/cheque)
  - `solde` : Solde actuel (décimal 15,2)
  - `statut` : Statut du compte (actif/bloque/ferme)
  - `client_id` : Référence vers le client (clé étrangère)
  - `motif_blocage` : Motif du blocage (optionnel)
  - `supprime` : Indicateur de suppression logique
- **Relations** :
  - `belongsTo` : Client
  - `hasMany` : Transactions
- **Scopes** :
  - `nonSupprimes` : Comptes non supprimés, type cheque ou epargne, statut actif (global)
  - `numero($numero)` : Filtre par numéro
  - `client($telephone)` : Filtre par téléphone du client

#### Transaction
- **Clé primaire** : UUID
- **Champs** :
  - `compte_id` : Référence vers le compte (clé étrangère avec cascade)
  - `montant` : Montant de la transaction (décimal 15,2)
  - `type` : Type de transaction (debit/credit)
- **Relations** :
  - `belongsTo` : Compte

#### Client
- **Clé primaire** : UUID
- **Champs** :
  - `nom` : Nom complet du client
  - `email` : Adresse email unique
  - `telephone` : Numéro de téléphone (optionnel)
  - `cni` : Numéro de carte d'identité nationale (unique)
- **Relations** :
  - `hasMany` : Comptes

## Installation

1. **Cloner le projet**
   ```bash
   git clone <repository-url>
   cd gestion-banque
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   npm install
   ```

3. **Configuration de l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configuration de l'environnement**
    - Modifier le fichier `.env` avec vos paramètres :
      ```env
      APP_NAME=Gestion-Banque
      APP_ENV=local
      APP_KEY=base64:your-generated-key
      APP_DEBUG=true
      APP_URL=http://localhost

      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=gestion_banque
      DB_USERNAME=your_username
      DB_PASSWORD=your_password

      PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
      PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=your-secret
      ```

5. **Configuration de la base de données**
    - Créer la base de données `gestion_banque`
    - Exécuter les migrations :
      ```bash
      php artisan migrate
      ```

6. **Configuration de Laravel Passport pour UUID**
    ```bash
    # Publier la configuration Passport
    php artisan vendor:publish --tag=passport-config

    # Activer les UUIDs pour les clients dans config/passport.php
    'client_uuids' => true,

    # Installer Passport
    php artisan passport:install --force
    ```

7. **Peupler la base de données (optionnel)**
    ```bash
    php artisan db:seed
    ```

## API Documentation

### GET /api/v1/comptes

Endpoint pour lister tous les comptes bancaires avec filtrage, recherche, tri et pagination.

#### Authentification
- **Type** : Bearer Token (Laravel Passport)
- **Header** : `Authorization: Bearer {token}`

#### Paramètres de requête (optionnels)
- `page` : Numéro de page (défaut: 1)
- `limit` : Nombre d'éléments par page (défaut: 15)
- `type` : Type de compte (`epargne` ou `cheque`)
- `statut` : Statut du compte (`actif`, `bloque`, `ferme`)
- `search` : Recherche par numéro de compte ou nom du titulaire
- `sort` : Champ de tri (défaut: `created_at`)
- `order` : Ordre de tri (`asc` ou `desc`, défaut: `desc`)

#### Middleware
- `auth:api` : Authentification obligatoire
- `rating` : Vérification du rating utilisateur (bloque si rating >= 5)

#### Exemples de requêtes

```bash
# Lister tous les comptes
GET /api/v1/comptes

# Filtrer par type épargne
GET /api/v1/comptes?type=epargne

# Recherche par numéro
GET /api/v1/comptes?search=CPT-ABC123

# Tri par solde décroissant
GET /api/v1/comptes?sort=solde&order=desc

# Pagination
GET /api/v1/comptes?page=2&limit=10
```

#### Réponse JSON

```json
{
  "data": [
    {
      "id": "uuid-compte",
      "numeroCompte": "CPT-ABC123",
      "titulaire": "John Doe",
      "type": "epargne",
      "solde": 1500.50,
      "devise": "XOF",
      "dateCreation": "2025-01-15",
      "statut": "actif",
      "motifBlocage": null,
      "metadata": {
        "derniereModification": "2025-01-15 10:30:00",
        "version": "1.0"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 45,
    "last_page": 3
  }
}
```

## Utilisation

### Création d'un compte
```php
use App\Models\Compte;
use App\Http\Requests\StoreCompteRequest;

$validated = $request->validated(); // Utilise StoreCompteRequest
$compte = Compte::create($validated);
// Le numéro est généré automatiquement
```

### Enregistrement d'une transaction
```php
use App\Models\Transaction;

Transaction::create([
    'compte_id' => $compte->id,
    'montant' => 100.50,
    'type' => 'credit'
]);
```

### Relations
```php
// Récupérer les transactions d'un compte
$transactions = $compte->transactions;

// Récupérer le client d'un compte
$client = $compte->client;

// Récupérer le compte d'une transaction
$compte = $transaction->compte;
```

### Service Cloud
```php
use App\Services\CompteCloudService;

$cloudService = new CompteCloudService();
$archivedAccounts = $cloudService->getArchivedSavingsAccounts();
```

## Validation

### StoreClientRequest
- `nom` : requis, chaîne, max 255 caractères
- `email` : requis, email valide, unique dans clients
- `telephone` : optionnel, chaîne, max 20 caractères
- `cni` : requis, chaîne, max 20 caractères, unique dans clients

### StoreCompteRequest
- `intitule` : requis, chaîne, max 255 caractères
- `solde` : requis, numérique, minimum 0
- `client_id` : requis, doit exister dans la table clients

## Données de test

### Factories
- **ClientFactory** : Génère des clients avec nom, email unique, téléphone optionnel et CNI unique
- **CompteFactory** : Génère des comptes avec numéro unique, intitulé aléatoire et solde positif
- **TransactionFactory** : Génère des transactions avec montants et types aléatoires

### Seeders
- **ClientSeeder** : Crée 10 clients de test
- **CompteSeeder** : Crée 30 comptes de test
- **TransactionSeeder** : Crée 50 transactions de test

## Structure des fichiers

```
app/
├── Http/
│   ├── Controllers/
│   │   └── CompteController.php
│   ├── Middleware/
│   │   └── RatingMiddleware.php
│   ├── Requests/
│   │   ├── StoreClientRequest.php
│   │   └── StoreCompteRequest.php
│   └── Resources/
│       └── CompteResource.php
├── Models/
│   ├── Client.php
│   ├── Compte.php
│   └── Transaction.php
├── Services/
│   └── CompteCloudService.php
database/
├── factories/
│   ├── ClientFactory.php
│   ├── CompteFactory.php
│   └── TransactionFactory.php
├── migrations/
│   ├── ..._create_clients_table.php
│   ├── ..._create_comptes_table.php
│   └── ..._create_transactions_table.php
└── seeders/
    ├── ClientSeeder.php
    ├── CompteSeeder.php
    ├── DatabaseSeeder.php
    └── TransactionSeeder.php
routes/
└── api.php
```

## Middleware Rating

Le middleware `RatingMiddleware` permet de bloquer l'accès aux utilisateurs ayant un rating trop élevé.

### Configuration
- **Alias** : `rating`
- **Paramètre** : `rating_limit` (défaut: 5)

### Utilisation
```php
Route::middleware(['auth:api', 'rating'])->group(function () {
    // Routes protégées
});
```

### Personnalisation du seuil
```php
Route::middleware(['auth:api', 'rating:3'])->group(function () {
    // Bloque si rating >= 3
});
```

## Service Cloud

Le `CompteCloudService` permet de récupérer les comptes épargne archivés depuis un service cloud externe.

### Configuration
Ajouter dans `config/services.php` :
```php
'compte_cloud' => [
    'url' => env('COMPTE_CLOUD_URL', 'https://api.compte-cloud.com/v1'),
],
```

### Utilisation
```php
use App\Services\CompteCloudService;

$service = new CompteCloudService();
$accounts = $service->getArchivedSavingsAccounts();
```

## Configuration Passport UUID

### Problème résolu
Laravel Passport utilise par défaut des clés auto-incrémentées pour les clients OAuth, mais avec des utilisateurs utilisant des UUIDs, cela cause des erreurs de type de données dans PostgreSQL.

### Solution implémentée

#### 1. Migrations modifiées
- `oauth_clients.id` : changé de `bigIncrements` à `uuid()->primary()`
- `oauth_clients.user_id` : changé de `unsignedBigInteger` à `uuid()`
- `oauth_personal_access_clients.client_id` : changé de `unsignedBigInteger` à `uuid()`

#### 2. Configuration Passport
```php
// config/passport.php
'client_uuids' => true,
```

#### 3. Configuration Auth
```php
// config/auth.php
'guards' => [
    'api' => [
        'driver' => 'passport',
        'provider' => 'clients',
    ],
],
'providers' => [
    'clients' => [
        'driver' => 'eloquent',
        'model' => App\Models\Client::class,
    ],
],
```

#### 4. Modèle Client
```php
use Laravel\Passport\HasApiTokens;

class Client extends Model
{
    use HasFactory, HasUuids, HasApiTokens;

    protected $keyType = 'string';
    public $incrementing = false;

    // Méthodes pour Passport
    public function getKeyName() { return 'id'; }
    public function getKeyType() { return 'string'; }
}
```

### Commandes à exécuter
```bash
# Publier la config Passport
php artisan vendor:publish --tag=passport-config

# Activer les UUIDs dans config/passport.php
# 'client_uuids' => true,

# Migrer avec les nouvelles tables
php artisan migrate:fresh

# Installer Passport
php artisan passport:install --force

# Peupler la base
php artisan db:seed
```

### Test de fonctionnement
```php
$client = Client::first();
$token = $client->createToken('API Token')->accessToken;
// Retourne maintenant un token JWT valide
```

## Bonnes pratiques implémentées

- **API RESTful** : Architecture REST avec ressources Laravel
- **Authentification sécurisée** : Utilisation de Laravel Passport avec UUID
- **Middleware personnalisés** : Contrôle d'accès basé sur le rating
- **Filtrage et recherche avancés** : Requêtes optimisées avec scopes Eloquent
- **Pagination automatique** : Gestion efficace des gros volumes de données
- **Resources Laravel** : Formatage JSON structuré et cohérent
- **UUID comme clés primaires** : Sécurité et prévention des attaques par énumération
- **Validation côté serveur** : Protection contre les données invalides
- **Relations Eloquent** : Gestion facile des associations
- **Factories et Seeders** : Génération de données de test cohérentes
- **Mutators automatiques** : Génération automatique des numéros de compte
- **Contraintes de base de données** : Intégrité référentielle avec clés étrangères
- **Cascade delete** : Suppression automatique des transactions lors de la suppression d'un compte
- **Services externes** : Intégration avec APIs cloud

## Tests

```bash
# Exécuter tous les tests
php artisan test

# Exécuter les tests avec couverture
php artisan test --coverage
```

## Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
