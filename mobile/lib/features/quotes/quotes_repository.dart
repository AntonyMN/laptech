import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/models/quote_model.dart';

class QuotesRepository {
  final ApiClient _apiClient = ApiClient.instance;

  Future<Map<String, dynamic>> getQuotes({String? status, String? search}) async {
    final query = <String, dynamic>{};
    if (status != null && status.isNotEmpty && status != 'all') {
      query['status'] = status;
    }
    if (search != null && search.isNotEmpty) {
      query['search'] = search;
    }

    final response = await _apiClient.get(
      ApiEndpoints.quotes,
      queryParameters: query,
    );

    final data = response.data as Map<String, dynamic>;
    final quotesRaw = data['quotes'] as List? ?? [];
    final quotes = quotesRaw.map((e) => QuoteModel.fromJson(e as Map<String, dynamic>)).toList();
    final stats = data['stats'] as Map<String, dynamic>? ?? {};

    return {
      'quotes': quotes,
      'stats': stats,
    };
  }

  Future<QuoteModel> updateQuoteStatus(int id, String status) async {
    final response = await _apiClient.patch(
      '${ApiEndpoints.quotes}/$id/status',
      data: {'status': status},
    );

    final data = response.data as Map<String, dynamic>;
    return QuoteModel.fromJson(data['quote'] as Map<String, dynamic>);
  }

  Future<void> deleteQuote(int id) async {
    await _apiClient.delete('${ApiEndpoints.quotes}/$id');
  }
}
