<?php

namespace StrannyiTip\Insomnia\Core;

/**
 * HTTP proxy.
 */
class Proxy
{
    /**
     * HTTP type.
     *
     * @const int
     */
    public const int HTTP = CURLPROXY_HTTP;

    /**
     * SOCKS4 type.
     *
     * @const int
     */
    public const int SOCKS4 = CURLPROXY_SOCKS4;

    /**
     * SOCKS5 type.
     *
     * @const int
     */
    public const int SOCKS5 = CURLPROXY_SOCKS5;

    /**
     * BASIC auth type.
     *
     * @const int
     */
    public const int AUTH_BASIC = CURLAUTH_BASIC;

    /**
     * NTLM auth type.
     *
     * @const int
     */
    public const int AUTH_NTLM = CURLAUTH_NTLM;

    /**
     * Address.
     *
     * @var string
     */
    private string $address;

    /**
     * Port.
     *
     * @var int
     */
    private int $port;

    /**
     * Type.
     *
     * @var int
     */
    private int $type = Proxy::HTTP;

    /**
     * Username.
     *
     * @var string
     */
    private string $username;

    /**
     * Password.
     *
     * @var string
     */
    private string $password;

    /**
     * SSL password.
     *
     * @var string
     */
    private string $ssl_password;

    /**
     * HTTP authenticate type.
     *
     * @var int
     */
    private int $auth_type = Proxy::AUTH_BASIC;

    /**
     * Headers.
     *
     * @var array
     */
    private array $headers = [];

    /**
     * Set connection address.
     *
     * @param string $address Connection address
     *
     * @return Proxy
     */
    public function setAddress(string $address): Proxy
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Set connection port.
     *
     * @param int $port Connection port
     *
     * @return Proxy
     */
    public function setPort(int $port): Proxy
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Set type.
     *
     * @param int $type Type
     *
     * @return Proxy
     */
    public function setType(int $type): Proxy
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set connection auth username.
     *
     * @param string $username Username
     *
     * @return Proxy
     */
    public function setUsername(string $username): Proxy
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set connection auth password.
     *
     * @param string $password Password
     *
     * @return Proxy
     */
    public function setPassword(string $password): Proxy
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Add HTTP header.
     *
     * @param string $header Key
     * @param string|int $value Value
     *
     * @return Proxy
     */
    public function addHeader(string $header, string|int $value): Proxy
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Set SSL password.
     *
     * @param string $password Password
     *
     * @return Proxy
     */
    public function setSSLPassword(string $password): Proxy
    {
        $this->ssl_password = $password;

        return $this;
    }

    /**
     * Get cURL options builded array.
     *
     * @return array
     */
    public function getOptions(): array
    {
        $options = [
            CURLOPT_PROXY => $this->address,
            CURLOPT_PROXYPORT => $this->port,
            CURLOPT_PROXYTYPE => $this->type,
            CURLOPT_PROXYHEADER => $this->headers,
            CURLOPT_PROXYAUTH => $this->auth_type,
        ];

        if (!empty($this->username)) {
            $options[CURLOPT_PROXYUSERPWD] = $this->username . ':' . $this->password;
        }
        if (!empty($this->ssl_password)) {
            $options[CURLOPT_PROXY_KEYPASSWD] = $this->ssl_password;
        }

        return $options;
    }
}