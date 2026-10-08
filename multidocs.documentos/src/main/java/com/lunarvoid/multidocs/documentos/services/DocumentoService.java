package com.lunarvoid.multidocs.documentos.services;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;

import com.lunarvoid.multidocs.documentos.entidade.Documento;
import com.lunarvoid.multidocs.documentos.entidade.TipoPadrao;
import com.lunarvoid.multidocs.documentos.entidade.TipoPersonalizado;
import com.lunarvoid.multidocs.documentos.repositorio.DocumentoRepositorio;
import com.lunarvoid.multidocs.documentos.repositorio.TipoPadraoRepositorio;
import com.lunarvoid.multidocs.documentos.repositorio.TipoPersonalizadoRepositorio;
import com.lunarvoid.multidocs.dtos.DocumentoListaResponseDTO;
import com.lunarvoid.multidocs.dtos.DocumentoRequestDTO;
import com.lunarvoid.multidocs.dtos.DocumentoResponseDTO;
import com.lunarvoid.multidocs.dtos.TipoDocumentoRequestDTO;
import com.lunarvoid.multidocs.dtos.TipoDocumentoResponseDTO;
import com.lunarvoid.multidocs.dtos.enums.TipoDocumento;

@Service
public class DocumentoService {

    @Value("${documentos.diretorio}")
    private String dir;

    @Autowired
    private DocumentoRepositorio documentoRepositorio;

    @Autowired
    private TipoPadraoRepositorio tipoPadraoRepositorio;

    @Autowired
    private TipoPersonalizadoRepositorio tipoPersonalizadoRepositorio;

    public DocumentoResponseDTO insert(
            DocumentoRequestDTO dados,
            MultipartFile arquivo,
            Long usuarioId) {

        if (arquivo == null || arquivo.isEmpty()) {
            throw new RuntimeException("Arquivo não enviado");
        }

        if (!"application/pdf".equals(arquivo.getContentType())) {
            throw new RuntimeException("O arquivo deve ser um PDF");
        }

        if (documentoRepositorio
                .findByUsuarioIdAndTipoAndTipoId(
                        usuarioId,
                        dados.tipo(),
                        dados.tipoId())
                .isPresent()) {

            throw new RuntimeException(
                    "O usuário já possui um documento desse tipo");
        }

        validarTipo(dados, usuarioId);

        try {

            Path diretorio = Paths.get(dir);
            Files.createDirectories(diretorio);

            // Primeiro salva para gerar o ID
            Documento documento = new Documento(
                    LocalDate.now(),
                    "",
                    usuarioId,
                    dados.tipo(),
                    dados.tipoId()
            );

            documento = documentoRepositorio.save(documento);

            // Agora podemos usar o ID
            String nomeArquivo = documento.getId() + ".pdf";

            Path caminhoArquivo = diretorio.resolve(nomeArquivo);

            arquivo.transferTo(caminhoArquivo);

            // Atualiza o documento já persistido
            documento.setCaminhoArquivo(caminhoArquivo.toString());

            documentoRepositorio.save(documento);

            return new DocumentoResponseDTO(
                    documento.getId(),
                    documento.getData()
            );

        } catch (IOException e) {

            throw new RuntimeException(
                    "Erro ao salvar o documento", e);
        }
    }

    private void validarTipo(
            DocumentoRequestDTO dados,
            Long usuarioId) {

        if (dados.tipo() == TipoDocumento.PADRAO) {

            if (!tipoPadraoRepositorio
                    .existsById(dados.tipoId())) {

                throw new RuntimeException(
                        "Tipo padrão não encontrado");
            }

            return;
        }

        if (dados.tipo() == TipoDocumento.PERSONALIZADO) {

            TipoPersonalizado tipo =
                    tipoPersonalizadoRepositorio
                            .findById(dados.tipoId())
                            .orElseThrow(() ->
                                    new RuntimeException(
                                            "Tipo personalizado não encontrado"));

            if (!tipo.getUsuarioId().equals(usuarioId)) {

                throw new RuntimeException(
                        "O tipo personalizado não pertence ao usuário");
            }

            return;
        }

        throw new RuntimeException("Tipo de documento inválido");
    }

    public List<DocumentoListaResponseDTO> findAll(Long usuarioId) {

        List<DocumentoListaResponseDTO> resposta =
                new ArrayList<>();

        List<Documento> documentos =
                documentoRepositorio.findByUsuarioId(usuarioId);

        List<TipoPadrao> tiposPadrao =
                tipoPadraoRepositorio.findAll();

        List<TipoPersonalizado> tiposPersonalizados =
                tipoPersonalizadoRepositorio.findByUsuarioId(usuarioId);

        for (TipoPadrao tipo : tiposPadrao) {

            Documento documento =
                    encontrarDocumento(
                            documentos,
                            TipoDocumento.PADRAO,
                            tipo.getId());

            resposta.add(
                    new DocumentoListaResponseDTO(
                            new TipoDocumentoResponseDTO(
                                    tipo.getId(),
                                    tipo.getNome(),
                                    TipoDocumento.PADRAO,
                                    tipo.getUrl()
                            ),
                            converterDocumento(documento)
                    )
            );
        }

        for (TipoPersonalizado tipo : tiposPersonalizados) {

            Documento documento =
                    encontrarDocumento(
                            documentos,
                            TipoDocumento.PERSONALIZADO,
                            tipo.getId());

            resposta.add(
                    new DocumentoListaResponseDTO(
                            new TipoDocumentoResponseDTO(
                                    tipo.getId(),
                                    tipo.getNome(),
                                    TipoDocumento.PERSONALIZADO,
                                    null
                            ),
                            converterDocumento(documento)
                    )
            );
        }

        return resposta;
    }

    private Documento encontrarDocumento(
            List<Documento> documentos,
            TipoDocumento tipo,
            Long tipoId) {

        return documentos.stream()
                .filter(documento ->
                        documento.getTipo() == tipo
                                && documento.getTipoId().equals(tipoId))
                .findFirst()
                .orElse(null);
    }

    private DocumentoResponseDTO converterDocumento(
            Documento documento) {

        if (documento == null) {
            return null;
        }

        return new DocumentoResponseDTO(
                documento.getId(),
                documento.getData()
        );
    }

    public TipoDocumentoResponseDTO insertTipo(
        TipoDocumentoRequestDTO obj,
        Long usuarioId) {

        if (tipoPersonalizadoRepositorio
                .findByUsuarioIdAndNome(
                        usuarioId,
                        obj.nome())
                .isPresent()) {

            throw new RuntimeException(
                    "Nome de tipo personalizado já existente");
        }

        TipoPersonalizado tipoPersonalizado =
                tipoPersonalizadoRepositorio.save(
                        new TipoPersonalizado(
                                obj.nome(),
                                usuarioId));

        return new TipoDocumentoResponseDTO(
                tipoPersonalizado.getId(),
                tipoPersonalizado.getNome(),
                TipoDocumento.PERSONALIZADO,
                null
        );
    }

    public byte[] findDocumento(Long id, Long userId) {

        Documento documento =
                documentoRepositorio.findByUsuarioIdAndId(userId, id)
                        .orElseThrow(() ->
                                new RuntimeException(
                                        "Documento não encontrado"));

        try {

            Path caminho =
                    Paths.get(documento.getCaminhoArquivo());

            return Files.readAllBytes(caminho);

        } catch (IOException e) {

            throw new RuntimeException(
                    "Erro ao ler o documento do disco", e);
        }
    }

    public List<String> names(List<DocumentoRequestDTO> obj, Long usuarioId){
        List<String> names = new ArrayList<>();

        for (DocumentoRequestDTO d : obj) {
                names.add(documentoRepositorio.findByUsuarioIdAndTipoAndTipoId(usuarioId, d.tipo(), d.tipoId()).orElseThrow(() -> new RuntimeException("falha ao mesclar documentos")).getCaminhoArquivo());
        }
        
        return names;
    }
}