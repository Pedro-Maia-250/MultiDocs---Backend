package com.lunarvoid.multidocs.documentos.entidade;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import jakarta.persistence.UniqueConstraint;

@Entity
@Table(
        name = "tipo_personalizado_tb",
        uniqueConstraints = {
                @UniqueConstraint(
                        name = "uk_tipo_personalizado_usuario_nome",
                        columnNames = {"usuario_id", "nome"}
                )
        }
)
public class TipoPersonalizado {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String nome;

    @Column(name = "usuario_id", nullable = false)
    private Long usuarioId;

    protected TipoPersonalizado() {
    }

    public TipoPersonalizado(String nome, Long usuarioId) {
        this.nome = nome;
        this.usuarioId = usuarioId;
    }

    public Long getId() {
        return id;
    }

    public String getNome() {
        return nome;
    }

    public Long getUsuarioId() {
        return usuarioId;
    }
}