package com.lunarvoid.multidocs.borda.services;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.HttpHeaders;
import org.springframework.stereotype.Service;
import org.springframework.web.client.RestClient;

import com.lunarvoid.multidocs.dtos.UsuarioRequestDTO;
import com.lunarvoid.multidocs.dtos.UsuarioResponseDTO;

import jakarta.servlet.http.HttpServletRequest;

@Service
public class UsuarioService {

    private final RestClient restClient;
    private final HttpServletRequest request;

    @Value ("${auth.ser.url}")
    private String serviceUrl;

    public UsuarioService(RestClient.Builder builder, HttpServletRequest request){
        this.restClient = builder.build();
        this.request = request;
    }

    public UsuarioResponseDTO findById(){
        String token = request.getHeader(HttpHeaders.AUTHORIZATION);

        return restClient.get().uri(serviceUrl + "/usuarios").header(HttpHeaders.AUTHORIZATION, token).retrieve().body(UsuarioResponseDTO.class);
    }

    public UsuarioResponseDTO insert(UsuarioRequestDTO obj){
        return restClient.post().uri(serviceUrl + "/usuarios").body(obj).retrieve().body(UsuarioResponseDTO.class);
    }
}
