import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/database/app_database.dart';
import '../../core/models/order_model.dart';

class OrdersRepository {
  final ApiClient _api = ApiClient.instance;
  final AppDatabase _db = AppDatabase.instance;

  Future<List<OrderModel>> getOrders({String? status, String? search}) async {
    try {
      final queryParams = <String, dynamic>{
        'per_page': 50,
      };
      if (status != null && status != 'all' && status.isNotEmpty) {
        queryParams['status'] = status;
      }
      if (search != null && search.trim().isNotEmpty) {
        queryParams['search'] = search.trim();
      }

      final response = await _api.get(ApiEndpoints.orders, queryParameters: queryParams);
      final listData = response.data['data'] as List? ?? [];
      final orders = listData.map((json) => OrderModel.fromJson(json)).toList();

      // Cache to SQLite
      await _db.cacheOrders(orders);

      return orders;
    } catch (_) {
      // Offline fallback
      return await _db.getCachedOrders(status: status, search: search);
    }
  }

  Future<OrderModel> getOrderDetail(int id) async {
    final response = await _api.get('${ApiEndpoints.orders}/$id');
    return OrderModel.fromJson(response.data['order']);
  }

  Future<OrderModel> updateOrderStatus(int id, String status) async {
    final response = await _api.patch(
      '${ApiEndpoints.orders}/$id/status',
      data: {'status': status},
    );
    final updated = OrderModel.fromJson(response.data['order']);
    // Update cache
    await _db.cacheOrders([updated]);
    return updated;
  }
}
