import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/currency_formatter.dart';
import '../auth/auth_controller.dart';
import '../auth/login_screen.dart';
import 'profile_edit_dialog.dart';
import 'settings_controller.dart';

class SettingsScreen extends ConsumerStatefulWidget {
  const SettingsScreen({super.key});

  @override
  ConsumerState<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends ConsumerState<SettingsScreen> {
  final _serverUrlController = TextEditingController();
  bool _isTestingConnection = false;
  String? _testResult;

  @override
  void initState() {
    super.initState();
    _serverUrlController.text = ref.read(settingsControllerProvider).serverUrl;
  }

  @override
  void dispose() {
    _serverUrlController.dispose();
    super.dispose();
  }

  Future<void> _testConnection() async {
    setState(() {
      _isTestingConnection = true;
      _testResult = null;
    });

    final success = await ref.read(settingsControllerProvider.notifier).testConnection(
          _serverUrlController.text.trim(),
        );

    if (mounted) {
      setState(() {
        _isTestingConnection = false;
        _testResult = success ? 'Connection verified! Server is reachable.' : 'Connection failed. Check host and port.';
      });
    }
  }

  Future<void> _handleLogout() async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.darkSurface,
        title: const Text('Sign Out', style: TextStyle(color: Colors.white)),
        content: const Text('Are you sure you want to end your administrative session?',
            style: TextStyle(color: AppColors.darkMuted)),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(ctx).pop(false),
            child: const Text('CANCEL'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.of(ctx).pop(true),
            child: const Text('SIGN OUT'),
          ),
        ],
      ),
    );

    if (confirmed == true && mounted) {
      await ref.read(authControllerProvider.notifier).logout();
      if (mounted) {
        Navigator.of(context).pushAndRemoveUntil(
          MaterialPageRoute(builder: (_) => const LoginScreen()),
          (route) => false,
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final settings = ref.watch(settingsControllerProvider);
    final user = ref.watch(authControllerProvider).user;

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: const Text('Settings & Profile'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Admin Profile Card
            if (user != null) ...[
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: AppColors.darkSurface,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: AppColors.darkBorder),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 52,
                      height: 52,
                      decoration: BoxDecoration(
                        color: AppColors.primary.withValues(alpha: 0.2),
                        shape: BoxShape.circle,
                        border: Border.all(color: AppColors.primary, width: 2),
                      ),
                      child: Center(
                        child: Text(
                          user.name.isNotEmpty ? user.name.substring(0, 1).toUpperCase() : 'A',
                          style: const TextStyle(
                            fontFamily: 'Montserrat',
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                            color: AppColors.primaryBright,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Text(
                                user.name,
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontWeight: FontWeight.bold,
                                  fontSize: 15,
                                ),
                              ),
                              const SizedBox(width: 8),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                decoration: BoxDecoration(
                                  color: AppColors.primary.withValues(alpha: 0.15),
                                  borderRadius: BorderRadius.circular(4),
                                ),
                                child: const Text(
                                  'ADMIN',
                                  style: TextStyle(
                                    color: AppColors.primaryBright,
                                    fontSize: 9,
                                    fontWeight: FontWeight.bold,
                                    letterSpacing: 1.0,
                                  ),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 2),
                          Text(user.email, style: const TextStyle(color: AppColors.darkMuted, fontSize: 12)),
                        ],
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.edit_outlined, color: AppColors.primary),
                      onPressed: () {
                        showDialog(
                          context: context,
                          builder: (_) => ProfileEditDialog(user: user),
                        );
                      },
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 20),
            ],

            // Section: Server Endpoint Configuration
            _buildSectionHeader('API SERVER CONFIGURATION', Icons.dns_outlined),
            const SizedBox(height: 10),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.darkSurface,
                borderRadius: BorderRadius.circular(14),
                border: Border.all(color: AppColors.darkBorder),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Backend Host Endpoint', style: TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 4),
                  const Text('Configure API gateway URL for development or production.', style: TextStyle(color: AppColors.darkMuted, fontSize: 11)),
                  const SizedBox(height: 10),
                  TextFormField(
                    controller: _serverUrlController,
                    style: const TextStyle(color: Colors.white, fontSize: 13),
                    decoration: const InputDecoration(
                      isDense: true,
                      hintText: 'http://127.0.0.1:8000',
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      ElevatedButton(
                        onPressed: () async {
                          final messenger = ScaffoldMessenger.of(context);
                          await ref.read(settingsControllerProvider.notifier).updateServerUrl(_serverUrlController.text.trim());
                          if (mounted) {
                            messenger.showSnackBar(
                              const SnackBar(backgroundColor: AppColors.success, content: Text('Server URL updated')),
                            );
                          }
                        },
                        child: const Text('APPLY'),
                      ),
                      const SizedBox(width: 8),
                      OutlinedButton(
                        onPressed: _isTestingConnection ? null : _testConnection,
                        child: _isTestingConnection
                            ? const SizedBox(width: 14, height: 14, child: CircularProgressIndicator(strokeWidth: 2))
                            : const Text('TEST PING'),
                      ),
                    ],
                  ),
                  if (_testResult != null) ...[
                    const SizedBox(height: 10),
                    Text(
                      _testResult!,
                      style: TextStyle(
                        fontSize: 12,
                        color: _testResult!.contains('verified') ? AppColors.success : AppColors.danger,
                      ),
                    ),
                  ],
                ],
              ),
            ),

            const SizedBox(height: 20),

            // Section: Local SQLite & Offline Sync
            _buildSectionHeader('SQLITE OFFLINE CACHE & SYNC', Icons.storage_outlined),
            const SizedBox(height: 10),
            Container(
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
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Last Synchronized', style: TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w600)),
                          const SizedBox(height: 2),
                          Text(
                            settings.lastSyncTime != null ? CurrencyFormatter.formatDate(settings.lastSyncTime!.toIso8601String()) : 'Not synced yet',
                            style: const TextStyle(color: AppColors.darkMuted, fontSize: 11),
                          ),
                        ],
                      ),
                      ElevatedButton.icon(
                        onPressed: settings.isSyncing
                            ? null
                            : () async {
                                final messenger = ScaffoldMessenger.of(context);
                                await ref.read(settingsControllerProvider.notifier).syncAll();
                                if (mounted) {
                                  messenger.showSnackBar(
                                    const SnackBar(backgroundColor: AppColors.success, content: Text('Full cache refreshed from server!')),
                                  );
                                }
                              },
                        icon: settings.isSyncing
                            ? const SizedBox(width: 14, height: 14, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                            : const Icon(Icons.sync, size: 16),
                        label: const Text('SYNC NOW'),
                      ),
                    ],
                  ),
                  const Divider(color: AppColors.darkBorder, height: 24),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Clear Local SQLite Cache', style: TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w600)),
                          Text('Wipes offline copy of catalog, orders, and stats', style: TextStyle(color: AppColors.darkMuted, fontSize: 11)),
                        ],
                      ),
                      OutlinedButton(
                        style: OutlinedButton.styleFrom(foregroundColor: AppColors.warning),
                        onPressed: () async {
                          final messenger = ScaffoldMessenger.of(context);
                          await ref.read(settingsControllerProvider.notifier).clearLocalCache();
                          if (mounted) {
                            messenger.showSnackBar(
                              const SnackBar(content: Text('Local SQLite cache cleared.')),
                            );
                          }
                        },
                        child: const Text('CLEAR'),
                      ),
                    ],
                  ),
                ],
              ),
            ),

            const SizedBox(height: 20),

            // Section: Appearance & System Info
            _buildSectionHeader('SYSTEM & PREFERENCES', Icons.info_outline),
            const SizedBox(height: 10),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppColors.darkSurface,
                borderRadius: BorderRadius.circular(14),
                border: Border.all(color: AppColors.darkBorder),
              ),
              child: Column(
                children: [
                  SwitchListTile(
                    contentPadding: EdgeInsets.zero,
                    activeThumbColor: AppColors.primary,
                    title: const Text('Dark Mode Appearance', style: TextStyle(color: Colors.white, fontSize: 13)),
                    subtitle: const Text('Laptech signature high-contrast dark theme', style: TextStyle(color: AppColors.darkMuted, fontSize: 11)),
                    value: settings.isDarkMode,
                    onChanged: (val) {
                      ref.read(settingsControllerProvider.notifier).toggleTheme(val);
                    },
                  ),
                  const Divider(color: AppColors.darkBorder, height: 20),
                  _buildSystemInfoRow('App Name', settings.systemInfo?['app_name'] ?? 'Laptech Admin Mobile'),
                  _buildSystemInfoRow('Platform Version', '1.0.0+1 (Stable)'),
                  _buildSystemInfoRow('API Gateway Version', settings.systemInfo?['api_version'] ?? 'v1'),
                  _buildSystemInfoRow('Laravel Backend', settings.systemInfo?['laravel_version'] ?? '13.x'),
                  _buildSystemInfoRow('PHP Runtime', settings.systemInfo?['php_version'] ?? '8.3'),
                  _buildSystemInfoRow('MySQL Connection', settings.systemInfo?['database_connected'] == true ? 'Connected (Active)' : 'Unknown'),
                ],
              ),
            ),

            const SizedBox(height: 28),

            // Sign Out Button
            SizedBox(
              height: 50,
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.darkSurfaceMuted,
                  foregroundColor: AppColors.danger,
                  side: const BorderSide(color: AppColors.danger),
                ),
                icon: const Icon(Icons.logout, size: 18),
                label: const Text('SIGN OUT', style: TextStyle(fontWeight: FontWeight.bold, letterSpacing: 1.0)),
                onPressed: _handleLogout,
              ),
            ),

            const SizedBox(height: 36),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title, IconData icon) {
    return Row(
      children: [
        Icon(icon, size: 16, color: AppColors.primary),
        const SizedBox(width: 8),
        Text(
          title,
          style: const TextStyle(
            fontFamily: 'Montserrat',
            fontSize: 12,
            fontWeight: FontWeight.bold,
            letterSpacing: 1.2,
            color: Colors.white,
          ),
        ),
      ],
    );
  }

  Widget _buildSystemInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: AppColors.darkMuted, fontSize: 12)),
          Text(value, style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }
}
