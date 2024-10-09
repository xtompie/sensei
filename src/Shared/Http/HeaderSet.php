<?php

namespace App\Shared\Http;

class HeaderSet
{
    /**
     * Generates cache-control headers for a response.
     *
     * @param int $maxAge The maximum age in seconds for caching the response
     * @return array<string, string> Headers to control caching of the response
     */
    public static function cacheControl(int $maxAge): array
    {
        return [
            'Cache-Control' => 'public, max-age=' . $maxAge,
            'Expires' => gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT',
        ];
    }

    /**
     * Generates headers for the Content-Length based on file size.
     *
     * @param string $path The file path
     * @return array<string, string> Headers for content length based on the file size
     */
    public static function contentLengthByFilePath(string $path): array
    {
        $size = filesize($path);
        return [
            'Content-Length' => (string) $size,
        ];
    }

    /**
     * Generates Content-Disposition headers for attachment.
     *
     * @param string|null $filename The optional name of the file
     * @return array<string, string> Headers for attachment content disposition
     */
    public static function contentDispositionAttachment(?string $filename = null): array
    {
        return [
            'Content-Disposition' => 'attachment' . ($filename ? '; filename="' . rawurlencode($filename) . '"' : ''),
        ];
    }

    /**
     * Generates Content-Disposition headers for inline display.
     *
     * @param string|null $filename The optional name of the file
     * @return array<string, string> Headers for inline content disposition
     */
    public static function contentDispositionInline(?string $filename = null): array
    {
        return [
            'Content-Disposition' => 'inline' . ($filename ? '; filename="' . rawurlencode($filename) . '"' : ''),
        ];
    }

    /**
     * Generates Content-Type headers for application/octet-stream.
     *
     * @return array<string, string> Headers for application/octet-stream content type
     */
    public static function contentTypeApplicationOctetStream(): array
    {
        return [
            'Content-Type' => 'application/octet-stream',
        ];
    }

    /**
     * Generates Content-Type headers based on the file path's extension.
     *
     * @param string $path The file path
     * @return array<string, string> Headers for content type based on the file path
     */
    public static function contentTypeByFilePath(string $path): array
    {
        $mimeType = mime_content_type($path);
        if ($mimeType === false) {
            $mimeType = 'application/octet-stream';
        }
        return [
            'Content-Type' => $mimeType,
        ];
    }

    /**
     * Generates headers for a JSON response.
     *
     * @return array<string, string> Headers to be used for a JSON response
     */
    public static function json(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Generates headers for a no-content (HTTP 204) response.
     *
     * @return array<string, string> Headers to be used for a no-content response
     */
    public static function noContent(): array
    {
        return [
            'Content-Length' => '0',
        ];
    }

    /**
     * Generates headers for disabling cache in the response.
     *
     * @return array<string, string> Headers to prevent caching of the response
     */
    public static function noCache(): array
    {
        return [
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];
    }
}
