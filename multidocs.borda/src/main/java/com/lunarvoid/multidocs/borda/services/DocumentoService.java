package com.lunarvoid.multidocs.borda.services;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.core.ParameterizedTypeReference;
import org.springframework.core.io.ByteArrayResource;
import org.springframework.http.HttpEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.MediaType;
import org.springframework.stereotype.Service;
import org.springframework.util.LinkedMultiValueMap;
import org.springframework.util.MultiValueMap;
import org.springframework.web.client.RestClient;
import org.springframework.web.multipart.MultipartFile;

import com.lunarvoid.multidocs.dtos.DocumentoListaResponseDTO;
import com.lunarvoid.multidocs.dtos.DocumentoRequestDTO;
import com.lunarvoid.multidocs.dtos.DocumentoResponseDTO;
import com.lunarvoid.multidocs.dtos.TipoDocumentoRequestDTO;
import com.lunarvoid.multidocs.dtos.TipoDocumentoResponseDTO;
import com.lunarvoid.multidocs.dtos.UsuarioResponseDTO;

import jakarta.servlet.http.HttpServletRequest;

@Service
public class DocumentoService {

    private final RestClient restClient;
    private final HttpServletRequest request;

    @Autowired
    UsuarioService usuarioService;

    @Value("${documentos.ser.url}")
    private String serviceUrl;

    public DocumentoService(
            RestClient.Builder builder,
            HttpServletRequest request) {

        this.restClient = builder.build();
        this.request = request;
    }


    public DocumentoResponseDTO insert(
            DocumentoRequestDTO dados,
            MultipartFile arquivo) {

        String token =
                request.getHeader(HttpHeaders.AUTHORIZATION);

        try {

            ByteArrayResource arquivoResource =
                    new ByteArrayResource(arquivo.getBytes()) {

                        @Override
                        public String getFilename() {
                            return arquivo.getOriginalFilename();
                        }
                    };

            HttpHeaders dadosHeaders = new HttpHeaders();
            dadosHeaders.setContentType(MediaType.APPLICATION_JSON);

            HttpEntity<DocumentoRequestDTO> dadosPart =
                    new HttpEntity<>(dados, dadosHeaders);

            MultiValueMap<String, Object> body =
                    new LinkedMultiValueMap<>();

            body.add("dados", dadosPart);
            body.add("arquivo", arquivoResource);

            return restClient
                    .post()
                    .uri(serviceUrl + "/documentos/{usuarioId}", getUsuarioId())
                    .header(HttpHeaders.AUTHORIZATION, token)
                    .contentType(MediaType.MULTIPART_FORM_DATA)
                    .body(body)
                    .retrieve()
                    .body(DocumentoResponseDTO.class);

        } catch (Exception e) {

            throw new RuntimeException(
                    "Erro ao enviar documento para o serviço de documentos",
                    e);
        }
    }


    public List<DocumentoListaResponseDTO> findAll() {

        String token =
                request.getHeader(HttpHeaders.AUTHORIZATION);

        return restClient
                .get()
                .uri(serviceUrl + "/documentos/{usuarioId}", getUsuarioId())
                .header(HttpHeaders.AUTHORIZATION, token)
                .retrieve()
                .body(
                    new ParameterizedTypeReference<
                        List<DocumentoListaResponseDTO>>() {}
                );
    }


    public TipoDocumentoResponseDTO insertTipo(
            TipoDocumentoRequestDTO obj) {


        String token =
                request.getHeader(HttpHeaders.AUTHORIZATION);

        return restClient
                .post()
                .uri(serviceUrl + "/documentos/tipos/{usuarioId}", getUsuarioId())
                .header(HttpHeaders.AUTHORIZATION, token)
                .contentType(MediaType.APPLICATION_JSON)
                .body(obj)
                .retrieve()
                .body(TipoDocumentoResponseDTO.class);
    }


    public List<TipoDocumentoResponseDTO> findTipos() {

        String token =
                request.getHeader(HttpHeaders.AUTHORIZATION);

        return restClient
                .get()
                .uri(serviceUrl + "/documentos/tipos/{usuarioId}", getUsuarioId() )
                .header(HttpHeaders.AUTHORIZATION, token)
                .retrieve()
                .body(
                    new ParameterizedTypeReference<
                        List<TipoDocumentoResponseDTO>>() {}
                );
    }


    public byte[] findDocumento(Long id) {


        String token =
                request.getHeader(HttpHeaders.AUTHORIZATION);

        return restClient
                .get()
                .uri(
                    serviceUrl + "/documentos/{id}/{usuarioId}",
                    id,
                    getUsuarioId()
                )
                .header(HttpHeaders.AUTHORIZATION, token)
                .retrieve()
                .body(byte[].class);
    }

    public Long getUsuarioId(){
        UsuarioResponseDTO user = usuarioService.findById();
        return user.id();
    }
}