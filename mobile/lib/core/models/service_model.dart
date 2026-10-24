class ServiceCategoryModel {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final int servicesCount;

  ServiceCategoryModel({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.servicesCount = 0,
  });

  factory ServiceCategoryModel.fromJson(Map<String, dynamic> json) {
    return ServiceCategoryModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      description: json['description']?.toString(),
      servicesCount: json['services_count'] is int ? json['services_count'] : int.tryParse(json['services_count']?.toString() ?? '0') ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'description': description,
      'services_count': servicesCount,
    };
  }
}

class ServiceModel {
  final int id;
  final String name;
  final String slug;
  final String description;
  final List<String> features;
  final String? icon;
  final String? image;
  final bool isFeatured;
  final int? serviceCategoryId;
  final ServiceCategoryModel? category;

  ServiceModel({
    required this.id,
    required this.name,
    required this.slug,
    required this.description,
    this.features = const [],
    this.icon,
    this.image,
    this.isFeatured = false,
    this.serviceCategoryId,
    this.category,
  });

  factory ServiceModel.fromJson(Map<String, dynamic> json) {
    List<String> parsedFeatures = [];
    if (json['features'] is List) {
      parsedFeatures = (json['features'] as List).map((e) => e.toString()).toList();
    } else if (json['features'] is String && json['features'].toString().isNotEmpty) {
      parsedFeatures = [json['features'].toString()];
    }

    return ServiceModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      description: json['description'] ?? '',
      features: parsedFeatures,
      icon: json['icon']?.toString(),
      image: json['image']?.toString(),
      isFeatured: json['is_featured'] == true || json['is_featured'] == 1,
      serviceCategoryId: json['service_category_id'] is int
          ? json['service_category_id']
          : int.tryParse(json['service_category_id']?.toString() ?? ''),
      category: json['category'] is Map<String, dynamic> ? ServiceCategoryModel.fromJson(json['category']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'description': description,
      'features': features,
      'icon': icon,
      'image': image,
      'is_featured': isFeatured,
      'service_category_id': serviceCategoryId,
      'category': category?.toJson(),
    };
  }
}
