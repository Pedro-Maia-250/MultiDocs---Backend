package com.lunarvoid.multidocs.dtos;

import jakarta.validation.constraints.NotBlank;

public record UsuarioRequestDTO(
    @NotBlank String username,
    @NotBlank String password
) {}