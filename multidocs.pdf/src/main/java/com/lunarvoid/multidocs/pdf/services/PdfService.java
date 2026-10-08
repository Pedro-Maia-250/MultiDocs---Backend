package com.lunarvoid.multidocs.pdf.services;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.List;

import org.apache.pdfbox.Loader;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.PDPage;
import org.springframework.stereotype.Service;

@Service
public class PdfService {
    
    public byte[] merge(List<String> caminhos) {

    try (PDDocument documentoFinal = new PDDocument();
         ByteArrayOutputStream output = new ByteArrayOutputStream()) {

        for (String caminho : caminhos) {

            Path arquivo = Paths.get(caminho);

            if (!Files.exists(arquivo)) {
                throw new RuntimeException(
                    "Arquivo não encontrado: " + caminho
                );
            }

            try (PDDocument documento = Loader.loadPDF(arquivo.toFile())) {

                for (PDPage pagina : documento.getPages()) {
                    documentoFinal.importPage(pagina);
                }
            }
        }

        documentoFinal.save(output);

        return output.toByteArray();

    } catch (IOException e) {
        throw new RuntimeException(
            "Erro ao mesclar os documentos PDF",
            e
        );
    }
}
}
