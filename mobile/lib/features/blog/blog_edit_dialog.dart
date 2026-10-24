import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/blog_post_model.dart';
import '../../core/theme/app_colors.dart';
import 'blog_controller.dart';

class BlogEditDialog extends ConsumerStatefulWidget {
  final BlogPostModel? post;
  final List<BlogCategoryModel> categories;

  const BlogEditDialog({super.key, this.post, required this.categories});

  @override
  ConsumerState<BlogEditDialog> createState() => _BlogEditDialogState();
}

class _BlogEditDialogState extends ConsumerState<BlogEditDialog> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _titleController;
  late final TextEditingController _excerptController;
  late final TextEditingController _contentController;
  late final TextEditingController _imageController;
  late int? _selectedCategoryId;
  late bool _isPublished;
  bool _isSaving = false;

  @override
  void initState() {
    super.initState();
    _titleController = TextEditingController(text: widget.post?.title ?? '');
    _excerptController = TextEditingController(text: widget.post?.excerpt ?? '');
    _contentController = TextEditingController(text: widget.post?.content ?? '');
    _imageController = TextEditingController(text: widget.post?.featuredImage ?? '');
    _selectedCategoryId = widget.post?.blogCategoryId ?? (widget.categories.isNotEmpty ? widget.categories.first.id : null);
    _isPublished = widget.post?.isPublished ?? true;
  }

  @override
  void dispose() {
    _titleController.dispose();
    _excerptController.dispose();
    _contentController.dispose();
    _imageController.dispose();
    super.dispose();
  }

  Future<void> _handleSave() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedCategoryId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select a category')),
      );
      return;
    }

    setState(() => _isSaving = true);

    final payload = {
      'title': _titleController.text.trim(),
      'blog_category_id': _selectedCategoryId,
      'excerpt': _excerptController.text.trim(),
      'content': _contentController.text.trim(),
      'featured_image': _imageController.text.trim(),
      'is_published': _isPublished,
    };

    final success = await ref.read(blogControllerProvider.notifier).savePost(payload, id: widget.post?.id);

    if (mounted) {
      setState(() => _isSaving = false);
      if (success) {
        Navigator.of(context).pop();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: AppColors.success,
            content: Text(widget.post == null ? 'Article published successfully' : 'Article updated successfully'),
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final isEditing = widget.post != null;

    return Dialog(
      backgroundColor: AppColors.darkSurface,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 500, maxHeight: 680),
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      isEditing ? 'Edit Blog Article' : 'New Blog Article',
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        fontFamily: 'Montserrat',
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.close, color: AppColors.darkMuted),
                      onPressed: () => Navigator.of(context).pop(),
                    ),
                  ],
                ),
                const Divider(color: AppColors.darkBorder),
                Expanded(
                  child: SingleChildScrollView(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const SizedBox(height: 8),
                        const Text('Article Title *', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _titleController,
                          style: const TextStyle(color: Colors.white, fontSize: 13),
                          decoration: const InputDecoration(
                            hintText: 'e.g. Next-Gen Enterprise Server Architecture',
                          ),
                          validator: (val) => val == null || val.trim().isEmpty ? 'Title is required' : null,
                        ),
                        const SizedBox(height: 14),

                        const Text('Category *', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 6),
                        DropdownButtonFormField<int>(
                          initialValue: _selectedCategoryId,
                          dropdownColor: AppColors.darkSurface,
                          style: const TextStyle(color: Colors.white, fontSize: 13),
                          decoration: const InputDecoration(isDense: true),
                          items: widget.categories.map((c) {
                            return DropdownMenuItem<int>(
                              value: c.id,
                              child: Text(c.name),
                            );
                          }).toList(),
                          onChanged: (val) {
                            setState(() => _selectedCategoryId = val);
                          },
                        ),
                        const SizedBox(height: 14),

                        const Text('Short Excerpt', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _excerptController,
                          maxLines: 2,
                          style: const TextStyle(color: Colors.white, fontSize: 13),
                          decoration: const InputDecoration(
                            hintText: 'Brief summary displayed on listings...',
                          ),
                        ),
                        const SizedBox(height: 14),

                        const Text('Featured Image URL', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _imageController,
                          style: const TextStyle(color: Colors.white, fontSize: 13),
                          decoration: const InputDecoration(
                            hintText: 'https://images.unsplash.com/... or /storage/blog/...',
                          ),
                        ),
                        const SizedBox(height: 14),

                        const Text('Article Content (HTML / Markdown) *', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _contentController,
                          maxLines: 6,
                          style: const TextStyle(color: Colors.white, fontSize: 13),
                          decoration: const InputDecoration(
                            hintText: 'Write the complete article content...',
                          ),
                          validator: (val) => val == null || val.trim().isEmpty ? 'Content is required' : null,
                        ),
                        const SizedBox(height: 14),

                        SwitchListTile(
                          contentPadding: EdgeInsets.zero,
                          title: const Text('Publish Immediately', style: TextStyle(color: Colors.white, fontSize: 13)),
                          subtitle: const Text('Make this article publicly visible on laptech.co.ke/blog', style: TextStyle(color: AppColors.darkMuted, fontSize: 11)),
                          value: _isPublished,
                          activeThumbColor: AppColors.primary,
                          onChanged: (val) => setState(() => _isPublished = val),
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    TextButton(
                      onPressed: () => Navigator.of(context).pop(),
                      child: const Text('CANCEL'),
                    ),
                    const SizedBox(width: 8),
                    ElevatedButton(
                      onPressed: _isSaving ? null : _handleSave,
                      child: _isSaving
                          ? const SizedBox(width: 16, height: 16, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                          : Text(isEditing ? 'UPDATE' : 'PUBLISH'),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
