package com.biblio.auth.service;

import com.biblio.auth.entity.User;
import com.biblio.auth.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class UserService implements UserDetailsService {

    @Autowired
    private UserRepository userRepository;

    @Autowired
    private PasswordEncoder passwordEncoder;

    /**
     * Charge un utilisateur par son email (requis par Spring Security)
     */
    @Override
    public UserDetails loadUserByUsername(String email) throws UsernameNotFoundException {
        User user = userRepository.findByEmail(email)
                .orElseThrow(() -> new UsernameNotFoundException("Utilisateur non trouvé avec l'email: " + email));
        return user;
    }

    /**
     * Authentifie un utilisateur avec email et mot de passe
     */
    public Optional<User> authenticate(String email, String motdepasse) {
        // Pour le moment, on utilise la méthode simple comme dans Symfony
        return userRepository.findByEmailAndMotdepasse(email, motdepasse);
    }

    /**
     * Authentifie un utilisateur avec email et mot de passe encodé
     */
    public Optional<User> authenticateWithEncodedPassword(String email, String motdepasse) {
        Optional<User> userOpt = userRepository.findByEmail(email);
        if (userOpt.isPresent()) {
            User user = userOpt.get();
            if (passwordEncoder.matches(motdepasse, user.getMotdepasse())) {
                return Optional.of(user);
            }
        }
        return Optional.empty();
    }

    /**
     * Créer un nouvel utilisateur
     */
    public User createUser(String nom, String email, String motdepasse, User.Role role) {
        if (userRepository.existsByEmail(email)) {
            throw new RuntimeException("Un utilisateur avec cet email existe déjà");
        }

        User user = new User();
        user.setNom(nom);
        user.setEmail(email);
        user.setMotdepasse(passwordEncoder.encode(motdepasse));
        user.setRole(role);
        user.setEnabled(true);

        return userRepository.save(user);
    }

    /**
     * Trouver un utilisateur par email
     */
    public Optional<User> findByEmail(String email) {
        return userRepository.findByEmail(email);
    }

    /**
     * Trouver un utilisateur par ID
     */
    public Optional<User> findById(Long id) {
        return userRepository.findById(id);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public User updateUser(User user) {
        return userRepository.save(user);
    }

    /**
     * Supprimer un utilisateur
     */
    public void deleteUser(Long id) {
        userRepository.deleteById(id);
    }

    /**
     * Lister tous les utilisateurs
     */
    public List<User> getAllUsers() {
        return userRepository.findAll();
    }

    /**
     * Lister les utilisateurs par rôle
     */
    public List<User> getUsersByRole(User.Role role) {
        return userRepository.findByRole(role);
    }

    /**
     * Changer le mot de passe d'un utilisateur
     */
    public void changePassword(Long userId, String newPassword) {
        Optional<User> userOpt = userRepository.findById(userId);
        if (userOpt.isPresent()) {
            User user = userOpt.get();
            user.setMotdepasse(passwordEncoder.encode(newPassword));
            userRepository.save(user);
        } else {
            throw new RuntimeException("Utilisateur non trouvé");
        }
    }

    /**
     * Activer/désactiver un utilisateur
     */
    public void toggleUserStatus(Long userId) {
        Optional<User> userOpt = userRepository.findById(userId);
        if (userOpt.isPresent()) {
            User user = userOpt.get();
            user.setEnabled(!user.isEnabled());
            userRepository.save(user);
        } else {
            throw new RuntimeException("Utilisateur non trouvé");
        }
    }
}