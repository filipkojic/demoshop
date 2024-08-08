<?php

namespace Infrastructure\HTTP\Response;

/**
 * Class HtmlResponse
 *
 * Represents an HTML response with methods to set the status code, headers, and body.
 */
class HtmlResponse extends HttpResponse
{
    /**
     * @var string The body content of the response.
     */
    protected string $body;

    /**
     * HtmlResponse constructor.
     * Initializes the response with a status code, headers, and body content.
     *
     * @param int $statusCode HTTP status code (e.g., 200, 404).
     * @param array $headers Headers to be sent with the response.
     * @param string $body The body content of the response.
     */
    public function __construct(int $statusCode = 200, array $headers = [], string $body = '')
    {
        parent::__construct($statusCode, $headers);
        $this->body = $body;
    }

    /**
     * Static method to create an HtmlResponse from a view file.
     *
     * @param string $viewFile The path to the view file.
     * @param array $data Data to be extracted and passed to the view.
     * @param int $statusCode HTTP status code (e.g., 200, 404).
     * @param array $headers Headers to be sent with the response.
     * @return HtmlResponse
     */
    public static function fromView(string $viewFile, array $data = [], int $statusCode = 200, array $headers = []): HtmlResponse
    {
        // Extract data to variables
        extract($data);

        // Capture the output of the view file
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        return new self($statusCode, $headers, $content);
    }

    /**
     * Send the HTML response to the client.
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->body;
    }
}
