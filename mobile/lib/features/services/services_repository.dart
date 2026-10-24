import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/database/app_database.dart';
import '../../core/models/service_model.dart';

class ServicesRepository {
  final ApiClient _api = ApiClient.instance;
  final AppDatabase _db = AppDatabase.instance;

  Future<List<ServiceModel>> getServices({int? categoryId, String? search}) async {
    try {
      final queryParams = <String, dynamic>{};
      if (categoryId != null && categoryId > 0) queryParams['category_id'] = categoryId;
      if (search != null && search.trim().isNotEmpty) queryParams['search'] = search.trim();

      final response = await _api.get(ApiEndpoints.services, queryParameters: queryParams);
      final listData = response.data['services'] as List? ?? [];
      final services = listData.map((json) => ServiceModel.fromJson(json)).toList();

      // Cache to SQLite
      await _db.cacheServices(services);

      return services;
    } catch (_) {
      // Offline fallback from SQLite
      return await _db.getCachedServices(categoryId: categoryId, search: search);
    }
  }

  Future<List<ServiceCategoryModel>> getCategories() async {
    try {
      final response = await _api.get(ApiEndpoints.serviceCategories);
      final listData = response.data['categories'] as List? ?? [];
      return listData.map((json) => ServiceCategoryModel.fromJson(json)).toList();
    } catch (_) {
      return [];
    }
  }

  Future<ServiceModel> createService({
    required String name,
    String? slug,
    required String description,
    int? serviceCategoryId,
    List<String> features = const [],
    String? icon,
    bool isFeatured = false,
  }) async {
    final response = await _api.post(
      ApiEndpoints.services,
      data: {
        'name': name,
        'slug': slug,
        'description': description,
        'service_category_id': serviceCategoryId,
        'features': features,
        'icon': icon,
        'is_featured': isFeatured,
      },
    );
    return ServiceModel.fromJson(response.data['service']);
  }

  Future<ServiceModel> updateService({
    required int id,
    String? name,
    String? slug,
    String? description,
    int? serviceCategoryId,
    List<String>? features,
    String? icon,
    bool? isFeatured,
  }) async {
    final data = <String, dynamic>{};
    if (name != null) data['name'] = name;
    if (slug != null) data['slug'] = slug;
    if (description != null) data['description'] = description;
    if (serviceCategoryId != null) data['service_category_id'] = serviceCategoryId;
    if (features != null) data['features'] = features;
    if (icon != null) data['icon'] = icon;
    if (isFeatured != null) data['is_featured'] = isFeatured;

    final response = await _api.put('${ApiEndpoints.services}/$id', data: data);
    return ServiceModel.fromJson(response.data['service']);
  }

  Future<void> deleteService(int id) async {
    await _api.delete('${ApiEndpoints.services}/$id');
  }
}
