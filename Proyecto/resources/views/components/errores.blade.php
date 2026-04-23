@if ($errors->any())
    <div style="background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <strong>¡El portero de Laravel ha bloqueado la entrada por esto:</strong>
        <ul style="margin-top: 10px; margin-bottom: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif