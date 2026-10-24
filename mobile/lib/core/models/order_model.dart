import 'package:flutter/material.dart';
import '../theme/app_colors.dart';

class OrderItemModel {
  final String name;
  final double price;
  final int quantity;
  final String? image;

  OrderItemModel({
    required this.name,
    required this.price,
    required this.quantity,
    this.image,
  });

  factory OrderItemModel.fromJson(Map<String, dynamic> json) {
    return OrderItemModel(
      name: json['name'] ?? json['product_name'] ?? 'Product Item',
      price: json['price'] is num ? (json['price'] as num).toDouble() : double.tryParse(json['price']?.toString() ?? '0') ?? 0.0,
      quantity: json['quantity'] is int ? json['quantity'] : int.tryParse(json['quantity']?.toString() ?? '1') ?? 1,
      image: json['image']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'price': price,
      'quantity': quantity,
      'image': image,
    };
  }
}

class OrderModel {
  final int id;
  final int? userId;
  final List<OrderItemModel> items;
  final double total;
  final String status;
  final Map<String, dynamic> shippingAddress;
  final String? createdAt;

  OrderModel({
    required this.id,
    this.userId,
    required this.items,
    required this.total,
    required this.status,
    required this.shippingAddress,
    this.createdAt,
  });

  String get customerName {
    return shippingAddress['full_name'] ??
        shippingAddress['name'] ??
        shippingAddress['recipient_name'] ??
        'Customer #$id';
  }

  String get customerEmail {
    return shippingAddress['email'] ?? '';
  }

  String get customerPhone {
    return shippingAddress['phone'] ?? '';
  }

  String get deliveryAddress {
    final parts = [
      shippingAddress['address'] ?? shippingAddress['address_line1'],
      shippingAddress['city'],
      shippingAddress['postal_code'],
      shippingAddress['country'],
    ].where((part) => part != null && part.toString().trim().isNotEmpty).toList();

    return parts.isEmpty ? 'Local Pickup / Standard Shipping' : parts.join(', ');
  }

  Color get statusColor {
    switch (status.toLowerCase()) {
      case 'paid':
        return AppColors.success;
      case 'shipped':
        return AppColors.info;
      case 'delivered':
        return AppColors.purple;
      case 'cancelled':
        return AppColors.danger;
      case 'pending':
      default:
        return AppColors.warning;
    }
  }

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    List<OrderItemModel> parsedItems = [];
    if (json['items'] is List) {
      for (final item in json['items']) {
        if (item is Map<String, dynamic>) {
          parsedItems.add(OrderItemModel.fromJson(item));
        }
      }
    }

    Map<String, dynamic> address = {};
    if (json['shipping_address'] is Map) {
      address = Map<String, dynamic>.from(json['shipping_address']);
    }

    return OrderModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      userId: json['user_id'] is int ? json['user_id'] : int.tryParse(json['user_id']?.toString() ?? ''),
      items: parsedItems,
      total: json['total'] is num ? (json['total'] as num).toDouble() : double.tryParse(json['total']?.toString() ?? '0') ?? 0.0,
      status: json['status'] ?? 'pending',
      shippingAddress: address,
      createdAt: json['created_at']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'items': items.map((i) => i.toJson()).toList(),
      'total': total,
      'status': status,
      'shipping_address': shippingAddress,
      'created_at': createdAt,
    };
  }
}
