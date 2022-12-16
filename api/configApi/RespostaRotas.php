<?php
namespace RespostaRotas;

class RespostaRotas{

    private int $statusHttp = 200;
    private string $typeResposta = 'json';
    private array $headers = array();
    private array $body = array();
    private string|null $error;
    private string|null $accept;


    public function __construct(string $accept = '*/*')
    {
        $this->accept = $accept;
    }

    private function applyHeaders(): void
    {
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        http_response_code($this->statusHttp);
    }

    public function body(array $content): void
    {
        $this->body = $content;
    }

    public function statusHttp(int $statusHttp): void
    {
        $this->statusHttp = $statusHttp;
    }

    public function headers(string $title, string|array $content): void
    {
        $this->headers[$title] = $content;
    }

    public function error(string|array $error): void
    {
        $this->error = $error;
    }

    public function content_type(string $typeResposta): void
    {
        $this->typeResposta = $typeResposta;
    }

    public function response(): array|string
    {
        header('Content-Type: application/json; charset=utf-8');
        $this->applyHeaders();
        return json_encode($this->body);
    }
}
?>