import 'package:flutter/material.dart';
import '../theme/app_colors.dart';
import 'service_model.dart';

class QuoteModel {
  final int id;
  final int? serviceId;
  final int? userId;
  final String name;
  final String email;
  final String? phone;
  final String details;
  final String status;
  final String? createdAt;
  final ServiceModel? service;

  QuoteModel({
    required this.id,
    this.serviceId,
    this.userId,
    required this.name,
    required this.email,
    this.phone,
    required this.details,
    this.status = 'pending',
    this.createdAt,
    this.service,
  });

  bool get isPending => status == 'pending';
  bool get isResponded => status == 'responded';
  bool get isClosed => status == 'closed';

  String get serviceName => service?.name ?? 'General Inquiry';

  Color get statusColor {
    switch (status.toLowerCase()) {
      case 'pending':
        return AppColors.warning;
      case 'responded':
        return AppColors.primary;
      case 'closed':
        return AppColors.success;
      default:
        return AppColors.darkMuted;
    }
  }

  factory QuoteModel.fromJson(Map<String, dynamic> json) {
    return QuoteModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      serviceId: json['service_id'] != null ? int.tryParse(json['service_id'].toString()) : null,
      userId: json['user_id'] != null ? int.tryParse(json['user_id'].toString()) : null,
      name: json['name']?.toString() ?? '',
      email: json['email']?.toString() ?? '',
      phone: json['phone']?.toString(),
      details: json['details']?.toString() ?? '',
      status: json['status']?.toString() ?? 'pending',
      createdAt: json['created_at']?.toString(),
      service: json['service'] is Map<String, dynamic> ? ServiceModel.fromJson(json['service']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'service_id': serviceId,
      'user_id': userId,
      'name': name,
      'email': email,
      'phone': phone,
      'details': details,
      'status': status,
      'created_at': createdAt,
      'service': service?.toJson(),
    };
  }
}
