class Usuario {
  final int? id;
  final String username;

  Usuario({
    this.id,
    required this.username,
  });

  factory Usuario.fromJson(Map<String, dynamic> json) {
    return Usuario(
      id: json['id'],
      username: json['username'] ?? '',
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'username': username,
      };
}
