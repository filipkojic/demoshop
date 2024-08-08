<?php

namespace Infrastructure\HTTP\Response;

/**
 * Class JsonResponse
 *
 * Represents a JSON response with methods to set the status code, headers, and body.
 */
class JsonResponse extends HttpResponse
{
    /**
     * @var array The body of the JSON response.
     */
    protected array $body;

    /**
     * JsonResponse constructor.
     * Initializes the response with a default status code and headers, and sets the body as an array.
     *
     * @param int $statusCode HTTP status code (e.g., 200, 404).
     * @param array $headers Headers to be sent with the response.
     * @param array $body Body of the response.
     */
    public function __construct(int $statusCode = 200, array $headers = [], array $body = [])
    {
        parent::__construct($statusCode, $headers);
        $this->body = $body;
        $this->addHeader('Content-Type', 'application/json');
    }

    /**
     * Send the JSON response to the client.
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo json_encode($this->body);
    }
}
