import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/service_model.dart';
import 'services_repository.dart';

class ServicesState {
  final bool isLoading;
  final List<ServiceModel> services;
  final List<ServiceCategoryModel> categories;
  final int? selectedCategoryId;
  final String searchQuery;
  final String? errorMessage;

  const ServicesState({
    this.isLoading = false,
    this.services = const [],
    this.categories = const [],
    this.selectedCategoryId,
    this.searchQuery = '',
    this.errorMessage,
  });

  ServicesState copyWith({
    bool? isLoading,
    List<ServiceModel>? services,
    List<ServiceCategoryModel>? categories,
    int? selectedCategoryId,
    bool clearCategory = false,
    String? searchQuery,
    String? errorMessage,
    bool clearError = false,
  }) {
    return ServicesState(
      isLoading: isLoading ?? this.isLoading,
      services: services ?? this.services,
      categories: categories ?? this.categories,
      selectedCategoryId: clearCategory ? null : (selectedCategoryId ?? this.selectedCategoryId),
      searchQuery: searchQuery ?? this.searchQuery,
      errorMessage: clearError ? null : (errorMessage ?? this.errorMessage),
    );
  }
}

final servicesRepositoryProvider = Provider<ServicesRepository>((ref) {
  return ServicesRepository();
});

class ServicesController extends StateNotifier<ServicesState> {
  final ServicesRepository _repo;

  ServicesController(this._repo) : super(const ServicesState());

  Future<void> fetchCategories() async {
    try {
      final categories = await _repo.getCategories();
      state = state.copyWith(categories: categories);
    } catch (_) {}
  }

  Future<void> fetchServices() async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final services = await _repo.getServices(
        categoryId: state.selectedCategoryId,
        search: state.searchQuery,
      );
      state = state.copyWith(isLoading: false, services: services);
    } catch (_) {
      state = state.copyWith(
        isLoading: false,
        errorMessage: 'Unable to load services. Showing offline cached data if available.',
      );
    }
  }

  void setCategory(int? categoryId) {
    if (state.selectedCategoryId == categoryId) {
      state = state.copyWith(clearCategory: true);
    } else {
      state = state.copyWith(selectedCategoryId: categoryId);
    }
    fetchServices();
  }

  void setSearch(String query) {
    state = state.copyWith(searchQuery: query);
    fetchServices();
  }

  Future<bool> deleteService(int id) async {
    try {
      await _repo.deleteService(id);
      state = state.copyWith(
        services: state.services.where((s) => s.id != id).toList(),
      );
      return true;
    } catch (_) {
      return false;
    }
  }
}

final servicesControllerProvider = StateNotifierProvider<ServicesController, ServicesState>((ref) {
  final repo = ref.watch(servicesRepositoryProvider);
  return ServicesController(repo);
});
