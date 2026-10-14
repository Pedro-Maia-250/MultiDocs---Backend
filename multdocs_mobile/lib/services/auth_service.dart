import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/token_response.dart';
import '../models/usuario.dart';
import 'api_service.dart';

class AuthService {
  final ApiService api;

  AuthService({ApiService? api}) : api = api ?? ApiService();

  Future<TokenResponse> login({
    required String username,
    required String password,
  }) async {
    final response = await api.postJson('/auth', {
      'username': username,
      'password': password,
    });

    final tokenResponse =
        TokenResponse.fromJson(jsonDecode(response.body));

    await _saveToken(tokenResponse.token);
    api.token = tokenResponse.token;

    return tokenResponse;
  }

  Future<Usuario> cadastrar({
    required String username,
    required String password,
  }) async {
    final response = await api.postJson('/usuarios', {
      'username': username,
      'password': password,
    });

    return Usuario.fromJson(jsonDecode(response.body));
  }

  Future<bool> carregarToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    if (token == null || token.isEmpty) {
      return false;
    }

    api.token = token;
    return true;
  }

  Future<void> _saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
    api.token = null;
  }
}
