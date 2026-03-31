# Filament Banners

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)

O pacote **Filament Banners** permite gerenciar e exibir banners de forma dinâmica no seu projeto. 
Ele oferece suporte para múltiplas localizações, campos personalizados (meta), suporte a imagens responsivas (desktop, notebook, mobile) e vídeos.

## Instalação

Você pode instalar o pacote via composer:

```bash
composer require agenciafmd/filament-banners
```

Execute as migrações:

```bash
php artisan migrate
```

Publique o arquivo de configuração (opcional):

```bash
php artisan vendor:publish --tag="filament-banners:config"
```

## Registro no Filament

Para habilitar o recurso no painel administrativo, adicione o plugin ao seu painel:

```php
use Agenciafmd\Faqs\BannersPlugin;

return [
    'plugins' => [
        BannersPlugin::class,
    ],
];
```

## Configuração

No arquivo `config/filament-banners.php`, você pode definir as localizações dos banners. 
Cada localização pode ter configurações específicas de imagens e campos extras (meta).

```php
'locations' => [
    'home' => [
        'label' => 'Home',
        'files' => [
            'desktop' => [
                'visible' => true,
                'width' => 1920,
                'height' => 1080,
                'ratio' => ['16:9'],
                'media' => '(min-width: 1400px)',
            ],
            // ... outras resoluções
        ],
        'meta' => [
            [
                'type' => \Agenciafmd\Banners\Enums\Meta::TEXT,
                'label' => 'Título',
                'name' => 'title',
            ],
        ],
    ],
],
```

## Uso no Frontend

O pacote disponibiliza um componente Blade para facilitar a exibição dos banners.

### Componente de Banner

Você pode usar o componente em qualquer arquivo Blade:

```blade
<x-banner quantity="3" location="home" :random="false" />
```

### Parâmetros do Componente

- `quantity`: Quantidade de banners a serem exibidos (padrão: 3).
- `location`: Localização definida no arquivo de configuração (padrão: 'home').
- `random`: Se os banners devem ser exibidos em ordem aleatória (padrão: false).

## Campos Adicionais (Meta)

Você pode adicionar campos extras aos banners através da configuração. Os tipos suportados no `Enums\Meta` são:
- `TEXT`
- `SELECT`
- `REPEATER`

Estes campos estarão disponíveis na variável `$banners` retornada para a view dentro da chave `meta`.

```blade
@foreach($banners as $banner)
    <h2>{{ $banner['meta']['title'] ?? $banner['name'] }}</h2>
@endforeach
```
