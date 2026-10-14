class TokenResponse {
  final String token;
  final String type;
  final int expiresIn;

  TokenResponse({
    required this.token,
    required this.type,
    required this.expiresIn,
  });

  factory TokenResponse.fromJson(Map<String, dynamic> json) {
    return TokenResponse(
      token: json['token'] ?? '',
      type: json['type'] ?? 'Bearer',
      expiresIn: (json['expiresIn'] ?? 0) as int,
    );
  }
}
