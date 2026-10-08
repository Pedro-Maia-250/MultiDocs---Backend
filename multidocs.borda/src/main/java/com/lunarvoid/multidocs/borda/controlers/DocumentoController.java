package com.lunarvoid.multidocs.borda.controlers;

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

import com.lunarvoid.multidocs.borda.services.DocumentoService;
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
@RequestMapping ("/documentos")
@Tag (name = "Documentos", description = "Endpoints responsáveis pelo gerenciamento dos documentos dos usuários.")
public class DocumentoController {

    @Autowired 
    private DocumentoService service;

    @Operation (
        summary = "Submete um documento",
        description = "Recebe o tipo do documento e o arquivo PDF para armazenamento.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses ({
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
        )
    })
    @PostMapping(consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    public ResponseEntity<DocumentoResponseDTO> insert(
            @RequestPart ("dados") @Valid DocumentoRequestDTO dados,
            @RequestPart("arquivo") MultipartFile arquivo) {

        DocumentoResponseDTO response = service.insert(dados, arquivo);

        URI url = ServletUriComponentsBuilder
                .fromCurrentRequest()
                .path("/{id}")
                .buildAndExpand(response.id())
                .toUri();

        return ResponseEntity.created(url).body(response);
    }


    @Operation(
        summary = "Lista os documentos",
        description = "Retorna os tipos de documentos cadastrados e o documento associado a cada tipo, quando existir.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses({
        @ApiResponse(
            responseCode = "200",
            description = "Documentos listados com sucesso"
        )
    })
    @GetMapping
    public ResponseEntity<List<DocumentoListaResponseDTO>> findAll() {

        return ResponseEntity.ok(service.findAll());
    }


    @Operation(
        summary = "Cadastra novo tipo de documento",
        description = "Recebe o nome de um novo tipo de documento e retorna seus dados cadastrados.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses({
        @ApiResponse(
            responseCode = "201",
            description = "Tipo de documento criado com sucesso"
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
    @PostMapping("/tipos")
    public ResponseEntity<TipoDocumentoResponseDTO> insertTipo(
            @RequestBody @Valid TipoDocumentoRequestDTO obj) {

        TipoDocumentoResponseDTO response = service.insertTipo(obj);

        URI url = ServletUriComponentsBuilder
                .fromCurrentRequest()
                .path("")
                .buildAndExpand(response.id())
                .toUri();

        return ResponseEntity.created(url).body(response);
    }


    @Operation(
        summary = "Busca o documento físico",
        description = "Retorna o arquivo PDF correspondente ao documento informado.",
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
    @GetMapping("/{id}")
    public ResponseEntity<byte[]> findDocumento(@PathVariable Long id) {

        byte[] arquivo = service.findDocumento(id);

        return ResponseEntity.ok()
                .contentType(MediaType.APPLICATION_PDF)
                .body(arquivo);
    }
}