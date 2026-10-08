package com.lunarvoid.multidocs.dtos;

public record DocumentoListaResponseDTO(
        TipoDocumentoResponseDTO tipo,
        DocumentoResponseDTO documento
) {}