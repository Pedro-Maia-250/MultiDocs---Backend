
import 'package:flutter/material.dart';

import '../../models/exception_response.dart';
import '../../services/tipo_documento_service.dart';

class CriarTipoPage extends StatefulWidget {
  const CriarTipoPage({super.key});

  @override
  State<CriarTipoPage> createState() => _CriarTipoPageState();
}

class _CriarTipoPageState extends State<CriarTipoPage> {
  final nomeController = TextEditingController();
  final service = TipoDocumentoService();

  bool salvando = false;

  Future<void> salvar() async {
    final nome = nomeController.text.trim();

    if (nome.isEmpty) {
      mensagem('Informe o nome do documento.');
      return;
    }

    setState(() => salvando = true);

    try {
      final tipo = await service.criarTipo(nome: nome);

      if (!mounted) return;

      Navigator.pop(context, tipo);
    } on ApiException catch (e) {
      mensagem(
        'Erro HTTP ${e.statusCode}: ${e.message}',
      );
    } catch (e) {
      mensagem('Não foi possível criar o tipo: $e');
    } finally {
      if (mounted) {
        setState(() => salvando = false);
      }
    }
  }

  void mensagem(String texto) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(texto)),
    );
  }

  @override
  void dispose() {
    nomeController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Novo tipo de documento'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            TextField(
              controller: nomeController,
              enabled: !salvando,
              textCapitalization: TextCapitalization.words,
              decoration: const InputDecoration(
                labelText: 'Nome do documento',
                hintText: 'Ex.: CNH',
                border: OutlineInputBorder(),
              ),
              onSubmitted: (_) {
                if (!salvando) salvar();
              },
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              child: FilledButton(
                onPressed: salvando ? null : salvar,
                child: salvando
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                        ),
                      )
                    : const Text('SALVAR'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}