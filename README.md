# esolutions/ws

Cliente HTTP para WhatsApp API. Envía mensajes de texto y documentos PDF, y gestiona sesiones.

## Instalación

```bash
composer require esolutions/ws
```

## Namespace

```
Esolutions\Ws\
```

---

## Configuración

Agregar en `config/esolutions.php`:

```php
'ws' => [
    'url'   => env('WS_API_URL'),
    'token' => env('WS_API_TOKEN'),
],
```

Agregar en `.env`:

```dotenv
WS_API_URL=http://tu-servidor-whatsapp/api
WS_API_TOKEN=tu_token_aqui
```

---

## Uso

```php
use Esolutions\Ws\Service as WhatsApp;
```

### Enviar PDF

Envía un archivo PDF como adjunto a un número de WhatsApp.

```php
$pdfContent = file_get_contents('/ruta/al/archivo.pdf');

$result = WhatsApp::sendPdf(
    base64Pdf: base64_encode($pdfContent),
    number: '51987654321',
    message: 'Adjunto su comprobante de pago',
    filename: 'factura_F001-001.pdf'
);

// → ['success' => true, 'messageId' => 'abc123']
// → ['success' => false, 'message' => 'Sesión no disponible']
```

### Enviar texto

```php
$result = WhatsApp::sendText(
    sessionId: 'session-01',
    to: '51987654321',
    text: 'Su pedido fue confirmado. Gracias por su compra.'
);
```

### Listar sesiones

```php
$sessions = WhatsApp::getSessions();

// → [
//     'success' => true,
//     'data' => [
//         ['id' => 'session-01', 'status' => 'connected'],
//         ['id' => 'session-02', 'status' => 'disconnected'],
//     ]
// ]
```

### Estado de una sesión

```php
$status = WhatsApp::getSessionStatus('session-01');

// → ['success' => true, 'data' => ['status' => 'connected']]
```

### Estado de entrega de un mensaje

```php
$status = WhatsApp::getMessageStatus('abc123');

// → ['success' => true, 'data' => ['status' => 'delivered']]
```

---

## Métodos

| Método | Timeout | Descripción |
|---|---|---|
| `sendPdf($base64, $number, $message, $filename)` | 30s | Envía un PDF como archivo adjunto |
| `sendText($sessionId, $to, $text)` | 15s | Envía mensaje de texto plano |
| `getSessions()` | 15s | Lista todas las sesiones disponibles |
| `getSessionStatus($sessionId)` | 15s | Estado de una sesión específica |
| `getMessageStatus($messageId)` | 15s | Estado de entrega de un mensaje |

---

## Comportamiento

- Todos los métodos retornan `array` con `success` como clave principal.
- Los errores de red o de la API se capturan internamente — nunca lanza excepciones.
- SSL verify desactivado para compatibilidad con servidores locales/privados.
- Timeout de PDF es mayor (30s) porque el servidor puede tardar en procesar archivos grandes.

---

## Formato del número

El número de destino debe incluir el código de país sin `+`:

```
51987654321   ✅  Perú
1234567890    ❌  Sin código de país
+51987654321  ❌  Con símbolo +
```
