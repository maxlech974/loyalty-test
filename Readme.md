# Gestion des notes de frais - API - Test Technique

Ce projet est une API de gestion de notes de frais.

## Prérequis

Pour faire fonctionner ce projet, vous devez avoir installé Docker et Docker-Compose sur votre machine. 

## Installation

1. Clonez le projet depuis le repo github.
2. Récupérez votre UID et votre GID en utilisant la commande suivante dans le terminal : 
```bash
id -u && id -g
```
3. configurez le .env à la racine du projet avec les bons UID et GID
4. dans le dossier courant tapper la commande: 
```bash
sudo chown -R <votre-UID>:<votre-GID> ./*
```

### Construisez et démarrez les conteneurs Docker :

Lancez les commandes :

```bash
docker-compose build
docker-compose up -d
```


Votre API est maintenant accessible à l'adresse http://localhost:8087 (ou le port que vous avez configuré dans vos variables d'environnement .env).

PhpMyAdmin est quant à lui accessible sur le port 8088 par défaut.

### Installation du projet symfony et mise en place de la base de données

Pour installer toutes les dépendances du framework et lancer les migrations ainsi qu'installer un jeu de données pour pouvoir tester.

1. Installer les dépendances: 

```bash
docker-compose exec php composer install
```

2. Lancer les migrations du schéma des entités vers la base de données en exécutant la commande suivante :

**Attention chez moi je dois effectuer cette commande deux fois pour que ça fonctionne je ne comprends pas pouquoi**
```bash
docker-compose exec php bin/console d:m:m --no-interaction
```

3. créer la base de donnée de test et lancer les migrations: 
   
```bash
docker-compose exec php bin/console d:d:c --env=test --no-interaction
docker-compose exec php bin/console d:m:m --env=test --no-interaction
```
4. lancez les fixtures sur la base de donnée. (les fixtures sont regnérées à chaque test pour les tests)

```bash
docker-compose exec php bin/console hautelook:fixtures:load --no-interaction
```

## Génération des clés JWT

Pour générer les clés nécessaires pour le JWT, exécutez les commandes suivantes :

**Attention, j'ai configuré le mot de passe des clés dans mon .env, si vous ne souhaitez pas modifier le fichier .env alors mettre dans le prompt comme mot de passe : test**

```bash
docker-compose exec php mkdir -p config/jwt
docker-compose exec php openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
docker-compose exec php openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

Lors de la création de la clé privée, un mot de passe vous sera demandé. Ce mot de passe doit être ajouté à votre fichier .env sous la variable JWT_PASSPHRASE (test configuré par défaut)




## Utilisation

Cette API contient les routes suivantes :

- `GET /api/expense` : récupérer toutes les notes de frais
- `POST /api/expense` : créer une nouvelle note de frais
- `GET /api/expense/{id}` : récupérer une note de frais spécifique
- `PUT /api/expense/{id}` : mettre à jour une note de frais spécifique
- `DELETE /api/expense/{id}` : supprimer une note de frais spécifique

**Pour tester les routes, un export Insomnia contenant tous les endpoints de l'application est mis à disposition. Les formats d'envoi de données pour les requêtes POST et PUT sont déjà configurés dans cet export.**


## Connexion

Pour vous connecter, utilisez le format de requête :
```json
{
  "email", "maximelechere.dev@gmail.com",
  "password", "test"
}
```

## Tests

Pour lancer les tests, vous pouvez exécuter la commande suivante à partir du répertoire racine du projet :

```bash
docker-compose exec php bin/phpunit
```

Cela exécutera tous les tests unitaires et fonctionnels de l'application.

## Choix de la Stack Technique

- **Symfony** : Choisi pour sa robustesse et sa flexibilité. Il fournit une structure de code cohérente, facilitant la maintenance et l'évolutivité.

- **API Platform** : Permet une création rapide d'APIs REST avec une documentation automatiquement générée. Il s'intègre parfaitement avec Symfony, facilitant la construction de l'API.

- **Docker-compose** : Fournit un environnement de développement isolé, garantissant une cohérence entre les différentes machines sur lesquelles l'API est déployée.

- **MySQL** : Système de gestion de base de données fiable et performant. Parfaitement intégré à Symfony via Doctrine, il simplifie les opérations de base de données.

- **Fixtures** : Utilisées pour peupler la base de données avec des données de test, elles permettent de vérifier que l'API fonctionne correctement dans divers scénarios.

Cette stack a été choisie pour sa robustesse, son efficacité et sa facilité d'utilisation, permettant un développement rapide et de qualité.
