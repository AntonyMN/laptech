class UserModel {
  final int id;
  final String name;
  final String email;
  final bool isAdmin;
  final List<String> roles;
  final List<String> permissions;
  final String? createdAt;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    required this.isAdmin,
    this.roles = const [],
    this.permissions = const [],
    this.createdAt,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      isAdmin: json['is_admin'] == true || json['is_admin'] == 1,
      roles: (json['roles'] as List<dynamic>?)?.map((e) => e.toString()).toList() ?? [],
      permissions: (json['permissions'] as List<dynamic>?)?.map((e) => e.toString()).toList() ?? [],
      createdAt: json['created_at']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'is_admin': isAdmin,
      'roles': roles,
      'permissions': permissions,
      'created_at': createdAt,
    };
  }
}
