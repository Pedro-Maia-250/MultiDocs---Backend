import 'dart:convert';
import 'dart:typed_data';
import 'package:file_picker/file_picker.dart';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';
import '../models/documento.dart';
import '../models/tipo_documento.dart';
import 'api_service.dart';

class DocumentoService {
  final ApiService api;

  DocumentoService({ApiService? api}) : api = api ?? ApiService();

  Future<List<DocumentoLista>> listarDocumentos() async {
    final response = await api.get('/documentos');
    final List<dynamic> data = jsonDecode(response.body);

    return data
        .map((item) => DocumentoLista.fromJson(item))
        .toList();
  }

  Future<PlatformFile?> selecionarPdf() async {
  final arquivo = await FilePicker.pickFile(
    type: FileType.custom,
    allowedExtensions: ['pdf'],
  );

  return arquivo;
}

  Future<Documento> enviarDocumento({
  required PlatformFile arquivo,
  required TipoDocumento tipo,
}) async {
  final request = http.MultipartRequest(
    'POST',
    Uri.parse('${ApiService.baseUrl}/documentos'),
  );

  request.headers.addAll({
    'Accept': 'application/json',
    if (api.token != null)
      'Authorization': 'Bearer ${api.token}',
  });

  final dados = jsonEncode({
    'tipo': tipo.tipo.apiValue,
    'tipoId': tipo.id,
  });

  request.files.add(
    http.MultipartFile.fromString(
      'dados',
      dados,
      contentType: MediaType('application', 'json'),
    ),
  );

  final bytes = await arquivo.readAsBytes();

  request.files.add(
    http.MultipartFile.fromBytes(
      'arquivo',
      bytes,
      filename: arquivo.name,
      contentType: MediaType('application', 'pdf'),
    ),
  );

  final streamed = await request.send();
  final response = await http.Response.fromStream(streamed);

  if (response.statusCode < 200 || response.statusCode >= 300) {
    throw Exception(
      'Erro ao enviar PDF (${response.statusCode}): ${response.body}',
    );
  }

  return Documento.fromJson(
    jsonDecode(response.body),
  );
}

  Future<Uint8List> baixarDocumento(int id) async {
    final response = await api.get('/documentos/$id');
    return response.bodyBytes;
  }

  Future<Uint8List> mesclarDocumentos(
    List<DocumentoLista> selecionados,
  ) async {
    final body = selecionados.map((item) {
      return {
        'tipo': item.tipo.tipo.apiValue,
        'tipoId': item.tipo.id,
      };
    }).toList();

    final response = await api.postJsonArray('/pdf/mesclar', body);
    return response.bodyBytes;
  }
}
