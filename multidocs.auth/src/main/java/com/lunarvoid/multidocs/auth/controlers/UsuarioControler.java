package com.lunarvoid.multidocs.auth.controlers;

import java.net.URI;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.servlet.support.ServletUriComponentsBuilder;

import com.lunarvoid.multidocs.auth.services.UsuarioService;
import com.lunarvoid.multidocs.dtos.ExceptionResponseDTO;
import com.lunarvoid.multidocs.dtos.UsuarioRequestDTO;
import com.lunarvoid.multidocs.dtos.UsuarioResponseDTO;

import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.media.Content;
import io.swagger.v3.oas.annotations.media.Schema;
import io.swagger.v3.oas.annotations.responses.ApiResponse;
import io.swagger.v3.oas.annotations.responses.ApiResponses;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;

@RestController
@RequestMapping(value = "/usuarios")
@Tag (name = "Usuarios", description = "Endpoints responsáveis pelo gerenciamento dos usuários.")
public class UsuarioControler {

    @Autowired
    UsuarioService service;

    @Operation (
        summary = "Busca dados do Usuario pelo id",
        description = "Recebe id do usuario e retorna UsuarioResponseDTO",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses ({
        @ApiResponse (
            responseCode = "200",
            description = "Busca realizada com sucesso"
        ),
        @ApiResponse(
            responseCode = "404",
            description = "Usuario não existe",
            content = @Content(mediaType = "application/json", schema = @Schema(implementation = ExceptionResponseDTO.class))
        )
    })
    @GetMapping()
    public ResponseEntity<UsuarioResponseDTO> findDados(){
        return ResponseEntity.ok().body(service.findDados());
    }

    @Operation (
        summary = "Cadastra novo usuario",
        description = "Recebe dados do usuario e retorna UsuarioResponseDTO"
    )
    @ApiResponses ({
        @ApiResponse (
            responseCode = "201",
            description = "Usuario criado com sucesso"
        ),
        @ApiResponse(
            responseCode = "409",
            description = "Esse email de usuario já existe",
            content = @Content(mediaType = "application/json", schema = @Schema(implementation = ExceptionResponseDTO.class))
        )
    })
    @PostMapping
    public ResponseEntity<UsuarioResponseDTO> insert(@RequestBody @Valid UsuarioRequestDTO obj){
        UsuarioResponseDTO response = service.insert(obj);
        URI url = ServletUriComponentsBuilder.fromCurrentRequest().path("/{id}").buildAndExpand(response.id()).toUri();
        return ResponseEntity.created(url).body(response);
    }
}
