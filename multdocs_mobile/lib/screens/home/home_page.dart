import 'package:flutter/material.dart';
import '../../models/tipo_documento.dart';
import '../../services/auth_service.dart';
import '../../services/documento_service.dart';
import '../../services/tipo_documento_service.dart';
import '../documentos/documentos_page.dart';
import '../login/login_page.dart';
import '../tipos_documentos/criar_tipo_page.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final tipoService = TipoDocumentoService();
  final documentoService = DocumentoService();
  final authService = AuthService();

  List<TipoDocumento> tipos = [];
  int aba = 0;
  bool carregando = true;

  @override
  void initState() {
    super.initState();
    carregarTipos();
  }

  Future<void> carregarTipos() async {
    try {
      final documentos = await documentoService.listarDocumentos();

      final mapa = <int, TipoDocumento>{};
      for (final item in documentos) {
        mapa[item.tipo.id] = item.tipo;
      }

      if (mounted) {
        setState(() {
          tipos = mapa.values.toList();
          carregando = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => carregando = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Erro ao carregar documentos: $e')),
        );
      }
    }
  }

  Future<void> adicionarTipo() async {
    final criado = await Navigator.push<TipoDocumento>(
      context,
      MaterialPageRoute(
        builder: (_) => const CriarTipoPage(),
      ),
    );

    if (criado != null) {
      setState(() => tipos.add(criado));
    }
  }

  Future<void> sair() async {
    await authService.logout();

    if (!mounted) return;

    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (_) => const LoginPage()),
      (_) => false,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('MultiDocs'),
        actions: [
          IconButton(
            tooltip: 'Sair',
            onPressed: sair,
            icon: const Icon(Icons.logout),
          ),
        ],
      ),
      body: carregando
          ? const Center(child: CircularProgressIndicator())
          : aba == 0
              ? DocumentosPage(
                  tipos: tipos,
                  onAtualizar: carregarTipos,
                )
              : _tiposPage(),
      bottomNavigationBar: NavigationBar(
        selectedIndex: aba,
        onDestinationSelected: (index) {
          setState(() => aba = index);
        },
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.folder),
            label: 'Documentos',
          ),
          NavigationDestination(
            icon: Icon(Icons.category),
            label: 'Tipos',
          ),
        ],
      ),
    );
  }

  Widget _tiposPage() {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        const Text(
          'Tipos de documentos',
          style: TextStyle(
            fontSize: 24,
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 16),
        ...tipos.map(
          (tipo) => Card(
            child: ListTile(
              leading: const Icon(Icons.description),
              title: Text(tipo.nome),
              subtitle: Text(tipo.tipo.apiValue),
              trailing: tipo.tipo == TipoDocumentoEnum.padrao
                  ? const Chip(label: Text('Padrão'))
                  : const Chip(label: Text('Personalizado')),
            ),
          ),
        ),
        const SizedBox(height: 16),
        FilledButton.icon(
          onPressed: adicionarTipo,
          icon: const Icon(Icons.add),
          label: const Text('Novo tipo de documento'),
        ),
      ],
    );
  }
}
