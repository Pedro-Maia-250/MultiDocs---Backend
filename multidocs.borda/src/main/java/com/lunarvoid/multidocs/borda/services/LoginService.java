package com.lunarvoid.multidocs.borda.services;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.HttpHeaders;
import org.springframework.stereotype.Service;
import org.springframework.web.client.RestClient;

import com.lunarvoid.multidocs.dtos.TokenResponseDTO;
import com.lunarvoid.multidocs.dtos.UsuarioRequestDTO;

import jakarta.servlet.http.HttpServletRequest;

@Service 
public class LoginService {

    private final RestClient restClient;
    private final HttpServletRequest request;

    @Value ("${auth.ser.url}")
    private String serviceUrl;

    public LoginService(RestClient.Builder builder, HttpServletRequest request) {
        this.restClient = builder.build();
        this.request = request;
    }

    public TokenResponseDTO login(UsuarioRequestDTO obj) {

        return restClient
                .post()
                .uri(serviceUrl + "/auth")
                .body(obj)
                .retrieve()
                .body(TokenResponseDTO.class);
    }

    public TokenResponseDTO refreshToken() {

        String token = request.getHeader(HttpHeaders.AUTHORIZATION);

        return restClient
                .post()
                .uri(serviceUrl + "/auth/r")
                .header(HttpHeaders.AUTHORIZATION, token)
                .retrieve()
                .body(TokenResponseDTO.class);
    }
}
