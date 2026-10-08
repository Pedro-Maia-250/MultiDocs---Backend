package com.lunarvoid.multidocs.documentos.controlers;

import java.net.URI;
import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestPart;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.multipart.MultipartFile;
import org.springframework.web.servlet.support.ServletUriComponentsBuilder;

import com.lunarvoid.multidocs.documentos.services.DocumentoService;
import com.lunarvoid.multidocs.dtos.DocumentoListaResponseDTO;
import com.lunarvoid.multidocs.dtos.DocumentoRequestDTO;
import com.lunarvoid.multidocs.dtos.DocumentoResponseDTO;
import com.lunarvoid.multidocs.dtos.ExceptionResponseDTO;
import com.lunarvoid.multidocs.dtos.TipoDocumentoRequestDTO;
import com.lunarvoid.multidocs.dtos.TipoDocumentoResponseDTO;

import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.media.Content;
import io.swagger.v3.oas.annotations.media.Schema;
import io.swagger.v3.oas.annotations.responses.ApiResponse;
import io.swagger.v3.oas.annotations.responses.ApiResponses;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;

@RestController
@RequestMapping("/documentos")
@Tag(
    name = "Documentos",
    description = "Endpoints responsáveis pelo gerenciamento dos documentos dos usuários."
)
public class DocumentoController {

    @Autowired
    private DocumentoService service;


    @Operation(
        summary = "Submete um documento",
        description = "Recebe o ID do usuário, o tipo do documento e o arquivo PDF para armazenamento.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses({
        @ApiResponse(
            responseCode = "201",
            description = "Documento submetido com sucesso"
        ),
        @ApiResponse(
            responseCode = "400",
            description = "Dados do documento inválidos",
            content = @Content(
                mediaType = "application/json",
                schema = @Schema(implementation = ExceptionResponseDTO.class)
            )
        ),
        @ApiResponse(
            responseCode = "404",
            description = "Tipo de documento não encontrado",
            content = @Content(
                mediaType = "application/json",
                schema = @Schema(implementation = ExceptionResponseDTO.class)
            )
        ),
        @ApiResponse(
            responseCode = "409",
            description = "O usuário já possui um documento desse tipo",
            content = @Content(
                mediaType = "application/json",
                schema = @Schema(implementation = ExceptionResponseDTO.class)
            )
        )
    })
    @PostMapping(
        value = "/{usuarioId}",
        consumes = MediaType.MULTIPART_FORM_DATA_VALUE
    )
    public ResponseEntity<DocumentoResponseDTO> insert(
            @PathVariable Long usuarioId,
            @RequestPart("dados") @Valid DocumentoRequestDTO dados,
            @RequestPart("arquivo") MultipartFile arquivo) {

        DocumentoResponseDTO response =
                service.insert(dados, arquivo, usuarioId);

        URI url = ServletUriComponentsBuilder
                .fromCurrentRequest()
                .path("/{id}")
                .buildAndExpand(response.id())
                .toUri();

        return ResponseEntity
                .created(url)
                .body(response);
    }


    @Operation(
        summary = "Lista os documentos",
        description = "Retorna os documentos pertencentes ao usuário informado.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses({
        @ApiResponse(
            responseCode = "200",
            description = "Documentos listados com sucesso"
        )
    })
    @GetMapping("/{usuarioId}")
    public ResponseEntity<List<DocumentoListaResponseDTO>> findAll(
            @PathVariable Long usuarioId) {

        return ResponseEntity.ok(
            service.findAll(usuarioId)
        );
    }


    @Operation(
        summary = "Cadastra novo tipo de documento",
        description = "Recebe o ID do usuário e o nome de um novo tipo de documento personalizado.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses({
        @ApiResponse(
            responseCode = "201",
            description = "Tipo de documento criado com sucesso"
        ),
        @ApiResponse(
            responseCode = "400",
            description = "Dados inválidos",
            content = @Content(
                mediaType = "application/json",
                schema = @Schema(implementation = ExceptionResponseDTO.class)
            )
        ),
        @ApiResponse(
            responseCode = "409",
            description = "Esse tipo de documento já existe",
            content = @Content(
                mediaType = "application/json",
                schema = @Schema(implementation = ExceptionResponseDTO.class)
            )
        )
    })
    @PostMapping("/tipos/{usuarioId}")
    public ResponseEntity<TipoDocumentoResponseDTO> insertTipo(
            @PathVariable Long usuarioId,
            @RequestBody @Valid TipoDocumentoRequestDTO obj) {

        TipoDocumentoResponseDTO response =
                service.insertTipo(obj, usuarioId);

        URI url = ServletUriComponentsBuilder
                .fromCurrentRequest()
                .path("/{id}")
                .buildAndExpand(response.id())
                .toUri();

        return ResponseEntity
                .created(url)
                .body(response);
    }


    @Operation(
        summary = "Busca o documento físico",
        description = "Retorna o arquivo PDF correspondente ao documento informado para o usuário.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses({
        @ApiResponse(
            responseCode = "200",
            description = "Documento encontrado",
            content = @Content(
                mediaType = "application/pdf"
            )
        ),
        @ApiResponse(
            responseCode = "404",
            description = "Documento não encontrado",
            content = @Content(
                mediaType = "application/json",
                schema = @Schema(implementation = ExceptionResponseDTO.class)
            )
        )
    })
    @GetMapping("/{id}/{userId}")
    public ResponseEntity<byte[]> findDocumento(
            @PathVariable Long id,
            @PathVariable Long userId) {

        byte[] arquivo =
                service.findDocumento(id, userId);

        return ResponseEntity
                .ok()
                .contentType(MediaType.APPLICATION_PDF)
                .body(arquivo);
    }

    @PostMapping(value = "/names/{usuarioId}")
    public ResponseEntity<List<String>> names(@RequestBody List<DocumentoRequestDTO> obj, @PathVariable Long usuarioId){
        return ResponseEntity.ok().body(service.names(obj, usuarioId));
    }
}