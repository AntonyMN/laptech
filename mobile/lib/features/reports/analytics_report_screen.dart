import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/report_model.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/currency_formatter.dart';
import '../../core/utils/url_launcher_helper.dart';
import 'reports_controller.dart';

class AnalyticsReportScreen extends ConsumerWidget {
  const AnalyticsReportScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(reportsControllerProvider);

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: const Text('Analytics & Reports'),
        actions: [
          IconButton(
            tooltip: 'Open in Web Admin Portal',
            icon: const Icon(Icons.open_in_browser, color: AppColors.primary),
            onPressed: () => LaptechUrlLauncher.launchWebAdmin('/admin/dashboard', context: context),
          ),
          IconButton(
            tooltip: 'Refresh Analytics',
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(reportsControllerProvider.notifier).loadReport(),
          ),
        ],
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : state.report == null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(Icons.analytics_outlined, size: 64, color: AppColors.darkMuted),
                      const SizedBox(height: 12),
                      Text(
                        state.errorMessage ?? 'Unable to generate analytics',
                        style: const TextStyle(color: Colors.white, fontSize: 14),
                      ),
                      const SizedBox(height: 16),
                      ElevatedButton(
                        onPressed: () => ref.read(reportsControllerProvider.notifier).loadReport(),
                        child: const Text('RETRY'),
                      ),
                    ],
                  ),
                )
              : RefreshIndicator(
                  onRefresh: () => ref.read(reportsControllerProvider.notifier).loadReport(),
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        // Report Header Banner
                        Container(
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(
                            gradient: LinearGradient(
                              colors: [
                                AppColors.primary.withValues(alpha: 0.15),
                                AppColors.darkSurface,
                              ],
                              begin: Alignment.topLeft,
                              end: Alignment.bottomRight,
                            ),
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(color: AppColors.primary.withValues(alpha: 0.3)),
                          ),
                          child: Row(
                            children: [
                              Container(
                                padding: const EdgeInsets.all(12),
                                decoration: BoxDecoration(
                                  color: AppColors.primary.withValues(alpha: 0.2),
                                  shape: BoxShape.circle,
                                ),
                                child: const Icon(Icons.insights, color: AppColors.primaryBright, size: 28),
                              ),
                              const SizedBox(width: 14),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    const Text(
                                      'EXECUTIVE BUSINESS REPORT',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontFamily: 'Montserrat',
                                        fontWeight: FontWeight.bold,
                                        fontSize: 14,
                                        letterSpacing: 1.0,
                                      ),
                                    ),
                                    const SizedBox(height: 4),
                                    Text(
                                      'Generated: ${CurrencyFormatter.formatDate(state.report!.generatedAt)}',
                                      style: const TextStyle(color: AppColors.darkMuted, fontSize: 11),
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),

                        const SizedBox(height: 20),

                        // Section 1: Financial & Revenue
                        _buildSectionTitle('FINANCIAL OVERVIEW', Icons.payments_outlined),
                        const SizedBox(height: 10),
                        _buildFinancialCard(state.report!.financial),

                        const SizedBox(height: 20),

                        // Section 2: Order Fulfillment & Logistics
                        _buildSectionTitle('ORDER FULFILLMENT & LOGISTICS', Icons.local_shipping_outlined),
                        const SizedBox(height: 10),
                        _buildOrdersCard(state.report!.orders),

                        const SizedBox(height: 20),

                        // Section 3: Inventory Health & Valuation
                        _buildSectionTitle('INVENTORY HEALTH & VALUATION', Icons.inventory_2_outlined),
                        const SizedBox(height: 10),
                        _buildInventoryCard(state.report!.inventory),

                        const SizedBox(height: 20),

                        // Section 4: Quotes Pipeline
                        _buildSectionTitle('QUOTATIONS & INQUIRIES PIPELINE', Icons.request_quote_outlined),
                        const SizedBox(height: 10),
                        _buildQuotesCard(state.report!.quotes),

                        const SizedBox(height: 20),

                        // Section 5: Content & Users
                        _buildSectionTitle('CONTENT & USERS SUMMARY', Icons.people_outline),
                        const SizedBox(height: 10),
                        _buildContentUsersCard(state.report!.content, state.report!.users),

                        const SizedBox(height: 24),

                        // Quick action launcher for web admin
                        SizedBox(
                          height: 48,
                          child: ElevatedButton.icon(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppColors.primary,
                              foregroundColor: Colors.black,
                            ),
                            icon: const Icon(Icons.open_in_browser, size: 18),
                            label: const Text(
                              'OPEN DETAILED WEB ANALYTICS',
                              style: TextStyle(fontWeight: FontWeight.bold, letterSpacing: 0.8),
                            ),
                            onPressed: () => LaptechUrlLauncher.launchWebAdmin('/admin/dashboard', context: context),
                          ),
                        ),

                        const SizedBox(height: 32),
                      ],
                    ),
                  ),
                ),
    );
  }

  Widget _buildSectionTitle(String title, IconData icon) {
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
            letterSpacing: 1.1,
            color: Colors.white,
          ),
        ),
      ],
    );
  }

  Widget _buildFinancialCard(FinancialReport fin) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.darkSurface,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.darkBorder),
      ),
      child: Column(
        children: [
          Row(
            children: [
              Expanded(
                child: _buildMetricTile(
                  'Delivered Revenue',
                  CurrencyFormatter.format(fin.totalRevenue),
                  color: AppColors.success,
                ),
              ),
              Container(width: 1, height: 40, color: AppColors.darkBorder),
              Expanded(
                child: _buildMetricTile(
                  'Pending Revenue',
                  CurrencyFormatter.format(fin.pendingRevenue),
                  color: AppColors.warning,
                ),
              ),
            ],
          ),
          const Divider(color: AppColors.darkBorder, height: 24),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Average Order Value (AOV)', style: TextStyle(color: AppColors.darkMuted, fontSize: 12)),
              Text(
                CurrencyFormatter.format(fin.averageOrderValue),
                style: const TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.bold),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildOrdersCard(OrdersReport ord) {
    return Container(
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
              const Text('Fulfillment Rate', style: TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w600)),
              Text(
                '${ord.fulfillmentRate}%',
                style: const TextStyle(color: AppColors.primaryBright, fontSize: 13, fontWeight: FontWeight.bold),
              ),
            ],
          ),
          const SizedBox(height: 8),
          ClipRRect(
            borderRadius: BorderRadius.circular(4),
            child: LinearProgressIndicator(
              value: ord.total > 0 ? (ord.delivered / ord.total).clamp(0.0, 1.0) : 0.0,
              backgroundColor: AppColors.darkPage,
              valueColor: const AlwaysStoppedAnimation<Color>(AppColors.primary),
              minHeight: 6,
            ),
          ),
          const SizedBox(height: 16),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              _buildMiniStatusBadge('Total', ord.total.toString(), Colors.white),
              _buildMiniStatusBadge('Delivered', ord.delivered.toString(), AppColors.success),
              _buildMiniStatusBadge('Shipped', ord.shipped.toString(), AppColors.primary),
              _buildMiniStatusBadge('Pending', ord.pending.toString(), AppColors.warning),
              _buildMiniStatusBadge('Cancelled', ord.cancelled.toString(), AppColors.danger),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildInventoryCard(InventoryReport inv) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.darkSurface,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.darkBorder),
      ),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Estimated Inventory Valuation', style: TextStyle(color: AppColors.darkMuted, fontSize: 12)),
              Text(
                CurrencyFormatter.format(inv.estimatedValue),
                style: const TextStyle(color: AppColors.primaryBright, fontSize: 14, fontWeight: FontWeight.bold),
              ),
            ],
          ),
          const Divider(color: AppColors.darkBorder, height: 20),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _buildInventoryPill('Active Catalog', inv.activeProducts.toString(), Colors.white),
              _buildInventoryPill('Low Stock (≤5)', inv.lowStock.toString(), AppColors.warning),
              _buildInventoryPill('Out of Stock', inv.outOfStock.toString(), AppColors.danger),
              _buildInventoryPill('Archived', inv.archivedProducts.toString(), AppColors.darkMuted),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildQuotesCard(QuotesReport q) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.darkSurface,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.darkBorder),
      ),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Inquiry Response Rate', style: TextStyle(color: AppColors.darkMuted, fontSize: 12)),
              Text(
                '${q.responseRate}%',
                style: const TextStyle(color: AppColors.success, fontSize: 14, fontWeight: FontWeight.bold),
              ),
            ],
          ),
          const Divider(color: AppColors.darkBorder, height: 20),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _buildInventoryPill('Total Quotes', q.total.toString(), Colors.white),
              _buildInventoryPill('Pending', q.pending.toString(), AppColors.warning),
              _buildInventoryPill('Responded', q.responded.toString(), AppColors.primary),
              _buildInventoryPill('Closed', q.closed.toString(), AppColors.success),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildContentUsersCard(ContentReport cnt, UsersReport usr) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.darkSurface,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.darkBorder),
      ),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              _buildMetricTile('Blog Articles', '${cnt.published} / ${cnt.totalPosts} pub'),
              _buildMetricTile('IT Services', cnt.totalServices.toString()),
              _buildMetricTile('Total Users', '${usr.total} (${usr.admins} admin)'),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMetricTile(String label, String value, {Color? color}) {
    return Column(
      children: [
        Text(value, style: TextStyle(color: color ?? Colors.white, fontSize: 15, fontWeight: FontWeight.bold, fontFamily: 'Montserrat')),
        const SizedBox(height: 2),
        Text(label, style: const TextStyle(color: AppColors.darkMuted, fontSize: 11)),
      ],
    );
  }

  Widget _buildMiniStatusBadge(String label, String count, Color color) {
    return Column(
      children: [
        Text(count, style: TextStyle(color: color, fontWeight: FontWeight.bold, fontSize: 14)),
        const SizedBox(height: 2),
        Text(label, style: const TextStyle(color: AppColors.darkMuted, fontSize: 10)),
      ],
    );
  }

  Widget _buildInventoryPill(String label, String count, Color color) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.15),
            borderRadius: BorderRadius.circular(8),
            border: Border.all(color: color.withValues(alpha: 0.5)),
          ),
          child: Text(count, style: TextStyle(color: color, fontWeight: FontWeight.bold, fontSize: 13)),
        ),
        const SizedBox(height: 4),
        Text(label, style: const TextStyle(color: AppColors.darkMuted, fontSize: 10)),
      ],
    );
  }
}
