# Changelog

## [v1.0.0] - 2026-06-11
### Fixed
- Removed hardcoded `"version"` field from `composer.json` (caused Packagist to skip tags)
- Published to Packagist — `repositories` block no longer needed in consumer projects

### Added
- Initial release
- `Service::sendPdf($base64, $number, $message, $filename)` — send PDF as WhatsApp attachment
- `Service::sendText($sessionId, $to, $text)` — send plain text message
- `Service::getSessions()` — list all available sessions
- `Service::getSessionStatus($sessionId)` — get session status
- `Service::getMessageStatus($messageId)` — get message delivery status
- Phone number format: country code without `+` (e.g. `51987654321`)
