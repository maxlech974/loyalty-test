# Gestion des notes de frais - API - Test Technique

Ce projet est une API de gestion de notes de frais.

## Prérequis

Pour faire fonctionner ce projet, vous devez avoir installé Docker et Docker-Compose sur votre machine. 

## Installation

### Construisez et démarrez les conteneurs Docker :

```bash
docker-compose up -d
```

Votre API est maintenant accessible à l'adresse http://localhost:8080 (ou le port que vous avez configuré dans vos variables d'environnement .env).

## Utilisation

Cette API contient les routes suivantes :

- `GET /notes` : récupérer toutes les notes de frais
- `GET /notes/{id}` : récupérer une note de frais spécifique
- `POST /notes` : créer une nouvelle note de frais
- `PUT /notes/{id}` : mettre à jour une note de frais spécifique
- `DELETE /notes/{id}` : supprimer une note de frais spécifique

## Tests

Pour lancer les tests, vous pouvez exécuter la commande suivante à partir du répertoire racine du projet :

```bash
docker-compose exec php vendor/bin/phpunit
```

Cela exécutera tous les tests unitaires et fonctionnels de l'application.