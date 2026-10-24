import 'package:intl/intl.dart';

class CurrencyFormatter {
  static final NumberFormat _formatter = NumberFormat('#,##0.00', 'en_US');
  static final NumberFormat _compactFormatter = NumberFormat.compact(locale: 'en_US');

  static String format(num amount, {String currency = 'KES'}) {
    return '$currency ${_formatter.format(amount)}';
  }

  static String formatCompact(num amount, {String currency = 'KES'}) {
    return '$currency ${_compactFormatter.format(amount)}';
  }

  static String formatDate(String? isoString) {
    if (isoString == null || isoString.isEmpty) return '—';
    try {
      final dt = DateTime.parse(isoString).toLocal();
      return DateFormat('MMM dd, yyyy • HH:mm').format(dt);
    } catch (_) {
      return isoString;
    }
  }

  static String formatDateShort(String? isoString) {
    if (isoString == null || isoString.isEmpty) return '—';
    try {
      final dt = DateTime.parse(isoString).toLocal();
      return DateFormat('MMM dd, yyyy').format(dt);
    } catch (_) {
      return isoString;
    }
  }
}
