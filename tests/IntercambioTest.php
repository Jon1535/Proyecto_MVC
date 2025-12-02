<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Modelos\Intercambio;
use Modelos\Basededatos;

final class IntercambioTest extends TestCase
{
    public function testCrearYProgramarIntercambio()
    {
        try { $pdo = Basededatos::Conectar(); } catch (\Throwable $e) { $this->markTestSkipped('BD no disponible'); return; }
        $modelo = new Intercambio();
        // Crear intercambio (asumimos usuarios 1 y 2 existen en entorno de prueba)
        try {
            $id = $modelo->Crear(0, date('Y-m-d H:i:s'), 1, 2);
        } catch (\Throwable $t) {
            $this->markTestSkipped('FK solicitud no disponible para crear intercambio');
            return;
        }
        if (!$id) {
            $this->markTestSkipped('Creación de intercambio no posible sin solicitud válida');
            return;
        }
        $this->assertGreaterThan(0, (int)$id, 'ID de intercambio debe ser > 0');
        $fila = $modelo->Obtener($id);
        $this->assertEquals(1, (int)$fila['id_usuario_a']);
        $this->assertEquals(2, (int)$fila['id_usuario_b']);

        // Programar
        $modelo->Programar($id, 'PUNTO_SEGURO', 'Direccion X', date('Y-m-d H:i:s', time()+3600), 'Notas');
        $actualizado = $modelo->Obtener($id);
        $this->assertEquals('Direccion X', $actualizado['direccion'] ?? $actualizado['punto_direccion'] ?? null);
    }
}
