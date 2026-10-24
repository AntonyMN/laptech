class ReportSummaryModel {
  final String generatedAt;
  final FinancialReport financial;
  final OrdersReport orders;
  final InventoryReport inventory;
  final QuotesReport quotes;
  final ContentReport content;
  final UsersReport users;

  ReportSummaryModel({
    required this.generatedAt,
    required this.financial,
    required this.orders,
    required this.inventory,
    required this.quotes,
    required this.content,
    required this.users,
  });

  factory ReportSummaryModel.fromJson(Map<String, dynamic> json) {
    return ReportSummaryModel(
      generatedAt: json['generated_at']?.toString() ?? '',
      financial: FinancialReport.fromJson(json['financial'] as Map<String, dynamic>? ?? {}),
      orders: OrdersReport.fromJson(json['orders'] as Map<String, dynamic>? ?? {}),
      inventory: InventoryReport.fromJson(json['inventory'] as Map<String, dynamic>? ?? {}),
      quotes: QuotesReport.fromJson(json['quotes'] as Map<String, dynamic>? ?? {}),
      content: ContentReport.fromJson(json['content'] as Map<String, dynamic>? ?? {}),
      users: UsersReport.fromJson(json['users'] as Map<String, dynamic>? ?? {}),
    );
  }
}

class FinancialReport {
  final double totalRevenue;
  final double pendingRevenue;
  final double averageOrderValue;
  final String currency;

  FinancialReport({
    required this.totalRevenue,
    required this.pendingRevenue,
    required this.averageOrderValue,
    required this.currency,
  });

  factory FinancialReport.fromJson(Map<String, dynamic> json) {
    return FinancialReport(
      totalRevenue: (json['total_revenue'] as num?)?.toDouble() ?? 0.0,
      pendingRevenue: (json['pending_revenue'] as num?)?.toDouble() ?? 0.0,
      averageOrderValue: (json['average_order_value'] as num?)?.toDouble() ?? 0.0,
      currency: json['currency']?.toString() ?? 'KES',
    );
  }
}

class OrdersReport {
  final int total;
  final int delivered;
  final int pending;
  final int processing;
  final int shipped;
  final int cancelled;
  final double fulfillmentRate;

  OrdersReport({
    required this.total,
    required this.delivered,
    required this.pending,
    required this.processing,
    required this.shipped,
    required this.cancelled,
    required this.fulfillmentRate,
  });

  factory OrdersReport.fromJson(Map<String, dynamic> json) {
    return OrdersReport(
      total: (json['total'] as num?)?.toInt() ?? 0,
      delivered: (json['delivered'] as num?)?.toInt() ?? 0,
      pending: (json['pending'] as num?)?.toInt() ?? 0,
      processing: (json['processing'] as num?)?.toInt() ?? 0,
      shipped: (json['shipped'] as num?)?.toInt() ?? 0,
      cancelled: (json['cancelled'] as num?)?.toInt() ?? 0,
      fulfillmentRate: (json['fulfillment_rate'] as num?)?.toDouble() ?? 0.0,
    );
  }
}

class InventoryReport {
  final int activeProducts;
  final int archivedProducts;
  final int lowStock;
  final int outOfStock;
  final double estimatedValue;

  InventoryReport({
    required this.activeProducts,
    required this.archivedProducts,
    required this.lowStock,
    required this.outOfStock,
    required this.estimatedValue,
  });

  factory InventoryReport.fromJson(Map<String, dynamic> json) {
    return InventoryReport(
      activeProducts: (json['active_products'] as num?)?.toInt() ?? 0,
      archivedProducts: (json['archived_products'] as num?)?.toInt() ?? 0,
      lowStock: (json['low_stock'] as num?)?.toInt() ?? 0,
      outOfStock: (json['out_of_stock'] as num?)?.toInt() ?? 0,
      estimatedValue: (json['estimated_value'] as num?)?.toDouble() ?? 0.0,
    );
  }
}

class QuotesReport {
  final int total;
  final int pending;
  final int responded;
  final int closed;
  final double responseRate;

  QuotesReport({
    required this.total,
    required this.pending,
    required this.responded,
    required this.closed,
    required this.responseRate,
  });

  factory QuotesReport.fromJson(Map<String, dynamic> json) {
    return QuotesReport(
      total: (json['total'] as num?)?.toInt() ?? 0,
      pending: (json['pending'] as num?)?.toInt() ?? 0,
      responded: (json['responded'] as num?)?.toInt() ?? 0,
      closed: (json['closed'] as num?)?.toInt() ?? 0,
      responseRate: (json['response_rate'] as num?)?.toDouble() ?? 0.0,
    );
  }
}

class ContentReport {
  final int totalPosts;
  final int published;
  final int drafts;
  final int totalServices;

  ContentReport({
    required this.totalPosts,
    required this.published,
    required this.drafts,
    required this.totalServices,
  });

  factory ContentReport.fromJson(Map<String, dynamic> json) {
    return ContentReport(
      totalPosts: (json['total_posts'] as num?)?.toInt() ?? 0,
      published: (json['published'] as num?)?.toInt() ?? 0,
      drafts: (json['drafts'] as num?)?.toInt() ?? 0,
      totalServices: (json['total_services'] as num?)?.toInt() ?? 0,
    );
  }
}

class UsersReport {
  final int total;
  final int admins;

  UsersReport({
    required this.total,
    required this.admins,
  });

  factory UsersReport.fromJson(Map<String, dynamic> json) {
    return UsersReport(
      total: (json['total'] as num?)?.toInt() ?? 0,
      admins: (json['admins'] as num?)?.toInt() ?? 0,
    );
  }
}
