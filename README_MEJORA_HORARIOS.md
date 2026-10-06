# Módulo de mejora — Asignación automatizada de horarios

Este ZIP conserva el proyecto Laravel original y agrega el prototipo funcional descrito en el proyecto de mejora de J & P Periféricos S.A.C. No se reemplazó el CSS corporativo; la interfaz nueva reutiliza Bootstrap/Inspinia y los componentes existentes.

## Qué se agregó

- Registro y actualización de disponibilidad por practicante, día, intervalo, modalidad y origen de la restricción.
- Uso automático de los horarios de clases ya registrados como restricción dura.
- Configuración de bloques existentes por área con turno, modalidad, cupo mínimo y cupo máximo.
- Motor de scoring explicable para ordenar candidatos factibles.
- Motor CSP con backtracking para formar una propuesta sin cruces y respetando cupos.
- Propuesta parcial con detalle de bloques pendientes cuando no existe solución completa.
- Revalidación antes de confirmar y persistencia transaccional de la propuesta.
- Consulta de horarios confirmados por practicante, área y turno.
- Solicitudes de cambio de turno/área con búsqueda automática de alternativa.
- Conservación del cupo mínimo al reasignar: si hace falta, el motor busca un sustituto compatible.
- Historial básico de propuestas, asignaciones reemplazadas y solicitudes de cambio.

## Por qué no se agregó una API de IA externa

La tesis define como solución un algoritmo de **scoring + CSP/backtracking**. Por eso el prototipo implementa esa lógica directamente y de forma explicable, sin introducir una API externa que requiera claves, internet o un servicio no documentado en el proyecto. Esto facilita la sustentación y evita que el prototipo dependa de terceros.

## Instalación en Windows / VS Code

1. Descomprime el proyecto y abre la carpeta raíz en VS Code.
2. Instala PHP 8.x, Composer, Node.js y MySQL si aún no los tienes.
3. Desde la terminal de la carpeta del proyecto ejecuta:

```bash
composer install
npm install
```

4. Si no existe `.env`, créalo desde el ejemplo:

```bash
copy .env.example .env
php artisan key:generate
```

5. Configura en `.env` la conexión de MySQL (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
6. Ejecuta las migraciones nuevas:

```bash
php artisan migrate
php artisan optimize:clear
```

7. Para ejecutar el proyecto:

```bash
php artisan serve
npm run dev
```

También se incluye `INSTALAR_DEPENDENCIAS.bat` para automatizar los pasos de Composer/NPM y generación inicial del `.env`.

## Cómo probar el módulo

1. Inicia sesión como administrador.
2. Verifica que las áreas ya tengan horarios presenciales configurados en el sistema original.
3. En **Horarios Generales > Asignación automática**, abre **Bloques y cupos** y revisa mínimo/máximo (por defecto 2 y 5).
4. Registra disponibilidad para varios practicantes. La disponibilidad debe cubrir por completo el bloque que se quiera asignar.
5. Si el practicante tiene un horario de clases que cruza ese bloque, el motor lo descartará automáticamente.
6. Pulsa **Generar propuesta inteligente**.
7. Si aparece como `FACTIBLE`, revisa el detalle y pulsa **Confirmar y guardar horarios**.
8. Los practicantes pueden consultar sus horarios y enviar solicitudes de cambio.
9. El administrador puede usar **Resolver** para buscar una alternativa sin cruces y respetando cupos.

## Archivos principales agregados

- `app/Http/Controllers/AsignacionHorariosController.php`
- `app/Services/HorarioAsignacionService.php`
- `app/Models/DisponibilidadPracticante.php`
- `app/Models/ConfiguracionBloqueHorario.php`
- `app/Models/PropuestaHorario.php`
- `app/Models/PropuestaHorarioDetalle.php`
- `app/Models/AsignacionPracticante.php`
- `app/Models/SolicitudCambioHorario.php`
- `database/migrations/2026_10_05_000001_create_modulo_asignacion_automatizada.php`
- `resources/views/inspiniaViews/horarios/asignacion_automatica.blade.php`

## Importante para la demostración

El motor no inventa disponibilidad. Si un practicante no tiene una franja `disponible` que cubra el bloque, no será asignado. Esto permite demostrar claramente cómo el sistema evita cruces de estudio/trabajo y por qué una propuesta puede quedar parcial.
