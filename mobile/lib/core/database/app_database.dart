import 'dart:convert';
import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:path/path.dart';
import 'package:path_provider/path_provider.dart';
import 'package:sqflite_common_ffi/sqflite_ffi.dart';
import '../models/category_model.dart';
import '../models/order_model.dart';
import '../models/product_model.dart';
import '../models/service_model.dart';

class AppDatabase {
  static final AppDatabase instance = AppDatabase._internal();
  AppDatabase._internal();

  Database? _db;

  Future<Database> get database async {
    if (_db != null) return _db!;
    _db = await _initDatabase();
    return _db!;
  }

  Future<Database> _initDatabase() async {
    // If running on desktop (Linux/macOS/Windows) or unit tests, initialize sqflite FFI
    if (!kIsWeb && (Platform.isLinux || Platform.isMacOS || Platform.isWindows)) {
      sqfliteFfiInit();
      databaseFactory = databaseFactoryFfi;
    }

    String dbPath;
    if (kIsWeb) {
      dbPath = 'laptech_admin.db';
    } else {
      try {
        final docDir = await getApplicationDocumentsDirectory();
        dbPath = join(docDir.path, 'laptech_admin_cache.db');
      } catch (_) {
        dbPath = inMemoryDatabasePath;
      }
    }

    return await openDatabase(
      dbPath,
      version: 1,
      onCreate: (db, version) async {
        await db.execute('''
          CREATE TABLE cached_products (
            id INTEGER PRIMARY KEY,
            category_id INTEGER,
            name TEXT,
            slug TEXT,
            description TEXT,
            price REAL,
            stock INTEGER,
            status TEXT,
            is_featured INTEGER,
            is_archived INTEGER,
            image TEXT,
            specifications TEXT,
            category_json TEXT,
            created_at TEXT,
            cached_at TEXT
          )
        ''');

        await db.execute('''
          CREATE TABLE cached_orders (
            id INTEGER PRIMARY KEY,
            user_id INTEGER,
            total REAL,
            status TEXT,
            customer_name TEXT,
            items_count INTEGER,
            shipping_address_json TEXT,
            items_json TEXT,
            created_at TEXT,
            cached_at TEXT
          )
        ''');

        await db.execute('''
          CREATE TABLE cached_services (
            id INTEGER PRIMARY KEY,
            name TEXT,
            slug TEXT,
            description TEXT,
            features_json TEXT,
            icon TEXT,
            image TEXT,
            is_featured INTEGER,
            service_category_id INTEGER,
            category_json TEXT,
            cached_at TEXT
          )
        ''');

        await db.execute('''
          CREATE TABLE cached_dashboard (
            id INTEGER PRIMARY KEY,
            stats_json TEXT,
            alerts_json TEXT,
            quicklinks_json TEXT,
            recent_orders_json TEXT,
            cached_at TEXT
          )
        ''');

        await db.execute('''
          CREATE TABLE cached_categories (
            id INTEGER PRIMARY KEY,
            name TEXT,
            slug TEXT,
            description TEXT,
            active_products_count INTEGER
          )
        ''');
      },
    );
  }

  // ---- Products Caching ----
  Future<void> cacheProducts(List<ProductModel> products) async {
    final db = await database;
    final batch = db.batch();
    final now = DateTime.now().toIso8601String();

    for (final p in products) {
      batch.insert(
        'cached_products',
        {
          'id': p.id,
          'category_id': p.categoryId,
          'name': p.name,
          'slug': p.slug,
          'description': p.description,
          'price': p.price,
          'stock': p.stock,
          'status': p.status,
          'is_featured': p.isFeatured ? 1 : 0,
          'is_archived': p.isArchived ? 1 : 0,
          'image': p.image,
          'specifications': p.specifications != null ? jsonEncode(p.specifications) : null,
          'category_json': p.category != null ? jsonEncode(p.category!.toJson()) : null,
          'created_at': p.createdAt,
          'cached_at': now,
        },
        conflictAlgorithm: ConflictAlgorithm.replace,
      );
    }
    await batch.commit(noResult: true);
  }

  Future<List<ProductModel>> getCachedProducts({
    String? search,
    int? categoryId,
    String? archiveFilter, // 'active', 'archived', 'all'
  }) async {
    final db = await database;
    String whereClause = '1=1';
    List<dynamic> args = [];

    if (archiveFilter == 'active' || archiveFilter == null) {
      whereClause += ' AND is_archived = 0';
    } else if (archiveFilter == 'archived') {
      whereClause += ' AND is_archived = 1';
    }

    if (categoryId != null && categoryId > 0) {
      whereClause += ' AND category_id = ?';
      args.add(categoryId);
    }

    if (search != null && search.trim().isNotEmpty) {
      whereClause += ' AND (name LIKE ? OR description LIKE ?)';
      final term = '%${search.trim()}%';
      args.add(term);
      args.add(term);
    }

    final rows = await db.query(
      'cached_products',
      where: whereClause,
      whereArgs: args,
      orderBy: 'id DESC',
    );

    return rows.map((row) {
      CategoryModel? cat;
      if (row['category_json'] != null) {
        try {
          cat = CategoryModel.fromJson(jsonDecode(row['category_json'] as String));
        } catch (_) {}
      }

      Map<String, dynamic>? specs;
      if (row['specifications'] != null) {
        try {
          specs = jsonDecode(row['specifications'] as String);
        } catch (_) {}
      }

      return ProductModel(
        id: row['id'] as int,
        categoryId: row['category_id'] as int,
        name: row['name'] as String? ?? '',
        slug: row['slug'] as String? ?? '',
        description: row['description'] as String? ?? '',
        price: (row['price'] as num?)?.toDouble() ?? 0.0,
        stock: (row['stock'] as int?) ?? 0,
        status: row['status'] as String? ?? 'Brand new',
        isFeatured: (row['is_featured'] as int?) == 1,
        isArchived: (row['is_archived'] as int?) == 1,
        image: row['image'] as String?,
        specifications: specs,
        category: cat,
        createdAt: row['created_at'] as String?,
      );
    }).toList();
  }

  // ---- Orders Caching ----
  Future<void> cacheOrders(List<OrderModel> orders) async {
    final db = await database;
    final batch = db.batch();
    final now = DateTime.now().toIso8601String();

    for (final o in orders) {
      batch.insert(
        'cached_orders',
        {
          'id': o.id,
          'user_id': o.userId,
          'total': o.total,
          'status': o.status,
          'customer_name': o.customerName,
          'items_count': o.items.length,
          'shipping_address_json': jsonEncode(o.shippingAddress),
          'items_json': jsonEncode(o.items.map((i) => i.toJson()).toList()),
          'created_at': o.createdAt,
          'cached_at': now,
        },
        conflictAlgorithm: ConflictAlgorithm.replace,
      );
    }
    await batch.commit(noResult: true);
  }

  Future<List<OrderModel>> getCachedOrders({String? status, String? search}) async {
    final db = await database;
    String whereClause = '1=1';
    List<dynamic> args = [];

    if (status != null && status != 'all' && status.isNotEmpty) {
      whereClause += ' AND status = ?';
      args.add(status);
    }

    if (search != null && search.trim().isNotEmpty) {
      whereClause += ' AND (customer_name LIKE ? OR id LIKE ?)';
      final term = '%${search.trim()}%';
      args.add(term);
      args.add(term);
    }

    final rows = await db.query(
      'cached_orders',
      where: whereClause,
      whereArgs: args,
      orderBy: 'id DESC',
    );

    return rows.map((row) {
      Map<String, dynamic> address = {};
      if (row['shipping_address_json'] != null) {
        try {
          address = jsonDecode(row['shipping_address_json'] as String);
        } catch (_) {}
      }

      List<OrderItemModel> items = [];
      if (row['items_json'] != null) {
        try {
          final decoded = jsonDecode(row['items_json'] as String) as List;
          items = decoded.map((i) => OrderItemModel.fromJson(i)).toList();
        } catch (_) {}
      }

      return OrderModel(
        id: row['id'] as int,
        userId: row['user_id'] as int?,
        items: items,
        total: (row['total'] as num?)?.toDouble() ?? 0.0,
        status: row['status'] as String? ?? 'pending',
        shippingAddress: address,
        createdAt: row['created_at'] as String?,
      );
    }).toList();
  }

  // ---- Services Caching ----
  Future<void> cacheServices(List<ServiceModel> services) async {
    final db = await database;
    final batch = db.batch();
    final now = DateTime.now().toIso8601String();

    for (final s in services) {
      batch.insert(
        'cached_services',
        {
          'id': s.id,
          'name': s.name,
          'slug': s.slug,
          'description': s.description,
          'features_json': jsonEncode(s.features),
          'icon': s.icon,
          'image': s.image,
          'is_featured': s.isFeatured ? 1 : 0,
          'service_category_id': s.serviceCategoryId,
          'category_json': s.category != null ? jsonEncode(s.category!.toJson()) : null,
          'cached_at': now,
        },
        conflictAlgorithm: ConflictAlgorithm.replace,
      );
    }
    await batch.commit(noResult: true);
  }

  Future<List<ServiceModel>> getCachedServices({int? categoryId, String? search}) async {
    final db = await database;
    String whereClause = '1=1';
    List<dynamic> args = [];

    if (categoryId != null && categoryId > 0) {
      whereClause += ' AND service_category_id = ?';
      args.add(categoryId);
    }

    if (search != null && search.trim().isNotEmpty) {
      whereClause += ' AND (name LIKE ? OR description LIKE ?)';
      final term = '%${search.trim()}%';
      args.add(term);
      args.add(term);
    }

    final rows = await db.query(
      'cached_services',
      where: whereClause,
      whereArgs: args,
      orderBy: 'name ASC',
    );

    return rows.map((row) {
      List<String> features = [];
      if (row['features_json'] != null) {
        try {
          features = (jsonDecode(row['features_json'] as String) as List).map((e) => e.toString()).toList();
        } catch (_) {}
      }

      ServiceCategoryModel? cat;
      if (row['category_json'] != null) {
        try {
          cat = ServiceCategoryModel.fromJson(jsonDecode(row['category_json'] as String));
        } catch (_) {}
      }

      return ServiceModel(
        id: row['id'] as int,
        name: row['name'] as String? ?? '',
        slug: row['slug'] as String? ?? '',
        description: row['description'] as String? ?? '',
        features: features,
        icon: row['icon'] as String?,
        image: row['image'] as String?,
        isFeatured: (row['is_featured'] as int?) == 1,
        serviceCategoryId: row['service_category_id'] as int?,
        category: cat,
      );
    }).toList();
  }

  // ---- Dashboard Caching ----
  Future<void> cacheDashboard({
    required Map<String, dynamic> stats,
    required List<dynamic> alerts,
    required List<dynamic> quickLinks,
    required List<dynamic> recentOrders,
  }) async {
    final db = await database;
    final now = DateTime.now().toIso8601String();

    await db.insert(
      'cached_dashboard',
      {
        'id': 1,
        'stats_json': jsonEncode(stats),
        'alerts_json': jsonEncode(alerts),
        'quicklinks_json': jsonEncode(quickLinks),
        'recent_orders_json': jsonEncode(recentOrders),
        'cached_at': now,
      },
      conflictAlgorithm: ConflictAlgorithm.replace,
    );
  }

  Future<Map<String, dynamic>?> getCachedDashboard() async {
    final db = await database;
    final rows = await db.query('cached_dashboard', where: 'id = 1');
    if (rows.isEmpty) return null;

    final row = rows.first;
    return {
      'stats': jsonDecode(row['stats_json'] as String),
      'alerts': jsonDecode(row['alerts_json'] as String),
      'quick_links': jsonDecode(row['quicklinks_json'] as String),
      'recent_orders': jsonDecode(row['recent_orders_json'] as String),
      'cached_at': row['cached_at'],
    };
  }

  Future<void> clearAllCache() async {
    final db = await database;
    await db.delete('cached_products');
    await db.delete('cached_orders');
    await db.delete('cached_services');
    await db.delete('cached_dashboard');
    await db.delete('cached_categories');
  }
}
