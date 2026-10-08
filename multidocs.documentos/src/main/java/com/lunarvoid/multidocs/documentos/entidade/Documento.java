package com.lunarvoid.multidocs.documentos.entidade;

import java.time.LocalDate;

import com.lunarvoid.multidocs.dtos.enums.TipoDocumento;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import jakarta.persistence.UniqueConstraint;

@Entity
@Table(
        name = "documento_tb",
        uniqueConstraints = {
                @UniqueConstraint(
                        name = "uk_documento_usuario_tipo",
                        columnNames = {"usuario_id", "tipo", "tipo_id"}
                )
        }
)
public class Documento {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private LocalDate data;

    @Column(nullable = false)
    private String caminhoArquivo;

    @Column(name = "usuario_id", nullable = false)
    private Long usuarioId;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private TipoDocumento tipo;

    @Column(name = "tipo_id", nullable = false)
    private Long tipoId;

    protected Documento() {
    }

    public Documento(
            LocalDate data,
            String caminhoArquivo,
            Long usuarioId,
            TipoDocumento tipo,
            Long tipoId) {

        this.data = data;
        this.caminhoArquivo = caminhoArquivo;
        this.usuarioId = usuarioId;
        this.tipo = tipo;
        this.tipoId = tipoId;
    }

    public Long getId() {
        return id;
    }

    public LocalDate getData() {
        return data;
    }

    public String getCaminhoArquivo() {
        return caminhoArquivo;
    }

    

    public void setCaminhoArquivo(String caminhoArquivo) {
        this.caminhoArquivo = caminhoArquivo;
    }

    public Long getUsuarioId() {
        return usuarioId;
    }

    public TipoDocumento getTipo() {
        return tipo;
    }

    public Long getTipoId() {
        return tipoId;
    }
}