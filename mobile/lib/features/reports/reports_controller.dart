import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/models/report_model.dart';
import 'reports_repository.dart';

class ReportsState {
  final bool isLoading;
  final ReportSummaryModel? report;
  final String? errorMessage;

  ReportsState({
    this.isLoading = false,
    this.report,
    this.errorMessage,
  });

  ReportsState copyWith({
    bool? isLoading,
    ReportSummaryModel? report,
    String? errorMessage,
  }) {
    return ReportsState(
      isLoading: isLoading ?? this.isLoading,
      report: report ?? this.report,
      errorMessage: errorMessage,
    );
  }
}

class ReportsController extends StateNotifier<ReportsState> {
  final ReportsRepository _repository;

  ReportsController(this._repository) : super(ReportsState()) {
    loadReport();
  }

  Future<void> loadReport() async {
    state = state.copyWith(isLoading: true, errorMessage: null);
    try {
      final report = await _repository.getSummaryReport();
      state = state.copyWith(isLoading: false, report: report);
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        errorMessage: 'Failed to generate report: $e',
      );
    }
  }
}

final reportsRepositoryProvider = Provider<ReportsRepository>((ref) {
  return ReportsRepository();
});

final reportsControllerProvider = StateNotifierProvider<ReportsController, ReportsState>((ref) {
  return ReportsController(ref.watch(reportsRepositoryProvider));
});
