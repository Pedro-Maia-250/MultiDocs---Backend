package com.lunarvoid.multidocs.dtos;

public record TokenResponseDTO(
    String token,
    String type,
    Long expiresIn
) {}

