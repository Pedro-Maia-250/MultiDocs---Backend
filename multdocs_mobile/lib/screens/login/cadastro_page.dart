import 'package:flutter/material.dart';
import '../../models/exception_response.dart';
import '../../services/auth_service.dart';

class CadastroPage extends StatefulWidget {
  const CadastroPage({super.key});

  @override
  State<CadastroPage> createState() => _CadastroPageState();
}

class _CadastroPageState extends State<CadastroPage> {
  final usernameController = TextEditingController();
  final senhaController = TextEditingController();
  final confirmarController = TextEditingController();

  final authService = AuthService();
  bool carregando = false;

  Future<void> cadastrar() async {
    if (usernameController.text.trim().isEmpty ||
        senhaController.text.isEmpty) {
      _mensagem('Preencha usuário e senha.');
      return;
    }

    if (senhaController.text != confirmarController.text) {
      _mensagem('As senhas não coincidem.');
      return;
    }

    setState(() => carregando = true);

    try {
      await authService.cadastrar(
        username: usernameController.text.trim(),
        password: senhaController.text,
      );

      if (!mounted) return;

      _mensagem('Usuário criado com sucesso.');
      Navigator.pop(context);
    } on ApiException catch (e) {
      _mensagem(e.message);
    } catch (_) {
      _mensagem('Não foi possível criar o usuário.');
    } finally {
      if (mounted) setState(() => carregando = false);
    }
  }

  void _mensagem(String texto) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(texto)),
    );
  }

  @override
  void dispose() {
    usernameController.dispose();
    senhaController.dispose();
    confirmarController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Criar conta')),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Column(
            children: [
              TextField(
                controller: usernameController,
                decoration: const InputDecoration(
                  labelText: 'Usuário',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 16),
              TextField(
                controller: senhaController,
                obscureText: true,
                decoration: const InputDecoration(
                  labelText: 'Senha',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 16),
              TextField(
                controller: confirmarController,
                obscureText: true,
                decoration: const InputDecoration(
                  labelText: 'Confirmar senha',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                child: FilledButton(
                  onPressed: carregando ? null : cadastrar,
                  child: carregando
                      ? const CircularProgressIndicator()
                      : const Text('CADASTRAR'),
                ),
              ),
              TextButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Voltar para o login'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
