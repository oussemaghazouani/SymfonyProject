package com.biblio.auth.dto;

import com.biblio.auth.entity.User;

public class LoginResponse {
    
    private String token;
    private String username;
    private String email;
    private User.Role role;
    private Long userId;
    private long expiresIn;
    
    // Constructeurs
    public LoginResponse() {}
    
    public LoginResponse(String token, String username, String email, User.Role role, Long userId, long expiresIn) {
        this.token = token;
        this.username = username;
        this.email = email;
        this.role = role;
        this.userId = userId;
        this.expiresIn = expiresIn;
    }
    
    // Getters et Setters
    public String getToken() {
        return token;
    }
    
    public void setToken(String token) {
        this.token = token;
    }
    
    public String getUsername() {
        return username;
    }
    
    public void setUsername(String username) {
        this.username = username;
    }
    
    public String getEmail() {
        return email;
    }
    
    public void setEmail(String email) {
        this.email = email;
    }
    
    public User.Role getRole() {
        return role;
    }
    
    public void setRole(User.Role role) {
        this.role = role;
    }
    
    public Long getUserId() {
        return userId;
    }
    
    public void setUserId(Long userId) {
        this.userId = userId;
    }
    
    public long getExpiresIn() {
        return expiresIn;
    }
    
    public void setExpiresIn(long expiresIn) {
        this.expiresIn = expiresIn;
    }
}