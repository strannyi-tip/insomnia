<?php

namespace StrannyiTip\Insomnia\Core;

use CurlHandle;
use StrannyiTip\Insomnia\Exception\BadOptionException;

/**
 * Insomnia.
 */
final class Insomnia
{
    /**
     * Descriptor.
     *
     * @var CurlHandle|false
     */
    private CurlHandle|false $descriptor = false;

    /**
     * Request options.
     *
     * @var array
     */
    private array $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => [],
    ];

    /**
     * Request result.
     *
     * @var string
     */
    private string $result;

    /**
     * Files.
     *
     * @var array
     */
    private array $files = [];

    /**
     * Cookies.
     *
     * @var array
     */
    private array $cookies = [];

    /**
     * Response headers.
     *
     * @var array
     */
    private array $response_headers = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->descriptor = \curl_init();
    }

    /**
     * Connect to address.
     *
     * @param string $host Host
     * @param int $port Port
     *
     * @return Insomnia
     */
    public function connect(string $host, int $port): Insomnia
    {
        $this->options[CURLOPT_URL] = $host . ':' . $port;

        return $this;
    }

    /**
     * GET request.
     *
     * @return bool
     */
    public function get(): bool
    {
        $this->options[CURLOPT_POST] = false;

        $this->send();

        return !$this->isErrorHandled();
    }

    /**
     * POST request.
     *
     * @param iterable $data POST body
     *
     * @return bool
     */
    public function post(iterable $data = []): bool
    {
        $this->options[CURLOPT_POST] = true;
        $this->options[CURLOPT_POSTFIELDS] = $data;
        $this->options[CURLOPT_POSTFIELDS]['files'] = \json_encode($this->files);

        $this->send();

        return !$this->isErrorHandled();
    }

    /**
     * Add HTTP header.
     *
     * @param string $header Key like Content-Type
     *
     * @return $this
     */
    public function addHeader(string $header): Insomnia
    {
        $this->options[CURLOPT_HTTPHEADER][] = $header;

        return $this;
    }

    /**
     * Rewrite cURL option.
     *
     * @param int $option cURL options constant
     * @param string|int $value Value
     *
     * @return Insomnia
     */
    public function rewriteOption(int $option, string|int $value): Insomnia
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Set several headers.
     *
     * @param array $headers HTTP headers array
     *
     * @return $this
     */
    public function setHeaders(array $headers): Insomnia
    {
        $this->options[CURLOPT_HTTPHEADER] = $headers;

        return $this;
    }

    /**
     * Get error code.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return \curl_errno($this->descriptor);
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return \curl_error($this->descriptor);
    }

    /**
     * Get request result array.
     *
     * @return array
     */
    public function asArray(): array
    {
        return $this->decodeResult();
    }

    /**
     * Get request result as Result object.
     *
     * @return Result Request result
     */
    public function asObject(): Result
    {
        return new Result($this->decodeResult());
    }

    /**
     * Get request result as string.
     *
     * @return string
     */
    public function asString(): string
    {
        return $this->parseResponse();
    }

    /**
     * Set request proxy.
     *
     * @param Proxy $proxy Request proxy
     *
     * @return Insomnia
     */
    public function setProxy(Proxy $proxy): Insomnia
    {
        $this->options += $proxy->getOptions();

        return $this;
    }

    /**
     * Add file.
     *
     * @param string $filename Filename
     *
     * @return Insomnia
     */
    public function addFile(string $filename): Insomnia
    {
        $this->files[] = \curl_file_create($filename, \mime_content_type($filename), \basename($filename));

        return $this;
    }

    /**
     * Add cookie.
     *
     * @param string $name Name
     * @param string|int $value Value
     *
     * @return Insomnia
     */
    public function addCookie(string $name, string|int $value): Insomnia
    {
        $this->cookies[$name] = $value;

        return $this;
    }

    /**
     * Get response headers.
     *
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return $this->response_headers;
    }

    /**
     * Send request.
     *
     * @return void
     */
    private function send(): void
    {
        $cookies_file = tempnam('/tmp', md5('cookies'));
        $this->options[CURLOPT_HEADER][] = 'Set-Cookie: ' . $this->buildCookies();
        $this->options[CURLOPT_COOKIE] = $this->buildCookies();
        $this->options[CURLOPT_COOKIEFILE] = $cookies_file;
        $this->options[CURLOPT_COOKIEJAR] = $cookies_file;
        if (\curl_setopt_array($this->descriptor, $this->options)) {
            $this->result = \curl_exec($this->descriptor);
        } else {
            throw new BadOptionException(\curl_error($this->descriptor));
        }

        $this->close();
    }

    /**
     * Close descriptor.
     *
     * @return void
     */
    private function close(): void
    {
        \curl_close($this->descriptor);
    }

    /**
     * Is request has errors.
     *
     * @return bool
     */
    private function isErrorHandled(): bool
    {
        return 0 !== \curl_errno($this->descriptor);
    }

    /**
     * Decode result json.
     *
     * @return array
     */
    private function decodeResult(): array
    {
        return \json_decode($this->parseResponse(), true) ?? [];
    }

    /**
     * Parse response.
     *
     * @return string
     */
    private function parseResponse(): string
    {
        list($headers, $content) = explode("\r\n\r\n", $this->result, 2);
        $this->response_headers = explode("\r\n", $headers);

        return $content ?? '';
    }

    /**
     * Build cookies.
     *
     * @return string
     */
    private function buildCookies(): string
    {
        $result = '';
        foreach ($this->cookies as $name => $value) {
            $result .= $name . '=' . $value . '; ';
        }

        return substr($result, 0, -2);
    }
}