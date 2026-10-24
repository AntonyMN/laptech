import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/database/app_database.dart';
import '../../core/models/dashboard_stats_model.dart';
import '../../core/models/order_model.dart';

class DashboardData {
  final DashboardStats stats;
  final List<DashboardAlert> alerts;
  final List<QuickLink> quickLinks;
  final List<OrderModel> recentOrders;
  final bool isOffline;
  final String? cachedAt;

  DashboardData({
    required this.stats,
    required this.alerts,
    required this.quickLinks,
    required this.recentOrders,
    this.isOffline = false,
    this.cachedAt,
  });
}

class DashboardRepository {
  final ApiClient _api = ApiClient.instance;
  final AppDatabase _db = AppDatabase.instance;

  Future<DashboardData> getDashboardData() async {
    try {
      final response = await _api.get(ApiEndpoints.dashboard);
      final data = response.data;

      final stats = DashboardStats.fromJson(data['stats'] ?? {});

      final alerts = ((data['alerts'] as List?) ?? [])
          .map((a) => DashboardAlert.fromJson(a))
          .toList();

      final quickLinks = ((data['quick_links'] as List?) ?? [])
          .map((q) => QuickLink.fromJson(q))
          .toList();

      final recentOrders = ((data['recent_orders'] as List?) ?? [])
          .map((o) => OrderModel.fromJson(o))
          .toList();

      // Save to SQLite
      await _db.cacheDashboard(
        stats: stats.toJson(),
        alerts: alerts.map((a) => a.toJson()).toList(),
        quickLinks: quickLinks.map((q) => q.toJson()).toList(),
        recentOrders: recentOrders.map((o) => o.toJson()).toList(),
      );

      return DashboardData(
        stats: stats,
        alerts: alerts,
        quickLinks: quickLinks,
        recentOrders: recentOrders,
        isOffline: false,
      );
    } catch (_) {
      // Fallback to SQLite Cache
      final cached = await _db.getCachedDashboard();
      if (cached != null) {
        final stats = DashboardStats.fromJson(cached['stats'] ?? {});
        final alerts = ((cached['alerts'] as List?) ?? [])
            .map((a) => DashboardAlert.fromJson(a))
            .toList();
        final quickLinks = ((cached['quick_links'] as List?) ?? [])
            .map((q) => QuickLink.fromJson(q))
            .toList();
        final recentOrders = ((cached['recent_orders'] as List?) ?? [])
            .map((o) => OrderModel.fromJson(o))
            .toList();

        return DashboardData(
          stats: stats,
          alerts: alerts,
          quickLinks: quickLinks,
          recentOrders: recentOrders,
          isOffline: true,
          cachedAt: cached['cached_at'],
        );
      }
      rethrow;
    }
  }
}
