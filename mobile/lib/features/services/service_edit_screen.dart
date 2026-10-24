import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/service_model.dart';
import '../../core/theme/app_colors.dart';
import 'services_controller.dart';

class ServiceEditScreen extends ConsumerStatefulWidget {
  final ServiceModel? service;

  const ServiceEditScreen({super.key, this.service});

  @override
  ConsumerState<ServiceEditScreen> createState() => _ServiceEditScreenState();
}

class _ServiceEditScreenState extends ConsumerState<ServiceEditScreen> {
  final _formKey = GlobalKey<FormState>();

  late TextEditingController _nameController;
  late TextEditingController _slugController;
  late TextEditingController _descriptionController;
  late TextEditingController _iconController;

  int? _selectedCategoryId;
  bool _isFeatured = false;
  bool _isSaving = false;

  final List<TextEditingController> _featureControllers = [];

  @override
  void initState() {
    super.initState();
    final s = widget.service;

    _nameController = TextEditingController(text: s?.name ?? '');
    _slugController = TextEditingController(text: s?.slug ?? '');
    _descriptionController = TextEditingController(text: s?.description ?? '');
    _iconController = TextEditingController(text: s?.icon ?? 'build');

    _selectedCategoryId = s?.serviceCategoryId;
    _isFeatured = s?.isFeatured ?? false;

    if (s?.features != null && s!.features.isNotEmpty) {
      for (final f in s.features) {
        _featureControllers.add(TextEditingController(text: f));
      }
    } else {
      _featureControllers.add(TextEditingController(text: 'Enterprise SLA & Support'));
    }

    Future.microtask(() => ref.read(servicesControllerProvider.notifier).fetchCategories());
  }

  @override
  void dispose() {
    _nameController.dispose();
    _slugController.dispose();
    _descriptionController.dispose();
    _iconController.dispose();
    for (final c in _featureControllers) {
      c.dispose();
    }
    super.dispose();
  }

  void _addFeature() {
    setState(() {
      _featureControllers.add(TextEditingController());
    });
  }

  void _removeFeature(int index) {
    setState(() {
      _featureControllers[index].dispose();
      _featureControllers.removeAt(index);
    });
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSaving = true);

    final features = _featureControllers
        .map((c) => c.text.trim())
        .where((text) => text.isNotEmpty)
        .toList();

    try {
      final repo = ref.read(servicesRepositoryProvider);

      if (widget.service == null) {
        // Create
        await repo.createService(
          name: _nameController.text.trim(),
          slug: _slugController.text.trim().isEmpty ? null : _slugController.text.trim(),
          description: _descriptionController.text.trim(),
          serviceCategoryId: _selectedCategoryId,
          features: features,
          icon: _iconController.text.trim().isEmpty ? null : _iconController.text.trim(),
          isFeatured: _isFeatured,
        );
      } else {
        // Update
        await repo.updateService(
          id: widget.service!.id,
          name: _nameController.text.trim(),
          slug: _slugController.text.trim().isEmpty ? null : _slugController.text.trim(),
          description: _descriptionController.text.trim(),
          serviceCategoryId: _selectedCategoryId,
          features: features,
          icon: _iconController.text.trim().isEmpty ? null : _iconController.text.trim(),
          isFeatured: _isFeatured,
        );
      }

      await ref.read(servicesControllerProvider.notifier).fetchServices();

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: AppColors.success,
            content: Text(widget.service == null ? 'Service created successfully' : 'Service updated successfully'),
          ),
        );
        Navigator.of(context).pop();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(backgroundColor: AppColors.danger, content: Text('Error saving service: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => _isSaving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final categories = ref.watch(servicesControllerProvider).categories;
    final isEditing = widget.service != null;

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: Text(isEditing ? 'Edit Service' : 'New Service'),
        actions: [
          TextButton(
            onPressed: _isSaving ? null : _save,
            child: _isSaving
                ? const SizedBox(
                    width: 18,
                    height: 18,
                    child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                  )
                : const Text(
                    'SAVE',
                    style: TextStyle(fontWeight: FontWeight.bold, color: AppColors.primaryBright),
                  ),
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              TextFormField(
                controller: _nameController,
                style: const TextStyle(color: Colors.white),
                decoration: const InputDecoration(
                  labelText: 'Service Name *',
                  hintText: 'e.g. Enterprise Infrastructure & Cloud Support',
                ),
                validator: (val) => val == null || val.trim().isEmpty ? 'Service name is required' : null,
              ),
              const SizedBox(height: 16),

              DropdownButtonFormField<int>(
                initialValue: _selectedCategoryId,
                dropdownColor: AppColors.darkSurface,
                style: const TextStyle(color: Colors.white),
                decoration: const InputDecoration(
                  labelText: 'Service Category',
                ),
                items: categories
                    .map((cat) => DropdownMenuItem(
                          value: cat.id,
                          child: Text(cat.name, style: const TextStyle(color: Colors.white)),
                        ))
                    .toList(),
                onChanged: (val) => setState(() => _selectedCategoryId = val),
              ),
              const SizedBox(height: 16),

              TextFormField(
                controller: _descriptionController,
                maxLines: 4,
                style: const TextStyle(color: Colors.white),
                decoration: const InputDecoration(
                  labelText: 'Service Scope & Description *',
                  hintText: 'Provide in-depth breakdown of service deliverables...',
                ),
                validator: (val) => val == null || val.trim().isEmpty ? 'Description is required' : null,
              ),
              const SizedBox(height: 16),

              TextFormField(
                controller: _iconController,
                style: const TextStyle(color: Colors.white),
                decoration: const InputDecoration(
                  labelText: 'Icon Keyword',
                  hintText: 'e.g. server, security, cloud, network, build',
                ),
              ),
              const SizedBox(height: 16),

              SwitchListTile(
                contentPadding: EdgeInsets.zero,
                activeThumbColor: AppColors.primary,
                title: const Text('Featured Service', style: TextStyle(color: Colors.white, fontSize: 14)),
                subtitle: const Text('Highlight on agency overview and dashboard', style: TextStyle(color: AppColors.darkMuted, fontSize: 12)),
                value: _isFeatured,
                onChanged: (val) => setState(() => _isFeatured = val),
              ),
              const SizedBox(height: 20),

              // Dynamic Features Section
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: AppColors.darkSurface,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: AppColors.darkBorder),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text(
                          'Deliverables & Features',
                          style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
                        ),
                        TextButton.icon(
                          onPressed: _addFeature,
                          icon: const Icon(Icons.add, size: 16),
                          label: const Text('Add Feature'),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    ..._featureControllers.asMap().entries.map((entry) {
                      final idx = entry.key;
                      final ctrl = entry.value;
                      return Padding(
                        padding: const EdgeInsets.only(bottom: 8),
                        child: Row(
                          children: [
                            const Icon(Icons.check_circle_outline, size: 16, color: AppColors.primary),
                            const SizedBox(width: 8),
                            Expanded(
                              child: TextFormField(
                                controller: ctrl,
                                style: const TextStyle(color: Colors.white, fontSize: 13),
                                decoration: InputDecoration(
                                  isDense: true,
                                  hintText: 'Feature #${idx + 1}',
                                ),
                              ),
                            ),
                            IconButton(
                              icon: const Icon(Icons.delete_outline, size: 18, color: AppColors.danger),
                              onPressed: () => _removeFeature(idx),
                            ),
                          ],
                        ),
                      );
                    }),
                  ],
                ),
              ),

              const SizedBox(height: 32),

              SizedBox(
                height: 50,
                child: ElevatedButton(
                  onPressed: _isSaving ? null : _save,
                  child: _isSaving
                      ? const CircularProgressIndicator(color: Colors.white)
                      : Text(isEditing ? 'UPDATE SERVICE' : 'CREATE SERVICE'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
