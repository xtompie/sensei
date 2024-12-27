<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Container\Container;
use App\Shared\Tpl\Tpl;
use Laminas\Diactoros\CallbackStream;
use Laminas\Diactoros\Response as DiactorosResponse;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Xtompie\Result\Error;
use Xtompie\Result\ErrorCollection;

final class Response extends DiactorosResponse
{
    /**
     * Create a new response with a given body, status, and headers.
     *
     * @param string $body The response body content
     * @param int $status The HTTP status code
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * Response::new('Response Body', 200, ['Content-Type' => 'text/plain']);
     */
    public static function new(string $body = '', int $status = 200, array $headers = []): Response
    {
        $resource = fopen('php://temp', 'r+');
        if ($resource === false) {
            throw new \RuntimeException('Unable to open temporary stream');
        }
        fwrite($resource, $body);
        rewind($resource);

        $stream = new Stream($resource);

        return new Response(body: $stream, status: $status, headers: $headers);
    }

    /**
     * Create an OK response (HTTP 200).
     *
     * @param string $body The response body content
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * Response::ok('OK');
     */
    public static function ok(string $body = '', array $headers = []): Response
    {
        return static::new(body: $body, status: 200, headers: $headers);
    }

    /**
     * Create an HTML response.
     *
     * @param string $body The HTML content to be sent
     * @param int $status The HTTP status code
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * Response::html('<h1>Hello World</h1>', 200);
     */
    public static function html(string $body, int $status = 200, array $headers = []): Response
    {
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'text/html; charset=UTF-8';
        }

        return Response::new(body: $body, status: $status, headers: $headers);
    }

    /**
     * Create an HTML response by rendering a template.
     *
     * This method uses the Tpl service to render an HTML template with the provided data,
     * and returns an HTTP response with a default `Content-Type` of `text/html`.
     *
     * @param string $template the template to render
     * @param array<string,mixed> $data optional data to pass to the template
     * @param int $status the HTTP status code (default: 200)
     * @param array<string,string|array<string>> $headers optional headers to include in the response
     * @return Response the HTML response
     *
     * @example
     * Response::tpl('template.tpl.php', ['name' => 'John'], 200, ['X-Custom-Header' => 'value']);
     */
    public static function tpl(string $template, array $data = [], int $status = 200, array $headers = []): Response
    {
        $html = Container::container()->get(Tpl::class)->__invoke($template, $data);
        return static::html($html, $status, $headers);
    }

    /**
     * Create a JSON response.
     *
     * @param array<mixed> $body The data to be encoded as JSON
     * @param int $status The HTTP status code
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * Response::json(['name' => 'John Doe'], 200);
     */
    public static function json(array $body, int $status = 200, array $headers = []): Response
    {
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }

        $body = json_encode($body);
        if ($body === false) {
            throw new RuntimeException('Failed to encode data as JSON');
        }
        return Response::new(body: $body, status: $status, headers: $headers);
    }

    /**
     * Create a created response (HTTP 201).
     *
     * @param string|null $location The location header (optional)
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * Response::created('/resources/1234');
     */
    public static function created(?string $location = null, array $headers = []): Response
    {
        if ($location !== null) {
            $headers['Location'] = $location;
        }

        return new Response(status: 201, headers: $headers);
    }

    /**
     * Create an accepted response (HTTP 202).
     *
     * @param string|null $location The location header (optional)
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * Response::accepted('/statuses/1234');
     */
    public static function accepted(?string $location = null, array $headers = []): Response
    {
        if ($location !== null) {
            $headers['Location'] = $location;
        }

        return new Response(status: 202, headers: $headers);
    }

    /**
     * Create a no content response (HTTP 204).
     *
     * @param int $status The HTTP status code (default: 204)
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * Response::noContent(204);
     */
    public static function noContent(int $status = 204, array $headers = []): Response
    {
        return new Response(status: $status, headers: $headers);
    }

    /**
     * Create a redirect response.
     *
     * @param string $url The redirect URL
     * @param int $status The HTTP status code (default: 302)
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * Response::redirect('https://example.com', 302);
     */
    public static function redirect(string $url, int $status = 302, array $headers = []): Response
    {
        $headers['Location'] = $url;
        return new Response(status: $status, headers: $headers);
    }

    /**
     * Create a redirect response to a given controller with parameters.
     *
     * This method generates a URL based on the specified controller and parameters,
     * then returns a redirect response to that URL.
     *
     * @param class-string $controller the controller to redirect to
     * @param array<string,mixed> $parameters optional parameters for the URL generation
     * @param int $status the HTTP status code for the redirect (default: 302)
     * @param array<string,string|array<string>> $headers optional headers to include in the response
     * @return Response the redirect response
     *
     * @example
     * Response::redirectToController($controllerInstance, ['id' => 123], 301, ['X-Custom-Header' => 'value']);
     */
    public static function redirectToController(string $controller, array $parameters = [], int $status = 302, array $headers = []): Response
    {
        $url = Container::container()->get(Url::class)->__invoke($controller, $parameters);
        return static::redirect($url, $status, $headers);
    }

    /**
     * Create a response that refreshes the current page.
     *
     * @return Response
     *
     * @example
     * Response::refresh();
     */
    public static function refresh(): Response
    {
        $uri = (string) Container::container()->get(Request::class)->getUri();
        return static::redirect($uri);
    }

    public static function errors(?ErrorCollection $errors = null, int $status = 400, ?string $msg = null): Response
    {
        if ($errors !== null) {
            return Response::json(
                body: [
                    'errors' => $errors->mapToArray(
                        fn (Error $e) => [
                            'message' => $e->message(),
                            'key' => $e->key(),
                            'space' => $e->space(),
                        ]
                    ),
                ],
                status: $status,
            );
        }
        return Response::new(body: $msg ?: 'Error ' . $status, status: $status);
    }

    public static function badRequest(?ErrorCollection $errors = null, int $status = 400): Response
    {
        return static::errors(errors: $errors, status: $status, msg: '400 Bad Request');
    }

    public static function unauthorized(): Response
    {
        return Response::new(body: '401 Unauthorized', status: 401);
    }

    public static function forbidden(): Response
    {
        return Response::new(body: '403 Forbidden', status: 403);
    }

    public static function notFound(): Response
    {
        return Container::container()->get(NotFound::class)->__invoke();
    }

    public static function methodNotAllowed(): Response
    {
        return static::new(body: '405 Method Not Allowed', status: 405);
    }

    public static function conflict(?ErrorCollection $errors = null, int $status = 409): Response
    {
        return static::errors(errors: $errors, status: $status, msg: '409 Conflict');
    }

    public static function internalServerError(): Response
    {
        return Container::container()->get(InternalServerError::class)->__invoke();
    }

    /**
     * Create a streamed response from a given stream.
     *
     * @param StreamInterface $stream The stream to be sent
     * @param int $status The HTTP status code
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * $stream = new \Laminas\Diactoros\Stream(fopen('path/to/file.txt', 'rb'));
     * Response::stream($stream, 200, ['Content-Type' => 'application/octet-stream']);
     */
    public static function stream(StreamInterface $stream, int $status = 200, array $headers = []): Response
    {
        return new Response(body: $stream, status: $status, headers: $headers);
    }

    /**
     * Create a response from a callback that generates content.
     *
     * @param callable(): void $callback The callback function to generate content
     * @param int $status The HTTP status code
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * $callback = function () {
     *     echo "Line 1\n";
     *     echo "Line 2\n";
     * };
     * Response::callback($callback, 200, ['Content-Type' => 'text/plain']);
     */
    public static function callback(callable $callback, int $status = 200, array $headers = []): Response
    {
        $stream = new CallbackStream($callback);
        return new Response(body: $stream, status: $status, headers: $headers);
    }

    /**
     * Create a response using a handler (resource) for writing content.
     *
     * @param callable(resource): void $callback The callback that receives a handler (resource) and writes to it
     * @param int $status The HTTP status code
     * @param array<string,string|array<string>> $headers The response headers
     * @return Response
     *
     * @example
     * $callback = function ($handler) {
     *     fwrite($handler, "Line 1\n");
     *     fwrite($handler, "Line 2\n");
     * };
     * Response::handler($callback, 200, ['Content-Type' => 'text/plain']);
     */
    public static function handler(callable $callback, int $status = 200, array $headers = []): Response
    {
        $callbackWrapper = function () use ($callback) {
            $handler = fopen('php://output', 'w');
            if ($handler === false) {
                throw new RuntimeException('Unable to open output stream');
            }
            $callback($handler);
            fclose($handler);
        };

        $stream = new CallbackStream($callbackWrapper);
        return new Response(body: $stream, status: $status, headers: $headers);
    }

    /**
     * Returns a response for serving a file from the file system.
     *
     * @param string $path The absolute path to the file on the file system
     * @param string $filename The name of the file to be used in the Content-Disposition header
     * @param array<string,string|array<string>> $headers Optional additional headers to include in the response
     * @return Response The response serving the file
     *
     * @example
     * Response::file('path/to/file.pdf', 'downloaded-file.pdf', ['Content-Type' => 'application/pdf']);
     */
    public static function file(string $path, ?string $filename = null, array $headers = [], bool $attachment = true): Response
    {
        $resource = fopen($path, 'rb');
        if ($resource === false) {
            throw new RuntimeException('Unable to open file: ' . $path);
        }
        $stream = new Stream($resource);

        if ($filename === null) {
            $filename = basename($path);
        }

        $headers = [
            ...HeaderSet::contentLengthByFilePath($path),
            ...HeaderSet::contentTypeByFilePath($path),
            ...($attachment ? HeaderSet::contentDispositionAttachment($filename) : []),
            ...(!$attachment ? HeaderSet::contentDispositionInline($filename) : []),
        ];

        return new Response($stream, 200, $headers);
    }
}
