package com.biblio.auth.repository;

import com.biblio.auth.entity.User;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface UserRepository extends JpaRepository<User, Long> {
    
    /**
     * Trouve un utilisateur par son email
     */
    Optional<User> findByEmail(String email);
    
    /**
     * Vérifie si un utilisateur existe avec cet email
     */
    boolean existsByEmail(String email);
    
    /**
     * Trouve un utilisateur par email et mot de passe (pour l'authentification simple)
     */
    Optional<User> findByEmailAndMotdepasse(String email, String motdepasse);
    
    /**
     * Trouve tous les utilisateurs par rôle
     */
    java.util.List<User> findByRole(User.Role role);
}