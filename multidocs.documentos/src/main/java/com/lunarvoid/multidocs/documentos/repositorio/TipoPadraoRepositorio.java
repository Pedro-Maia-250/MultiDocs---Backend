package com.lunarvoid.multidocs.documentos.repositorio;

import java.util.Optional;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import com.lunarvoid.multidocs.documentos.entidade.TipoPadrao;

@Repository
public interface TipoPadraoRepositorio extends JpaRepository<TipoPadrao, Long> {

    Optional<TipoPadrao> findByNome(String nome);
}