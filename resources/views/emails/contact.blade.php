<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Nuevo mensaje de contacto</title></head>
<body style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #A51C30;">Nuevo mensaje — La Sangre Berlín</h2>
    <table style="width:100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px; font-weight:bold; border-bottom:1px solid #eee;">Nombre</td>
            <td style="padding: 8px; border-bottom:1px solid #eee;">{{ $contactMessage->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight:bold; border-bottom:1px solid #eee;">Email</td>
            <td style="padding: 8px; border-bottom:1px solid #eee;">
                <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a>
            </td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight:bold; border-bottom:1px solid #eee;">Asunto</td>
            <td style="padding: 8px; border-bottom:1px solid #eee;">{{ $contactMessage->subject }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight:bold; vertical-align:top;">Mensaje</td>
            <td style="padding: 8px; white-space: pre-wrap;">{{ $contactMessage->message }}</td>
        </tr>
    </table>
    <p style="color:#888; font-size:12px; margin-top:30px;">
        Recibido el {{ $contactMessage->created_at->format('d/m/Y H:i') }}
    </p>
</body>
</html>
