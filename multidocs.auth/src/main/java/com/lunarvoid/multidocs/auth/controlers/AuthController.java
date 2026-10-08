package com.lunarvoid.multidocs.auth.controlers;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpHeaders;
import org.springframework.http.ResponseEntity;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.lunarvoid.multidocs.auth.services.LoginService;
import com.lunarvoid.multidocs.dtos.ExceptionResponseDTO;
import com.lunarvoid.multidocs.dtos.TokenResponseDTO;
import com.lunarvoid.multidocs.dtos.UsuarioAuthResponseDTO;
import com.lunarvoid.multidocs.dtos.UsuarioRequestDTO;

import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.media.Content;
import io.swagger.v3.oas.annotations.media.Schema;
import io.swagger.v3.oas.annotations.responses.ApiResponse;
import io.swagger.v3.oas.annotations.responses.ApiResponses;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;

@RestController 
@RequestMapping ("/auth")
@Tag (name = "Autenticação", description = "Endpoints responsáveis pela autenticação e renovação de tokens.")
public class AuthController {

    @Autowired 
    private LoginService service;

    @Operation (
        summary = "Realiza login",
        description = "Autentica o usuário e retorna um token JWT no padrão Bearer."
    )
    @ApiResponses ({
        @ApiResponse (
            responseCode = "200",
            description = "Login realizado com sucesso"
        ),
        @ApiResponse(
            responseCode = "400",
            description = "Dados de login inválidos",
            content = @Content(mediaType = "application/json", schema = @Schema(implementation = ExceptionResponseDTO.class))
        )
    })
    @PostMapping 
    public ResponseEntity<TokenResponseDTO> login(
            @RequestBody @Valid UsuarioRequestDTO dto) {

        return ResponseEntity.ok(service.login(dto));
    }

    @Operation(
        summary = "Renova o token",
        description = "Gera um novo token JWT utilizando o token enviado no cabeçalho Authorization.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses({
        @ApiResponse(
            responseCode = "200",
            description = "Token renovado com sucesso"
        ),
        @ApiResponse(
            responseCode = "401",
            description = "Token inválido ou expirado",
            content = @Content(mediaType = "application/json", schema = @Schema(implementation = ExceptionResponseDTO.class))
        )
    })
    @PostMapping("/r")
    public ResponseEntity<TokenResponseDTO> refreshToken() {
        return ResponseEntity.ok(service.refreshToken());
    }

    @Operation(
        summary = "Valida o token",
        description = "Confirma que o JWT enviado no Authorization e valido e retorna a identidade autenticada.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses({
        @ApiResponse(
            responseCode = "200",
            description = "Token valido"
        ),
        @ApiResponse(
            responseCode = "401",
            description = "Token invalido ou expirado",
            content = @Content(mediaType = "application/json", schema = @Schema(implementation = ExceptionResponseDTO.class))
        )
    })
    @GetMapping("/verify")
    public ResponseEntity<UsuarioAuthResponseDTO> verifyToken(
            @AuthenticationPrincipal UserDetails usuario) {

        UsuarioAuthResponseDTO response = new UsuarioAuthResponseDTO(
                usuario.getUsername(),
                usuario.getAuthorities()
                        .stream()
                        .map(GrantedAuthority::getAuthority)
                        .toList()
        );

        return ResponseEntity.ok()
                .header(HttpHeaders.CACHE_CONTROL, "no-store")
                .body(response);
    }
}
