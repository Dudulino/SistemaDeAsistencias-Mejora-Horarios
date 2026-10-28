<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Asistente inteligente | SDA</title></head>
<body>
<div id="wrapper">
@include('components.inspinia.side_nav_bar-inspinia')
<div class="row wrapper border-bottom white-bg page-heading">
 <div class="col-lg-12"><h2><i class="fa fa-comments-o"></i> Asistente inteligente</h2>
 <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li><li class="breadcrumb-item active">Asistente</li></ol></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
 <div class="row">
  <div class="col-lg-8">
   <div class="ibox"><div class="ibox-title"><h5>¿En qué puedo ayudarte?</h5></div>
    <div class="ibox-content">
      <p class="text-muted">Pregunta cómo usar un módulo o dónde encontrarlo. Los enlaces disponibles respetan tu perfil.</p>
      <div id="asistente-respuestas" role="status" aria-live="polite" style="min-height:130px;max-height:390px;overflow-y:auto;padding:14px;background:#f7f9fc;border:1px solid #e7eaec;border-radius:8px;margin-bottom:15px">
       <p><strong>Asistente:</strong> Hola. Puedes preguntar: «¿Cómo asigno horarios?», «¿Dónde cambio los cupos?» o «¿Cuántas solicitudes hay pendientes?».</p>
      </div>
      <form id="asistente-form">
       @csrf
       <label for="pregunta">Tu consulta</label>
       <div class="input-group">
        <input id="pregunta" type="text" class="form-control" maxlength="700" minlength="3" required autocomplete="off" placeholder="Ej. ¿Dónde genero una propuesta de horarios?">
        <div class="input-group-append"><button id="asistente-enviar" type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Preguntar</button></div>
       </div>
      </form>
      @unless($geminiConfigurado)<div class="alert alert-warning m-t-md">Gemini no está configurado. Los accesos directos siguen disponibles; agrega GEMINI_API_KEY al archivo .env para activar las respuestas de IA.</div>@endunless
      <p class="text-muted m-t-md"><small>Modo seguro: solo lectura. No envíes datos personales ni contraseñas a la IA. La asignación continúa ejecutándose en el motor existente, no en Gemini.</small></p>
    </div>
   </div>
  </div>
  <div class="col-lg-4"><div class="ibox"><div class="ibox-title"><h5><i class="fa fa-compass"></i> Ir directamente a un módulo</h5></div>
   <div class="ibox-content"><p>Un clic para abrir la función que necesitas:</p>
   @foreach($modulos as $modulo)
    <a class="btn btn-white btn-block text-left" style="white-space:normal;text-align:left" href="{{ route($modulo['ruta']) }}"><i class="fa fa-angle-right"></i> <strong>{{ $modulo['nombre'] }}</strong><br><small class="text-muted">{{ $modulo['descripcion'] }}</small></a>
   @endforeach
   </div></div>
  </div>
 </div>
</div>
</div>
<script>
(function () {
 const form = document.getElementById('asistente-form');
 const entrada = document.getElementById('pregunta');
 const panel = document.getElementById('asistente-respuestas');
 const boton = document.getElementById('asistente-enviar');
 const endpoint = @json(route('asistenteGemini.preguntar'));
 const token = @json(csrf_token());
 function parrafo(etiqueta, contenido) {
   const p = document.createElement('p');
   const b = document.createElement('strong');
   b.textContent = etiqueta + ': ';
   p.appendChild(b);
   p.appendChild(document.createTextNode(contenido));
   panel.appendChild(p);
 }
 form.addEventListener('submit', async function (event) {
   event.preventDefault();
   const pregunta = entrada.value.trim();
   if (pregunta.length < 3) return;
   parrafo('Tú', pregunta);
   entrada.value = '';
   boton.disabled = true;
   boton.textContent = 'Consultando...';
   try {
     const response = await fetch(endpoint, {
       method: 'POST',
       headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':token},
       credentials:'same-origin',
       body: JSON.stringify({pregunta})
     });
     if (!response.ok) throw new Error(response.status === 429 ? 'Demasiadas consultas. Intenta nuevamente.' : 'No fue posible procesar la consulta.');
     const data = await response.json();
     parrafo('Asistente', data.respuesta || 'Sin respuesta.');
     if (data.aviso) parrafo('Aviso', data.aviso);
     (data.accesos || []).forEach(function (acceso) {
       const a = document.createElement('a');
       a.href = acceso.url;
       a.className = 'btn btn-outline-primary btn-sm m-r-xs m-b-xs';
       a.textContent = 'Abrir ' + acceso.nombre;
       panel.appendChild(a);
     });
   } catch (e) { parrafo('Aviso', e.message); }
   finally { boton.disabled = false; boton.innerHTML = '<i class="fa fa-paper-plane"></i> Preguntar'; panel.scrollTop = panel.scrollHeight; entrada.focus(); }
 });
})();
</script>
</body></html>