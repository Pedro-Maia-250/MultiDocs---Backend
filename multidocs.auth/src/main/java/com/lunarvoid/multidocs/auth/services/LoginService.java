package com.lunarvoid.multidocs.auth.services;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.stereotype.Service;

import com.lunarvoid.multidocs.auth.entidade.Usuario;
import com.lunarvoid.multidocs.dtos.TokenResponseDTO;
import com.lunarvoid.multidocs.dtos.UsuarioRequestDTO;

@Service
public class LoginService {
    
    @Autowired
    private UsuarioService uSer;

    @Autowired
    private TokenService tSer;

    @Autowired
    AuthenticationManager manager;

    public TokenResponseDTO login(UsuarioRequestDTO obj){
        UsernamePasswordAuthenticationToken token = new UsernamePasswordAuthenticationToken(obj.username(), obj.password());
        Usuario usuario = (Usuario) manager.authenticate(token).getPrincipal();
        return new TokenResponseDTO(tSer.gerarToken(usuario), "Bearer", TokenService.EXPIRES_IN_SECONDS);
    }

    public TokenResponseDTO refreshToken(){
        return new TokenResponseDTO(tSer.gerarToken(uSer.getUsuarioLogado()), "Bearer", TokenService.EXPIRES_IN_SECONDS);
    }
}
