import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/order_model.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/currency_formatter.dart';
import 'orders_controller.dart';

class OrderDetailScreen extends ConsumerStatefulWidget {
  final OrderModel order;

  const OrderDetailScreen({super.key, required this.order});

  @override
  ConsumerState<OrderDetailScreen> createState() => _OrderDetailScreenState();
}

class _OrderDetailScreenState extends ConsumerState<OrderDetailScreen> {
  late OrderModel _currentOrder;
  bool _isUpdating = false;

  final List<String> _validStatuses = [
    'pending',
    'paid',
    'shipped',
    'delivered',
    'cancelled',
  ];

  @override
  void initState() {
    super.initState();
    _currentOrder = widget.order;
  }

  Future<void> _changeStatus(String newStatus) async {
    if (_isUpdating || newStatus == _currentOrder.status) return;

    setState(() => _isUpdating = true);
    final success = await ref.read(ordersControllerProvider.notifier).updateStatus(
          _currentOrder.id,
          newStatus,
        );

    if (mounted) {
      if (success) {
        setState(() {
          _currentOrder = OrderModel(
            id: _currentOrder.id,
            userId: _currentOrder.userId,
            items: _currentOrder.items,
            total: _currentOrder.total,
            status: newStatus,
            shippingAddress: _currentOrder.shippingAddress,
            createdAt: _currentOrder.createdAt,
          );
        });
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: AppColors.success,
            content: Text('Order status changed to ${newStatus.toUpperCase()}'),
          ),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            backgroundColor: AppColors.danger,
            content: Text('Failed to update order status. Please check connection.'),
          ),
        );
      }
      setState(() => _isUpdating = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: Text('Order #${_currentOrder.id} Details'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Status Card with Status Change Action
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.darkSurface,
                borderRadius: BorderRadius.circular(14),
                border: Border.all(color: AppColors.darkBorder),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'ORDER STATUS',
                            style: TextStyle(
                              fontSize: 10,
                              fontWeight: FontWeight.bold,
                              letterSpacing: 1.2,
                              color: AppColors.darkMuted,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                            decoration: BoxDecoration(
                              color: _currentOrder.statusColor.withValues(alpha: 0.15),
                              borderRadius: BorderRadius.circular(8),
                              border: Border.all(color: _currentOrder.statusColor.withValues(alpha: 0.5)),
                            ),
                            child: Text(
                              _currentOrder.status.toUpperCase(),
                              style: TextStyle(
                                color: _currentOrder.statusColor,
                                fontWeight: FontWeight.bold,
                                fontSize: 12,
                              ),
                            ),
                          ),
                        ],
                      ),
                      if (_isUpdating)
                        const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(strokeWidth: 2, color: AppColors.primary),
                        )
                      else
                        DropdownButton<String>(
                          value: _currentOrder.status,
                          dropdownColor: AppColors.darkSurface,
                          underline: const SizedBox(),
                          icon: const Icon(Icons.arrow_drop_down, color: AppColors.primary),
                          style: const TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w600),
                          items: _validStatuses
                              .map((s) => DropdownMenuItem(
                                    value: s,
                                    child: Text(s.toUpperCase()),
                                  ))
                              .toList(),
                          onChanged: (newVal) {
                            if (newVal != null) _changeStatus(newVal);
                          },
                        ),
                    ],
                  ),
                  const Divider(color: AppColors.darkBorder, height: 24),
                  Row(
                    children: [
                      const Icon(Icons.calendar_today_outlined, size: 14, color: AppColors.darkMuted),
                      const SizedBox(width: 8),
                      Text(
                        'Placed on: ${CurrencyFormatter.formatDate(_currentOrder.createdAt)}',
                        style: const TextStyle(color: AppColors.darkMuted, fontSize: 12),
                      ),
                    ],
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // Customer & Delivery Address Card
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.darkSurface,
                borderRadius: BorderRadius.circular(14),
                border: Border.all(color: AppColors.darkBorder),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'CUSTOMER & SHIPPING DETAILS',
                    style: TextStyle(
                      fontFamily: 'Montserrat',
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                      letterSpacing: 1.2,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 12),
                  _buildDetailRow(Icons.person_outline, 'Customer Name', _currentOrder.customerName),
                  if (_currentOrder.customerEmail.isNotEmpty)
                    _buildDetailRow(Icons.email_outlined, 'Email', _currentOrder.customerEmail),
                  if (_currentOrder.customerPhone.isNotEmpty)
                    _buildDetailRow(Icons.phone_outlined, 'Phone', _currentOrder.customerPhone),
                  _buildDetailRow(Icons.location_on_outlined, 'Delivery Address', _currentOrder.deliveryAddress),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // Itemized Breakdown Card
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.darkSurface,
                borderRadius: BorderRadius.circular(14),
                border: Border.all(color: AppColors.darkBorder),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'ORDER ITEMS',
                        style: TextStyle(
                          fontFamily: 'Montserrat',
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                          letterSpacing: 1.2,
                          color: Colors.white,
                        ),
                      ),
                      Text(
                        '${_currentOrder.items.length} item(s)',
                        style: const TextStyle(color: AppColors.darkMuted, fontSize: 12),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  if (_currentOrder.items.isEmpty)
                    const Padding(
                      padding: EdgeInsets.symmetric(vertical: 8),
                      child: Text('No item details available.', style: TextStyle(color: AppColors.darkMuted)),
                    )
                  else
                    ..._currentOrder.items.map((item) => Padding(
                          padding: const EdgeInsets.only(bottom: 12),
                          child: Row(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Container(
                                padding: const EdgeInsets.all(8),
                                decoration: BoxDecoration(
                                  color: AppColors.darkSurfaceMuted,
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                child: const Icon(Icons.laptop, size: 20, color: AppColors.primary),
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      item.name,
                                      style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 13),
                                    ),
                                    const SizedBox(height: 2),
                                    Text(
                                      'Qty: ${item.quantity} × ${CurrencyFormatter.format(item.price)}',
                                      style: const TextStyle(color: AppColors.darkMuted, fontSize: 11),
                                    ),
                                  ],
                                ),
                              ),
                              Text(
                                CurrencyFormatter.format(item.price * item.quantity),
                                style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
                              ),
                            ],
                          ),
                        )),
                  const Divider(color: AppColors.darkBorder, height: 20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'GRAND TOTAL',
                        style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.white),
                      ),
                      Text(
                        CurrencyFormatter.format(_currentOrder.total),
                        style: const TextStyle(
                          fontFamily: 'Montserrat',
                          fontWeight: FontWeight.w800,
                          fontSize: 18,
                          color: AppColors.primaryBright,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 16, color: AppColors.primary),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: const TextStyle(color: AppColors.darkMuted, fontSize: 10)),
                const SizedBox(height: 2),
                Text(value, style: const TextStyle(color: Colors.white, fontSize: 13)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
