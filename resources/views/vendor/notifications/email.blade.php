<x-mail::message>
{{-- Greeting --}}
@if ($level === 'error')
# @lang('Ops!')
@else
# @lang('Olá!')
@endif

{{-- Intro Lines --}}
Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha da sua conta.

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
Redefinir Senha
</x-mail::button>
@endisset

{{-- Outro Lines --}}

<p>Este link de redefinição de senha expirará em 60 minutos.</p>
<p>Se você não solicitou uma redefinição de senha, nenhuma ação adicional será necessária.</p>

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('At. te'),<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "Se você estiver tendo problemas para clicar no botão de \":actionText\", copie e cole o URL abaixo\n".
    'em seu navegador:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
