package com.biblio.auth.controller;

import com.biblio.auth.dto.LoginRequest;
import com.biblio.auth.dto.LoginResponse;
import com.biblio.auth.entity.User;
import com.biblio.auth.service.UserService;
import com.biblio.auth.util.JwtUtil;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpSession;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/auth")
@CrossOrigin(origins = "*", maxAge = 3600)
public class AuthController {

    @Autowired
    private UserService userService;

    @Autowired
    private JwtUtil jwtUtil;

    /**
     * Endpoint de connexion
     */
    @PostMapping("/login")
    public ResponseEntity<?> login(@Valid @RequestBody LoginRequest loginRequest, HttpServletRequest request) {
        try {
            // Authentifier l'utilisateur
            Optional<User> userOpt = userService.authenticate(loginRequest.getEmail(), loginRequest.getMotdepasse());
            
            if (userOpt.isEmpty()) {
                Map<String, String> error = new HashMap<>();
                error.put("error", "Email ou mot de passe incorrect!");
                return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(error);
            }

            User user = userOpt.get();

            // Générer le token JWT
            String token = jwtUtil.generateToken(user);

            // Stocker les informations dans la session
            HttpSession session = request.getSession();
            session.setAttribute("token", token);
            session.setAttribute("username", user.getNom());
            session.setAttribute("role", user.getRole().name());
            session.setAttribute("userId", user.getId());

            // Créer la réponse
            LoginResponse response = new LoginResponse(
                token,
                user.getNom(),
                user.getEmail(),
                user.getRole(),
                user.getId(),
                jwtUtil.getExpirationTime()
            );

            return ResponseEntity.ok(response);

        } catch (Exception e) {
            Map<String, String> error = new HashMap<>();
            error.put("error", "Erreur lors de l'authentification: " + e.getMessage());
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(error);
        }
    }

    /**
     * Endpoint de déconnexion
     */
    @PostMapping("/logout")
    public ResponseEntity<?> logout(HttpServletRequest request) {
        try {
            // Invalider la session
            HttpSession session = request.getSession(false);
            if (session != null) {
                session.invalidate();
            }

            Map<String, String> response = new HashMap<>();
            response.put("message", "Déconnexion réussie");
            return ResponseEntity.ok(response);

        } catch (Exception e) {
            Map<String, String> error = new HashMap<>();
            error.put("error", "Erreur lors de la déconnexion: " + e.getMessage());
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(error);
        }
    }

    /**
     * Endpoint pour vérifier le statut de l'authentification
     */
    @GetMapping("/status")
    public ResponseEntity<?> getAuthStatus(HttpServletRequest request) {
        try {
            HttpSession session = request.getSession(false);
            if (session == null) {
                return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("Non authentifié");
            }

            String token = (String) session.getAttribute("token");
            if (token == null || !jwtUtil.validateToken(token)) {
                return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("Token invalide");
            }

            Map<String, Object> status = new HashMap<>();
            status.put("authenticated", true);
            status.put("username", session.getAttribute("username"));
            status.put("role", session.getAttribute("role"));
            status.put("userId", session.getAttribute("userId"));

            return ResponseEntity.ok(status);

        } catch (Exception e) {
            Map<String, String> error = new HashMap<>();
            error.put("error", "Erreur lors de la vérification du statut: " + e.getMessage());
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(error);
        }
    }

    /**
     * Endpoint pour renouveler le token
     */
    @PostMapping("/refresh")
    public ResponseEntity<?> refreshToken(HttpServletRequest request) {
        try {
            HttpSession session = request.getSession(false);
            if (session == null) {
                return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("Session non trouvée");
            }

            String oldToken = (String) session.getAttribute("token");
            if (oldToken == null) {
                return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("Token non trouvé");
            }

            String email = jwtUtil.getEmailFromToken(oldToken);
            Optional<User> userOpt = userService.findByEmail(email);

            if (userOpt.isEmpty()) {
                return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("Utilisateur non trouvé");
            }

            // Générer un nouveau token
            User user = userOpt.get();
            String newToken = jwtUtil.generateToken(user);

            // Mettre à jour la session
            session.setAttribute("token", newToken);

            Map<String, Object> response = new HashMap<>();
            response.put("token", newToken);
            response.put("expiresIn", jwtUtil.getExpirationTime());

            return ResponseEntity.ok(response);

        } catch (Exception e) {
            Map<String, String> error = new HashMap<>();
            error.put("error", "Erreur lors du renouvellement du token: " + e.getMessage());
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(error);
        }
    }

    /**
     * Endpoint pour obtenir les informations de l'utilisateur connecté
     */
    @GetMapping("/me")
    public ResponseEntity<?> getCurrentUser(HttpServletRequest request) {
        try {
            HttpSession session = request.getSession(false);
            if (session == null) {
                return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("Non authentifié");
            }

            String token = (String) session.getAttribute("token");
            if (token == null || !jwtUtil.validateToken(token)) {
                return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("Token invalide");
            }

            String email = jwtUtil.getEmailFromToken(token);
            Optional<User> userOpt = userService.findByEmail(email);

            if (userOpt.isEmpty()) {
                return ResponseEntity.status(HttpStatus.NOT_FOUND).body("Utilisateur non trouvé");
            }

            User user = userOpt.get();
            Map<String, Object> userInfo = new HashMap<>();
            userInfo.put("id", user.getId());
            userInfo.put("nom", user.getNom());
            userInfo.put("email", user.getEmail());
            userInfo.put("role", user.getRole());
            userInfo.put("enabled", user.isEnabled());

            return ResponseEntity.ok(userInfo);

        } catch (Exception e) {
            Map<String, String> error = new HashMap<>();
            error.put("error", "Erreur lors de la récupération des informations utilisateur: " + e.getMessage());
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(error);
        }
    }
}