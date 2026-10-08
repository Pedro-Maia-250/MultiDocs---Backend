package com.lunarvoid.multidocs.documentos.repositorio;

import java.util.List;
import java.util.Optional;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import com.lunarvoid.multidocs.documentos.entidade.TipoPersonalizado;

@Repository
public interface TipoPersonalizadoRepositorio extends JpaRepository<TipoPersonalizado, Long> {

    List<TipoPersonalizado> findByUsuarioId(Long usuarioId);

    Optional<TipoPersonalizado> findByUsuarioIdAndNome(
            Long usuarioId,
            String nome
    );
}