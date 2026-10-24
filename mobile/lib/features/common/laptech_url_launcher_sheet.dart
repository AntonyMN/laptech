import 'package:flutter/material.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/url_launcher_helper.dart';

class LaptechUrlLauncherSheet extends StatefulWidget {
  const LaptechUrlLauncherSheet({super.key});

  static void show(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: AppColors.darkSurface,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (_) => const LaptechUrlLauncherSheet(),
    );
  }

  @override
  State<LaptechUrlLauncherSheet> createState() => _LaptechUrlLauncherSheetState();
}

class _LaptechUrlLauncherSheetState extends State<LaptechUrlLauncherSheet> {
  final _customPathController = TextEditingController(text: '/admin/dashboard');

  @override
  void dispose() {
    _customPathController.dispose();
    super.dispose();
  }

  void _launch(String urlOrPath) {
    Navigator.of(context).pop();
    if (urlOrPath.startsWith('http://') || urlOrPath.startsWith('https://')) {
      LaptechUrlLauncher.launchWeb(urlOrPath, context: context);
    } else {
      LaptechUrlLauncher.launchWebAdmin(urlOrPath, context: context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Padding(
        padding: EdgeInsets.only(
          left: 20,
          right: 20,
          top: 20,
          bottom: MediaQuery.of(context).viewInsets.bottom + 20,
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: AppColors.primary.withValues(alpha: 0.15),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: const Icon(Icons.rocket_launch, color: AppColors.primaryBright, size: 22),
                ),
                const SizedBox(width: 12),
                const Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'LAPTECH.CO.KE URL LAUNCHER',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 14,
                          fontWeight: FontWeight.bold,
                          fontFamily: 'Montserrat',
                          letterSpacing: 0.8,
                        ),
                      ),
                      Text(
                        'Direct portal and web browser routing',
                        style: TextStyle(color: AppColors.darkMuted, fontSize: 11),
                      ),
                    ],
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.close, color: AppColors.darkMuted),
                  onPressed: () => Navigator.of(context).pop(),
                ),
              ],
            ),
            const Divider(color: AppColors.darkBorder, height: 24),

            // Custom Path Launcher Input
            const Text(
              'Launch Custom Route / URL',
              style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _customPathController,
                    style: const TextStyle(color: Colors.white, fontSize: 13),
                    decoration: InputDecoration(
                      prefixText: 'https://laptech.co.ke',
                      prefixStyle: const TextStyle(color: AppColors.darkMuted, fontSize: 12),
                      hintText: '/admin/...',
                      isDense: true,
                      contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                ElevatedButton(
                  onPressed: () {
                    final path = _customPathController.text.trim();
                    if (path.isNotEmpty) {
                      _launch(path);
                    }
                  },
                  child: const Text('OPEN'),
                ),
              ],
            ),

            const SizedBox(height: 20),
            const Text(
              'Predefined Portal Destinations',
              style: TextStyle(color: AppColors.darkMuted, fontSize: 11, fontWeight: FontWeight.bold, letterSpacing: 0.8),
            ),
            const SizedBox(height: 10),

            // Predefined Quick Links Grid
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                _buildQuickChip('Live Storefront', 'https://laptech.co.ke', Icons.storefront),
                _buildQuickChip('Web Admin Dashboard', '/admin/dashboard', Icons.dashboard),
                _buildQuickChip('Web Quotes Manager', '/admin/quotes', Icons.request_quote),
                _buildQuickChip('Web Blog Manager', '/admin/blog-posts', Icons.article),
                _buildQuickChip('Online Products', '/products', Icons.inventory),
                _buildQuickChip('IT Services Page', '/services', Icons.build),
                _buildQuickChip('Security Audit Logs', '/admin/audit', Icons.security),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildQuickChip(String label, String target, IconData icon) {
    return InkWell(
      onTap: () => _launch(target),
      borderRadius: BorderRadius.circular(10),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: AppColors.darkSurfaceMuted,
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: AppColors.darkBorder),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 14, color: AppColors.primary),
            const SizedBox(width: 6),
            Text(label, style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w500)),
            const SizedBox(width: 4),
            const Icon(Icons.arrow_outward, size: 12, color: AppColors.darkMuted),
          ],
        ),
      ),
    );
  }
}
