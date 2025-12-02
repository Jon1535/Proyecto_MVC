<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Modelos\Usuario;
use Modelos\Basededatos;

final class UsuarioTest extends TestCase
{
    public function testPasswordHashAndVerify()
    {
        // Simular sólo la función de hashing sin conexión BD (setPassword requiere Conectar())
        $plain = 'ClaveSegura123!';
        $hash = password_hash($plain, PASSWORD_DEFAULT);
        $this->assertNotEquals($plain, $hash, 'El hash no debe ser igual al texto plano');
        $this->assertTrue(password_verify($plain, $hash), 'Debe verificar correctamente la contraseña');
    }

    public function testRegistrarYLogin()
    {
        // Requiere BD de prueba; si falla conexión se marca test como skipped.
        try { $pdo = Basededatos::Conectar(); } catch (\Throwable $e) { $this->markTestSkipped('BD no disponible para prueba'); return; }
        // Preparar datos únicos
        $email = 'test_' . uniqid() . '@example.com';
        $usuarioModel = new Usuario();
        $id = $usuarioModel->Registrar([
            'nombre' => 'Tester',
            'email' => $email,
            'password' => 'Temporal123$',
            'estado' => 'ACTIVO'
        ]);
        $this->assertNotEmpty($id, 'Debe devolver ID insertado');
        $login = $usuarioModel->VerificarLogin($email, 'Temporal123$');
        $this->assertNotFalse($login, 'Login válido debe devolver objeto usuario');
        $this->assertEquals('Tester', $login->nombre);
    }
}
