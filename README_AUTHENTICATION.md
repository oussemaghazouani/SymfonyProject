# SaaS Client - Système d'Authentification

## Vue d'ensemble

Ce document décrit l'implémentation complète du système d'authentification pour la plateforme SaaS Client, conforme aux spécifications des User Stories US1.1, US1.2 et US1.3.

## Fonctionnalités implémentées

### 1. Inscription des Clients (US1.1)

**Route**: `/auth/client/register`

**Champs requis**:
- Identifiant unique
- Email (avec validation)
- Mot de passe (minimum 6 caractères) + confirmation
- Type d'utilisateur (personne physique ou entreprise)
- Nom et prénom
- Nom d'entreprise (conditionnel si type = entreprise)
- Acceptation des termes et conditions (obligatoire)
- Acceptation des politiques de confidentialité (obligatoire)

**Processus**:
1. Soumission du formulaire → validation des données
2. Création du compte → génération du code de vérification email
3. Envoi de l'email de vérification
4. Redirection vers la page de vérification email

### 2. Inscription des Prestataires (US1.1)

**Route**: `/auth/provider/register`

**Champs requis**:
- Email (avec validation)
- Mot de passe (minimum 6 caractères) + confirmation
- Type d'utilisateur (personne physique ou entreprise)
- Nom et prénom
- Domaine d'activité (obligatoire pour les prestataires)
- Acceptation des termes et conditions (obligatoire)
- Acceptation des politiques de confidentialité (obligatoire)

**Processus**: Identique aux clients, mais avec attribution du rôle `ROLE_PROVIDER`

### 3. Vérification Email

**Route**: `/auth/verify-email`

**Fonctionnalités**:
- Code à 6 chiffres envoyé par email
- Expiration après 15 minutes
- Possibilité de renvoyer le code après 60 secondes
- Code utilisable une seule fois
- Interface avec compteur de 60 secondes

### 4. Connexion (US1.2)

**Route**: `/auth/login`

**Processus**:
1. Saisie identifiant/email + mot de passe
2. Vérification des credentials
3. Vérification du statut email (doit être vérifié)
4. Redirection vers la vérification téléphone

### 5. Vérification Téléphone (US1.2)

**Route**: `/auth/verify-phone`

**Processus en 2 étapes**:
1. **Étape 1**: Saisie du numéro de téléphone
2. **Étape 2**: Vérification du code à 6 chiffres

**Après validation**: Redirection vers le tableau de bord approprié selon le rôle

### 6. Mot de passe oublié (US1.3)

**Routes**:
- `/auth/forgot-password` - Saisie email
- `/auth/reset-password-verify` - Vérification code
- `/auth/reset-password-new` - Nouveau mot de passe

**Processus**:
1. Saisie email → envoi code de réinitialisation
2. Vérification code à 6 chiffres
3. Création nouveau mot de passe
4. Redirection vers la connexion

## Structure technique

### Entités

#### User Entity (`src/Entity/User.php`)
```php
- id: int
- identifiant: string (nullable)
- email: string (unique)
- motdepasse: string (hashed)
- nom: string
- prenom: string
- userType: string (personne_physique|entreprise)
- nomEntreprise: string (nullable)
- domaineActivite: string (nullable, pour prestataires)
- role: string (ROLE_CLIENT|ROLE_PROVIDER|ROLE_ADMIN)
- acceptTerms: boolean
- acceptPrivacy: boolean
- emailVerified: boolean
- verificationCode: string (nullable)
- verificationCodeExpiry: DateTime (nullable)
- telephone: string (nullable)
- phoneVerified: boolean
- phoneVerificationCode: string (nullable)
- phoneVerificationCodeExpiry: DateTime (nullable)
- passwordResetCode: string (nullable)
- passwordResetCodeExpiry: DateTime (nullable)
- createdAt: DateTime
- lastLoginAt: DateTime (nullable)
```

### Contrôleurs

#### AuthController (`src/Controller/AuthController.php`)
- `clientRegister()` - Inscription client
- `providerRegister()` - Inscription prestataire
- `verifyEmail()` - Vérification email
- `resendVerification()` - Renvoyer code vérification
- `login()` - Connexion
- `verifyPhone()` - Vérification téléphone
- `forgotPassword()` - Mot de passe oublié
- `resetPasswordVerify()` - Vérification code reset
- `resetPasswordNew()` - Nouveau mot de passe

#### ClientDashboardController (`src/Controller/ClientDashboardController.php`)
- Tableau de bord client avec statistiques

#### ProviderDashboardController (`src/Controller/ProviderDashboardController.php`)
- Tableau de bord prestataire avec limitations d'essai

### Forms

#### Formulaires d'inscription
- `ClientRegistrationType` - Inscription client
- `ServiceProviderRegistrationType` - Inscription prestataire

#### Formulaires de vérification
- `VerificationCodeType` - Code à 6 chiffres
- `PhoneVerificationType` - Numéro de téléphone
- `PasswordResetType` - Nouveau mot de passe

### Services

#### EmailService (`src/Service/EmailService.php`)
- `sendEmailVerification()` - Email de vérification inscription
- `sendPasswordReset()` - Email de réinitialisation mot de passe
- `sendPhoneVerification()` - Email de vérification téléphone

### Templates Email

#### Templates d'email (`templates/emails/`)
- `email_verification.html.twig` - Vérification inscription
- `password_reset.html.twig` - Réinitialisation mot de passe
- `phone_verification.html.twig` - Vérification téléphone

Tous les emails respectent le format spécifié avec:
- En-tête SaaS Client
- Code de vérification mis en évidence
- Informations de sécurité
- Footer avec mentions légales

### Templates Interface

#### Pages d'authentification (`templates/auth/`)
- `client_register.html.twig` - Inscription client
- `provider_register.html.twig` - Inscription prestataire
- `login.html.twig` - Connexion
- `verify_email.html.twig` - Vérification email
- `verify_phone.html.twig` - Vérification téléphone
- `forgot_password.html.twig` - Mot de passe oublié
- `reset_password_verify.html.twig` - Code réinitialisation
- `reset_password_new.html.twig` - Nouveau mot de passe

#### Tableaux de bord
- `client/dashboard.html.twig` - Interface client
- `provider/dashboard.html.twig` - Interface prestataire avec limitations d'essai

## Configuration Sécurité

### Security.yaml
```yaml
providers:
    app_user_provider:
        entity:
            class: App\Entity\User
            property: email

firewalls:
    main:
        lazy: true
        provider: app_user_provider
        form_login:
            login_path: auth_login
            check_path: auth_login
            username_parameter: identifiant
            password_parameter: password

access_control:
    - { path: ^/auth, roles: PUBLIC_ACCESS }
    - { path: ^/client, roles: ROLE_CLIENT }
    - { path: ^/provider, roles: ROLE_PROVIDER }
    - { path: ^/admin, roles: ROLE_ADMIN }
```

## Migration Base de Données

Le fichier de migration `Version20250102120000.php` ajoute tous les champs nécessaires à la table `user` existante.

## Fonctionnalités Spéciales

### 1. Champ Entreprise Conditionnel
- JavaScript dynamique pour afficher/masquer le champ "Nom d'entreprise"
- Validation conditionnelle selon le type d'utilisateur

### 2. Compteur de Renvoie
- Interface avec compteur de 60 secondes pour le renvoie de codes
- Désactivation temporaire du bouton

### 3. Validation des Codes
- Codes à 6 chiffres avec expiration de 15 minutes
- Auto-formatage des champs de saisie
- Nettoyage automatique après utilisation

### 4. Messages d'Erreur Contextuels
- Messages d'erreur spécifiques selon le contexte
- Flash messages pour les succès/erreurs

### 5. Responsive Design
- Interface responsive avec Bootstrap 5
- Design moderne avec FontAwesome
- Cards et formulaires stylisés

## Conformité aux Spécifications

✅ **US1.1 Client**: Tous les champs et validations implémentés
✅ **US1.1 Prestataire**: Formulaire spécialisé avec domaine d'activité
✅ **US1.2 Connexion**: Authentification + vérification téléphone
✅ **US1.3 Mot de passe oublié**: Processus complet de réinitialisation
✅ **Emails**: Templates conformes aux spécifications
✅ **Codes de vérification**: 6 chiffres, expiration, usage unique
✅ **Interface**: Design moderne et responsive
✅ **Limitation d'essai**: Message pour prestataires avec fonctionnalités verrouillées

## Installation et Configuration

1. Appliquer la migration: `php bin/console doctrine:migrations:migrate`
2. Configurer le service email dans `.env`
3. Accéder à la page d'accueil: `/`
4. Tester les différents parcours d'inscription et connexion

## Routes Principales

- `/` - Page d'accueil
- `/auth/client/register` - Inscription client
- `/auth/provider/register` - Inscription prestataire
- `/auth/login` - Connexion
- `/auth/verify-email` - Vérification email
- `/auth/verify-phone` - Vérification téléphone
- `/auth/forgot-password` - Mot de passe oublié
- `/client/dashboard` - Tableau de bord client
- `/provider/dashboard` - Tableau de bord prestataire