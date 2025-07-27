# Biblio Auth Service - Service d'Authentification Spring Boot

## Description
Service d'authentification RESTful développé avec Spring Boot pour la gestion des utilisateurs et l'authentification JWT dans l'écosystème Biblio Esprit.

## Fonctionnalités

### 🔐 Authentification
- **Connexion** avec email/mot de passe
- **Génération de tokens JWT** avec expiration configurable
- **Gestion des sessions** avec stockage des informations utilisateur
- **Déconnexion** avec invalidation de session
- **Validation et renouvellement** des tokens

### 👥 Gestion des Utilisateurs
- **Inscription** de nouveaux utilisateurs
- **Gestion des rôles** (USER, ADMIN)
- **CRUD complet** pour les utilisateurs (Admin uniquement)
- **Changement de mot de passe**
- **Activation/désactivation** des comptes

### 🛡️ Sécurité
- **Authentification JWT** 
- **Hashage des mots de passe** avec BCrypt
- **Protection CORS** configurée
- **Autorisation basée sur les rôles**
- **Validation des données** d'entrée

## Structure du Projet

```
src/main/java/com/biblio/auth/
├── BiblioAuthApplication.java          # Classe principale
├── config/
│   └── SecurityConfig.java             # Configuration Spring Security
├── controller/
│   ├── AuthController.java             # Endpoints d'authentification
│   ├── UserController.java             # Gestion des utilisateurs
│   └── HealthController.java           # Endpoints de santé
├── dto/
│   ├── LoginRequest.java               # DTO pour la connexion
│   ├── LoginResponse.java              # DTO réponse de connexion
│   └── RegisterRequest.java            # DTO pour l'inscription
├── entity/
│   └── User.java                       # Entité utilisateur
├── repository/
│   └── UserRepository.java             # Repository JPA
├── service/
│   └── UserService.java                # Service métier
└── util/
    └── JwtUtil.java                    # Utilitaires JWT
```

## Configuration

### Base de Données
Configurez votre base de données MySQL dans `application.yml` :

```yaml
spring:
  datasource:
    url: jdbc:mysql://localhost:3306/biblio_esprit
    username: votre_username
    password: votre_password
```

### JWT
Modifiez la clé secrète et l'expiration dans `application.yml` :

```yaml
jwt:
  secret: VOTRE_CLE_SECRETE_TRES_LONGUE
  expiration: 432000000 # 5 jours
```

## API Endpoints

### 🔐 Authentification (`/api/auth`)

#### POST `/api/auth/login`
Connecter un utilisateur
```json
{
  "email": "user@example.com",
  "motdepasse": "password123"
}
```

**Réponse :**
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "username": "John Doe",
  "email": "user@example.com",
  "role": "ROLE_USER",
  "userId": 1,
  "expiresIn": 432000000
}
```

#### POST `/api/auth/logout`
Déconnecter l'utilisateur actuel

#### GET `/api/auth/status`
Vérifier le statut d'authentification

#### POST `/api/auth/refresh`
Renouveler le token JWT

#### GET `/api/auth/me`
Obtenir les informations de l'utilisateur connecté

### 👥 Gestion Utilisateurs (`/api/user`)

#### POST `/api/user/register`
Inscrire un nouvel utilisateur
```json
{
  "nom": "John Doe",
  "email": "john@example.com",
  "motdepasse": "password123",
  "role": "ROLE_USER"
}
```

#### GET `/api/user/all` 🔒 Admin
Lister tous les utilisateurs

#### GET `/api/user/{id}` 🔒 Admin
Obtenir un utilisateur par ID

#### PUT `/api/user/{id}` 🔒 Admin
Mettre à jour un utilisateur

#### DELETE `/api/user/{id}` 🔒 Admin
Supprimer un utilisateur

#### PATCH `/api/user/{id}/toggle-status` 🔒 Admin
Activer/désactiver un utilisateur

#### PATCH `/api/user/{id}/change-password` 🔒 Admin/Propriétaire
Changer le mot de passe

### 💚 Santé (`/api/health`)

#### GET `/api/health`
État général du service

#### GET `/api/health/ready`
Vérifier la disponibilité

#### GET `/api/health/live`
Vérifier la vivacité

## Installation et Démarrage

### Prérequis
- Java 17+
- Maven 3.6+
- MySQL 8.0+

### Étapes

1. **Cloner et configurer**
```bash
git clone <votre-repo>
cd biblio-auth-service
```

2. **Configurer la base de données**
- Créer une base de données MySQL nommée `biblio_esprit`
- Mettre à jour les credentials dans `application.yml`

3. **Compiler et démarrer**
```bash
mvn clean install
mvn spring-boot:run
```

4. **Vérifier le démarrage**
```bash
curl http://localhost:8080/api/health
```

## Utilisation

### Exemple de connexion
```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@biblio.com",
    "motdepasse": "admin123"
  }'
```

### Exemple d'inscription
```bash
curl -X POST http://localhost:8080/api/user/register \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "John Doe",
    "email": "john@example.com",
    "motdepasse": "password123",
    "role": "ROLE_USER"
  }'
```

## Rôles et Permissions

| Rôle | Permissions |
|------|-------------|
| `ROLE_USER` | Accès aux endpoints utilisateur standard |
| `ROLE_ADMIN` | Accès complet, gestion des utilisateurs |

## Sécurité

- 🔐 **Mots de passe hashés** avec BCrypt
- 🎫 **Tokens JWT** avec expiration
- 🛡️ **CORS** configuré pour les origines autorisées
- 🔒 **Endpoints protégés** par rôle
- ✅ **Validation** des données d'entrée

## Technologies Utilisées

- **Spring Boot 3.2.0**
- **Spring Security 6**
- **Spring Data JPA**
- **JWT (JJWT)**
- **MySQL**
- **Maven**

## Support

Pour toute question ou problème, contactez l'équipe de développement.

---

**Version :** 1.0.0  
**Auteur :** Équipe Biblio Esprit  
**Licence :** MIT