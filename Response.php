<?php

class Response
{
    private int $httpCode;
    private string $message;
    private mixed $data;

    public function __construct(
        int $httpCode,
        string $message,
        $data = null
    )
    {
        $this->httpCode = $httpCode;
        $this->message = $message;
        $this->data = $data;
    }


    public function send(): void
    {
        header('Content-Type: application/json');
        http_response_code($this->httpCode);
        echo json_encode(array(
            'success' => $this->httpCode == HttpErrorCodes::HTTP_OK,
            'message' => $this->message,
            'data' => $this->data
        ));
        exit();
    }

    public static function ok(
        string $message,
        $data = null
    ): Response
    {
        return new Response(HttpErrorCodes::HTTP_OK, $message, $data);
    }

    public static function created(
        string $message,
        $data = null
    ): Response
    {
        return new Response(HttpErrorCodes::HTTP_CREATED, $message, $data);
    }

    public static function error(
        int $httpCode = HttpErrorCodes::HTTP_INTERNAL_SERVER_ERROR,
        string $message,
        $data = null
    ): Response {
        return new Response($httpCode, $message, $data);
    }


}