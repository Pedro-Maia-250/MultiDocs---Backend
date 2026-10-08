package com.lunarvoid.multidocs.dtos;

import jakarta.validation.constraints.NotBlank;

public record TipoDocumentoRequestDTO(
        @NotBlank
        String nome
) {
}
