import 'package:dio/dio.dart';
import '../../core/api/api_client.dart';
import '../../core/api/api_endpoints.dart';
import '../../core/models/user_model.dart';

class AuthRepository {
  final ApiClient _api = ApiClient.instance;

  Future<UserModel> login({
    required String email,
    required String password,
  }) async {
    try {
      final response = await _api.post(
        ApiEndpoints.login,
        data: {
          'email': email.trim(),
          'password': password,
          'device_name': 'Laptech Flutter Admin App',
        },
      );

      final token = response.data['token'] as String?;
      await _api.setToken(token);

      return UserModel.fromJson(response.data['user']);
    } on DioException catch (e) {
      if (e.response?.statusCode == 403) {
        throw Exception(
          e.response?.data['message'] ??
              'Access denied. This application is restricted to administrator accounts only.',
        );
      } else if (e.response?.statusCode == 422) {
        throw Exception(
          e.response?.data['message'] ?? 'Invalid credentials. Please verify your email and password.',
        );
      }
      throw Exception(e.response?.data['message'] ?? 'Failed to connect to server (${e.message})');
    }
  }

  Future<UserModel?> getProfile() async {
    if (_api.token == null) return null;
    try {
      final response = await _api.get(ApiEndpoints.user);
      return UserModel.fromJson(response.data['user']);
    } catch (_) {
      return null;
    }
  }

  Future<UserModel> updateProfile({
    required String name,
    required String email,
    String? password,
  }) async {
    final payload = <String, dynamic>{
      'name': name.trim(),
      'email': email.trim(),
    };
    if (password != null && password.isNotEmpty) {
      payload['password'] = password;
    }

    final response = await _api.put(ApiEndpoints.profile, data: payload);
    return UserModel.fromJson(response.data['user']);
  }

  Future<void> logout() async {
    try {
      if (_api.token != null) {
        await _api.post(ApiEndpoints.logout);
      }
    } catch (_) {
      // Continue clearing local token even if network failed
    } finally {
      await _api.setToken(null);
    }
  }
}
