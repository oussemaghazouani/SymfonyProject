package com.biblio.auth.controller;

import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.time.LocalDateTime;
import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/health")
@CrossOrigin(origins = "*", maxAge = 3600)
public class HealthController {

    /**
     * Endpoint pour vérifier la santé de l'application
     */
    @GetMapping
    public ResponseEntity<?> health() {
        Map<String, Object> response = new HashMap<>();
        response.put("status", "UP");
        response.put("service", "Biblio Auth Service");
        response.put("timestamp", LocalDateTime.now());
        response.put("version", "1.0.0");
        
        return ResponseEntity.ok(response);
    }

    /**
     * Endpoint pour vérifier la disponibilité
     */
    @GetMapping("/ready")
    public ResponseEntity<?> ready() {
        Map<String, Object> response = new HashMap<>();
        response.put("ready", true);
        response.put("timestamp", LocalDateTime.now());
        
        return ResponseEntity.ok(response);
    }

    /**
     * Endpoint pour vérifier la vivacité
     */
    @GetMapping("/live")
    public ResponseEntity<?> live() {
        Map<String, Object> response = new HashMap<>();
        response.put("alive", true);
        response.put("timestamp", LocalDateTime.now());
        
        return ResponseEntity.ok(response);
    }
}