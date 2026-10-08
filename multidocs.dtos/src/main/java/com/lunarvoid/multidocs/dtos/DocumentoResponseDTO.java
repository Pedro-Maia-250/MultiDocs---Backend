package com.lunarvoid.multidocs.dtos;

import java.time.LocalDate;

public record DocumentoResponseDTO(
        Long id,
        LocalDate data
) {
}
