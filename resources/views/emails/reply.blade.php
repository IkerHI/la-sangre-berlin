<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Respuesta de La Sangre Berlín</title></head>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #A51C30;">La Sangre Berlín</h2>
    <p style="color: #555; font-size: 14px;">Hola {{ $contactMessage->name }},</p>
    <div style="background: #f9f9f9; border-left: 3px solid #A51C30; padding: 16px; margin: 20px 0; white-space: pre-wrap; font-size: 14px; line-height: 1.6;">{{ $body }}</div>
    <hr style="border: none; border-top: 1px solid #eee; margin: 24px 0;">
    <p style="color: #888; font-size: 12px;">
        Este es un mensaje en respuesta a tu consulta del {{ $contactMessage->created_at->format('d/m/Y') }}.<br>
        No respondas directamente a este correo — escríbenos a
        <a href="mailto:contacto@lasangreberlin.com">contacto@lasangreberlin.com</a>.
    </p>
</body>
</html>
