import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:laptech_admin/core/database/app_database.dart';
import 'package:laptech_admin/core/models/category_model.dart';
import 'package:laptech_admin/core/models/dashboard_stats_model.dart';
import 'package:laptech_admin/core/models/order_model.dart';
import 'package:laptech_admin/core/models/product_model.dart';
import 'package:laptech_admin/core/models/service_model.dart';
import 'package:laptech_admin/core/models/user_model.dart';
import 'package:laptech_admin/core/utils/currency_formatter.dart';
import 'package:laptech_admin/main.dart';
import 'package:sqflite_common_ffi/sqflite_ffi.dart';

void main() {
  setUpAll(() {
    sqfliteFfiInit();
    databaseFactory = databaseFactoryFfi;
  });

  group('Model Serialization Tests', () {
    test('UserModel serialization and admin checks', () {
      final user = UserModel.fromJson({
        'id': 1,
        'name': 'Admin User',
        'email': 'admin@laptech.com',
        'is_admin': true,
        'roles': ['admin', 'manager'],
        'permissions': ['access admin', 'manage products'],
      });

      expect(user.id, 1);
      expect(user.name, 'Admin User');
      expect(user.isAdmin, isTrue);
      expect(user.roles.contains('admin'), isTrue);
      expect(user.permissions.contains('manage products'), isTrue);

      final json = user.toJson();
      expect(json['email'], 'admin@laptech.com');
    });

    test('ProductModel serialization and Image URL resolution', () {
      final product = ProductModel.fromJson({
        'id': 10,
        'category_id': 2,
        'name': 'Dell PowerEdge R760',
        'slug': 'dell-poweredge-r760',
        'description': 'High performance rack server',
        'price': 450000,
        'stock': 4,
        'status': 'Brand new',
        'is_featured': true,
        'is_archived': false,
        'image': '/images/dell_poweredge_r760.png',
        'specifications': {'CPU': 'Intel Xeon Gold', 'RAM': '128GB'},
        'category': {'id': 2, 'name': 'Servers', 'slug': 'servers'},
      });

      expect(product.id, 10);
      expect(product.name, 'Dell PowerEdge R760');
      expect(product.price, 450000.0);
      expect(product.stock, 4);
      expect(product.category?.name, 'Servers');
      expect(product.specifications?['RAM'], '128GB');

      // Test URL resolution
      final resolved = product.resolveImageUrl('http://127.0.0.1:8000');
      expect(resolved, 'http://127.0.0.1:8000/images/dell_poweredge_r760.png');

      final resolvedOnline = product.resolveImageUrl('https://laptech.co.ke/api');
      expect(resolvedOnline, 'https://laptech.co.ke/images/dell_poweredge_r760.png');

      final directProduct = ProductModel(
        id: 11,
        categoryId: 1,
        name: 'Remote Image Laptop',
        slug: 'laptop-1',
        description: 'Test',
        price: 50000,
        stock: 5,
        status: 'Brand new',
        image: 'https://images.unsplash.com/photo-test.jpg',
      );
      expect(directProduct.resolveImageUrl('http://127.0.0.1:8000'), 'https://images.unsplash.com/photo-test.jpg');
    });

    test('OrderModel item calculation and customer helpers', () {
      final order = OrderModel.fromJson({
        'id': 105,
        'user_id': 3,
        'total': 18500.50,
        'status': 'shipped',
        'shipping_address': {
          'full_name': 'Sarah Connor',
          'email': 'sarah@skynet.com',
          'phone': '+254712345678',
          'city': 'Nairobi',
          'address': 'Westlands Commercial Center',
        },
        'items': [
          {'name': 'Logitech MX Master 3S', 'price': 14500.0, 'quantity': 1},
          {'name': 'Laptop Stand', 'price': 4000.50, 'quantity': 1},
        ],
      });

      expect(order.id, 105);
      expect(order.customerName, 'Sarah Connor');
      expect(order.customerEmail, 'sarah@skynet.com');
      expect(order.items.length, 2);
      expect(order.total, 18500.50);
      expect(order.status, 'shipped');
      expect(order.deliveryAddress.contains('Westlands'), isTrue);
    });

    test('ServiceModel features parsing', () {
      final service = ServiceModel.fromJson({
        'id': 5,
        'name': 'Cloud Migration',
        'slug': 'cloud-migration',
        'description': 'Seamless cloud migration with zero downtime',
        'features': ['AWS & Azure Setup', 'Database Migration', '24/7 SLA Support'],
        'icon': 'cloud',
        'is_featured': true,
      });

      expect(service.id, 5);
      expect(service.features.length, 3);
      expect(service.features[0], 'AWS & Azure Setup');
      expect(service.isFeatured, isTrue);
    });

    test('DashboardStats and CurrencyFormatter', () {
      final stats = DashboardStats.fromJson({
        'revenue': 1250000.75,
        'total_orders': 45,
        'pending_orders': 6,
        'total_products': 98,
        'low_stock_count': 3,
      });

      expect(stats.revenue, 1250000.75);
      expect(stats.totalOrders, 45);
      expect(stats.lowStockCount, 3);

      final formatted = CurrencyFormatter.format(stats.revenue);
      expect(formatted.contains('1,250,000.75'), isTrue);
    });
  });

  group('SQLite Database Cache Integration Tests', () {
    test('Can write and read products from SQLite cache', () async {
      final db = AppDatabase.instance;

      final testProducts = [
        ProductModel(
          id: 991,
          categoryId: 1,
          name: 'Cached ThinkPad T14',
          slug: 'cached-thinkpad-t14',
          description: 'Offline cached test product',
          price: 95000,
          stock: 2,
          status: 'Ex-UK',
          isArchived: false,
          category: CategoryModel(id: 1, name: 'Laptops', slug: 'laptops'),
        ),
      ];

      await db.cacheProducts(testProducts);
      final retrieved = await db.getCachedProducts(search: 'ThinkPad');

      expect(retrieved.isNotEmpty, isTrue);
      expect(retrieved.first.id, 991);
      expect(retrieved.first.name, 'Cached ThinkPad T14');
      expect(retrieved.first.price, 95000.0);
    });

    test('Can write and read orders from SQLite cache', () async {
      final db = AppDatabase.instance;

      final testOrders = [
        OrderModel(
          id: 501,
          total: 8000.0,
          status: 'pending',
          shippingAddress: {'name': 'John Doe', 'city': 'Mombasa'},
          items: [OrderItemModel(name: 'Wireless Mouse', price: 4000, quantity: 2)],
        ),
      ];

      await db.cacheOrders(testOrders);
      final retrieved = await db.getCachedOrders(status: 'pending');

      expect(retrieved.isNotEmpty, isTrue);
      expect(retrieved.first.id, 501);
      expect(retrieved.first.customerName, 'John Doe');
    });

    test('Can write and read dashboard snapshot from SQLite cache', () async {
      final db = AppDatabase.instance;

      await db.cacheDashboard(
        stats: {'revenue': 50000.0, 'total_orders': 10},
        alerts: [{'id': 'test_alert', 'title': 'Test Warning', 'type': 'warning', 'message': 'Msg', 'count': 1, 'action_tab': 'products'}],
        quickLinks: [],
        recentOrders: [],
      );

      final cached = await db.getCachedDashboard();
      expect(cached, isNotNull);
      expect(cached!['stats']['revenue'], 50000.0);
    });
  });

  group('Widget Application Tests', () {
    testWidgets('LaptechAdminApp renders splash screen', (WidgetTester tester) async {
      await tester.pumpWidget(
        const ProviderScope(
          child: LaptechAdminApp(),
        ),
      );

      // Verify Laptech branding title is displayed
      expect(find.text('LAPTECH'), findsOneWidget);
      expect(find.text('ADMINISTRATOR ACCESS'), findsOneWidget);
    });
  });
}
