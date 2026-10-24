class DashboardStats {
  final double revenue;
  final int totalOrders;
  final int pendingOrders;
  final int deliveredOrders;
  final int totalProducts;
  final int archivedProducts;
  final int lowStockCount;
  final int outOfStockCount;
  final int totalServices;
  final int totalQuotes;
  final int totalUsers;

  DashboardStats({
    this.revenue = 0.0,
    this.totalOrders = 0,
    this.pendingOrders = 0,
    this.deliveredOrders = 0,
    this.totalProducts = 0,
    this.archivedProducts = 0,
    this.lowStockCount = 0,
    this.outOfStockCount = 0,
    this.totalServices = 0,
    this.totalQuotes = 0,
    this.totalUsers = 0,
  });

  factory DashboardStats.fromJson(Map<String, dynamic> json) {
    return DashboardStats(
      revenue: json['revenue'] is num ? (json['revenue'] as num).toDouble() : double.tryParse(json['revenue']?.toString() ?? '0') ?? 0.0,
      totalOrders: json['total_orders'] is int ? json['total_orders'] : int.tryParse(json['total_orders']?.toString() ?? '0') ?? 0,
      pendingOrders: json['pending_orders'] is int ? json['pending_orders'] : int.tryParse(json['pending_orders']?.toString() ?? '0') ?? 0,
      deliveredOrders: json['delivered_orders'] is int ? json['delivered_orders'] : int.tryParse(json['delivered_orders']?.toString() ?? '0') ?? 0,
      totalProducts: json['total_products'] is int ? json['total_products'] : int.tryParse(json['total_products']?.toString() ?? '0') ?? 0,
      archivedProducts: json['archived_products'] is int ? json['archived_products'] : int.tryParse(json['archived_products']?.toString() ?? '0') ?? 0,
      lowStockCount: json['low_stock_count'] is int ? json['low_stock_count'] : int.tryParse(json['low_stock_count']?.toString() ?? '0') ?? 0,
      outOfStockCount: json['out_of_stock_count'] is int ? json['out_of_stock_count'] : int.tryParse(json['out_of_stock_count']?.toString() ?? '0') ?? 0,
      totalServices: json['total_services'] is int ? json['total_services'] : int.tryParse(json['total_services']?.toString() ?? '0') ?? 0,
      totalQuotes: json['total_quotes'] is int ? json['total_quotes'] : int.tryParse(json['total_quotes']?.toString() ?? '0') ?? 0,
      totalUsers: json['total_users'] is int ? json['total_users'] : int.tryParse(json['total_users']?.toString() ?? '0') ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'revenue': revenue,
      'total_orders': totalOrders,
      'pending_orders': pendingOrders,
      'delivered_orders': deliveredOrders,
      'total_products': totalProducts,
      'archived_products': archivedProducts,
      'low_stock_count': lowStockCount,
      'out_of_stock_count': outOfStockCount,
      'total_services': totalServices,
      'total_quotes': totalQuotes,
      'total_users': totalUsers,
    };
  }
}

class DashboardAlert {
  final String id;
  final String type;
  final String title;
  final String message;
  final int count;
  final String actionTab;

  DashboardAlert({
    required this.id,
    required this.type,
    required this.title,
    required this.message,
    required this.count,
    required this.actionTab,
  });

  factory DashboardAlert.fromJson(Map<String, dynamic> json) {
    return DashboardAlert(
      id: json['id'] ?? '',
      type: json['type'] ?? 'info',
      title: json['title'] ?? '',
      message: json['message'] ?? '',
      count: json['count'] is int ? json['count'] : int.tryParse(json['count']?.toString() ?? '0') ?? 0,
      actionTab: json['action_tab'] ?? 'home',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'type': type,
      'title': title,
      'message': message,
      'count': count,
      'action_tab': actionTab,
    };
  }
}

class QuickLink {
  final String id;
  final String title;
  final String icon;
  final String category;
  final String? badge;
  final String target;

  QuickLink({
    required this.id,
    required this.title,
    required this.icon,
    required this.category,
    this.badge,
    required this.target,
  });

  factory QuickLink.fromJson(Map<String, dynamic> json) {
    return QuickLink(
      id: json['id'] ?? '',
      title: json['title'] ?? '',
      icon: json['icon'] ?? 'star',
      category: json['category'] ?? '',
      badge: json['badge']?.toString(),
      target: json['target'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'icon': icon,
      'category': category,
      'badge': badge,
      'target': target,
    };
  }
}
