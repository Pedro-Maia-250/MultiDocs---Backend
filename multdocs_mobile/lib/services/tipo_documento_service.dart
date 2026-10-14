
import 'dart:convert';

import '../models/tipo_documento.dart';
import 'api_service.dart';

class TipoDocumentoService {
  final ApiService api;

  TipoDocumentoService({ApiService? api})
      : api = api ?? ApiService();

  Future<List<TipoDocumento>> listarTipos() async {
    final response = await api.get('/documentos');
    final List<dynamic> dados = jsonDecode(response.body);

    final Map<int, TipoDocumento> tipos = {};

    for (final item in dados) {
      final mapa = Map<String, dynamic>.from(item);
      final tipoJson = mapa['tipo'];

      if (tipoJson is Map) {
        final tipo = TipoDocumento.fromJson(
          Map<String, dynamic>.from(tipoJson),
        );

        tipos[tipo.id] = tipo;
      }
    }

    return tipos.values.toList();
  }

  Future<TipoDocumento> criarTipo({
    required String nome,
  }) async {
    final response = await api.postJson(
      '/documentos/tipos',
      {'nome': nome},
    );

    final dados = jsonDecode(response.body);

    if (dados is! Map) {
      throw Exception(
        'A API retornou uma resposta inesperada ao criar o tipo.',
      );
    }

    return TipoDocumento.fromJson(
      Map<String, dynamic>.from(dados),
    );
  }
}