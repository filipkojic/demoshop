<?php

namespace Infrastructure\HTTP;

use Exception;
use Infrastructure\Utility\GlobalWrapper;

/**
 * Class HttpRequest
 *
 * Represents an HTTP request with methods to access query parameters, body parameters, headers, method, and URI.
 */
class HttpRequest
{
    /**
     * HttpRequest constructor.
     * Initializes the request parameters from the global PHP variables.
     */
    public function __construct(
        private array  $queryParams = [],
        public array   $bodyParams = [],
        private array  $headers = [],
        private string $method = '',
        private string $uri = ''
    )
    {
        try {
            $this->queryParams = GlobalWrapper::getGlobal('_GET');
            $this->bodyParams = GlobalWrapper::getGlobal('_POST');
            $this->headers = GlobalWrapper::getAllHeaders();
            $this->method = GlobalWrapper::getGlobal('_SERVER')['REQUEST_METHOD'];
            $this->uri = GlobalWrapper::getGlobal('_SERVER')['REQUEST_URI'];
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->queryParams = [];
            $this->bodyParams = [];
            $this->headers = [];
            $this->method = '';
            $this->uri = '';
        }
    }

    /**
     * Get a query parameter from the URL.
     *
     * @param string $key The key of the query parameter.
     * @param string|null $default The default value to return if the key is not found.
     * @return string|null The value of the query parameter or the default value.
     */
    public function getQueryParam(string $key, ?string $default = null): ?string
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Get a body parameter from the POST request.
     *
     * @param string $key The key of the body parameter.
     * @param string|null $default The default value to return if the key is not found.
     * @return string|null The value of the body parameter or the default value.
     */
    public function getBodyParam(string $key, ?string $default = null): ?string
    {
        return $this->bodyParams[$key] ?? $default;
    }

    /**
     * Get a header from the HTTP request.
     *
     * @param string $key The key of the header.
     * @param string|null $default The default value to return if the key is not found.
     * @return string|null The value of the header or the default value.
     */
    public function getHeader(string $key, ?string $default = null): ?string
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get the HTTP method of the request.
     *
     * @return string The HTTP method (e.g., GET, POST).
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the URI of the HTTP request.
     *
     * @return string The URI of the request.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get the ID from the URI.
     *
     * @return int|null The ID from the URI or null if not found.
     */
    public function getId(): ?int
    {
        $urlParts = explode('/', trim($this->getUri(), '/'));

        return isset($urlParts[1]) ? (int)$urlParts[1] : null;
    }

    /**
     * Get the path from the URI.
     *
     * @return string The path from the URI.
     */
    public function getPath(): string
    {
        $urlParts = explode('/', trim($this->uri, '/'));

        return $urlParts[0] ?? '';
    }

    /**
     * Get the raw JSON body and decode it.
     *
     * @return array|null The decoded JSON body or null if decoding fails.
     */
    public function getJsonBody(): ?array
    {
        $jsonData = file_get_contents('php://input');
        return json_decode($jsonData, true);
    }
}
