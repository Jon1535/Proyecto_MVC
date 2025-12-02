# PLAN DE PRUEBAS DEL SISTEMA RELEE

## 1. Objetivo
Garantizar que la plataforma RELEE (intercambio de libros) desarrollada en PHP (MVC), con interfaz Bootstrap 5 y base de datos MySQL, cumpla los requerimientos funcionales y no funcionales críticos: autenticación segura, gestión de publicaciones, flujo de intercambio y calificación entre usuarios.

## 2. Alcance
Incluye pruebas sobre módulos:
- Autenticación (registro, login, logout)
- Gestión de perfil (visualización/edición básica)
- Publicación de libros (crear, editar, eliminar, listado global)
- Listado y búsqueda de libros (filtros por título/autor)
- Solicitudes de intercambio (solicitar, aceptar, rechazar)
- Intercambios (programar, confirmar, rechazar entrega)
- Calificaciones (crear y cálculo de reputación)
- Notificaciones (creación, filtrado por destinatario)

Excluye por ahora: rendimiento, pruebas de carga, pruebas de accesibilidad profunda.

## 3. Referencias
- Código fuente en rama `docs/autoload`
- Casos funcionales (tabla RF adjunta en documentación interna)
- Estructura de BD: tablas `usuario`, `libro`, `intercambio`, `calificacion`

## 4. Roles y Responsabilidades
- QA / Ingeniero: ejecuta pruebas manuales y automáticas (PHPUnit)
- Desarrollador: corrige defectos y mantiene test suite
- Administrador BD: prepara datos iniciales de prueba (usuarios base)

## 5. Entorno de Pruebas
- Servidor: Apache + PHP >= 8.1
- BD: MySQL/MariaDB (schema `bd_intercambio_libros`)
- Navegador: Chrome/Firefox para validación UI
- Librerías: Composer (PHPUnit), Bootstrap 5

## 6. Datos de Prueba Iniciales
| Entidad | Ejemplo | Notas |
|---------|---------|-------|
| Usuario | (id=1) user1@mail.test | Activo para intercambio |
| Usuario | (id=2) user2@mail.test | Activo para intercambio |
| Libro   | varios publicados | Estados: PUBLICADO/OBSERVADO/ACORDADO |
| Intercambio | registros simulados | Para pruebas de confirmación |
| Calificacion | opcional inicial | Para promedio reputación |

## 7. Estrategia de Pruebas
Tipos:
- Funcionales (caja negra) sobre flujos y reglas de negocio.
- No funcionales básicos: validación de seguridad (hash de contraseña), consistencia de estado.

Niveles:
1. Presentación (validación visual de vistas principales).
2. Lógica de negocio (controladores y modelos).
3. Persistencia (operaciones CRUD vía PDO).

Criterios de Entrada:
- BD creada y accesible.
- Usuarios base presentes (id 1 y 2).
- Autoload de Composer funcionando.

Criterios de Salida:
- 100% de casos críticos aprobados.
- 0 defectos bloqueantes abiertos.
- Registro de incidentes actualizado.

## 8. Matriz de Cobertura (RF → Tests)
| RF | Descripción | Test Automatizado | Método Clave |
|----|-------------|-------------------|--------------|
| RF1 | Registro/Login | `UsuarioTest::testRegistrarYLogin()` | `Usuario::Registrar`, `Usuario::VerificarLogin` |
| RF2 | Inicio sesión correcto/fallido | `UsuarioTest` | Verificación `password_verify` |
| RF3 | Editar perfil | (Pendiente UI manual) | Validar restricción propietario |
| RF4 | Ver historial intercambios | (Pendiente) | `Intercambio::Obtener` |
| RF5 | Crear publicación | `LibroBusquedaTest` | `Libro::Insertar` |
| RF6 | Editar publicación | (Pendiente) | `Libro::Actualizar` |
| RF7 | Listar publicaciones | `LibroBusquedaTest` | `Libro::Listar` |
| RF8 | Búsqueda | (Pendiente: filtrar) | Controlador búsqueda |
| RF9 | Enviar solicitud intercambio | (Pendiente) | `Intercambio.controlador::Seleccionar` |
| RF10 | Consultar estado solicitud | `IntercambioTest` | `Intercambio::Obtener` |
| RF11 | Calificar usuario | `ValoracionTest` | `Valoracion::Crear`, `Valoracion::PromedioPorUsuario` |
| Notificaciones | Crear/filtrar | `NotificacionesTest` | JSON almacenamiento |

## 9. Diseño de Casos (Ejemplos Nuevos)
- Caso: Registro válido
  - Precondición: email no existe
  - Pasos: enviar formulario registro
  - Resultado esperado: usuario creado, hash distinto a contraseña.
- Caso: Registro inválido
  - Campos vacíos → mensaje error y sin inserción.
- Caso: Crear libro
  - Precondición: usuario autenticado
  - Pasos: enviar datos válidos + imagen
  - Resultado: libro PUBLICADO visible en listado.

## 10. Gestión de Datos
- Limpieza: usar BD de prueba distinta a producción.
- Aislamiento: tests que dependen de inserciones generan sufijos únicos (`uniqid`).
- Reutilización: IDs 1 y 2 reservados para casos básicos.

## 11. Severidad/Prioridad
| Severidad | Descripción |
|-----------|-------------|
| Crítica | Bloquea login o intercambio |
| Alta | Corrupción de datos libro/intercambio |
| Media | Error en mensajes/validaciones secundarias |
| Baja | Estética o texto |

## 12. Proceso de Ejecución
1. Preparar BD y usuarios base.
2. Ejecutar suite PHPUnit:
   ```bash
   vendor/bin/phpunit
   ```
3. Registrar fallos en formato: ID, descripción, severidad, módulo.
4. Reejecutar tras correcciones.

## 13. Métricas
- % casos aprobados = casos OK / casos totales.
- Tasa de fallos críticos = fallos críticos / total fallos.
- Tiempo medio corrección (opcional).

## 14. Riesgos
| Riesgo | Mitigación |
|--------|------------|
| Datos compartidos entre tests | Uso de sufijos únicos y BD aislada |
| Dependencia de usuarios inexistentes | Script de seed previo |
| Cambios en esquema BD | Actualizar modelos + fixtures |

## 15. Mejoras Futuras
- Añadir fixtures y rollback transaccional.
- Integrar pruebas de carga (ApacheBench / k6).
- Agregar prueba de rehash automático de contraseñas.
- Implementar tests para flujo completo de intercambio (aceptar/rechazar).

## 16. Ejecución Manual Complementaria
| Vista | Verificar |
|-------|-----------|
| Biblioteca | Listado y estados de libros |
| Perfil | Datos y reputación promedio |
| Intercambio Ver | Confirmaciones y programación |
| Valoración Crear | Bloqueo si duplicada o sin confirmación |
| Notificaciones | Enlace y conteo por usuario |

## 17. Cierre
Al concluir: compilar reporte final con resultados, defectos y recomendaciones.

---
Documento versión 1.0. Ajustar conforme se amplíe cobertura.
