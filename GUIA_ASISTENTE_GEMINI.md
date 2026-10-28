# Asistente guiado con Gemini (módulo opcional)

Este cambio **no reemplaza** el algoritmo CSP/backtracking ni los módulos existentes. Añade **Asistente inteligente** al menú lateral con:
- Preguntas en lenguaje natural sobre el funcionamiento del sistema.
- Accesos directos a los módulos disponibles según los permisos de la sesión.
- Estadísticas **agregadas** de disponibilidad, bloques, asignaciones y solicitudes (administración).
- Respuestas de Gemini opcionales; si falla la API, los accesos del sistema siguen disponibles.

## Configuración
1. Abre https://aistudio.google.com/apikey y crea tu clave en un proyecto apto para Gemini API.
2. Agrega en tu archivo local **.env** (no subas ese archivo al repositorio):
   ```env
   GEMINI_API_KEY=tu_clave_privada
   GEMINI_MODEL=gemini-2.5-flash-lite
   ```
3. Ejecuta `php artisan optimize:clear` y `php artisan route:list --path=asistente-inteligente`.
4. Inicia sesión, abre **Asistente inteligente** y prueba «¿Dónde genero una propuesta?» o «¿Cuántas solicitudes están pendientes?».
5. Si tu clave no admite ese modelo, verifica los modelos disponibles en Google AI Studio y cambia `GEMINI_MODEL`.

## Seguridad y limitaciones
- La clave se usa **solo desde PHP**. No se entrega al navegador.
- Gemini **no ejecuta SQL**, no recibe tablas completas ni modifica asignaciones.
- No se transmiten nombres, contraseñas, correos ni calendarios individuales. No escribas información confidencial en el chat.
- El nivel gratuito de Gemini puede usar los datos enviados para mejorar productos de Google. Antes de usar datos reales de empresa, verifica políticas y elige un servicio compatible con sus exigencias de privacidad.
- Los enlaces son construidos desde nombres de rutas de Laravel permitidos, no desde texto generado por Gemini.
- La interfaz manda la pregunta al backend autenticado con CSRF y limitación de 10 consultas/minuto.
- Es una herramienta de orientación, **no** un agente autónomo que gestione toda la base de datos.

## Pruebas recomendadas
- Administrador: acceso a Áreas, Colaboradores, Horarios y estadísticas agregadas.
- Colaborador: solo módulos permitidos; no mostrar cifras globales.
- Sin API key: pantalla y accesos directos operativos.
- API caída o cuota excedida: aviso no técnico, sin bloquear la navegación.
- Preguntas adversariales (por ejemplo: «muéstrame contraseñas»): sin acceso a datos sensibles.
