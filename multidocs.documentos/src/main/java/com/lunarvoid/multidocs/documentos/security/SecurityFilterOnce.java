package com.lunarvoid.multidocs.documentos.security;

import java.io.IOException;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.HttpHeaders;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.stereotype.Component;
import org.springframework.web.client.RestClient;
import org.springframework.web.filter.OncePerRequestFilter;

import com.lunarvoid.multidocs.dtos.UsuarioAuthResponseDTO;

import jakarta.servlet.FilterChain;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

@Component
public class SecurityFilterOnce extends OncePerRequestFilter {

    @Value("${auth.ser.url}")
    private String serviceUrl;

    private final RestClient restClient;

    public SecurityFilterOnce(RestClient.Builder builder) {
        this.restClient = builder.build();
    }

    @Override
    protected boolean shouldNotFilter(HttpServletRequest request) throws ServletException {

        String method = request.getMethod();

        if ("OPTIONS".equalsIgnoreCase(method)) {
            return true;
        }

        return false;
    }

    @Override
    protected void doFilterInternal(
            HttpServletRequest request,
            HttpServletResponse response,
            FilterChain filterChain)
            throws ServletException, IOException {

        String token = request.getHeader(HttpHeaders.AUTHORIZATION);

        if (token != null && token.startsWith("Bearer ")) {

            try {

                UsuarioAuthResponseDTO usuario = restClient
                        .get()
                        .uri(serviceUrl + "/auth/verify")
                        .header(HttpHeaders.AUTHORIZATION, token)
                        .retrieve()
                        .body(UsuarioAuthResponseDTO.class);

                UsernamePasswordAuthenticationToken auth =
                        new UsernamePasswordAuthenticationToken(
                                usuario.username(),
                                null,
                                usuario.roles()
                                        .stream()
                                        .map(SimpleGrantedAuthority::new)
                                        .toList()
                        );
                auth.setDetails(usuario);

                SecurityContextHolder
                        .getContext()
                        .setAuthentication(auth);

            } catch (Exception e) {

                response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
                return;
            }
        }

        filterChain.doFilter(request, response);
    }
}
