import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:image_picker/image_picker.dart';
import '../../core/models/product_model.dart';
import '../../core/theme/app_colors.dart';
import 'products_controller.dart';

class ProductEditScreen extends ConsumerStatefulWidget {
  final ProductModel? product;

  const ProductEditScreen({super.key, this.product});

  @override
  ConsumerState<ProductEditScreen> createState() => _ProductEditScreenState();
}

class _ProductEditScreenState extends ConsumerState<ProductEditScreen> {
  final _formKey = GlobalKey<FormState>();

  late TextEditingController _nameController;
  late TextEditingController _slugController;
  late TextEditingController _descriptionController;
  late TextEditingController _priceController;
  late TextEditingController _stockController;
  late TextEditingController _imageUrlController;

  int? _selectedCategoryId;
  String _selectedStatus = 'Brand new';
  bool _isFeatured = false;
  File? _pickedImageFile;
  bool _isSaving = false;

  // Specifications dynamic key-value list
  final List<MapEntry<TextEditingController, TextEditingController>> _specsControllers = [];

  final List<String> _statuses = [
    'Brand new',
    'Ex-UK',
    'Certified Refurbished',
  ];

  @override
  void initState() {
    super.initState();
    final p = widget.product;

    _nameController = TextEditingController(text: p?.name ?? '');
    _slugController = TextEditingController(text: p?.slug ?? '');
    _descriptionController = TextEditingController(text: p?.description ?? '');
    _priceController = TextEditingController(text: p != null ? p.price.toStringAsFixed(0) : '');
    _stockController = TextEditingController(text: p != null ? p.stock.toString() : '');
    _imageUrlController = TextEditingController(text: p?.image ?? '');

    _selectedCategoryId = p?.categoryId;
    _selectedStatus = p != null && _statuses.contains(p.status) ? p.status : 'Brand new';
    _isFeatured = p?.isFeatured ?? false;

    if (p?.specifications != null) {
      p!.specifications!.forEach((key, value) {
        _specsControllers.add(MapEntry(
          TextEditingController(text: key),
          TextEditingController(text: value.toString()),
        ));
      });
    }

    // Load categories if not yet loaded
    Future.microtask(() => ref.read(productsControllerProvider.notifier).fetchCategories());
  }

  @override
  void dispose() {
    _nameController.dispose();
    _slugController.dispose();
    _descriptionController.dispose();
    _priceController.dispose();
    _stockController.dispose();
    _imageUrlController.dispose();
    for (final pair in _specsControllers) {
      pair.key.dispose();
      pair.value.dispose();
    }
    super.dispose();
  }

  void _addSpecField() {
    setState(() {
      _specsControllers.add(MapEntry(
        TextEditingController(),
        TextEditingController(),
      ));
    });
  }

  void _removeSpecField(int index) {
    setState(() {
      _specsControllers[index].key.dispose();
      _specsControllers[index].value.dispose();
      _specsControllers.removeAt(index);
    });
  }

  Future<void> _pickImage(ImageSource source) async {
    try {
      final picker = ImagePicker();
      final picked = await picker.pickImage(source: source, maxWidth: 1200, imageQuality: 85);
      if (picked != null) {
        setState(() {
          _pickedImageFile = File(picked.path);
        });
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Failed to pick image: $e')),
        );
      }
    }
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedCategoryId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select a product category')),
      );
      return;
    }

    setState(() => _isSaving = true);

    // Build specs map
    final Map<String, dynamic> specs = {};
    for (final pair in _specsControllers) {
      final k = pair.key.text.trim();
      final v = pair.value.text.trim();
      if (k.isNotEmpty && v.isNotEmpty) {
        specs[k] = v;
      }
    }

    try {
      final repo = ref.read(productsRepositoryProvider);

      if (widget.product == null) {
        // Create
        await repo.createProduct(
          name: _nameController.text.trim(),
          slug: _slugController.text.trim().isEmpty ? null : _slugController.text.trim(),
          categoryId: _selectedCategoryId!,
          description: _descriptionController.text.trim(),
          price: double.tryParse(_priceController.text.trim()) ?? 0.0,
          stock: int.tryParse(_stockController.text.trim()) ?? 0,
          status: _selectedStatus,
          isFeatured: _isFeatured,
          image: _imageUrlController.text.trim().isEmpty ? null : _imageUrlController.text.trim(),
          imageFile: _pickedImageFile,
          specifications: specs.isEmpty ? null : specs,
        );
      } else {
        // Update
        await repo.updateProduct(
          id: widget.product!.id,
          name: _nameController.text.trim(),
          slug: _slugController.text.trim().isEmpty ? null : _slugController.text.trim(),
          categoryId: _selectedCategoryId!,
          description: _descriptionController.text.trim(),
          price: double.tryParse(_priceController.text.trim()) ?? 0.0,
          stock: int.tryParse(_stockController.text.trim()) ?? 0,
          status: _selectedStatus,
          isFeatured: _isFeatured,
          image: _imageUrlController.text.trim().isEmpty ? null : _imageUrlController.text.trim(),
          imageFile: _pickedImageFile,
          specifications: specs.isEmpty ? null : specs,
        );
      }

      await ref.read(productsControllerProvider.notifier).fetchProducts();

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: AppColors.success,
            content: Text(widget.product == null ? 'Product created successfully' : 'Product updated successfully'),
          ),
        );
        Navigator.of(context).pop();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(backgroundColor: AppColors.danger, content: Text('Error saving product: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => _isSaving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final categories = ref.watch(productsControllerProvider).categories;
    final isEditing = widget.product != null;

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: Text(isEditing ? 'Edit Product' : 'New Product'),
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
              // Product Name
              TextFormField(
                controller: _nameController,
                style: const TextStyle(color: Colors.white),
                decoration: const InputDecoration(
                  labelText: 'Product Title *',
                  hintText: 'e.g. ThinkPad X1 Carbon Gen 11',
                ),
                validator: (val) => val == null || val.trim().isEmpty ? 'Product title is required' : null,
              ),
              const SizedBox(height: 16),

              // Category Selector
              DropdownButtonFormField<int>(
                initialValue: _selectedCategoryId,
                dropdownColor: AppColors.darkSurface,
                style: const TextStyle(color: Colors.white),
                decoration: const InputDecoration(
                  labelText: 'Category *',
                ),
                items: categories
                    .map((cat) => DropdownMenuItem(
                          value: cat.id,
                          child: Text(cat.name, style: const TextStyle(color: Colors.white)),
                        ))
                    .toList(),
                onChanged: (val) => setState(() => _selectedCategoryId = val),
                validator: (val) => val == null ? 'Please select a category' : null,
              ),
              const SizedBox(height: 16),

              // Price & Stock in a Row
              Row(
                children: [
                  Expanded(
                    child: TextFormField(
                      controller: _priceController,
                      keyboardType: const TextInputType.numberWithOptions(decimal: true),
                      style: const TextStyle(color: Colors.white),
                      decoration: const InputDecoration(
                        labelText: 'Price (KES) *',
                        prefixText: 'KES ',
                        prefixStyle: TextStyle(color: AppColors.darkMuted),
                      ),
                      validator: (val) {
                        if (val == null || val.trim().isEmpty) return 'Required';
                        if (double.tryParse(val.trim()) == null) return 'Invalid number';
                        return null;
                      },
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: TextFormField(
                      controller: _stockController,
                      keyboardType: TextInputType.number,
                      style: const TextStyle(color: Colors.white),
                      decoration: const InputDecoration(
                        labelText: 'Stock Units *',
                      ),
                      validator: (val) {
                        if (val == null || val.trim().isEmpty) return 'Required';
                        if (int.tryParse(val.trim()) == null) return 'Invalid integer';
                        return null;
                      },
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),

              // Status Selector
              DropdownButtonFormField<String>(
                initialValue: _selectedStatus,
                dropdownColor: AppColors.darkSurface,
                style: const TextStyle(color: Colors.white),
                decoration: const InputDecoration(
                  labelText: 'Condition / Status *',
                ),
                items: _statuses
                    .map((s) => DropdownMenuItem(
                          value: s,
                          child: Text(s, style: const TextStyle(color: Colors.white)),
                        ))
                    .toList(),
                onChanged: (val) {
                  if (val != null) setState(() => _selectedStatus = val);
                },
              ),
              const SizedBox(height: 16),

              // Description
              TextFormField(
                controller: _descriptionController,
                maxLines: 4,
                style: const TextStyle(color: Colors.white),
                decoration: const InputDecoration(
                  labelText: 'Description *',
                  hintText: 'Enter full product description and highlights...',
                ),
                validator: (val) => val == null || val.trim().isEmpty ? 'Description is required' : null,
              ),
              const SizedBox(height: 20),

              // Image Selection Section
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
                    const Text(
                      'Product Image',
                      style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
                    ),
                    const SizedBox(height: 10),
                    if (_pickedImageFile != null) ...[
                      ClipRRect(
                        borderRadius: BorderRadius.circular(8),
                        child: Image.file(_pickedImageFile!, height: 140, width: double.infinity, fit: BoxFit.cover),
                      ),
                      const SizedBox(height: 8),
                    ],
                    Row(
                      children: [
                        OutlinedButton.icon(
                          onPressed: () => _pickImage(ImageSource.gallery),
                          icon: const Icon(Icons.photo_library, size: 16),
                          label: const Text('Pick Image'),
                        ),
                        const SizedBox(width: 8),
                        OutlinedButton.icon(
                          onPressed: () => _pickImage(ImageSource.camera),
                          icon: const Icon(Icons.camera_alt, size: 16),
                          label: const Text('Camera'),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    TextFormField(
                      controller: _imageUrlController,
                      style: const TextStyle(color: Colors.white, fontSize: 13),
                      decoration: const InputDecoration(
                        isDense: true,
                        labelText: 'Or Image Path / CDN URL',
                        hintText: '/images/dell_latitude.png or https://...',
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 16),

              // Featured Switch
              SwitchListTile(
                contentPadding: EdgeInsets.zero,
                activeThumbColor: AppColors.primary,
                title: const Text('Featured on Storefront', style: TextStyle(color: Colors.white, fontSize: 14)),
                subtitle: const Text('Display in homepage hero and featured sections', style: TextStyle(color: AppColors.darkMuted, fontSize: 12)),
                value: _isFeatured,
                onChanged: (val) => setState(() => _isFeatured = val),
              ),

              const SizedBox(height: 16),

              // Dynamic Specifications Section
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
                          'Hardware Specifications',
                          style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
                        ),
                        TextButton.icon(
                          onPressed: _addSpecField,
                          icon: const Icon(Icons.add, size: 16),
                          label: const Text('Add Spec'),
                        ),
                      ],
                    ),
                    if (_specsControllers.isEmpty)
                      const Padding(
                        padding: EdgeInsets.symmetric(vertical: 8),
                        child: Text(
                          'No custom specifications added. Tap "Add Spec" to add processor, RAM, storage, etc.',
                          style: TextStyle(color: AppColors.darkMuted, fontSize: 12),
                        ),
                      ),
                    ..._specsControllers.asMap().entries.map((entry) {
                      final idx = entry.key;
                      final pair = entry.value;
                      return Padding(
                        padding: const EdgeInsets.only(bottom: 8),
                        child: Row(
                          children: [
                            Expanded(
                              flex: 2,
                              child: TextFormField(
                                controller: pair.key,
                                style: const TextStyle(color: Colors.white, fontSize: 12),
                                decoration: const InputDecoration(
                                  isDense: true,
                                  hintText: 'Key (e.g. RAM)',
                                ),
                              ),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              flex: 3,
                              child: TextFormField(
                                controller: pair.value,
                                style: const TextStyle(color: Colors.white, fontSize: 12),
                                decoration: const InputDecoration(
                                  isDense: true,
                                  hintText: 'Value (e.g. 32GB DDR5)',
                                ),
                              ),
                            ),
                            IconButton(
                              icon: const Icon(Icons.delete_outline, color: AppColors.danger, size: 20),
                              onPressed: () => _removeSpecField(idx),
                            ),
                          ],
                        ),
                      );
                    }),
                  ],
                ),
              ),

              const SizedBox(height: 32),

              // Save Button
              SizedBox(
                height: 50,
                child: ElevatedButton(
                  onPressed: _isSaving ? null : _save,
                  child: _isSaving
                      ? const CircularProgressIndicator(color: Colors.white)
                      : Text(isEditing ? 'UPDATE PRODUCT' : 'CREATE PRODUCT'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
