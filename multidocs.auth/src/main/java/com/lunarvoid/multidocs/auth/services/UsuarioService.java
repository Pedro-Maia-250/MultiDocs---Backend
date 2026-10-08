package com.lunarvoid.multidocs.auth.services;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

import com.lunarvoid.multidocs.auth.entidade.Usuario;
import com.lunarvoid.multidocs.auth.repositorio.UsuarioRepositorio;
import com.lunarvoid.multidocs.dtos.UsuarioRequestDTO;
import com.lunarvoid.multidocs.dtos.UsuarioResponseDTO;

@Service
public class UsuarioService implements UserDetailsService {

    @Autowired
    private UsuarioRepositorio repositorio;

    @Autowired
    private PasswordEncoder passwordEncoder;

    public UsuarioResponseDTO findDados() {

        Usuario usuario = getUsuarioLogado();

        return new UsuarioResponseDTO(
                usuario.getId(),
                usuario.getUsername()
        );
    }

    public UsuarioResponseDTO insert(UsuarioRequestDTO obj) {

        if (!repositorio.findByUsername(obj.username()).isPresent()) {

            Usuario user = repositorio.save(
                    new Usuario(obj.username(), passwordEncoder.encode(obj.password()))
            );

            return new UsuarioResponseDTO(
                    user.getId(),
                    user.getUsername()
            );

        } else {
            throw new RuntimeException("Nome de usuario já existente");
        }
    }

    public Usuario getUsuarioLogado() {

        Authentication auth =
                SecurityContextHolder
                        .getContext()
                        .getAuthentication();

        UserDetails user = (UserDetails) auth.getPrincipal();

        return repositorio
                .findByUsername(user.getUsername())
                .orElseThrow(() ->
                        new RuntimeException(
                                "Falha ao pegar usuario do contexto"
                        )
                );
    }

    @Override
    public UserDetails loadUserByUsername(String username)
            throws UsernameNotFoundException {

        return repositorio
                .findByUsername(username)
                .orElseThrow(() ->
                        new UsernameNotFoundException(
                                "Usuário não encontrado"
                        )
                );
    }
}
