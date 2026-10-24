import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/models/blog_post_model.dart';

class BlogRepository {
  final ApiClient _apiClient = ApiClient.instance;

  Future<Map<String, dynamic>> getBlogPosts({bool? isPublished, int? categoryId, String? search}) async {
    final query = <String, dynamic>{};
    if (isPublished != null) {
      query['is_published'] = isPublished;
    }
    if (categoryId != null) {
      query['category_id'] = categoryId;
    }
    if (search != null && search.isNotEmpty) {
      query['search'] = search;
    }

    final response = await _apiClient.get(
      ApiEndpoints.blogPosts,
      queryParameters: query,
    );

    final data = response.data as Map<String, dynamic>;
    final postsRaw = data['posts'] as List? ?? [];
    final posts = postsRaw.map((e) => BlogPostModel.fromJson(e as Map<String, dynamic>)).toList();
    final stats = data['stats'] as Map<String, dynamic>? ?? {};

    return {
      'posts': posts,
      'stats': stats,
    };
  }

  Future<List<BlogCategoryModel>> getCategories() async {
    final response = await _apiClient.get(ApiEndpoints.blogCategories);
    final data = response.data as Map<String, dynamic>;
    final catsRaw = data['categories'] as List? ?? [];
    return catsRaw.map((e) => BlogCategoryModel.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<BlogPostModel> togglePublish(int id) async {
    final response = await _apiClient.patch('${ApiEndpoints.blogPosts}/$id/publish');
    final data = response.data as Map<String, dynamic>;
    return BlogPostModel.fromJson(data['post'] as Map<String, dynamic>);
  }

  Future<BlogPostModel> savePost(Map<String, dynamic> payload, {int? id}) async {
    if (id != null) {
      final response = await _apiClient.put('${ApiEndpoints.blogPosts}/$id', data: payload);
      final data = response.data as Map<String, dynamic>;
      return BlogPostModel.fromJson(data['post'] as Map<String, dynamic>);
    } else {
      final response = await _apiClient.post(ApiEndpoints.blogPosts, data: payload);
      final data = response.data as Map<String, dynamic>;
      return BlogPostModel.fromJson(data['post'] as Map<String, dynamic>);
    }
  }

  Future<void> deletePost(int id) async {
    await _apiClient.delete('${ApiEndpoints.blogPosts}/$id');
  }
}
