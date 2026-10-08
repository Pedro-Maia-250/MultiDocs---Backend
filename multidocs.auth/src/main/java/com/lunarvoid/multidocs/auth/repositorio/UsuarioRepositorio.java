package com.lunarvoid.multidocs.auth.repositorio;
import java.util.Optional;

import org.springframework.data.jpa.repository.JpaRepository;
import com.lunarvoid.multidocs.auth.entidade.Usuario;



public interface UsuarioRepositorio extends JpaRepository<Usuario ,Long> {
    public Optional<Usuario> findByUsername(String username);
}
