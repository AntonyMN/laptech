class CategoryModel {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final int activeProductsCount;

  CategoryModel({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.activeProductsCount = 0,
  });

  factory CategoryModel.fromJson(Map<String, dynamic> json) {
    return CategoryModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      description: json['description']?.toString(),
      activeProductsCount: json['active_products_count'] is int
          ? json['active_products_count']
          : int.tryParse(json['active_products_count']?.toString() ?? '0') ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'description': description,
      'active_products_count': activeProductsCount,
    };
  }
}
