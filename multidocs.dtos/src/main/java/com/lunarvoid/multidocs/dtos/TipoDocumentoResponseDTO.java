package com.lunarvoid.multidocs.dtos;

import com.lunarvoid.multidocs.dtos.enums.TipoDocumento;

public record TipoDocumentoResponseDTO(
        Long id,
        String nome,
        TipoDocumento tipo,
        String url
) {
}