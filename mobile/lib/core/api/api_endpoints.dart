class ApiEndpoints {
  // Production Base URL: https://laptech.co.ke/api
  // Local Development: http://127.0.0.1:8000/api (Desktop) / http://10.0.2.2:8000/api (Emulator)
  static const String defaultBaseUrl = 'https://laptech.co.ke/api';
  static const String androidEmulatorBaseUrl = 'http://10.0.2.2:8000/api';

  static const String login = '/v1/login';
  static const String user = '/v1/user';
  static const String profile = '/v1/profile';
  static const String logout = '/v1/logout';

  static const String dashboard = '/v1/dashboard';

  static const String products = '/v1/products';
  static const String categories = '/v1/categories';

  static const String orders = '/v1/orders';
  static const String orderStats = '/v1/orders/stats';

  static const String services = '/v1/services';
  static const String serviceCategories = '/v1/service-categories';

  static const String settings = '/v1/settings';
  static const String clearCache = '/v1/cache/clear';
}
