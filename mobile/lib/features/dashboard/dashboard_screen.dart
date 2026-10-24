import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/currency_formatter.dart';
import '../auth/auth_controller.dart';
import '../navigation/main_navigation_screen.dart';
import '../products/product_edit_screen.dart';
import '../services/service_edit_screen.dart';
import 'dashboard_controller.dart';

class DashboardScreen extends ConsumerStatefulWidget {
  const DashboardScreen({super.key});

  @override
  ConsumerState<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends ConsumerState<DashboardScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(dashboardControllerProvider.notifier).fetchDashboard());
  }

  void _navigateToTab(int tabIndex) {
    ref.read(navigationIndexProvider.notifier).state = tabIndex;
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(dashboardControllerProvider);
    final user = ref.watch(authControllerProvider).user;

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: Row(
          children: [
            Container(
              width: 32,
              height: 32,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: AppColors.primary.withValues(alpha: 0.2),
                border: Border.all(color: AppColors.primary, width: 1.5),
              ),
              child: Center(
                child: Image.asset(
                  'assets/images/logo.png',
                  width: 18,
                  height: 18,
                  errorBuilder: (_, __, ___) => const Icon(Icons.bolt, size: 18, color: AppColors.primary),
                ),
              ),
            ),
            const SizedBox(width: 10),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'LAPTECH ADMIN',
                  style: TextStyle(
                    fontFamily: 'Montserrat',
                    fontSize: 14,
                    fontWeight: FontWeight.w800,
                    letterSpacing: 1.2,
                    color: Colors.white,
                  ),
                ),
                Text(
                  user != null ? 'Signed in as ${user.name}' : 'Admin Dashboard',
                  style: const TextStyle(fontSize: 11, color: AppColors.darkMuted),
                ),
              ],
            ),
          ],
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh, color: Colors.white70),
            onPressed: () => ref.read(dashboardControllerProvider.notifier).fetchDashboard(),
          ),
        ],
      ),
      body: RefreshIndicator(
        color: AppColors.primary,
        onRefresh: () => ref.read(dashboardControllerProvider.notifier).fetchDashboard(),
        child: state.isLoading && state.data == null
            ? const Center(
                child: CircularProgressIndicator(color: AppColors.primary),
              )
            : state.errorMessage != null && state.data == null
                ? Center(
                    child: Padding(
                      padding: const EdgeInsets.all(24),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(Icons.cloud_off, size: 48, color: AppColors.danger),
                          const SizedBox(height: 16),
                          Text(
                            state.errorMessage!,
                            textAlign: TextAlign.center,
                            style: const TextStyle(color: Colors.white),
                          ),
                          const SizedBox(height: 16),
                          ElevatedButton.icon(
                            onPressed: () => ref.read(dashboardControllerProvider.notifier).fetchDashboard(),
                            icon: const Icon(Icons.refresh),
                            label: const Text('RETRY'),
                          ),
                        ],
                      ),
                    ),
                  )
                : _buildContent(state),
      ),
    );
  }

  Widget _buildContent(DashboardState state) {
    final data = state.data!;
    final stats = data.stats;

    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Offline Indicator Banner if applicable
          if (data.isOffline) ...[
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
              margin: const EdgeInsets.only(bottom: 16),
              decoration: BoxDecoration(
                color: AppColors.warning.withValues(alpha: 0.15),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: AppColors.warning.withValues(alpha: 0.4)),
              ),
              child: Row(
                children: [
                  const Icon(Icons.wifi_off, color: AppColors.warning, size: 20),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      'Offline Mode: Viewing cached data${data.cachedAt != null ? ' from ${CurrencyFormatter.formatDate(data.cachedAt)}' : ''}.',
                      style: const TextStyle(color: Colors.white, fontSize: 12),
                    ),
                  ),
                ],
              ),
            ),
          ],

          // Total Revenue Hero Card
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [Color(0xFF20232C), Color(0xFF16181F)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: AppColors.primary.withValues(alpha: 0.4)),
              boxShadow: [
                BoxShadow(
                  color: AppColors.primary.withValues(alpha: 0.12),
                  blurRadius: 20,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'TOTAL SALES REVENUE',
                      style: TextStyle(
                        fontSize: 11,
                        fontWeight: FontWeight.w700,
                        letterSpacing: 1.5,
                        color: AppColors.primaryBright,
                      ),
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: AppColors.primary.withValues(alpha: 0.15),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Row(
                        children: [
                          const Icon(Icons.trending_up, size: 14, color: AppColors.primaryBright),
                          const SizedBox(width: 4),
                          Text(
                            '${stats.totalOrders} Orders',
                            style: const TextStyle(fontSize: 11, color: AppColors.primaryBright, fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Text(
                  CurrencyFormatter.format(stats.revenue),
                  style: const TextStyle(
                    fontFamily: 'Montserrat',
                    fontSize: 28,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  '${stats.pendingOrders} pending fulfillment • ${stats.deliveredOrders} delivered',
                  style: const TextStyle(fontSize: 12, color: AppColors.darkMuted),
                ),
              ],
            ),
          ),

          const SizedBox(height: 16),

          // 2x2 Metric Grid
          GridView.count(
            crossAxisCount: 2,
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            crossAxisSpacing: 12,
            mainAxisSpacing: 12,
            childAspectRatio: 1.45,
            children: [
              _buildMetricCard(
                title: 'Catalog Items',
                value: stats.totalProducts.toString(),
                subtitle: '${stats.lowStockCount} low in stock',
                icon: Icons.inventory_2_outlined,
                accentColor: AppColors.primary,
                onTap: () => _navigateToTab(1),
              ),
              _buildMetricCard(
                title: 'Pending Orders',
                value: stats.pendingOrders.toString(),
                subtitle: 'Awaiting dispatch',
                icon: Icons.local_shipping_outlined,
                accentColor: AppColors.warning,
                onTap: () => _navigateToTab(2),
              ),
              _buildMetricCard(
                title: 'Active Services',
                value: stats.totalServices.toString(),
                subtitle: 'Technical offerings',
                icon: Icons.miscellaneous_services_outlined,
                accentColor: AppColors.info,
                onTap: () => _navigateToTab(3),
              ),
              _buildMetricCard(
                title: 'Quotations',
                value: stats.totalQuotes.toString(),
                subtitle: 'Customer requests',
                icon: Icons.request_quote_outlined,
                accentColor: AppColors.purple,
                onTap: () {},
              ),
            ],
          ),

          const SizedBox(height: 24),

          // Actionable Alerts Section
          if (data.alerts.isNotEmpty) ...[
            const Row(
              children: [
                Icon(Icons.warning_amber_rounded, size: 18, color: AppColors.warning),
                SizedBox(width: 8),
                Text(
                  'SYSTEM ALERTS',
                  style: TextStyle(
                    fontFamily: 'Montserrat',
                    fontSize: 13,
                    fontWeight: FontWeight.w700,
                    letterSpacing: 1.2,
                    color: Colors.white,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 10),
            ...data.alerts.map((alert) => Container(
                  margin: const EdgeInsets.only(bottom: 10),
                  padding: const EdgeInsets.all(14),
                  decoration: BoxDecoration(
                    color: AppColors.darkSurface,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(
                      color: alert.type == 'warning'
                          ? AppColors.warning.withValues(alpha: 0.5)
                          : AppColors.info.withValues(alpha: 0.5),
                    ),
                  ),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: (alert.type == 'warning' ? AppColors.warning : AppColors.info).withValues(alpha: 0.15),
                        ),
                        child: Icon(
                          alert.type == 'warning' ? Icons.notification_important : Icons.info_outline,
                          color: alert.type == 'warning' ? AppColors.warning : AppColors.info,
                          size: 20,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              alert.title,
                              style: const TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                                fontSize: 13,
                              ),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              alert.message,
                              style: const TextStyle(color: AppColors.darkMuted, fontSize: 12),
                            ),
                          ],
                        ),
                      ),
                      TextButton(
                        onPressed: () {
                          if (alert.actionTab == 'products') {
                            _navigateToTab(1);
                          } else if (alert.actionTab == 'orders') {
                            _navigateToTab(2);
                          }
                        },
                        child: const Text('VIEW', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                      ),
                    ],
                  ),
                )),
            const SizedBox(height: 16),
          ],

          // Quicklinks Grid
          const Row(
            children: [
              Icon(Icons.flash_on, size: 18, color: AppColors.primary),
              SizedBox(width: 8),
              Text(
                'QUICK ACTIONS',
                style: TextStyle(
                  fontFamily: 'Montserrat',
                  fontSize: 13,
                  fontWeight: FontWeight.w700,
                  letterSpacing: 1.2,
                  color: Colors.white,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: _buildQuickActionButton(
                  icon: Icons.add_circle_outline,
                  label: 'Add Product',
                  color: AppColors.primary,
                  onTap: () {
                    Navigator.of(context).push(
                      MaterialPageRoute(builder: (_) => const ProductEditScreen()),
                    );
                  },
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: _buildQuickActionButton(
                  icon: Icons.build_circle_outlined,
                  label: 'Add Service',
                  color: AppColors.info,
                  onTap: () {
                    Navigator.of(context).push(
                      MaterialPageRoute(builder: (_) => const ServiceEditScreen()),
                    );
                  },
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: _buildQuickActionButton(
                  icon: Icons.receipt_long_outlined,
                  label: 'View Orders',
                  color: AppColors.warning,
                  onTap: () => _navigateToTab(2),
                ),
              ),
            ],
          ),

          const SizedBox(height: 28),

          // Recent Orders
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Row(
                children: [
                  Icon(Icons.history, size: 18, color: Colors.white70),
                  SizedBox(width: 8),
                  Text(
                    'RECENT ORDERS',
                    style: TextStyle(
                      fontFamily: 'Montserrat',
                      fontSize: 13,
                      fontWeight: FontWeight.w700,
                      letterSpacing: 1.2,
                      color: Colors.white,
                    ),
                  ),
                ],
              ),
              GestureDetector(
                onTap: () => _navigateToTab(2),
                child: const Text(
                  'SEE ALL',
                  style: TextStyle(
                    color: AppColors.primaryBright,
                    fontWeight: FontWeight.w700,
                    fontSize: 11,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),

          if (data.recentOrders.isEmpty)
            Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: AppColors.darkSurface,
                borderRadius: BorderRadius.circular(12),
              ),
              child: const Center(
                child: Text('No orders recorded yet.', style: TextStyle(color: AppColors.darkMuted)),
              ),
            )
          else
            ...data.recentOrders.map((order) => Container(
                  margin: const EdgeInsets.only(bottom: 10),
                  padding: const EdgeInsets.all(14),
                  decoration: BoxDecoration(
                    color: AppColors.darkSurface,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: AppColors.darkBorder),
                  ),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          color: order.statusColor.withValues(alpha: 0.15),
                          shape: BoxShape.circle,
                        ),
                        child: Icon(Icons.shopping_bag_outlined, color: order.statusColor, size: 18),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              order.customerName,
                              style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              'Order #${order.id} • ${CurrencyFormatter.formatDateShort(order.createdAt)}',
                              style: const TextStyle(color: AppColors.darkMuted, fontSize: 11),
                            ),
                          ],
                        ),
                      ),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.end,
                        children: [
                          Text(
                            CurrencyFormatter.format(order.total),
                            style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w700, fontSize: 13),
                          ),
                          const SizedBox(height: 4),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                            decoration: BoxDecoration(
                              color: order.statusColor.withValues(alpha: 0.2),
                              borderRadius: BorderRadius.circular(4),
                            ),
                            child: Text(
                              order.status.toUpperCase(),
                              style: TextStyle(color: order.statusColor, fontSize: 9, fontWeight: FontWeight.bold),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                )),
        ],
      ),
    );
  }

  Widget _buildMetricCard({
    required String title,
    required String value,
    required String subtitle,
    required IconData icon,
    required Color accentColor,
    required VoidCallback onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: AppColors.darkSurface,
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: AppColors.darkBorder),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  title,
                  style: const TextStyle(fontSize: 12, color: AppColors.darkMuted, fontWeight: FontWeight.w600),
                ),
                Icon(icon, size: 18, color: accentColor),
              ],
            ),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  value,
                  style: const TextStyle(
                    fontFamily: 'Montserrat',
                    fontSize: 22,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  subtitle,
                  style: TextStyle(fontSize: 11, color: accentColor, fontWeight: FontWeight.w500),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildQuickActionButton({
    required IconData icon,
    required String label,
    required Color color,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 8),
        decoration: BoxDecoration(
          color: AppColors.darkSurface,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: AppColors.darkBorder),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, color: color, size: 24),
            const SizedBox(height: 6),
            Text(
              label,
              textAlign: TextAlign.center,
              style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w600),
            ),
          ],
        ),
      ),
    );
  }
}
