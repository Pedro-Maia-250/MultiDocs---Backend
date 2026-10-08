package com.lunarvoid.multidocs.dtos;

import java.util.List;

public record UsuarioAuthResponseDTO(
        String username,
        List<String> roles
) {
}