import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import '../../models/tipo_documento.dart';
import '../../services/documento_service.dart';

class EnviarDocumentoPage extends StatefulWidget {
  final TipoDocumento tipo;

  const EnviarDocumentoPage({
    super.key,
    required this.tipo,
  });

  @override
  State<EnviarDocumentoPage> createState() =>
      _EnviarDocumentoPageState();
}

class _EnviarDocumentoPageState extends State<EnviarDocumentoPage> {
  final service = DocumentoService();

  PlatformFile? arquivo;
  bool enviando = false;

  Future<void> selecionar() async {
    final resultado = await service.selecionarPdf();

    if (resultado != null) {
      setState(() => arquivo = resultado);
    }
  }

  Future<void> enviar() async {
    if (arquivo == null) {
      _mensagem('Selecione um PDF primeiro.');
      return;
    }

    setState(() => enviando = true);

    try {
      await service.enviarDocumento(
        arquivo: arquivo!,
        tipo: widget.tipo,
      );

      if (!mounted) return;

      _mensagem('Documento enviado com sucesso.');
      Navigator.pop(context, true);
    } catch (e) {
      _mensagem('Erro ao enviar documento: $e');
    } finally {
      if (mounted) setState(() => enviando = false);
    }
  }

  void _mensagem(String texto) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(texto)),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Enviar ${widget.tipo.nome}'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            const Icon(Icons.picture_as_pdf, size: 80),
            const SizedBox(height: 24),
            Text(
              'Tipo: ${widget.tipo.nome}',
              style: Theme.of(context).textTheme.titleLarge,
            ),
            const SizedBox(height: 24),
            if (arquivo != null)
              Card(
                child: ListTile(
                  leading: const Icon(Icons.picture_as_pdf),
                  title: Text(arquivo!.name),
                  subtitle: const Text('PDF selecionado'),
                ),
              ),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton.icon(
                onPressed: enviando ? null : selecionar,
                icon: const Icon(Icons.folder_open),
                label: const Text('Selecionar PDF'),
              ),
            ),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              child: FilledButton.icon(
                onPressed: enviando ? null : enviar,
                icon: const Icon(Icons.cloud_upload),
                label: enviando
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                        ),
                      )
                    : const Text('Enviar'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
