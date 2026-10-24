import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/api/api_client.dart';
import '../../core/models/product_model.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/currency_formatter.dart';
import '../common/image_viewer_screen.dart';
import 'product_edit_screen.dart';
import 'products_controller.dart';

class ProductsScreen extends ConsumerStatefulWidget {
  const ProductsScreen({super.key});

  @override
  ConsumerState<ProductsScreen> createState() => _ProductsScreenState();
}

class _ProductsScreenState extends ConsumerState<ProductsScreen> {
  final _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(productsControllerProvider.notifier).fetchCategories();
      ref.read(productsControllerProvider.notifier).fetchProducts();
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _openImageViewer(ProductModel product) {
    final resolved = product.resolveImageUrl(ApiClient.instance.baseUrl);
    if (resolved != null) {
      Navigator.of(context).push(
        MaterialPageRoute(
          builder: (_) => ImageViewerScreen(
            imageSource: resolved,
            heroTag: 'product_image_${product.id}',
            title: product.name,
          ),
        ),
      );
    }
  }

  Future<void> _confirmDelete(ProductModel product) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.darkSurface,
        title: const Text('Delete Product', style: TextStyle(color: Colors.white)),
        content: Text('Are you sure you want to permanently delete "${product.name}"? This action cannot be undone.',
            style: const TextStyle(color: AppColors.darkMuted)),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(false),
            child: const Text('CANCEL'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.of(ctx).pop(true),
            child: const Text('DELETE'),
          ),
        ],
      ),
    );

    if (confirmed == true) {
      final success = await ref.read(productsControllerProvider.notifier).deleteProduct(product.id);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: success ? AppColors.success : AppColors.danger,
            content: Text(success ? 'Product deleted.' : 'Failed to delete product.'),
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(productsControllerProvider);
    final baseUrl = ApiClient.instance.baseUrl;

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: const Text('Catalog Management'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(productsControllerProvider.notifier).fetchProducts(),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.add),
        label: const Text('Add Product', style: TextStyle(fontWeight: FontWeight.bold)),
        onPressed: () {
          Navigator.of(context).push(
            MaterialPageRoute(builder: (_) => const ProductEditScreen()),
          );
        },
      ),
      body: Column(
        children: [
          // Search & Filter Header
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            color: AppColors.darkSurface,
            child: Column(
              children: [
                // Search Bar
                TextField(
                  controller: _searchController,
                  style: const TextStyle(color: Colors.white, fontSize: 14),
                  decoration: InputDecoration(
                    hintText: 'Search products by title or specs...',
                    prefixIcon: const Icon(Icons.search, color: AppColors.primary, size: 20),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear, size: 18, color: AppColors.darkMuted),
                            onPressed: () {
                              _searchController.clear();
                              ref.read(productsControllerProvider.notifier).setSearch('');
                            },
                          )
                        : null,
                    isDense: true,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                  ),
                  onSubmitted: (val) {
                    ref.read(productsControllerProvider.notifier).setSearch(val);
                  },
                ),
                const SizedBox(height: 10),

                // Archive & Low Stock Filter Chips
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: [
                      _buildFilterChip(
                        label: 'Active',
                        isSelected: state.archiveFilter == 'active',
                        onTap: () => ref.read(productsControllerProvider.notifier).setArchiveFilter('active'),
                      ),
                      const SizedBox(width: 8),
                      _buildFilterChip(
                        label: 'Archived',
                        isSelected: state.archiveFilter == 'archived',
                        onTap: () => ref.read(productsControllerProvider.notifier).setArchiveFilter('archived'),
                      ),
                      const SizedBox(width: 8),
                      _buildFilterChip(
                        label: 'All Items',
                        isSelected: state.archiveFilter == 'all',
                        onTap: () => ref.read(productsControllerProvider.notifier).setArchiveFilter('all'),
                      ),
                      const SizedBox(width: 8),
                      _buildFilterChip(
                        label: '⚠️ Low Stock (≤3)',
                        isSelected: state.lowStockOnly,
                        onTap: () => ref.read(productsControllerProvider.notifier).toggleLowStock(),
                        accentColor: AppColors.warning,
                      ),
                    ],
                  ),
                ),

                // Category Chips if available
                if (state.categories.isNotEmpty) ...[
                  const SizedBox(height: 8),
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        ActionChip(
                          label: const Text('All Categories'),
                          backgroundColor: state.selectedCategoryId == null ? AppColors.primary : AppColors.darkSurfaceMuted,
                          labelStyle: TextStyle(
                            color: state.selectedCategoryId == null ? Colors.white : AppColors.darkMuted,
                            fontSize: 11,
                          ),
                          onPressed: () => ref.read(productsControllerProvider.notifier).setCategory(null),
                        ),
                        ...state.categories.map((c) => Padding(
                              padding: const EdgeInsets.only(left: 6),
                              child: ActionChip(
                                label: Text(c.name),
                                backgroundColor: state.selectedCategoryId == c.id ? AppColors.primary : AppColors.darkSurfaceMuted,
                                labelStyle: TextStyle(
                                  color: state.selectedCategoryId == c.id ? Colors.white : AppColors.darkMuted,
                                  fontSize: 11,
                                ),
                                onPressed: () => ref.read(productsControllerProvider.notifier).setCategory(c.id),
                              ),
                            )),
                      ],
                    ),
                  ),
                ],
              ],
            ),
          ),

          // Product List
          Expanded(
            child: RefreshIndicator(
              color: AppColors.primary,
              onRefresh: () => ref.read(productsControllerProvider.notifier).fetchProducts(),
              child: state.isLoading && state.products.isEmpty
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : state.products.isEmpty
                      ? ListView(
                          children: const [
                            SizedBox(height: 80),
                            Center(
                              child: Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(Icons.inventory_2_outlined, size: 48, color: AppColors.darkMuted),
                                  SizedBox(height: 12),
                                  Text(
                                    'No products match your filters',
                                    style: TextStyle(color: Colors.white, fontSize: 16),
                                  ),
                                  SizedBox(height: 6),
                                  Text(
                                    'Try adjusting search terms or add a new product',
                                    style: TextStyle(color: AppColors.darkMuted, fontSize: 12),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.only(left: 16, right: 16, top: 12, bottom: 84),
                          itemCount: state.products.length,
                          itemBuilder: (ctx, index) {
                            final p = state.products[index];
                            final imageUrl = p.resolveImageUrl(baseUrl);

                            return Container(
                              margin: const EdgeInsets.only(bottom: 12),
                              decoration: BoxDecoration(
                                color: AppColors.darkSurface,
                                borderRadius: BorderRadius.circular(14),
                                border: Border.all(
                                  color: p.stock <= 3
                                      ? AppColors.warning.withValues(alpha: 0.5)
                                      : AppColors.darkBorder,
                                ),
                              ),
                              child: Padding(
                                padding: const EdgeInsets.all(12),
                                child: Row(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    // Image Thumbnail with Zoom Prompt
                                    GestureDetector(
                                      onTap: () => _openImageViewer(p),
                                      child: Stack(
                                        children: [
                                          ClipRRect(
                                            borderRadius: BorderRadius.circular(10),
                                            child: Container(
                                              width: 80,
                                              height: 80,
                                              color: AppColors.darkSurfaceMuted,
                                              child: imageUrl != null
                                                  ? Hero(
                                                      tag: 'product_image_${p.id}',
                                                      child: CachedNetworkImage(
                                                        imageUrl: imageUrl,
                                                        fit: BoxFit.cover,
                                                        placeholder: (_, __) => const Center(
                                                          child: CircularProgressIndicator(strokeWidth: 2, color: AppColors.primary),
                                                        ),
                                                        errorWidget: (_, __, ___) => const Icon(
                                                          Icons.laptop_chromebook,
                                                          size: 36,
                                                          color: AppColors.darkMuted,
                                                        ),
                                                      ),
                                                    )
                                                  : const Icon(
                                                      Icons.devices_other,
                                                      size: 36,
                                                      color: AppColors.darkMuted,
                                                    ),
                                            ),
                                          ),
                                          Positioned(
                                            bottom: 4,
                                            right: 4,
                                            child: Container(
                                              padding: const EdgeInsets.all(3),
                                              decoration: BoxDecoration(
                                                color: Colors.black.withValues(alpha: 0.6),
                                                shape: BoxShape.circle,
                                              ),
                                              child: const Icon(Icons.zoom_in, size: 12, color: Colors.white),
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                    const SizedBox(width: 12),

                                    // Product Details
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Row(
                                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                            children: [
                                              Expanded(
                                                child: Text(
                                                  p.name,
                                                  maxLines: 1,
                                                  overflow: TextOverflow.ellipsis,
                                                  style: const TextStyle(
                                                    color: Colors.white,
                                                    fontWeight: FontWeight.bold,
                                                    fontSize: 14,
                                                  ),
                                                ),
                                              ),
                                              // Card Menu
                                              PopupMenuButton<String>(
                                                icon: const Icon(Icons.more_vert, size: 18, color: AppColors.darkMuted),
                                                color: AppColors.darkSurface,
                                                onSelected: (val) {
                                                  if (val == 'edit') {
                                                    Navigator.of(context).push(
                                                      MaterialPageRoute(builder: (_) => ProductEditScreen(product: p)),
                                                    );
                                                  } else if (val == 'archive') {
                                                    p.isArchived
                                                        ? ref.read(productsControllerProvider.notifier).restoreProduct(p.id)
                                                        : ref.read(productsControllerProvider.notifier).archiveProduct(p.id);
                                                  } else if (val == 'delete') {
                                                    _confirmDelete(p);
                                                  }
                                                },
                                                itemBuilder: (_) => [
                                                  const PopupMenuItem(
                                                    value: 'edit',
                                                    child: Row(
                                                      children: [
                                                        Icon(Icons.edit, size: 16, color: Colors.white70),
                                                        SizedBox(width: 8),
                                                        Text('Edit', style: TextStyle(color: Colors.white, fontSize: 13)),
                                                      ],
                                                    ),
                                                  ),
                                                  PopupMenuItem(
                                                    value: 'archive',
                                                    child: Row(
                                                      children: [
                                                        Icon(p.isArchived ? Icons.unarchive : Icons.archive, size: 16, color: Colors.white70),
                                                        SizedBox(width: 8),
                                                        Text(p.isArchived ? 'Restore' : 'Archive',
                                                            style: const TextStyle(color: Colors.white, fontSize: 13)),
                                                      ],
                                                    ),
                                                  ),
                                                  const PopupMenuItem(
                                                    value: 'delete',
                                                    child: Row(
                                                      children: [
                                                        Icon(Icons.delete_outline, size: 16, color: AppColors.danger),
                                                        SizedBox(width: 8),
                                                        Text('Delete', style: TextStyle(color: AppColors.danger, fontSize: 13)),
                                                      ],
                                                    ),
                                                  ),
                                                ],
                                              ),
                                            ],
                                          ),
                                          const SizedBox(height: 2),

                                          // Category & Condition
                                          Row(
                                            children: [
                                              if (p.category != null) ...[
                                                Text(
                                                  p.category!.name,
                                                  style: const TextStyle(color: AppColors.primaryBright, fontSize: 11, fontWeight: FontWeight.w600),
                                                ),
                                                const SizedBox(width: 6),
                                                const Text('•', style: TextStyle(color: AppColors.darkMuted, fontSize: 10)),
                                                const SizedBox(width: 6),
                                              ],
                                              Text(
                                                p.status,
                                                style: const TextStyle(color: AppColors.darkMuted, fontSize: 11),
                                              ),
                                            ],
                                          ),
                                          const SizedBox(height: 8),

                                          // Price & Stock Tag
                                          Row(
                                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                            children: [
                                              Text(
                                                CurrencyFormatter.format(p.price),
                                                style: const TextStyle(
                                                  fontFamily: 'Montserrat',
                                                  color: Colors.white,
                                                  fontWeight: FontWeight.w800,
                                                  fontSize: 14,
                                                ),
                                              ),
                                              Container(
                                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                                decoration: BoxDecoration(
                                                  color: (p.stock <= 0
                                                          ? AppColors.danger
                                                          : p.stock <= 3
                                                              ? AppColors.warning
                                                              : AppColors.success)
                                                      .withValues(alpha: 0.15),
                                                  borderRadius: BorderRadius.circular(6),
                                                  border: Border.all(
                                                    color: (p.stock <= 0
                                                            ? AppColors.danger
                                                            : p.stock <= 3
                                                                ? AppColors.warning
                                                                : AppColors.success)
                                                        .withValues(alpha: 0.4),
                                                  ),
                                                ),
                                                child: Text(
                                                  p.stock <= 0 ? 'OUT OF STOCK' : '${p.stock} in stock',
                                                  style: TextStyle(
                                                    color: p.stock <= 0
                                                        ? AppColors.danger
                                                        : p.stock <= 3
                                                            ? AppColors.warning
                                                            : AppColors.success,
                                                    fontSize: 10,
                                                    fontWeight: FontWeight.bold,
                                                  ),
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
                          },
                        ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChip({
    required String label,
    required bool isSelected,
    required VoidCallback onTap,
    Color? accentColor,
  }) {
    final activeColor = accentColor ?? AppColors.primary;
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
        decoration: BoxDecoration(
          color: isSelected ? activeColor.withValues(alpha: 0.2) : AppColors.darkSurfaceMuted,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(
            color: isSelected ? activeColor : AppColors.darkBorder,
          ),
        ),
        child: Text(
          label,
          style: TextStyle(
            color: isSelected ? Colors.white : AppColors.darkMuted,
            fontSize: 12,
            fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          ),
        ),
      ),
    );
  }
}
