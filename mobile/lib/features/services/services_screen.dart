import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/service_model.dart';
import '../../core/theme/app_colors.dart';
import 'service_edit_screen.dart';
import 'services_controller.dart';

class ServicesScreen extends ConsumerStatefulWidget {
  const ServicesScreen({super.key});

  @override
  ConsumerState<ServicesScreen> createState() => _ServicesScreenState();
}

class _ServicesScreenState extends ConsumerState<ServicesScreen> {
  final _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(servicesControllerProvider.notifier).fetchCategories();
      ref.read(servicesControllerProvider.notifier).fetchServices();
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  IconData _resolveIcon(String? iconName) {
    switch (iconName?.toLowerCase()) {
      case 'server':
        return Icons.dns;
      case 'security':
        return Icons.security;
      case 'cloud':
        return Icons.cloud_outlined;
      case 'network':
        return Icons.lan_outlined;
      case 'laptop':
      case 'hardware':
        return Icons.laptop_mac;
      default:
        return Icons.miscellaneous_services;
    }
  }

  Future<void> _confirmDelete(ServiceModel service) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.darkSurface,
        title: const Text('Delete Service', style: TextStyle(color: Colors.white)),
        content: Text(
          'Are you sure you want to remove "${service.name}"? This will delete the service from the platform.',
          style: const TextStyle(color: AppColors.darkMuted),
        ),
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
      final success = await ref.read(servicesControllerProvider.notifier).deleteService(service.id);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: success ? AppColors.success : AppColors.danger,
            content: Text(success ? 'Service deleted successfully' : 'Failed to delete service'),
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(servicesControllerProvider);

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: const Text('Services Management'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(servicesControllerProvider.notifier).fetchServices(),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.add),
        label: const Text('Add Service', style: TextStyle(fontWeight: FontWeight.bold)),
        onPressed: () {
          Navigator.of(context).push(
            MaterialPageRoute(builder: (_) => const ServiceEditScreen()),
          );
        },
      ),
      body: Column(
        children: [
          // Search & Category Bar
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            color: AppColors.darkSurface,
            child: Column(
              children: [
                TextField(
                  controller: _searchController,
                  style: const TextStyle(color: Colors.white, fontSize: 14),
                  decoration: InputDecoration(
                    hintText: 'Search IT services & infrastructure...',
                    prefixIcon: const Icon(Icons.search, color: AppColors.primary, size: 20),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear, size: 18, color: AppColors.darkMuted),
                            onPressed: () {
                              _searchController.clear();
                              ref.read(servicesControllerProvider.notifier).setSearch('');
                            },
                          )
                        : null,
                    isDense: true,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                  ),
                  onSubmitted: (val) {
                    ref.read(servicesControllerProvider.notifier).setSearch(val);
                  },
                ),
                if (state.categories.isNotEmpty) ...[
                  const SizedBox(height: 10),
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
                          onPressed: () => ref.read(servicesControllerProvider.notifier).setCategory(null),
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
                                onPressed: () => ref.read(servicesControllerProvider.notifier).setCategory(c.id),
                              ),
                            )),
                      ],
                    ),
                  ),
                ],
              ],
            ),
          ),

          // Services List
          Expanded(
            child: RefreshIndicator(
              color: AppColors.primary,
              onRefresh: () => ref.read(servicesControllerProvider.notifier).fetchServices(),
              child: state.isLoading && state.services.isEmpty
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : state.services.isEmpty
                      ? ListView(
                          children: const [
                            SizedBox(height: 80),
                            Center(
                              child: Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(Icons.miscellaneous_services_outlined, size: 48, color: AppColors.darkMuted),
                                  SizedBox(height: 12),
                                  Text(
                                    'No services registered yet',
                                    style: TextStyle(color: Colors.white, fontSize: 16),
                                  ),
                                  SizedBox(height: 6),
                                  Text(
                                    'Tap "+ Add Service" to configure a new technical service',
                                    style: TextStyle(color: AppColors.darkMuted, fontSize: 12),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        )
                      : ListView.builder(
                          padding: const EdgeInsets.only(left: 16, right: 16, top: 12, bottom: 84),
                          itemCount: state.services.length,
                          itemBuilder: (ctx, index) {
                            final service = state.services[index];

                            return Container(
                              margin: const EdgeInsets.only(bottom: 12),
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
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Container(
                                        padding: const EdgeInsets.all(10),
                                        decoration: BoxDecoration(
                                          color: AppColors.primary.withValues(alpha: 0.15),
                                          shape: BoxShape.circle,
                                        ),
                                        child: Icon(_resolveIcon(service.icon), color: AppColors.primary, size: 22),
                                      ),
                                      const SizedBox(width: 12),
                                      Expanded(
                                        child: Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Text(
                                              service.name,
                                              style: const TextStyle(
                                                fontFamily: 'Montserrat',
                                                color: Colors.white,
                                                fontWeight: FontWeight.bold,
                                                fontSize: 15,
                                              ),
                                            ),
                                            const SizedBox(height: 2),
                                            if (service.category != null)
                                              Text(
                                                service.category!.name,
                                                style: const TextStyle(
                                                  color: AppColors.primaryBright,
                                                  fontSize: 11,
                                                  fontWeight: FontWeight.w600,
                                                ),
                                              ),
                                          ],
                                        ),
                                      ),
                                      PopupMenuButton<String>(
                                        icon: const Icon(Icons.more_vert, size: 18, color: AppColors.darkMuted),
                                        color: AppColors.darkSurface,
                                        onSelected: (val) {
                                          if (val == 'edit') {
                                            Navigator.of(context).push(
                                              MaterialPageRoute(
                                                builder: (_) => ServiceEditScreen(service: service),
                                              ),
                                            );
                                          } else if (val == 'delete') {
                                            _confirmDelete(service);
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
                                  const SizedBox(height: 10),
                                  Text(
                                    service.description,
                                    maxLines: 2,
                                    overflow: TextOverflow.ellipsis,
                                    style: const TextStyle(color: AppColors.darkMuted, fontSize: 12, height: 1.4),
                                  ),
                                  if (service.features.isNotEmpty) ...[
                                    const SizedBox(height: 10),
                                    Wrap(
                                      spacing: 6,
                                      runSpacing: 6,
                                      children: service.features.take(3).map((f) {
                                        return Container(
                                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                          decoration: BoxDecoration(
                                            color: AppColors.darkSurfaceMuted,
                                            borderRadius: BorderRadius.circular(6),
                                            border: Border.all(color: AppColors.darkBorder),
                                          ),
                                          child: Text(
                                            '✓ $f',
                                            style: const TextStyle(color: Colors.white70, fontSize: 10),
                                          ),
                                        );
                                      }).toList(),
                                    ),
                                  ],
                                ],
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
}
