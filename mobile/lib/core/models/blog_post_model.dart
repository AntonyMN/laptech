class BlogCategoryModel {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final int postsCount;

  BlogCategoryModel({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.postsCount = 0,
  });

  factory BlogCategoryModel.fromJson(Map<String, dynamic> json) {
    return BlogCategoryModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      description: json['description']?.toString(),
      postsCount: json['posts_count'] != null ? int.tryParse(json['posts_count'].toString()) ?? 0 : 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'description': description,
      'posts_count': postsCount,
    };
  }
}

class BlogPostModel {
  final int id;
  final int blogCategoryId;
  final String title;
  final String slug;
  final String? excerpt;
  final String content;
  final String? featuredImage;
  final bool isPublished;
  final String? publishedAt;
  final String? createdAt;
  final BlogCategoryModel? category;

  BlogPostModel({
    required this.id,
    required this.blogCategoryId,
    required this.title,
    required this.slug,
    this.excerpt,
    required this.content,
    this.featuredImage,
    this.isPublished = false,
    this.publishedAt,
    this.createdAt,
    this.category,
  });

  String? resolveImageUrl(String baseUrl) {
    if (featuredImage == null || featuredImage!.trim().isEmpty) return null;
    final trimmed = featuredImage!.trim();
    if (trimmed.startsWith('http://') || trimmed.startsWith('https://')) {
      return trimmed;
    }
    var cleanBase = baseUrl.trim();
    while (cleanBase.endsWith('/')) {
      cleanBase = cleanBase.substring(0, cleanBase.length - 1);
    }
    if (cleanBase.endsWith('/api')) {
      cleanBase = cleanBase.substring(0, cleanBase.length - 4);
    }
    final cleanPath = trimmed.startsWith('/') ? trimmed : '/$trimmed';
    return '$cleanBase$cleanPath';
  }

  factory BlogPostModel.fromJson(Map<String, dynamic> json) {
    return BlogPostModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      blogCategoryId: json['blog_category_id'] is int
          ? json['blog_category_id']
          : int.tryParse(json['blog_category_id'].toString()) ?? 0,
      title: json['title']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      excerpt: json['excerpt']?.toString(),
      content: json['content']?.toString() ?? '',
      featuredImage: json['featured_image']?.toString(),
      isPublished: json['is_published'] == true || json['is_published'] == 1,
      publishedAt: json['published_at']?.toString(),
      createdAt: json['created_at']?.toString(),
      category: json['category'] is Map<String, dynamic> ? BlogCategoryModel.fromJson(json['category']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'blog_category_id': blogCategoryId,
      'title': title,
      'slug': slug,
      'excerpt': excerpt,
      'content': content,
      'featured_image': featuredImage,
      'is_published': isPublished,
      'published_at': publishedAt,
      'created_at': createdAt,
      'category': category?.toJson(),
    };
  }
}
