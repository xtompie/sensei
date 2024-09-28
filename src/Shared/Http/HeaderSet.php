<?php

namespace App\Shared\Http;

class HeaderSet
{
    /**
     * Generates headers for displaying content inline in the browser.
     *
     * @param string $filename The name of the file (e.g., for Content-Disposition)
     * @param string $contentType The MIME type of the content (e.g., 'application/pdf', 'image/png')
     * @return array<string, string> Headers to be used for an inline response
     */
    public function inline(string $filename, string $contentType): array
    {
        return [
            'Content-Disposition' => 'inline; filename="' . rawurlencode($filename) . '"',
            'Content-Type' => $contentType,
        ];
    }

    /**
     * Generates headers for forcing content download.
     *
     * @param string $filename The name of the file (e.g., for Content-Disposition)
     * @return array<string, string> Headers to be used for a download response
     */
    public function download(string $filename): array
    {
        return [
            'Content-Disposition' => 'attachment; filename="' . rawurlencode($filename) . '"',
            'Content-Type' => 'application/octet-stream',
        ];
    }

    /**
     * Generates headers for a JSON response.
     *
     * @return array<string, string> Headers to be used for a JSON response
     */
    public function json(): array
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
    public function noContent(): array
    {
        return [
            'Content-Length' => '0',
        ];
    }

    /**
     * Generates headers for an attachment without specifying a filename.
     *
     * @return array<string, string> Headers to be used for an attachment response without a filename
     */
    public function attachmentWithoutFilename(): array
    {
        return [
            'Content-Disposition' => 'attachment',
            'Content-Type' => 'application/octet-stream',
        ];
    }

    /**
     * Generates cache-control headers for a response.
     *
     * @param int $maxAge The maximum age in seconds for caching the response
     * @return array<string, string> Headers to control caching of the response
     */
    public function cacheControl(int $maxAge): array
    {
        return [
            'Cache-Control' => 'public, max-age=' . $maxAge,
            'Expires' => gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT',
        ];
    }

    /**
     * Generates headers for disabling cache in the response.
     *
     * @return array<string, string> Headers to prevent caching of the response
     */
    public function noCache(): array
    {
        return [
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];
    }
}
