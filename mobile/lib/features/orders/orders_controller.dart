import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/order_model.dart';
import 'orders_repository.dart';

class OrdersState {
  final bool isLoading;
  final List<OrderModel> orders;
  final String selectedStatus;
  final String searchQuery;
  final String? errorMessage;

  const OrdersState({
    this.isLoading = false,
    this.orders = const [],
    this.selectedStatus = 'all',
    this.searchQuery = '',
    this.errorMessage,
  });

  OrdersState copyWith({
    bool? isLoading,
    List<OrderModel>? orders,
    String? selectedStatus,
    String? searchQuery,
    String? errorMessage,
    bool clearError = false,
  }) {
    return OrdersState(
      isLoading: isLoading ?? this.isLoading,
      orders: orders ?? this.orders,
      selectedStatus: selectedStatus ?? this.selectedStatus,
      searchQuery: searchQuery ?? this.searchQuery,
      errorMessage: clearError ? null : (errorMessage ?? this.errorMessage),
    );
  }
}

final ordersRepositoryProvider = Provider<OrdersRepository>((ref) {
  return OrdersRepository();
});

class OrdersController extends StateNotifier<OrdersState> {
  final OrdersRepository _repo;

  OrdersController(this._repo) : super(const OrdersState());

  Future<void> fetchOrders() async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final orders = await _repo.getOrders(
        status: state.selectedStatus,
        search: state.searchQuery,
      );
      state = state.copyWith(isLoading: false, orders: orders);
    } catch (_) {
      state = state.copyWith(
        isLoading: false,
        errorMessage: 'Unable to load orders. Showing offline cached data if available.',
      );
    }
  }

  void setStatus(String status) {
    state = state.copyWith(selectedStatus: status);
    fetchOrders();
  }

  void setSearch(String query) {
    state = state.copyWith(searchQuery: query);
    fetchOrders();
  }

  Future<bool> updateStatus(int orderId, String newStatus) async {
    try {
      final updated = await _repo.updateOrderStatus(orderId, newStatus);
      state = state.copyWith(
        orders: state.orders.map((o) => o.id == orderId ? updated : o).toList(),
      );
      return true;
    } catch (_) {
      return false;
    }
  }
}

final ordersControllerProvider = StateNotifierProvider<OrdersController, OrdersState>((ref) {
  final repo = ref.watch(ordersRepositoryProvider);
  return OrdersController(repo);
});
