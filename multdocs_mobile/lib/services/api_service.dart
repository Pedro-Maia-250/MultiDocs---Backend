
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/exception_response.dart';

class ApiService {
  static const String baseUrl = 'https://md.lunarvoid.online';

  // Uma única instância para todas as telas.
  static final ApiService _instancia = ApiService._interno();

  factory ApiService() => _instancia;

  ApiService._interno();

  String? token;

  Map<String, String> headers({bool json = true}) {
    return {
      if (json) 'Content-Type': 'application/json',
      'Accept': '*/*',
      if (token != null && token!.isNotEmpty)
        'Authorization': 'Bearer $token',
    };
  }

  Future<http.Response> get(String endpoint) async {
    final response = await http.get(
      Uri.parse('$baseUrl$endpoint'),
      headers: headers(json: false),
    );
    _verificarResposta(response);
    return response;
  }

  Future<http.Response> postJson(
    String endpoint,
    Map<String, dynamic> body,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl$endpoint'),
      headers: headers(),
      body: jsonEncode(body),
    );
    _verificarResposta(response);
    return response;
  }

  Future<http.Response> postJsonArray(
    String endpoint,
    List<Map<String, dynamic>> body,
  ) async {
    final response = await http.post(
      Uri.parse('$baseUrl$endpoint'),
      headers: headers(),
      body: jsonEncode(body),
    );
    _verificarResposta(response);
    return response;
  }

  void _verificarResposta(http.Response response) {
    if (response.statusCode >= 200 &&
        response.statusCode < 300) {
      return;
    }

    var mensagem = 'Erro HTTP ${response.statusCode}';

    try {
      final data = jsonDecode(response.body);
      if (data is Map && data['message'] != null) {
        mensagem = data['message'].toString();
      }
    } catch (_) {}

    throw ApiException(response.statusCode, mensagem);
  }
}