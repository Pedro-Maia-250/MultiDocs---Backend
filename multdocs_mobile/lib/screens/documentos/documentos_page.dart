import 'package:flutter/material.dart';
import '../../models/documento.dart';
import '../../models/tipo_documento.dart';
import '../../services/documento_service.dart';
import '../../utils/file_utils.dart';
import 'enviar_documento_page.dart';

class DocumentosPage extends StatefulWidget {
  final List<TipoDocumento> tipos;
  final Future<void> Function() onAtualizar;

  const DocumentosPage({
    super.key,
    required this.tipos,
    required this.onAtualizar,
  });

  @override
  State<DocumentosPage> createState() => _DocumentosPageState();
}

class _DocumentosPageState extends State<DocumentosPage> {
  final service = DocumentoService();

  List<DocumentoLista> documentos = [];
  final Set<int> selecionados = {};
  bool carregando = true;
  bool processando = false;

  @override
  void initState() {
    super.initState();
    carregarDocumentos();
  }

  @override
  void didUpdateWidget(covariant DocumentosPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.tipos.length != widget.tipos.length) {
      carregarDocumentos();
    }
  }

  Future<void> carregarDocumentos() async {
    try {
      final resultado = await service.listarDocumentos();

      if (mounted) {
        setState(() {
          documentos = resultado;
          carregando = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => carregando = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Erro: $e')),
        );
      }
    }
  }

  Future<void> enviar(TipoDocumento tipo) async {
    final resultado = await Navigator.push<bool>(
      context,
      MaterialPageRoute(
        builder: (_) => EnviarDocumentoPage(tipo: tipo),
      ),
    );

    if (resultado == true) {
      await carregarDocumentos();
    }
  }

  Future<void> abrirDocumento(DocumentoLista item) async {
    if (item.documento == null) return;

    try {
      final bytes = await service.baixarDocumento(
        item.documento!.id,
      );

      await FileUtils.abrirPdf(
        bytes,
        '${item.tipo.nome}_${item.documento!.id}.pdf',
      );
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Erro ao abrir PDF: $e')),
        );
      }
    }
  }

  Future<void> mesclar() async {
    final itens = documentos
        .where(
          (item) =>
              item.documento != null &&
              selecionados.contains(item.documento!.id),
        )
        .toList();

    if (itens.length < 2) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Selecione pelo menos 2 documentos.'),
        ),
      );
      return;
    }

    setState(() => processando = true);

    try {
      final bytes = await service.mesclarDocumentos(itens);

      await FileUtils.abrirPdf(
        bytes,
        'MultiDocs_mesclado.pdf',
      );

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('PDF mesclado com sucesso.'),
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Erro ao mesclar: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => processando = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (carregando) {
      return const Center(child: CircularProgressIndicator());
    }

    return Column(
      children: [
        Expanded(
          child: RefreshIndicator(
            onRefresh: carregarDocumentos,
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                const Text(
                  'Meus documentos',
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 16),
                ...widget.tipos.map((tipo) {
                  final item = documentos.where(
                    (doc) => doc.tipo.id == tipo.id,
                  ).firstOrNull;

                  final temDocumento = item?.documento != null;

                  return Card(
                    child: ListTile(
                      leading: const Icon(Icons.folder),
                      title: Text(tipo.nome),
                      subtitle: Text(
                        temDocumento
                            ? 'Documento cadastrado'
                            : 'Nenhum documento enviado',
                      ),
                      trailing: temDocumento
                          ? Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Checkbox(
                                  value: selecionados.contains(
                                    item!.documento!.id,
                                  ),
                                  onChanged: (value) {
                                    setState(() {
                                      if (value == true) {
                                        selecionados.add(
                                          item.documento!.id,
                                        );
                                      } else {
                                        selecionados.remove(
                                          item.documento!.id,
                                        );
                                      }
                                    });
                                  },
                                ),
                                IconButton(
                                  tooltip: 'Visualizar PDF',
                                  onPressed: () => abrirDocumento(item),
                                  icon: const Icon(Icons.picture_as_pdf),
                                ),
                              ],
                            )
                          : IconButton(
                              tooltip: 'Enviar PDF',
                              onPressed: () => enviar(tipo),
                              icon: const Icon(Icons.upload_file),
                            ),
                    ),
                  );
                }),
              ],
            ),
          ),
        ),
        if (selecionados.isNotEmpty)
          SafeArea(
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: SizedBox(
                width: double.infinity,
                child: FilledButton.icon(
                  onPressed: processando ? null : mesclar,
                  icon: const Icon(Icons.merge_type),
                  label: Text(
                    processando
                        ? 'Processando...'
                        : 'Mesclar ${selecionados.length} PDFs',
                  ),
                ),
              ),
            ),
          ),
      ],
    );
  }
}
