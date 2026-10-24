import 'dart:io';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import '../../core/theme/app_colors.dart';

class ImageViewerScreen extends StatefulWidget {
  final String imageSource;
  final String? heroTag;
  final String? title;

  const ImageViewerScreen({
    super.key,
    required this.imageSource,
    this.heroTag,
    this.title,
  });

  @override
  State<ImageViewerScreen> createState() => _ImageViewerScreenState();
}

class _ImageViewerScreenState extends State<ImageViewerScreen> {
  final TransformationController _controller = TransformationController();
  TapDownDetails? _doubleTapDetails;

  void _handleDoubleTap() {
    if (_controller.value != Matrix4.identity()) {
      _controller.value = Matrix4.identity();
    } else if (_doubleTapDetails != null) {
      final position = _doubleTapDetails!.localPosition;
      final x = -position.dx * 1.5;
      final y = -position.dy * 1.5;
      _controller.value = Matrix4(
        2.5, 0, 0, 0,
        0, 2.5, 0, 0,
        0, 0, 1, 0,
        x, y, 0, 1,
      );
    }
  }

  Widget _buildImageWidget() {
    final src = widget.imageSource.trim();
    if (src.startsWith('http://') || src.startsWith('https://')) {
      return CachedNetworkImage(
        imageUrl: src,
        fit: BoxFit.contain,
        placeholder: (context, url) => const Center(
          child: CircularProgressIndicator(color: AppColors.primary),
        ),
        errorWidget: (context, url, error) => const Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(Icons.broken_image, size: 64, color: AppColors.darkMuted),
              SizedBox(height: 12),
              Text('Unable to load image', style: TextStyle(color: AppColors.darkMuted)),
            ],
          ),
        ),
      );
    } else if (src.startsWith('/')) {
      // Local file
      final file = File(src);
      if (file.existsSync()) {
        return Image.file(file, fit: BoxFit.contain);
      }
    }
    // Fallback asset
    return Image.asset(
      'assets/images/logo.png',
      fit: BoxFit.contain,
    );
  }

  @override
  Widget build(BuildContext context) {
    Widget imageContent = _buildImageWidget();
    if (widget.heroTag != null) {
      imageContent = Hero(
        tag: widget.heroTag!,
        child: imageContent,
      );
    }

    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: Colors.black.withValues(alpha: 0.7),
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.close, color: Colors.white),
          onPressed: () => Navigator.of(context).pop(),
        ),
        title: widget.title != null
            ? Text(
                widget.title!,
                style: const TextStyle(color: Colors.white, fontSize: 16),
                overflow: TextOverflow.ellipsis,
              )
            : null,
      ),
      body: GestureDetector(
        onDoubleTapDown: (details) => _doubleTapDetails = details,
        onDoubleTap: _handleDoubleTap,
        child: Center(
          child: InteractiveViewer(
            transformationController: _controller,
            minScale: 0.5,
            maxScale: 5.0,
            child: imageContent,
          ),
        ),
      ),
    );
  }
}
