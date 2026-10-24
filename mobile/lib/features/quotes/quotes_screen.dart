import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/quote_model.dart';
import '../../core/theme/app_colors.dart';
import '../../core/utils/currency_formatter.dart';
import '../../core/utils/url_launcher_helper.dart';
import 'quotes_controller.dart';

class QuotesScreen extends ConsumerStatefulWidget {
  const QuotesScreen({super.key});

  @override
  ConsumerState<QuotesScreen> createState() => _QuotesScreenState();
}

class _QuotesScreenState extends ConsumerState<QuotesScreen> {
  final _searchController = TextEditingController();

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _showStatusDialog(QuoteModel quote) {
    showModalBottomSheet(
      context: context,
      backgroundColor: AppColors.darkSurface,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (ctx) => SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Update Status for #${quote.id}',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  fontFamily: 'Montserrat',
                ),
              ),
              const SizedBox(height: 6),
              Text(
                'Client: ${quote.name} (${quote.serviceName})',
                style: const TextStyle(color: AppColors.darkMuted, fontSize: 13),
              ),
              const SizedBox(height: 18),
              _buildStatusOption(quote, 'pending', 'Pending (Under Review)', AppColors.warning),
              _buildStatusOption(quote, 'responded', 'Responded (Offer Sent)', AppColors.primary),
              _buildStatusOption(quote, 'closed', 'Closed (Resolved)', AppColors.success),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStatusOption(QuoteModel quote, String status, String label, Color color) {
    final isSelected = quote.status == status;
    return ListTile(
      contentPadding: EdgeInsets.zero,
      leading: Container(
        width: 14,
        height: 14,
        decoration: BoxDecoration(
          color: isSelected ? color : Colors.transparent,
          shape: BoxShape.circle,
          border: Border.all(color: color, width: 2),
        ),
      ),
      title: Text(
        label,
        style: TextStyle(
          color: isSelected ? Colors.white : AppColors.darkMuted,
          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          fontSize: 14,
        ),
      ),
      trailing: isSelected ? Icon(Icons.check, color: color, size: 18) : null,
      onTap: () async {
        Navigator.of(context).pop();
        if (quote.status != status) {
          final success = await ref.read(quotesControllerProvider.notifier).updateQuoteStatus(quote.id, status);
          if (mounted && success) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                backgroundColor: AppColors.success,
                content: Text('Quote #${quote.id} marked as ${status.toUpperCase()}'),
              ),
            );
          }
        }
      },
    );
  }

  Future<void> _confirmDelete(QuoteModel quote) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.darkSurface,
        title: const Text('Delete Inquiry', style: TextStyle(color: Colors.white)),
        content: Text(
          'Delete quote request from ${quote.name}? This action cannot be undone.',
          style: const TextStyle(color: AppColors.darkMuted),
        ),
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
      final success = await ref.read(quotesControllerProvider.notifier).deleteQuote(quote.id);
      if (mounted && success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Quote request removed.')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(quotesControllerProvider);

    return Scaffold(
      backgroundColor: AppColors.darkPage,
      appBar: AppBar(
        title: const Text('Quote Requests'),
        actions: [
          IconButton(
            tooltip: 'Open in Web Admin Portal',
            icon: const Icon(Icons.open_in_browser, color: AppColors.primary),
            onPressed: () => LaptechUrlLauncher.launchWebAdmin('/admin/quotes', context: context),
          ),
          IconButton(
            tooltip: 'Refresh Inquiries',
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(quotesControllerProvider.notifier).loadQuotes(),
          ),
        ],
      ),
      body: Column(
        children: [
          // Search & Filter header
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            color: AppColors.darkSurface,
            child: Column(
              children: [
                TextField(
                  controller: _searchController,
                  style: const TextStyle(color: Colors.white, fontSize: 13),
                  decoration: InputDecoration(
                    hintText: 'Search by client name, email, phone...',
                    prefixIcon: const Icon(Icons.search, size: 18, color: AppColors.darkMuted),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear, size: 16, color: AppColors.darkMuted),
                            onPressed: () {
                              _searchController.clear();
                              ref.read(quotesControllerProvider.notifier).search('');
                            },
                          )
                        : null,
                    isDense: true,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                  ),
                  onChanged: (val) {
                    ref.read(quotesControllerProvider.notifier).search(val.trim());
                  },
                ),
                const SizedBox(height: 10),
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: [
                      _buildFilterChip('all', 'All (${state.totalCount})', state.filterStatus),
                      const SizedBox(width: 8),
                      _buildFilterChip('pending', 'Pending (${state.pendingCount})', state.filterStatus, color: AppColors.warning),
                      const SizedBox(width: 8),
                      _buildFilterChip('responded', 'Responded', state.filterStatus, color: AppColors.primary),
                      const SizedBox(width: 8),
                      _buildFilterChip('closed', 'Closed', state.filterStatus, color: AppColors.success),
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
                : state.quotes.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.inbox_outlined, size: 64, color: AppColors.darkMuted.withValues(alpha: 0.5)),
                            const SizedBox(height: 12),
                            const Text(
                              'No quote requests found',
                              style: TextStyle(color: Colors.white, fontSize: 15, fontWeight: FontWeight.bold),
                            ),
                            const SizedBox(height: 4),
                            const Text(
                              'Inquiries from laptech.co.ke/services will appear here',
                              style: TextStyle(color: AppColors.darkMuted, fontSize: 12),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: () => ref.read(quotesControllerProvider.notifier).loadQuotes(),
                        child: ListView.separated(
                          padding: const EdgeInsets.all(16),
                          itemCount: state.quotes.length,
                          separatorBuilder: (_, __) => const SizedBox(height: 12),
                          itemBuilder: (context, index) {
                            final quote = state.quotes[index];
                            return _buildQuoteCard(quote);
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
      onTap: () => ref.read(quotesControllerProvider.notifier).setFilter(value),
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

  Widget _buildQuoteCard(QuoteModel quote) {
    return Container(
      decoration: BoxDecoration(
        color: AppColors.darkSurface,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(
          color: quote.isPending ? AppColors.warning.withValues(alpha: 0.4) : AppColors.darkBorder,
          width: quote.isPending ? 1.5 : 1,
        ),
      ),
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Top row: ID, service badge & status pill
          Row(
            children: [
              Text(
                '#Q-${quote.id}',
                style: const TextStyle(
                  color: AppColors.darkMuted,
                  fontSize: 12,
                  fontFamily: 'Montserrat',
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: Text(
                  quote.serviceName,
                  style: const TextStyle(
                    color: AppColors.primaryBright,
                    fontWeight: FontWeight.w600,
                    fontSize: 13,
                  ),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              InkWell(
                onTap: () => _showStatusDialog(quote),
                borderRadius: BorderRadius.circular(6),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                  decoration: BoxDecoration(
                    color: quote.statusColor.withValues(alpha: 0.15),
                    borderRadius: BorderRadius.circular(6),
                    border: Border.all(color: quote.statusColor, width: 1),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        quote.status.toUpperCase(),
                        style: TextStyle(
                          color: quote.statusColor,
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                          letterSpacing: 0.5,
                        ),
                      ),
                      const SizedBox(width: 4),
                      Icon(Icons.arrow_drop_down, color: quote.statusColor, size: 14),
                    ],
                  ),
                ),
              ),
            ],
          ),
          const Divider(color: AppColors.darkBorder, height: 18),

          // Client Info
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CircleAvatar(
                radius: 18,
                backgroundColor: AppColors.primary.withValues(alpha: 0.15),
                child: Text(
                  quote.name.isNotEmpty ? quote.name.substring(0, 1).toUpperCase() : '?',
                  style: const TextStyle(color: AppColors.primaryBright, fontWeight: FontWeight.bold),
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      quote.name,
                      style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      quote.email,
                      style: const TextStyle(color: AppColors.darkMuted, fontSize: 12),
                    ),
                    if (quote.phone != null && quote.phone!.isNotEmpty) ...[
                      const SizedBox(height: 2),
                      Text(
                        quote.phone!,
                        style: const TextStyle(color: AppColors.darkMuted, fontSize: 12),
                      ),
                    ],
                  ],
                ),
              ),
              if (quote.createdAt != null)
                Text(
                  CurrencyFormatter.formatDate(quote.createdAt!),
                  style: const TextStyle(color: AppColors.darkMuted, fontSize: 11),
                ),
            ],
          ),

          const SizedBox(height: 12),

          // Project Details
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: AppColors.darkPage,
              borderRadius: BorderRadius.circular(8),
              border: Border.all(color: AppColors.darkBorder.withValues(alpha: 0.5)),
            ),
            child: Text(
              quote.details.isNotEmpty ? quote.details : 'No additional details provided.',
              style: const TextStyle(color: Colors.white, fontSize: 12, height: 1.4),
            ),
          ),

          const SizedBox(height: 14),

          // Action Toolbar: Call, Email, WhatsApp, Delete
          Row(
            children: [
              if (quote.phone != null && quote.phone!.isNotEmpty) ...[
                IconButton.filledTonal(
                  tooltip: 'Call Client',
                  style: IconButton.styleFrom(
                    backgroundColor: AppColors.success.withValues(alpha: 0.15),
                    foregroundColor: AppColors.success,
                  ),
                  icon: const Icon(Icons.phone, size: 16),
                  onPressed: () => LaptechUrlLauncher.launchPhone(quote.phone!, context: context),
                ),
                const SizedBox(width: 8),
                IconButton.filledTonal(
                  tooltip: 'Message on WhatsApp',
                  style: IconButton.styleFrom(
                    backgroundColor: const Color(0xFF25D366).withValues(alpha: 0.15),
                    foregroundColor: const Color(0xFF25D366),
                  ),
                  icon: const Icon(Icons.chat, size: 16),
                  onPressed: () => LaptechUrlLauncher.launchWhatsApp(
                    quote.phone!,
                    message: 'Hello ${quote.name}, regarding your quotation request on Laptech for ${quote.serviceName}:',
                    context: context,
                  ),
                ),
                const SizedBox(width: 8),
              ],
              IconButton.filledTonal(
                tooltip: 'Send Email',
                style: IconButton.styleFrom(
                  backgroundColor: AppColors.primary.withValues(alpha: 0.15),
                  foregroundColor: AppColors.primaryBright,
                ),
                icon: const Icon(Icons.email, size: 16),
                onPressed: () => LaptechUrlLauncher.launchEmail(
                  quote.email,
                  subject: 'Laptech Systems - Quote Inquiry #${quote.id}',
                  body: 'Dear ${quote.name},\n\nThank you for requesting a quote for ${quote.serviceName}.\n\nBest regards,\nLaptech Systems',
                  context: context,
                ),
              ),
              const Spacer(),
              IconButton(
                tooltip: 'Delete',
                icon: const Icon(Icons.delete_outline, size: 18, color: AppColors.danger),
                onPressed: () => _confirmDelete(quote),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
