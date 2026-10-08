package com.lunarvoid.multidocs.auth.services;

import java.time.Instant;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;

import com.auth0.jwt.JWT;
import com.auth0.jwt.JWTVerifier;
import com.auth0.jwt.algorithms.Algorithm;
import com.auth0.jwt.exceptions.JWTCreationException;
import com.auth0.jwt.exceptions.JWTVerificationException;
import com.auth0.jwt.interfaces.DecodedJWT;
import com.lunarvoid.multidocs.auth.entidade.Usuario;

@Service
public class TokenService {

    public static final long EXPIRES_IN_SECONDS = 2592000L;
    
    @Value("${token.secret}")
    private String secret;

    public String gerarToken(Usuario user){
        try {
            Algorithm algorithm = Algorithm.HMAC256(secret);
            String token = JWT.create().withIssuer("multidocs").withSubject(user.getUsername()).withExpiresAt(Instant.now().plusSeconds(EXPIRES_IN_SECONDS)).sign(algorithm);
            return token;
        } catch (JWTCreationException e) {
            throw new RuntimeException("Falha ao gerar Token");
        }

    }

    public String verifyTokenAndGetUsername(String token){
        DecodedJWT decodedJWT;
        try {
            JWTVerifier verifier = JWT.require(Algorithm.HMAC256(secret)).withIssuer("multidocs").build();
            decodedJWT = verifier.verify(removeBearerPrefix(token));
            return decodedJWT.getSubject();
        } catch (JWTVerificationException e) {
            throw new RuntimeException("Falha ao verificar token");
        }
    }

    private String removeBearerPrefix(String token) {
        if (token != null && token.startsWith("Bearer ")) {
            return token.substring(7);
        }
        return token;
    }
}
