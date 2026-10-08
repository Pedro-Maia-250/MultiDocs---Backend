package com.lunarvoid.multidocs.borda.services;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.core.ParameterizedTypeReference;
import org.springframework.http.HttpHeaders;
import org.springframework.http.MediaType;
import org.springframework.stereotype.Service;
import org.springframework.web.client.RestClient;

import com.lunarvoid.multidocs.dtos.DocumentoRequestDTO;
import com.lunarvoid.multidocs.dtos.UsuarioResponseDTO;

import jakarta.servlet.http.HttpServletRequest;

@Service
public class PdfService {

    @Autowired
    UsuarioService usuarioService;

    private final RestClient restClient;
    private final HttpServletRequest request;

    @Value(value = "${pdf.ser.url}")
    private String pdfUrl;

    @Value("${documentos.ser.url}")
    private String documentoUrl;

    public PdfService(RestClient.Builder builder, HttpServletRequest request){
        this.restClient = builder.build();
        this.request = request;
    }
    
    public byte[] merge(List<DocumentoRequestDTO> documentos) {

    String token = request.getHeader(HttpHeaders.AUTHORIZATION);

    Long usuarioId = getUsuarioId();

    List<String> caminhos = restClient.post()
            .uri(
                documentoUrl + "/documentos/names/{usuarioId}",
                usuarioId
            )
            .header(HttpHeaders.AUTHORIZATION, token)
            .body(documentos)
            .retrieve()
            .body(new ParameterizedTypeReference<List<String>>() {});

    return restClient.post()
            .uri(pdfUrl + "/pdf/mesclar")
            .header(HttpHeaders.AUTHORIZATION, token)
            .contentType(MediaType.APPLICATION_JSON)
            .body(caminhos)
            .retrieve()
            .body(byte[].class);
}

    public Long getUsuarioId(){
        UsuarioResponseDTO user = usuarioService.findById();
        return user.id();
    }
}
