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
        name = "tipo_padrao_tb",
        uniqueConstraints = {
                @UniqueConstraint(
                        name = "uk_tipo_padrao_nome",
                        columnNames = {"nome"}
                )
        }
)
public class TipoPadrao {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String nome;

    @Column(nullable = false)
    private String url;

    protected TipoPadrao() {
    }

    public TipoPadrao(String nome, String url) {
        this.nome = nome;
        this.url = url;
    }

    public Long getId() {
        return id;
    }

    public String getNome() {
        return nome;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }
}