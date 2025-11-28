# Método: Usuario::Obtener($id)

**Definición (archivo):** `Modelos/Usuario.php`

## Propósito
Recuperar un usuario por su `id_usuario`.

## Firma aproximada
- `public function Obtener($id)`

## Parámetros
- `$id` (int) — id del usuario.

## Retorno
- `object|null` — objeto con datos del usuario o `null` si no existe.

## Uso / llamadas detectadas
- Implementación y llamadas internas en `Modelos/Usuario.php`.
- Puede utilizarse en controladores para cargar datos de perfil o validar propiedad en recursos.

## Recomendaciones
- Evitar devolver datos sensibles (p. ej. hash de contraseña) a la vista sin necesidad.

## Enlaces
- Modelo: `Modelos/Usuario.php`
- Documentación modelo: `docs/obsidian/03_Codebase/Models/Usuario.md`
