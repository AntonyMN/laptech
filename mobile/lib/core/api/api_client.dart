import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'api_endpoints.dart';

class ApiClient {
  static final ApiClient instance = ApiClient._internal();
  ApiClient._internal();

  late Dio _dio;
  String? _token;
  String _baseUrl = ApiEndpoints.defaultBaseUrl;

  Dio get dio => _dio;
  String get baseUrl => _baseUrl;
  String? get token => _token;

  Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');

    final savedBaseUrl = prefs.getString('custom_base_url');
    if (savedBaseUrl != null && savedBaseUrl.isNotEmpty) {
      _baseUrl = savedBaseUrl;
    } else {
      _baseUrl = ApiEndpoints.defaultBaseUrl;
    }

    _dio = Dio(
      BaseOptions(
        baseUrl: _baseUrl,
        connectTimeout: const Duration(seconds: 15),
        receiveTimeout: const Duration(seconds: 15),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      ),
    );

    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) {
          String base = _baseUrl.trim();
          if (base.isNotEmpty && !base.startsWith('http://') && !base.startsWith('https://')) {
            base = 'https://$base';
          }
          while (base.endsWith('/')) {
            base = base.substring(0, base.length - 1);
          }

          String path = options.path;
          if (!path.startsWith('http://') && !path.startsWith('https://')) {
            if (!path.startsWith('/')) {
              path = '/$path';
            }

            // If base ends with /api, strip redundant /api if path starts with /api
            if (base.endsWith('/api') && path.startsWith('/api/')) {
              path = path.substring(4);
            } else if (!base.endsWith('/api') && !path.startsWith('/api/')) {
              path = '/api$path';
            }

            options.baseUrl = '';
            options.path = '$base$path';
          }

          if (_token != null && _token!.isNotEmpty) {
            options.headers['Authorization'] = 'Bearer $_token';
          }
          return handler.next(options);
        },
        onError: (DioException error, handler) {
          debugPrint('API Error [${error.response?.statusCode}]: ${error.requestOptions.path}');
          return handler.next(error);
        },
      ),
    );
  }

  Future<void> setBaseUrl(String url) async {
    String cleanUrl = url.trim();
    if (cleanUrl.isNotEmpty && !cleanUrl.startsWith('http://') && !cleanUrl.startsWith('https://')) {
      cleanUrl = 'https://$cleanUrl';
    }
    while (cleanUrl.endsWith('/')) {
      cleanUrl = cleanUrl.substring(0, cleanUrl.length - 1);
    }
    _baseUrl = cleanUrl;
    _dio.options.baseUrl = _baseUrl;

    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('custom_base_url', _baseUrl);
  }

  Future<void> setToken(String? token) async {
    _token = token;
    final prefs = await SharedPreferences.getInstance();
    if (token != null) {
      await prefs.setString('auth_token', token);
    } else {
      await prefs.remove('auth_token');
    }
  }

  Future<Response> get(String path, {Map<String, dynamic>? queryParameters}) async {
    return await _dio.get(path, queryParameters: queryParameters);
  }

  Future<Response> post(String path, {dynamic data, Map<String, dynamic>? queryParameters}) async {
    return await _dio.post(path, data: data, queryParameters: queryParameters);
  }

  Future<Response> put(String path, {dynamic data, Map<String, dynamic>? queryParameters}) async {
    return await _dio.put(path, data: data, queryParameters: queryParameters);
  }

  Future<Response> patch(String path, {dynamic data, Map<String, dynamic>? queryParameters}) async {
    return await _dio.patch(path, data: data, queryParameters: queryParameters);
  }

  Future<Response> delete(String path, {dynamic data, Map<String, dynamic>? queryParameters}) async {
    return await _dio.delete(path, data: data, queryParameters: queryParameters);
  }
}
