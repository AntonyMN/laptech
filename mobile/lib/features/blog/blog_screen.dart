import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/api/api_client.dart';
import '../../core/models/blog_post_model.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/currency_formatter.dart';
import '../../core/utils/url_launcher_helper.dart';
import 'blog_controller.dart';
import 'blog_edit_dialog.dart';

class BlogScreen extends ConsumerStatefulWidget {
  const BlogScreen({super.key});

  @override
  ConsumerState<BlogScreen> createState() => _BlogScreenState();
}

class _BlogScreenState extends ConsumerState<BlogScreen> {
  final _searchController = TextEditingController();

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _openCreateDialog() {
    final state = ref.read(blogControllerProvider);
    showDialog(
      context: context,
      builder: (_) => BlogEditDialog(categories: state.categories),
    );
  }

  void _openEditDialog(BlogPostModel post) {
    final state = ref.read(blogControllerProvider);
    showDialog(
      context: context,
      builder: (_) => BlogEditDialog(post: post, categories: state.categories),
    );
  }

  Future<void> _confirmDelete(BlogPostModel post) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.darkSurface,
        title: const Text('Delete Article', style: TextStyle(color: Colors.white)),
        content: Text('Delete "${post.title}"? This will remove the article from the website.',
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

    if (confirmed == true && mounted) {
      final success = await ref.read(blogControllerProvider.notifier).deletePost(post.id);
      if (mounted && success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Article deleted.')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(blogControllerProvider);
    final baseUrl = ApiClient.instance.baseUrl;

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: const Text('Blog & Insights'),
        actions: [
          IconButton(
            tooltip: 'Open in Web Admin Portal',
            icon: const Icon(Icons.open_in_browser, color: AppColors.primary),
            onPressed: () => LaptechUrlLauncher.launchWebAdmin('/admin/blog-posts', context: context),
          ),
          IconButton(
            tooltip: 'Refresh',
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(blogControllerProvider.notifier).loadAll(),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppColors.primary,
        icon: const Icon(Icons.add, color: Colors.black),
        label: const Text('NEW ARTICLE', style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold)),
        onPressed: _openCreateDialog,
      ),
      body: Column(
        children: [
          // Filter bar
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            color: AppColors.darkSurface,
            child: Column(
              children: [
                TextField(
                  controller: _searchController,
                  style: const TextStyle(color: Colors.white, fontSize: 13),
                  decoration: InputDecoration(
                    hintText: 'Search articles by title or keyword...',
                    prefixIcon: const Icon(Icons.search, size: 18, color: AppColors.darkMuted),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear, size: 16, color: AppColors.darkMuted),
                            onPressed: () {
                              _searchController.clear();
                              ref.read(blogControllerProvider.notifier).search('');
                            },
                          )
                        : null,
                    isDense: true,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  ),
                  onChanged: (val) {
                    ref.read(blogControllerProvider.notifier).search(val.trim());
                  },
                ),
                const SizedBox(height: 10),
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: [
                      _buildFilterChip('all', 'All (${state.totalCount})', state.filter),
                      const SizedBox(width: 8),
                      _buildFilterChip('published', 'Published (${state.publishedCount})', state.filter, color: AppColors.success),
                      const SizedBox(width: 8),
                      _buildFilterChip('drafts', 'Drafts (${state.draftCount})', state.filter, color: AppColors.warning),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Main list
          Expanded(
            child: state.isLoading
                ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                : state.posts.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.article_outlined, size: 64, color: AppColors.darkMuted.withValues(alpha: 0.5)),
                            const SizedBox(height: 12),
                            const Text(
                              'No blog articles found',
                              style: TextStyle(color: Colors.white, fontSize: 15, fontWeight: FontWeight.bold),
                            ),
                            const SizedBox(height: 4),
                            const Text(
                              'Tap "+ NEW ARTICLE" to write a technical publication',
                              style: TextStyle(color: AppColors.darkMuted, fontSize: 12),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: () => ref.read(blogControllerProvider.notifier).loadAll(),
                        child: ListView.separated(
                          padding: const EdgeInsets.fromLTRB(16, 16, 16, 80),
                          itemCount: state.posts.length,
                          separatorBuilder: (_, __) => const SizedBox(height: 14),
                          itemBuilder: (context, index) {
                            final post = state.posts[index];
                            return _buildPostCard(post, baseUrl);
                          },
                        ),
                      ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChip(String value, String label, String current, {Color? color}) {
    final isSelected = current == value;
    final chipColor = color ?? AppColors.primary;
    return GestureDetector(
      onTap: () => ref.read(blogControllerProvider.notifier).setFilter(value),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
        decoration: BoxDecoration(
          color: isSelected ? chipColor.withValues(alpha: 0.2) : AppColors.darkPage,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(
            color: isSelected ? chipColor : AppColors.darkBorder,
            width: isSelected ? 1.5 : 1,
          ),
        ),
        child: Text(
          label,
          style: TextStyle(
            color: isSelected ? (color ?? AppColors.primaryBright) : AppColors.darkMuted,
            fontSize: 12,
            fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          ),
        ),
      ),
    );
  }

  Widget _buildPostCard(BlogPostModel post, String baseUrl) {
    final imageUrl = post.resolveImageUrl(baseUrl);

    return Container(
      decoration: BoxDecoration(
        color: AppColors.darkSurface,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.darkBorder),
      ),
      clipBehavior: Clip.antiAlias,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Post image if available
          if (imageUrl != null && imageUrl.isNotEmpty)
            SizedBox(
              height: 140,
              width: double.infinity,
              child: CachedNetworkImage(
                imageUrl: imageUrl,
                fit: BoxFit.cover,
                placeholder: (_, __) => Container(
                  color: AppColors.darkSurfaceMuted,
                  child: const Center(child: CircularProgressIndicator(strokeWidth: 2)),
                ),
                errorWidget: (_, __, ___) => Container(
                  color: AppColors.darkSurfaceMuted,
                  child: const Center(child: Icon(Icons.image_not_supported, color: AppColors.darkMuted)),
                ),
              ),
            ),

          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Category badge & Publish status pill
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                      decoration: BoxDecoration(
                        color: AppColors.primary.withValues(alpha: 0.15),
                        borderRadius: BorderRadius.circular(6),
                      ),
                      child: Text(
                        post.category?.name.toUpperCase() ?? 'GENERAL',
                        style: const TextStyle(
                          color: AppColors.primaryBright,
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    const Spacer(),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                      decoration: BoxDecoration(
                        color: (post.isPublished ? AppColors.success : AppColors.warning).withValues(alpha: 0.15),
                        borderRadius: BorderRadius.circular(6),
                        border: Border.all(color: post.isPublished ? AppColors.success : AppColors.warning),
                      ),
                      child: Text(
                        post.isPublished ? 'PUBLISHED' : 'DRAFT',
                        style: TextStyle(
                          color: post.isPublished ? AppColors.success : AppColors.warning,
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),

                // Title
                Text(
                  post.title,
                  style: const TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                    fontSize: 15,
                    height: 1.3,
                  ),
                ),

                if (post.excerpt != null && post.excerpt!.isNotEmpty) ...[
                  const SizedBox(height: 6),
                  Text(
                    post.excerpt!,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(color: AppColors.darkMuted, fontSize: 12, height: 1.3),
                  ),
                ],

                const SizedBox(height: 10),

                // Published at / Created at
                if (post.publishedAt != null || post.createdAt != null)
                  Text(
                    'Published: ${CurrencyFormatter.formatDate(post.publishedAt ?? post.createdAt!)}',
                    style: const TextStyle(color: AppColors.darkMuted, fontSize: 11),
                  ),

                const Divider(color: AppColors.darkBorder, height: 20),

                // Actions: View Live on Website, Toggle Publish, Edit, Delete
                Row(
                  children: [
                    // View Live Link
                    TextButton.icon(
                      icon: const Icon(Icons.launch, size: 14, color: AppColors.primary),
                      label: const Text('VIEW LIVE', style: TextStyle(color: AppColors.primaryBright, fontSize: 11, fontWeight: FontWeight.bold)),
                      onPressed: () {
                        final liveUrl = 'https://laptech.co.ke/blog/${post.slug}';
                        LaptechUrlLauncher.launchWeb(liveUrl, context: context);
                      },
                    ),
                    const Spacer(),
                    IconButton(
                      tooltip: post.isPublished ? 'Unpublish (Make Draft)' : 'Publish',
                      icon: Icon(
                        post.isPublished ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                        color: post.isPublished ? AppColors.warning : AppColors.success,
                        size: 20,
                      ),
                      onPressed: () => ref.read(blogControllerProvider.notifier).togglePublish(post.id),
                    ),
                    IconButton(
                      tooltip: 'Edit Article',
                      icon: const Icon(Icons.edit_outlined, color: AppColors.primary, size: 20),
                      onPressed: () => _openEditDialog(post),
                    ),
                    IconButton(
                      tooltip: 'Delete',
                      icon: const Icon(Icons.delete_outline, color: AppColors.danger, size: 20),
                      onPressed: () => _confirmDelete(post),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
