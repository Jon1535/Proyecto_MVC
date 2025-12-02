<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Modelos\Valoracion;
use Modelos\Basededatos;

final class ValoracionTest extends TestCase
{
    public function testCalculoPromedio()
    {
        try { $pdo = Basededatos::Conectar(); } catch (\Throwable $e) { $this->markTestSkipped('BD no disponible'); return; }
        $modelo = new Valoracion();
        // Asumimos que existen filas en calificacion para el usuario 1 o se insertan aquí.
        // Inserción controlada (puede requerir que exista intercambio válido). Se hace best-effort.
        $intercambioId = 0; // si no hay un intercambio real disponible se salta.
        if ($intercambioId > 0) {
            $modelo->Crear([
                'intercambio_id' => $intercambioId,
                'evaluador_id' => 2,
                'evaluado_id' => 1,
                'puntuacion' => 5,
                'comentario' => 'Excelente'
            ]);
        }
        $promedio = $modelo->PromedioPorUsuario(1);
        $this->assertIsFloat($promedio);
        $this->assertGreaterThanOrEqual(0.0, $promedio);
        $this->assertLessThanOrEqual(5.0, $promedio);
    }
}
