<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class NotificacionesTest extends TestCase
{
    private string $ruta;

    protected function setUp(): void
    {
        $base = __DIR__ . '/../almacenamiento';
        if (!is_dir($base)) { mkdir($base, 0777, true); }
        $this->ruta = $base . '/notificaciones_test.json';
        file_put_contents($this->ruta, json_encode([], JSON_UNESCAPED_UNICODE));
    }

    public function testAgregarNotificacion()
    {
        $lista = json_decode(file_get_contents($this->ruta), true);
        $lista[] = [
            'id' => uniqid('n', true),
            'destinatario' => 1,
            'remitente' => 2,
            'tipo' => 'intercambio_solicitado',
            'titulo' => 'Solicitud',
            'mensaje' => 'Te han solicitado un intercambio',
            'fecha' => date('c'),
            'leida' => false,
            'intercambio_id' => 10
        ];
        file_put_contents($this->ruta, json_encode($lista, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $nuevo = json_decode(file_get_contents($this->ruta), true);
        $this->assertCount(1, $nuevo);
        $this->assertEquals('Solicitud', $nuevo[0]['titulo']);
    }

    public function testFiltrarPorDestinatario()
    {
        $lista = [
            ['id' => 'n1', 'destinatario' => 1],
            ['id' => 'n2', 'destinatario' => 2],
            ['id' => 'n3', 'destinatario' => 1],
        ];
        file_put_contents($this->ruta, json_encode($lista));
        $data = json_decode(file_get_contents($this->ruta), true);
        $filtradas = array_filter($data, fn($n) => (int)$n['destinatario'] === 1);
        $this->assertCount(2, $filtradas);
    }
}
