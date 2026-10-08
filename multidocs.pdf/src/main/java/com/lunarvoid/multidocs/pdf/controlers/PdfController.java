package com.lunarvoid.multidocs.pdf.controlers;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.lunarvoid.multidocs.dtos.ExceptionResponseDTO;
import com.lunarvoid.multidocs.pdf.services.PdfService;

import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.media.Content;
import io.swagger.v3.oas.annotations.media.Schema;
import io.swagger.v3.oas.annotations.responses.ApiResponse;
import io.swagger.v3.oas.annotations.responses.ApiResponses;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;

@RestController
@RequestMapping("/pdf")
@Tag(name = "PDF", description = "Endpoints responsáveis pelas operações relacionadas a arquivos PDF.")
public class PdfController {

    @Autowired
    private PdfService service;

    @Operation (
        summary = "Mescla documentos PDF",
        description = "Recebe uma lista de documentos e retorna um único arquivo PDF contendo todos os documentos na ordem informada.",
        security = @SecurityRequirement(name = "bearerAuth")
    )
    @ApiResponses ({
        @ApiResponse (
            responseCode = "200",
            description = "PDFs mesclados com sucesso",
            content = @Content(
                mediaType = "application/pdf"
            )
        ),
        @ApiResponse(
            responseCode = "404",
            description = "Um ou mais documentos não foram encontrados",
            content = @Content(
                mediaType = "application/json",
                schema = @Schema(implementation = ExceptionResponseDTO.class)
            )
        ),
        @ApiResponse(
            responseCode = "400",
            description = "Lista de documentos inválida",
            content = @Content(
                mediaType = "application/json",
                schema = @Schema(implementation = ExceptionResponseDTO.class)
            )
        )
    })
    @PostMapping (
        value = "/mesclar",
        consumes = MediaType.APPLICATION_JSON_VALUE,
        produces = MediaType.APPLICATION_PDF_VALUE
    )
    public ResponseEntity<byte[]> merge(
            @RequestBody @Valid List<String> caminhos) {

        byte[] pdf = service.merge(caminhos);

        return ResponseEntity.ok()
                .contentType(MediaType.APPLICATION_PDF)
                .body(pdf);
    }
}
