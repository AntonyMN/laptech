import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/quote_model.dart';
import 'quotes_repository.dart';

class QuotesState {
  final bool isLoading;
  final List<QuoteModel> quotes;
  final Map<String, dynamic> stats;
  final String filterStatus;
  final String searchQuery;
  final String? errorMessage;

  QuotesState({
    this.isLoading = false,
    this.quotes = const [],
    this.stats = const {},
    this.filterStatus = 'all',
    this.searchQuery = '',
    this.errorMessage,
  });

  QuotesState copyWith({
    bool? isLoading,
    List<QuoteModel>? quotes,
    Map<String, dynamic>? stats,
    String? filterStatus,
    String? searchQuery,
    String? errorMessage,
  }) {
    return QuotesState(
      isLoading: isLoading ?? this.isLoading,
      quotes: quotes ?? this.quotes,
      stats: stats ?? this.stats,
      filterStatus: filterStatus ?? this.filterStatus,
      searchQuery: searchQuery ?? this.searchQuery,
      errorMessage: errorMessage,
    );
  }

  int get pendingCount => (stats['pending'] as num?)?.toInt() ?? 0;
  int get totalCount => (stats['total'] as num?)?.toInt() ?? quotes.length;
}

class QuotesController extends StateNotifier<QuotesState> {
  final QuotesRepository _repository;

  QuotesController(this._repository) : super(QuotesState()) {
    loadQuotes();
  }

  Future<void> loadQuotes() async {
    state = state.copyWith(isLoading: true, errorMessage: null);
    try {
      final res = await _repository.getQuotes(
        status: state.filterStatus,
        search: state.searchQuery,
      );

      state = state.copyWith(
        isLoading: false,
        quotes: res['quotes'] as List<QuoteModel>,
        stats: res['stats'] as Map<String, dynamic>,
      );
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        errorMessage: 'Failed to load quotes: $e',
      );
    }
  }

  void setFilter(String status) {
    if (state.filterStatus == status) return;
    state = state.copyWith(filterStatus: status);
    loadQuotes();
  }

  void search(String query) {
    state = state.copyWith(searchQuery: query);
    loadQuotes();
  }

  Future<bool> updateQuoteStatus(int id, String newStatus) async {
    try {
      final updated = await _repository.updateQuoteStatus(id, newStatus);
      final updatedList = state.quotes.map((q) => q.id == id ? updated : q).toList();
      
      // Update local stat counts
      final updatedStats = Map<String, dynamic>.from(state.stats);
      if (newStatus == 'closed' && updatedStats.containsKey('closed')) {
        updatedStats['closed'] = (updatedStats['closed'] as int? ?? 0) + 1;
        if (updatedStats.containsKey('pending') && (updatedStats['pending'] as int? ?? 0) > 0) {
          updatedStats['pending'] = (updatedStats['pending'] as int) - 1;
        }
      }

      state = state.copyWith(quotes: updatedList, stats: updatedStats);
      return true;
    } catch (e) {
      state = state.copyWith(errorMessage: 'Failed to update quote status: $e');
      return false;
    }
  }

  Future<bool> deleteQuote(int id) async {
    try {
      await _repository.deleteQuote(id);
      final updatedList = state.quotes.where((q) => q.id != id).toList();
      state = state.copyWith(quotes: updatedList);
      return true;
    } catch (e) {
      state = state.copyWith(errorMessage: 'Failed to delete quote: $e');
      return false;
    }
  }
}

final quotesRepositoryProvider = Provider<QuotesRepository>((ref) {
  return QuotesRepository();
});

final quotesControllerProvider = StateNotifierProvider<QuotesController, QuotesState>((ref) {
  return QuotesController(ref.watch(quotesRepositoryProvider));
});
