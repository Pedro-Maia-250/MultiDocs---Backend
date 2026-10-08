package com.lunarvoid.multidocs.documentos.repositorio;

import java.util.List;
import java.util.Optional;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import com.lunarvoid.multidocs.documentos.entidade.Documento;
import com.lunarvoid.multidocs.dtos.enums.TipoDocumento;

@Repository
public interface DocumentoRepositorio extends JpaRepository<Documento, Long> {

    List<Documento> findByUsuarioId(Long usuarioId);

    Optional<Documento> findByUsuarioIdAndId(Long usuarioId, Long id);

    Optional<Documento> findByUsuarioIdAndTipoAndTipoId(
            Long usuarioId,
            TipoDocumento tipo,
            Long tipoId
    );
}

