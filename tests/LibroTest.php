<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Modelos\Libro;

class LibroTest extends TestCase
{
    public function testTitulo()
    {
        $libro = new Libro();
        $libro->setTitulo("Cien años de soledad");
        $this->assertEquals("Cien años de soledad", $libro->getTitulo());
    }

    public function testAutor()
    {
        $libro = new Libro();
        $libro->setAutor("Gabriel García Márquez");
        $this->assertEquals("Gabriel García Márquez", $libro->getAutor());
    }
}
