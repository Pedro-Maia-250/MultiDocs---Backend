package com.lunarvoid.multidocs.dtos;

import com.lunarvoid.multidocs.dtos.enums.TipoDocumento;

import jakarta.validation.constraints.NotNull;

public record DocumentoRequestDTO(
        @NotNull 
        TipoDocumento tipo,

        @NotNull
        Long tipoId
) {
}