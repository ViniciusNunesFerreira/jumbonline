<x-mail::message>
<p>
    {{ __('Você tem um novo envio de formulário de contato!') }}
</p>
<p>
    {{ __('Aqui estão os detalhes:') }}
</p>
<ul>
    <li>
        <strong>{{ __('Nome:') }}</strong> {{ $name }}
    </li>
    <li>
        <strong>{{ __('Email:') }}</strong> {{ $email }}
    </li>
    <li>
        <strong>{{ __('Telefone:') }}</strong> {{ $phone }}
    </li>
</ul>
<p>
    {{ __('Mensagem:') }}
</p>
<x-mail::panel>
    {{ $message }}
</x-mail::panel>

<br>
{{ config('app.name') }}
</x-mail::message>
