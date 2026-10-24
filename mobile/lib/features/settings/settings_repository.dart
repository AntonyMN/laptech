import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/database/app_database.dart';

class SettingsRepository {
  final ApiClient _api = ApiClient.instance;
  final AppDatabase _db = AppDatabase.instance;

  Future<Map<String, dynamic>> getSystemSettings() async {
    try {
      final response = await _api.get(ApiEndpoints.settings);
      return response.data;
    } catch (_) {
      return {
        'app_name': 'Laptech Systems Admin',
        'version': '1.0.0',
        'api_version': 'v1',
        'database_connected': false,
      };
    }
  }

  Future<bool> testConnection(String url) async {
    try {
      await _api.setBaseUrl(url);
      final response = await _api.get(ApiEndpoints.settings);
      return response.statusCode == 200;
    } catch (_) {
      return false;
    }
  }

  Future<void> clearLocalCache() async {
    await _db.clearAllCache();
  }

  Future<void> clearServerCache() async {
    try {
      await _api.post(ApiEndpoints.clearCache);
    } catch (_) {}
  }
}
