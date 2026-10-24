import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';

class LaptechUrlLauncher {
  static const String baseDomain = 'laptech.co.ke';
  static const String liveWebsite = 'https://laptech.co.ke';
  static const String adminDashboard = 'https://laptech.co.ke/admin/dashboard';
  static const String adminQuotes = 'https://laptech.co.ke/admin/quotes';
  static const String adminBlog = 'https://laptech.co.ke/admin/blog-posts';
  static const String adminOrders = 'https://laptech.co.ke/admin/orders';
  static const String adminProducts = 'https://laptech.co.ke/admin/products';
  static const String adminServices = 'https://laptech.co.ke/admin/services';
  static const String adminAudit = 'https://laptech.co.ke/admin/audit';
  static const String storeCatalog = 'https://laptech.co.ke/products';
  static const String supportEmail = 'laptechsolutions.ke@gmail.com';

  /// Launch any web URL in the system browser
  static Future<bool> launchWeb(String url, {BuildContext? context}) async {
    try {
      String cleanUrl = url.trim();
      if (!cleanUrl.startsWith('http://') && !cleanUrl.startsWith('https://')) {
        cleanUrl = 'https://$cleanUrl';
      }

      final uri = Uri.parse(cleanUrl);
      final canLaunch = await canLaunchUrl(uri);
      if (canLaunch) {
        return await launchUrl(uri, mode: LaunchMode.externalApplication);
      } else {
        if (context != null && context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Could not open: $cleanUrl')),
          );
        }
        return false;
      }
    } catch (e) {
      debugPrint('Error launching URL: $e');
      if (context != null && context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error launching link: $e')),
        );
      }
      return false;
    }
  }

  /// Launch main website
  static Future<bool> launchLiveWebsite({BuildContext? context}) async {
    return await launchWeb(liveWebsite, context: context);
  }

  /// Launch Web Admin route based on laptech.co.ke
  static Future<bool> launchWebAdmin(String path, {BuildContext? context}) async {
    final cleanPath = path.startsWith('/') ? path : '/$path';
    return await launchWeb('$liveWebsite$cleanPath', context: context);
  }

  /// Launch phone call
  static Future<bool> launchPhone(String phone, {BuildContext? context}) async {
    try {
      final cleanPhone = phone.replaceAll(RegExp(r'[\s\-()]'), '');
      final uri = Uri(scheme: 'tel', path: cleanPhone);
      return await launchUrl(uri);
    } catch (e) {
      debugPrint('Error making call: $e');
      if (context != null && context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Could not dial $phone')),
        );
      }
      return false;
    }
  }

  /// Launch email composer
  static Future<bool> launchEmail(String email, {String? subject, String? body, BuildContext? context}) async {
    try {
      final uri = Uri(
        scheme: 'mailto',
        path: email.trim(),
        queryParameters: {
          if (subject != null) 'subject': subject,
          if (body != null) 'body': body,
        },
      );
      return await launchUrl(uri);
    } catch (e) {
      debugPrint('Error opening email client: $e');
      if (context != null && context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Could not open email app for $email')),
        );
      }
      return false;
    }
  }

  /// Launch WhatsApp chat
  static Future<bool> launchWhatsApp(String phone, {String? message, BuildContext? context}) async {
    try {
      var cleanPhone = phone.replaceAll(RegExp(r'[^0-9+]'), '');
      if (cleanPhone.startsWith('+')) {
        cleanPhone = cleanPhone.substring(1);
      } else if (cleanPhone.startsWith('0')) {
        cleanPhone = '254${cleanPhone.substring(1)}';
      }

      var url = 'https://wa.me/$cleanPhone';
      if (message != null && message.isNotEmpty) {
        url += '?text=${Uri.encodeComponent(message)}';
      }

      return await launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication);
    } catch (e) {
      debugPrint('Error opening WhatsApp: $e');
      if (context != null && context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Could not open WhatsApp for $phone')),
        );
      }
      return false;
    }
  }
}
