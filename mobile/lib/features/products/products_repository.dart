import 'dart:io';
import 'package:dio/dio.dart';
import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/database/app_database.dart';
import '../../core/models/category_model.dart';
import '../../core/models/product_model.dart';

class ProductsRepository {
  final ApiClient _api = ApiClient.instance;
  final AppDatabase _db = AppDatabase.instance;

  Future<List<ProductModel>> getProducts({
    String? search,
    int? categoryId,
    String? archiveFilter = 'active',
    bool? lowStock,
  }) async {
    try {
      final queryParams = <String, dynamic>{
        'per_page': 50,
      };
      if (search != null && search.trim().isNotEmpty) {
        queryParams['search'] = search.trim();
      }
      if (categoryId != null && categoryId > 0) {
        queryParams['category_id'] = categoryId;
      }
      if (archiveFilter != null && archiveFilter.isNotEmpty) {
        queryParams['archive_filter'] = archiveFilter;
      }
      if (lowStock == true) {
        queryParams['low_stock'] = 1;
      }

      final response = await _api.get(ApiEndpoints.products, queryParameters: queryParams);
      final listData = response.data['data'] as List? ?? [];
      final products = listData.map((json) => ProductModel.fromJson(json)).toList();

      // Cache to SQLite
      await _db.cacheProducts(products);

      return products;
    } catch (_) {
      // Offline fallback from SQLite
      return await _db.getCachedProducts(
        search: search,
        categoryId: categoryId,
        archiveFilter: archiveFilter,
      );
    }
  }

  Future<ProductModel> createProduct({
    required String name,
    String? slug,
    required int categoryId,
    required String description,
    required double price,
    required int stock,
    required String status,
    bool isFeatured = false,
    String? image,
    File? imageFile,
    Map<String, dynamic>? specifications,
  }) async {
    dynamic data;
    if (imageFile != null) {
      final formData = FormData.fromMap({
        'name': name,
        'category_id': categoryId,
        'description': description,
        'price': price,
        'stock': stock,
        'status': status,
        'is_featured': isFeatured ? 1 : 0,
        'image_file': await MultipartFile.fromFile(imageFile.path, filename: imageFile.path.split('/').last),
      });
      if (slug != null && slug.isNotEmpty) formData.fields.add(MapEntry('slug', slug));
      if (image != null && image.isNotEmpty) formData.fields.add(MapEntry('image', image));
      if (specifications != null) formData.fields.add(MapEntry('specifications', specifications.toString()));
      data = formData;
    } else {
      data = {
        'name': name,
        'slug': slug,
        'category_id': categoryId,
        'description': description,
        'price': price,
        'stock': stock,
        'status': status,
        'is_featured': isFeatured,
        'image': image,
        'specifications': specifications,
      };
    }

    final response = await _api.post(ApiEndpoints.products, data: data);
    return ProductModel.fromJson(response.data['product']);
  }

  Future<ProductModel> updateProduct({
    required int id,
    String? name,
    String? slug,
    int? categoryId,
    String? description,
    double? price,
    int? stock,
    String? status,
    bool? isFeatured,
    String? image,
    File? imageFile,
    Map<String, dynamic>? specifications,
  }) async {
    dynamic data;
    if (imageFile != null) {
      final formData = FormData.fromMap({
        'image_file': await MultipartFile.fromFile(imageFile.path, filename: imageFile.path.split('/').last),
      });
      if (name != null) formData.fields.add(MapEntry('name', name));
      if (slug != null) formData.fields.add(MapEntry('slug', slug));
      if (categoryId != null) formData.fields.add(MapEntry('category_id', categoryId.toString()));
      if (description != null) formData.fields.add(MapEntry('description', description));
      if (price != null) formData.fields.add(MapEntry('price', price.toString()));
      if (stock != null) formData.fields.add(MapEntry('stock', stock.toString()));
      if (status != null) formData.fields.add(MapEntry('status', status));
      if (isFeatured != null) formData.fields.add(MapEntry('is_featured', isFeatured ? '1' : '0'));
      if (specifications != null) formData.fields.add(MapEntry('specifications', specifications.toString()));
      data = formData;
    } else {
      data = <String, dynamic>{};
      if (name != null) data['name'] = name;
      if (slug != null) data['slug'] = slug;
      if (categoryId != null) data['category_id'] = categoryId;
      if (description != null) data['description'] = description;
      if (price != null) data['price'] = price;
      if (stock != null) data['stock'] = stock;
      if (status != null) data['status'] = status;
      if (isFeatured != null) data['is_featured'] = isFeatured;
      if (image != null) data['image'] = image;
      if (specifications != null) data['specifications'] = specifications;
    }

    // POST /api/v1/products/{id} supports multipart updates cleanly in Laravel
    final response = await _api.post('${ApiEndpoints.products}/$id', data: data);
    return ProductModel.fromJson(response.data['product']);
  }

  Future<void> deleteProduct(int id) async {
    await _api.delete('${ApiEndpoints.products}/$id');
  }

  Future<void> archiveProduct(int id) async {
    await _api.post('${ApiEndpoints.products}/$id/archive');
  }

  Future<void> restoreProduct(int id) async {
    await _api.post('${ApiEndpoints.products}/$id/restore');
  }

  Future<List<CategoryModel>> getCategories() async {
    try {
      final response = await _api.get(ApiEndpoints.categories);
      final listData = response.data['categories'] as List? ?? [];
      return listData.map((json) => CategoryModel.fromJson(json)).toList();
    } catch (_) {
      return [];
    }
  }
}
