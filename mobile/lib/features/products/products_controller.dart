import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/category_model.dart';
import '../../core/models/product_model.dart';
import 'products_repository.dart';

class ProductsState {
  final bool isLoading;
  final List<ProductModel> products;
  final List<CategoryModel> categories;
  final String searchQuery;
  final int? selectedCategoryId;
  final String archiveFilter;
  final bool lowStockOnly;
  final String? errorMessage;

  const ProductsState({
    this.isLoading = false,
    this.products = const [],
    this.categories = const [],
    this.searchQuery = '',
    this.selectedCategoryId,
    this.archiveFilter = 'active',
    this.lowStockOnly = false,
    this.errorMessage,
  });

  ProductsState copyWith({
    bool? isLoading,
    List<ProductModel>? products,
    List<CategoryModel>? categories,
    String? searchQuery,
    int? selectedCategoryId,
    bool clearCategory = false,
    String? archiveFilter,
    bool? lowStockOnly,
    String? errorMessage,
    bool clearError = false,
  }) {
    return ProductsState(
      isLoading: isLoading ?? this.isLoading,
      products: products ?? this.products,
      categories: categories ?? this.categories,
      searchQuery: searchQuery ?? this.searchQuery,
      selectedCategoryId: clearCategory ? null : (selectedCategoryId ?? this.selectedCategoryId),
      archiveFilter: archiveFilter ?? this.archiveFilter,
      lowStockOnly: lowStockOnly ?? this.lowStockOnly,
      errorMessage: clearError ? null : (errorMessage ?? this.errorMessage),
    );
  }
}

final productsRepositoryProvider = Provider<ProductsRepository>((ref) {
  return ProductsRepository();
});

class ProductsController extends StateNotifier<ProductsState> {
  final ProductsRepository _repo;

  ProductsController(this._repo) : super(const ProductsState());

  Future<void> fetchCategories() async {
    try {
      final categories = await _repo.getCategories();
      state = state.copyWith(categories: categories);
    } catch (_) {}
  }

  Future<void> fetchProducts() async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final products = await _repo.getProducts(
        search: state.searchQuery,
        categoryId: state.selectedCategoryId,
        archiveFilter: state.archiveFilter,
        lowStock: state.lowStockOnly,
      );
      state = state.copyWith(isLoading: false, products: products);
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        errorMessage: 'Unable to load products. Showing offline cached catalog if available.',
      );
    }
  }

  void setSearch(String query) {
    state = state.copyWith(searchQuery: query);
    fetchProducts();
  }

  void setCategory(int? categoryId) {
    if (state.selectedCategoryId == categoryId) {
      state = state.copyWith(clearCategory: true);
    } else {
      state = state.copyWith(selectedCategoryId: categoryId);
    }
    fetchProducts();
  }

  void setArchiveFilter(String filter) {
    state = state.copyWith(archiveFilter: filter);
    fetchProducts();
  }

  void toggleLowStock() {
    state = state.copyWith(lowStockOnly: !state.lowStockOnly);
    fetchProducts();
  }

  Future<bool> deleteProduct(int id) async {
    try {
      await _repo.deleteProduct(id);
      state = state.copyWith(
        products: state.products.where((p) => p.id != id).toList(),
      );
      return true;
    } catch (_) {
      return false;
    }
  }

  Future<bool> archiveProduct(int id) async {
    try {
      await _repo.archiveProduct(id);
      await fetchProducts();
      return true;
    } catch (_) {
      return false;
    }
  }

  Future<bool> restoreProduct(int id) async {
    try {
      await _repo.restoreProduct(id);
      await fetchProducts();
      return true;
    } catch (_) {
      return false;
    }
  }
}

final productsControllerProvider = StateNotifierProvider<ProductsController, ProductsState>((ref) {
  final repo = ref.watch(productsRepositoryProvider);
  return ProductsController(repo);
});
