<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class HttpClient
{
    private $fields = array();
    private $headers = array();
    private int $status_scode = 0;
    private string $user_agent = 'mikronuke http client';
    private bool $follow_redirect = false;
    private bool $setting_verify_ssl = true;
    private int $setting_timeout = 8000;
    private int $setting_connect_timeout = 8000;
    private string $setting_mimetype = '';

    // todo custom headers

    public function setParameter(string $key, string $value): void
    {
        $this->fields[$key] = $value;
    }

    public function addHeader(string $header): void
    {
        $this->headers[] = $header;
    }

    public function setFollowRedirect(bool $value): void
    {
        $this->follow_redirect = $value;
    }

    public function setUserAgent(string $value): void
    {
        $this->user_agent = $value;
    }

    public function getStatusCode(): ?int
    {
        return $this->status_code;
    }

    public function get(string $url): ?string
    {
        $fields_string = '';
        foreach ($this->fields as $key => $value) {
            $fields_string .= $key . '=' . rawurlencode($value) . '&';
        }
        $fields_string = rtrim($fields_string, '&');
        if (!empty($fields_string)) {
            $url = $url . '?' . $fields_string;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->follow_redirect);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        $response = curl_exec($ch);
        $this->status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // flush fields
        return $response;
    }

    public function post(string $url): ?string
    {
        $fields_string = '';
        foreach ($this->fields as $key => $value) {
            $fields_string .= $key . '=' . rawurlencode($value) . '&';
        }
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->follow_redirect);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        $response = curl_exec($ch);
        $this->status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }
}
