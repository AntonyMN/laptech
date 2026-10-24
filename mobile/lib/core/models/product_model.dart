import 'category_model.dart';

class ProductModel {
  final int id;
  final int categoryId;
  final String name;
  final String slug;
  final String description;
  final double price;
  final int stock;
  final String status;
  final bool isFeatured;
  final bool isArchived;
  final String? image;
  final Map<String, dynamic>? specifications;
  final CategoryModel? category;
  final String? createdAt;

  ProductModel({
    required this.id,
    required this.categoryId,
    required this.name,
    required this.slug,
    required this.description,
    required this.price,
    required this.stock,
    required this.status,
    this.isFeatured = false,
    this.isArchived = false,
    this.image,
    this.specifications,
    this.category,
    this.createdAt,
  });

  String? resolveImageUrl(String baseUrl) {
    if (image == null || image!.trim().isEmpty) return null;
    final trimmed = image!.trim();
    if (trimmed.startsWith('http://') || trimmed.startsWith('https://')) {
      return trimmed;
    }
    var cleanBase = baseUrl.trim();
    while (cleanBase.endsWith('/')) {
      cleanBase = cleanBase.substring(0, cleanBase.length - 1);
    }
    if (cleanBase.endsWith('/api')) {
      cleanBase = cleanBase.substring(0, cleanBase.length - 4);
    }
    final cleanPath = trimmed.startsWith('/') ? trimmed : '/$trimmed';
    return '$cleanBase$cleanPath';
  }

  factory ProductModel.fromJson(Map<String, dynamic> json) {
    Map<String, dynamic>? specs;
    if (json['specifications'] is Map) {
      specs = Map<String, dynamic>.from(json['specifications']);
    } else if (json['specifications'] is List) {
      specs = {};
      for (final item in json['specifications']) {
        if (item is Map && item['key'] != null) {
          specs[item['key'].toString()] = item['value']?.toString() ?? '';
        }
      }
    }

    return ProductModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      categoryId: json['category_id'] is int ? json['category_id'] : int.tryParse(json['category_id']?.toString() ?? '0') ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      description: json['description'] ?? '',
      price: json['price'] is num ? (json['price'] as num).toDouble() : double.tryParse(json['price']?.toString() ?? '0') ?? 0.0,
      stock: json['stock'] is int ? json['stock'] : int.tryParse(json['stock']?.toString() ?? '0') ?? 0,
      status: json['status'] ?? 'Brand new',
      isFeatured: json['is_featured'] == true || json['is_featured'] == 1,
      isArchived: json['is_archived'] == true || json['is_archived'] == 1,
      image: json['image']?.toString(),
      specifications: specs,
      category: json['category'] is Map<String, dynamic> ? CategoryModel.fromJson(json['category']) : null,
      createdAt: json['created_at']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'category_id': categoryId,
      'name': name,
      'slug': slug,
      'description': description,
      'price': price,
      'stock': stock,
      'status': status,
      'is_featured': isFeatured,
      'is_archived': isArchived,
      'image': image,
      'specifications': specifications,
      'category': category?.toJson(),
      'created_at': createdAt,
    };
  }
}
