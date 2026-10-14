import 'dart:io';
import 'dart:typed_data';
import 'package:open_filex/open_filex.dart';
import 'package:path_provider/path_provider.dart';

class FileUtils {
  static Future<File> salvarPdf(
    Uint8List bytes,
    String nome,
  ) async {
    final directory = await getApplicationDocumentsDirectory();
    final file = File('${directory.path}/$nome');
    await file.writeAsBytes(bytes, flush: true);
    return file;
  }

  static Future<void> abrirPdf(
    Uint8List bytes,
    String nome,
  ) async {
    final file = await salvarPdf(bytes, nome);
    await OpenFilex.open(file.path);
  }
}
