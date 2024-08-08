<?php

namespace Infrastructure\HTTP\Response;

/**
 * Class HttpResponse
 *
 * Represents an abstract HTTP response with methods to set the status code and headers.
 */
abstract class HttpResponse
{
    /**
     * HttpResponse constructor.
     * Initializes the response with a default status code and empty headers.
     *
     * @param int $statusCode HTTP status code (e.g., 200, 404).
     * @param array $headers Headers to be sent with the response.
     */
    public function __construct(
        protected int   $statusCode = 200,
        protected array $headers = []
    )
    {
    }

    /**
     * Add a header to the response.
     *
     * @param string $key The header name.
     * @param string $value The header value.
     */
    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * Send the HTTP response to the client.
     */
    abstract public function send(): void;
}
