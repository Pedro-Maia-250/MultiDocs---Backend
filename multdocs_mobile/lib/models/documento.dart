import 'tipo_documento.dart';

class Documento {
  final int id;
  final DateTime? data;

  Documento({
    required this.id,
    this.data,
  });

  factory Documento.fromJson(Map<String, dynamic> json) {
    return Documento(
      id: (json['id'] ?? 0) as int,
      data: json['data'] != null
          ? DateTime.tryParse(json['data'])
          : null,
    );
  }
}

class DocumentoLista {
  final TipoDocumento tipo;
  final Documento? documento;

  DocumentoLista({
    required this.tipo,
    this.documento,
  });

  factory DocumentoLista.fromJson(Map<String, dynamic> json) {
    return DocumentoLista(
      tipo: TipoDocumento.fromJson(json['tipo']),
      documento: json['documento'] != null
          ? Documento.fromJson(json['documento'])
          : null,
    );
  }
}
