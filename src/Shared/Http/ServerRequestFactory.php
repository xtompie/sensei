<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Laminas\Diactoros\ServerRequestFactory as DiactorosServerRequestFactory;
use Laminas\Diactoros\ServerRequestFilter\FilterServerRequestInterface;
use Laminas\Diactoros\ServerRequestFilter\FilterUsingXForwardedHeaders;
use Laminas\Diactoros\UriFactory;
use Psr\Http\Message\ServerRequestInterface;

use function Laminas\Diactoros\marshalHeadersFromSapi;
use function Laminas\Diactoros\marshalMethodFromSapi;
use function Laminas\Diactoros\marshalProtocolVersionFromSapi;
use function Laminas\Diactoros\normalizeServer;
use function Laminas\Diactoros\normalizeUploadedFiles;
use function Laminas\Diactoros\parseCookieHeader;

class ServerRequestFactory extends DiactorosServerRequestFactory
{
    /**
     * Create a request from the supplied superglobal values.
     *
     * If any argument is not supplied, the corresponding superglobal value will
     * be used.
     *
     * The ServerRequest created is then passed to the fromServer() method in
     * order to marshal the request URI and headers.
     *
     * @see fromServer()
     *
     * @param null|array<string,string> $server $_SERVER superglobal
     * @param null|array<string,string> $query $_GET superglobal
     * @param null|array<string,string|string[]> $body $_POST superglobal (values can be strings or arrays of strings)
     * @param null|array<string,string> $cookies $_COOKIE superglobal
     * @param null|array<string,array{name:string,type:string,tmp_name:string,error:int,size:int}> $files $_FILES superglobal
     *     If present, the
     *     generated request will be passed to this instance and the result
     *     returned by this method. When not present, a default instance of
     *     FilterUsingXForwardedHeaders is created, using the `trustReservedSubnets()`
     *     constructor.
     */
    public static function fromGlobals(
        ?array $server = null,
        ?array $query = null,
        ?array $body = null,
        ?array $cookies = null,
        ?array $files = null,
        ?FilterServerRequestInterface $requestFilter = null
    ): ServerRequestInterface {
        $requestFilter = $requestFilter ?? FilterUsingXForwardedHeaders::trustReservedSubnets();

        $server = normalizeServer(
            $server ?? $_SERVER,
            null
        );
        $files = normalizeUploadedFiles($files ?? $_FILES);
        $headers = marshalHeadersFromSapi($server);

        if (null === $cookies && array_key_exists('cookie', $headers)) {
            $cookies = is_string($headers['cookie']) ? parseCookieHeader($headers['cookie']) : [];
        }

        return $requestFilter(new Request(
            $server,
            $files,
            UriFactory::createFromSapi($server, $headers), // @phpstan-ignore-line
            marshalMethodFromSapi($server),
            'php://input',
            $headers,
            $cookies ?? $_COOKIE,
            $query ?? $_GET,
            $body ?? $_POST,
            marshalProtocolVersionFromSapi($server)
        ));
    }
}
