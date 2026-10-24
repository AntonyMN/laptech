import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/models/report_model.dart';

class ReportsRepository {
  final ApiClient _apiClient = ApiClient.instance;

  Future<ReportSummaryModel> getSummaryReport() async {
    final response = await _apiClient.get(ApiEndpoints.reportSummary);
    final data = response.data as Map<String, dynamic>;
    return ReportSummaryModel.fromJson(data);
  }
}
