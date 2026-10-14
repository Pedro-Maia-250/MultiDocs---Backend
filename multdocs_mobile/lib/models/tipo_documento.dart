enum TipoDocumentoEnum {
  padrao,
  personalizado;

  String get apiValue {
    switch (this) {
      case TipoDocumentoEnum.padrao:
        return 'PADRAO';
      case TipoDocumentoEnum.personalizado:
        return 'PERSONALIZADO';
    }
  }

  static TipoDocumentoEnum fromApi(String? value) {
    return value == 'PERSONALIZADO'
        ? TipoDocumentoEnum.personalizado
        : TipoDocumentoEnum.padrao;
  }
}

class TipoDocumento {
  final int id;
  final String nome;
  final TipoDocumentoEnum tipo;
  final String? url;

  TipoDocumento({
    required this.id,
    required this.nome,
    required this.tipo,
    this.url,
  });

  factory TipoDocumento.fromJson(Map<String, dynamic> json) {
    return TipoDocumento(
      id: (json['id'] ?? 0) as int,
      nome: json['nome'] ?? '',
      tipo: TipoDocumentoEnum.fromApi(json['tipo']),
      url: json['url'],
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'nome': nome,
        'tipo': tipo.apiValue,
        'url': url,
      };
}
