import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/blog_post_model.dart';
import 'blog_repository.dart';

class BlogState {
  final bool isLoading;
  final List<BlogPostModel> posts;
  final List<BlogCategoryModel> categories;
  final Map<String, dynamic> stats;
  final String filter; // 'all', 'published', 'drafts'
  final String searchQuery;
  final int? selectedCategoryId;
  final String? errorMessage;

  BlogState({
    this.isLoading = false,
    this.posts = const [],
    this.categories = const [],
    this.stats = const {},
    this.filter = 'all',
    this.searchQuery = '',
    this.selectedCategoryId,
    this.errorMessage,
  });

  BlogState copyWith({
    bool? isLoading,
    List<BlogPostModel>? posts,
    List<BlogCategoryModel>? categories,
    Map<String, dynamic>? stats,
    String? filter,
    String? searchQuery,
    int? selectedCategoryId,
    bool clearCategory = false,
    String? errorMessage,
  }) {
    return BlogState(
      isLoading: isLoading ?? this.isLoading,
      posts: posts ?? this.posts,
      categories: categories ?? this.categories,
      stats: stats ?? this.stats,
      filter: filter ?? this.filter,
      searchQuery: searchQuery ?? this.searchQuery,
      selectedCategoryId: clearCategory ? null : (selectedCategoryId ?? this.selectedCategoryId),
      errorMessage: errorMessage,
    );
  }

  int get totalCount => (stats['total'] as num?)?.toInt() ?? posts.length;
  int get publishedCount => (stats['published'] as num?)?.toInt() ?? 0;
  int get draftCount => (stats['drafts'] as num?)?.toInt() ?? 0;
}

class BlogController extends StateNotifier<BlogState> {
  final BlogRepository _repository;

  BlogController(this._repository) : super(BlogState()) {
    loadAll();
  }

  Future<void> loadAll() async {
    state = state.copyWith(isLoading: true, errorMessage: null);
    try {
      final categories = await _repository.getCategories();
      bool? isPub;
      if (state.filter == 'published') isPub = true;
      if (state.filter == 'drafts') isPub = false;

      final res = await _repository.getBlogPosts(
        isPublished: isPub,
        categoryId: state.selectedCategoryId,
        search: state.searchQuery,
      );

      state = state.copyWith(
        isLoading: false,
        categories: categories,
        posts: res['posts'] as List<BlogPostModel>,
        stats: res['stats'] as Map<String, dynamic>,
      );
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        errorMessage: 'Failed to load blog posts: $e',
      );
    }
  }

  void setFilter(String filter) {
    if (state.filter == filter) return;
    state = state.copyWith(filter: filter);
    loadAll();
  }

  void search(String query) {
    state = state.copyWith(searchQuery: query);
    loadAll();
  }

  void selectCategory(int? catId) {
    if (catId == null) {
      state = state.copyWith(clearCategory: true);
    } else {
      state = state.copyWith(selectedCategoryId: catId);
    }
    loadAll();
  }

  Future<bool> togglePublish(int id) async {
    try {
      final updated = await _repository.togglePublish(id);
      final updatedList = state.posts.map((p) => p.id == id ? updated : p).toList();
      state = state.copyWith(posts: updatedList);
      return true;
    } catch (e) {
      state = state.copyWith(errorMessage: 'Failed to toggle publication: $e');
      return false;
    }
  }

  Future<bool> deletePost(int id) async {
    try {
      await _repository.deletePost(id);
      final updatedList = state.posts.where((p) => p.id != id).toList();
      state = state.copyWith(posts: updatedList);
      return true;
    } catch (e) {
      state = state.copyWith(errorMessage: 'Failed to delete post: $e');
      return false;
    }
  }

  Future<bool> savePost(Map<String, dynamic> data, {int? id}) async {
    try {
      await _repository.savePost(data, id: id);
      await loadAll();
      return true;
    } catch (e) {
      state = state.copyWith(errorMessage: 'Failed to save post: $e');
      return false;
    }
  }
}

final blogRepositoryProvider = Provider<BlogRepository>((ref) {
  return BlogRepository();
});

final blogControllerProvider = StateNotifierProvider<BlogController, BlogState>((ref) {
  return BlogController(ref.watch(blogRepositoryProvider));
});
