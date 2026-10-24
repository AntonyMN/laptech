import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../core/api/api_client.dart';
import '../dashboard/dashboard_controller.dart';
import '../orders/orders_controller.dart';
import '../products/products_controller.dart';
import '../services/services_controller.dart';
import 'settings_repository.dart';

class SettingsState {
  final bool isLoading;
  final Map<String, dynamic>? systemInfo;
  final bool isDarkMode;
  final String serverUrl;
  final DateTime? lastSyncTime;
  final bool isSyncing;

  const SettingsState({
    this.isLoading = false,
    this.systemInfo,
    this.isDarkMode = true,
    this.serverUrl = '',
    this.lastSyncTime,
    this.isSyncing = false,
  });

  SettingsState copyWith({
    bool? isLoading,
    Map<String, dynamic>? systemInfo,
    bool? isDarkMode,
    String? serverUrl,
    DateTime? lastSyncTime,
    bool? isSyncing,
  }) {
    return SettingsState(
      isLoading: isLoading ?? this.isLoading,
      systemInfo: systemInfo ?? this.systemInfo,
      isDarkMode: isDarkMode ?? this.isDarkMode,
      serverUrl: serverUrl ?? this.serverUrl,
      lastSyncTime: lastSyncTime ?? this.lastSyncTime,
      isSyncing: isSyncing ?? this.isSyncing,
    );
  }
}

final settingsRepositoryProvider = Provider<SettingsRepository>((ref) {
  return SettingsRepository();
});

class SettingsController extends StateNotifier<SettingsState> {
  final SettingsRepository _repo;
  final Ref _ref;

  SettingsController(this._repo, this._ref) : super(const SettingsState()) {
    _init();
  }

  Future<void> _init() async {
    final prefs = await SharedPreferences.getInstance();
    final isDark = prefs.getBool('app_theme_dark') ?? true;
    final url = ApiClient.instance.baseUrl;

    state = state.copyWith(
      isDarkMode: isDark,
      serverUrl: url,
      lastSyncTime: DateTime.now(),
    );

    loadSystemInfo();
  }

  Future<void> loadSystemInfo() async {
    state = state.copyWith(isLoading: true);
    final info = await _repo.getSystemSettings();
    state = state.copyWith(isLoading: false, systemInfo: info);
  }

  Future<void> toggleTheme(bool isDark) async {
    state = state.copyWith(isDarkMode: isDark);
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('app_theme_dark', isDark);
  }

  Future<void> updateServerUrl(String newUrl) async {
    await ApiClient.instance.setBaseUrl(newUrl);
    state = state.copyWith(serverUrl: ApiClient.instance.baseUrl);
    await loadSystemInfo();
  }

  Future<bool> testConnection(String url) async {
    return await _repo.testConnection(url);
  }

  Future<void> syncAll() async {
    state = state.copyWith(isSyncing: true);
    try {
      await Future.wait([
        _ref.read(dashboardControllerProvider.notifier).fetchDashboard(),
        _ref.read(productsControllerProvider.notifier).fetchProducts(),
        _ref.read(ordersControllerProvider.notifier).fetchOrders(),
        _ref.read(servicesControllerProvider.notifier).fetchServices(),
      ]);
      state = state.copyWith(isSyncing: false, lastSyncTime: DateTime.now());
    } catch (_) {
      state = state.copyWith(isSyncing: false);
    }
  }

  Future<void> clearLocalCache() async {
    await _repo.clearLocalCache();
  }

  Future<void> clearServerCache() async {
    await _repo.clearServerCache();
  }
}

final settingsControllerProvider = StateNotifierProvider<SettingsController, SettingsState>((ref) {
  final repo = ref.watch(settingsRepositoryProvider);
  return SettingsController(repo, ref);
});
