package com.biblio.auth.dto;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;

public class LoginRequest {
    
    @NotBlank(message = "L'email est obligatoire")
    @Email(message = "Format d'email invalide")
    private String email;
    
    @NotBlank(message = "Le mot de passe est obligatoire")
    private String motdepasse;
    
    // Constructeurs
    public LoginRequest() {}
    
    public LoginRequest(String email, String motdepasse) {
        this.email = email;
        this.motdepasse = motdepasse;
    }
    
    // Getters et Setters
    public String getEmail() {
        return email;
    }
    
    public void setEmail(String email) {
        this.email = email;
    }
    
    public String getMotdepasse() {
        return motdepasse;
    }
    
    public void setMotdepasse(String motdepasse) {
        this.motdepasse = motdepasse;
    }
}