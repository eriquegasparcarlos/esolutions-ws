<?php

namespace Esolutions\Ws;

use Illuminate\Support\Facades\Http;
use Throwable;

class Service
{
    public static function sendPdf(string $base64Pdf, string $number, string $message = '', string $filename = 'document.pdf'): array
    {
        try {
            return self::http(30)
                ->post(self::url('/message/send/pdf'), [
                    'file'     => $base64Pdf,
                    'number'   => $number,
                    'message'  => $message,
                    'filename' => $filename,
                ])->json();
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function sendText(string $sessionId, string $to, string $text): array
    {
        try {
            return self::http()
                ->post(self::url('/messages/send/text'), [
                    'sessionId' => $sessionId,
                    'to'        => $to,
                    'text'      => $text,
                ])->json();
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function getSessions(): array
    {
        try {
            return self::http()->get(self::url('/sessions'))->json();
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function getSessionStatus(string $sessionId): array
    {
        try {
            return self::http()->get(self::url('/sessions/' . $sessionId . '/status'))->json();
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function getMessageStatus(string $messageId): array
    {
        try {
            return self::http()->get(self::url('/messages/' . $messageId . '/status'))->json();
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private static function http(int $timeout = 15): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withOptions(['verify' => false])
            ->withHeaders([
                'x-api-key'     => config('esolutions.ws.token'),
                'x-app-version' => config('version.version', ''),
                'x-app-build'   => config('version.build', ''),
            ])
            ->connectTimeout(5)
            ->timeout($timeout);
    }

    private static function url(string $path): string
    {
        return rtrim(config('esolutions.ws.url', ''), '/') . $path;
    }
}
