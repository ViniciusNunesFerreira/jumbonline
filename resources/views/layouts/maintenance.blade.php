<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        >
        <meta
            name="robots"
            content="index, follow"
        >

        {!! SEOMeta::generate() !!}

        {!! OpenGraph::generate() !!}

        {!! Twitter::generate() !!}

        <!-- Favicon -->
        <link
            rel="icon"
            href="{{ $brandSettings->favicon_path ? Storage::url($brandSettings->favicon_path) : asset('img/favicon.png') }}"
        >

        <!-- Fonts -->
        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
        >
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        >
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        >
        <meta name="description" content="Jumbonline é a pioneira, especializada na lista de jumbo de CDP, penitenciárias, CPP e CR! Com os melhores preços e variedades, entrega rápida e segura.">
        <meta name="keywords" content="Lista do jumbo, Lista de Jumbo, Lista do Jumbo CDP, Lista de Jumbo CDP, jumbo de, jumbo do, jumbo do cdp, telefone do cdp, telefone cdp, cdp fone, cdp de, jumbo, cdp, penitenciaria, cdp de santo andre, cdp de piracicaba, cdp de cerqueira , cezar, cdp de mogi das cruzes, cdp de americana, cdp de araraquara, cdp de belem, cdp de bauru, cdp de capela do alto, cdp de campinas, cdp de osasco, cdp de endereço, cdp de franco da rocha, cdp de feminino, cdp de franca, cdp de guarulhos, cdp de hortolandia, cdp de humaita, cdp de itapecerica da serra, cdp de cdp i, cdp de cdp ii, cdp de jundiai, cdp de maua, cdp de mongagua, cdp de nova independencia, cdp de pinheiros, cdp de praia grande, cdp de rio preto, cdp de ribeirão preto, cdp de riolandia, cdp de sorocaba, cdp de suzano, cdp de são vicente, cdp de taubaté, cdp de vila independencia, cdp de vila prudente">
        <meta itemprop="name" content="Jumbonline - A pioneira especializada na Lista de Jumbo dos CDP de SP">
        <meta itemprop="description" content="Jumbonline é a pioneira, especializada na lista de jumbo de CDP, penitenciárias, CPP e CR! Com os melhores preços e variedades, entrega rápida e segura.">
        <!-- Styles -->

        <script type="text/javascript">
      window.onload = function(){
      (function(d, script) {
      script = d.createElement('script');
      script.type = 'text/javascript';
      script.async = true;
      script.src = 'https://wa.me/5511957923791?text=Gostaria%20de%20mais%20informa%C3%A7%C3%B5es%20sobre%20o%20jumbo';
      d.getElementsByTagName('head')[0].appendChild(script);
      }(document));
      };
      </script>
      
        @livewireStyles
        @vite('resources/css/guest.css')
    </head>

    <body
        id="main"
        class="antialiased font-sans"
    >

    {{ $slot }}

    @if($generalSettings->cookie_consent_enabled)
        <x-cookie-consent />
    @endif


    <x-notification />

    <!-- Scripts -->
    @livewireScripts
    @vite('resources/js/guest.js')
    @stack('scripts')
    </body>
</html>