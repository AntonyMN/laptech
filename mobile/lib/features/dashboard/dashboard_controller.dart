import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'dashboard_repository.dart';

class DashboardState {
  final bool isLoading;
  final DashboardData? data;
  final String? errorMessage;

  const DashboardState({
    this.isLoading = false,
    this.data,
    this.errorMessage,
  });

  DashboardState copyWith({
    bool? isLoading,
    DashboardData? data,
    String? errorMessage,
    bool clearError = false,
  }) {
    return DashboardState(
      isLoading: isLoading ?? this.isLoading,
      data: data ?? this.data,
      errorMessage: clearError ? null : (errorMessage ?? this.errorMessage),
    );
  }
}

final dashboardRepositoryProvider = Provider<DashboardRepository>((ref) {
  return DashboardRepository();
});

class DashboardController extends StateNotifier<DashboardState> {
  final DashboardRepository _repo;

  DashboardController(this._repo) : super(const DashboardState());

  Future<void> fetchDashboard() async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final data = await _repo.getDashboardData();
      state = state.copyWith(isLoading: false, data: data);
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        errorMessage: 'Unable to load dashboard. Check your server connection.',
      );
    }
  }
}

final dashboardControllerProvider = StateNotifierProvider<DashboardController, DashboardState>((ref) {
  final repo = ref.watch(dashboardRepositoryProvider);
  return DashboardController(repo);
});
