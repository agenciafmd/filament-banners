# Filament – Banners

[![Downloads](https://img.shields.io/packagist/dt/agenciafmd/filament-banners.svg?style=flat-square)](https://packagist.org/packages/agenciafmd/filament-banners)
[![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Pacote de Banners para o painel administrativo (Admix). Permite gerenciar banners por localização, com imagens responsivas (desktop, notebook, mobile), vídeo, link e campos adicionais (meta), e exibi-los no frontend com um componente Blade.

## Requisitos

- PHP ^8.4
- Laravel ^12.0 | ^13.0
- Filament ^5.0
- agenciafmd/filament-admix v1.x-dev | dev-master

## Instalação

1. Instale o pacote via Composer:

```bash
composer require agenciafmd/filament-banners
```

2. Execute as migrações:

```bash
php artisan migrate
```

3. Populando o banco com dados de testes

Adicione o seeder no `database/seeders/DatabaseSeeder.php`:

```php
use Agenciafmd\Banners\Database\Seeders\BannerSeeder;

$this->call([
    BannerSeeder::class,
]);
```

Ou rode o seeder manualmente:

```bash
php artisan db:seed --class="Agenciafmd\Banners\Database\Seeders\BannerSeeder"
```

## Ativando no painel

O pacote inclui o plugin `BannersPlugin`, que registra o `BannerResource`. Adicione-o na config do Admix `config/filament-admix.php`:

```php
use Agenciafmd\Banners\BannersPlugin;

return [
    'plugins' => [
        BannersPlugin::class,
    ],
];
```

Após isso, o menu **Banners** aparecerá no painel, com as páginas de Listar, Criar e Editar.

## Configuração

Arquivo: `config/filament-banners.php` (publicável, veja [Publicação de assets](#publicação-de-assets)).

Cada localização define seus arquivos (imagens e vídeo) e os campos extras (meta):

```php
use Agenciafmd\Banners\Enums\Meta;

return [
    'name' => 'Banners',
    'navigation_group' => null,
    'navigation_sort' => 5,
    'locations' => [
        'home' => [
            'label' => 'Home',
            'files' => [
                'desktop' => [
                    'visible' => true,
                    'width' => 1920,
                    'height' => 1080,
                    'media' => '(min-width: 1400px)',
                ],
                'notebook' => [
                    'visible' => true,
                    'width' => 1440,
                    'height' => 810,
                    'media' => '(min-width: 768px)',
                ],
                'mobile' => [
                    'visible' => true,
                    'width' => 720,
                    'height' => 1280,
                    'media' => '(max-width: 767px)',
                ],
                'video' => [
                    'visible' => false,
                ],
            ],
            'meta' => [
                [
                    'type' => Meta::TEXT,
                    'label' => 'Título',
                    'name' => 'title',
                ],
                [
                    'type' => Meta::SELECT,
                    'label' => 'Status',
                    'name' => 'status',
                    'options' => [
                        'em-obras' => 'Em obras',
                        'pronto' => 'Pronto',
                    ],
                ],
            ],
        ],
    ],
];
```

| Chave | Padrão | Descrição |
|---|---|---|
| `name` | `Banners` | Nome do pacote. |
| `navigation_group` | `null` | Grupo do menu em que o Resource aparece. |
| `navigation_sort` | `5` | Posição do item no menu. |
| `locations` | `home` | Localizações disponíveis para os banners (a chave é o identificador usado no componente). |
| `locations.*.label` | `Home` | Rótulo da localização no painel. |
| `locations.*.files.{desktop,notebook,mobile}.visible` | `true` | Exibe o campo da imagem no formulário. |
| `locations.*.files.{desktop,notebook,mobile}.width` / `height` | ver acima | Dimensões do crop da imagem. |
| `locations.*.files.{desktop,notebook,mobile}.media` | ver acima | Media query usada no `<source>` da imagem responsiva. |
| `locations.*.files.video.visible` | `false` | Exibe o campo de vídeo no formulário. |
| `locations.*.meta` | `[]` | Campos adicionais da localização (veja abaixo). |

### Campos adicionais (meta)

Os tipos suportados estão no enum `Agenciafmd\Banners\Enums\Meta`:

- `Meta::TEXT`
- `Meta::SELECT` (usa a chave `options`, no formato `valor => rótulo`)
- `Meta::REPEATER`

Campos com `type` inválido são ignorados. Os valores ficam disponíveis na chave `meta` de cada banner entregue à view.

Banners excluídos há mais de 30 dias são removidos definitivamente pelo `model:prune`, agendado diariamente às 03h (os minutos vêm de `filament-admix.schedule.minutes`).

## Uso

O pacote registra o componente Blade `<x-banner>`:

```blade
<x-banner quantity="3" location="home" :random="false" />
```

| Parâmetro | Padrão | Descrição |
|---|---|---|
| `quantity` | `3` | Quantidade de banners exibidos. |
| `location` | `home` | Localização definida em `locations`. |
| `random` | `false` | Ordem aleatória; quando `false`, usa a ordenação padrão do model. |
| `template` | `filament-banners::components.home` | View usada para renderizar os banners. |

Somente banners ativos da localização são exibidos. A view recebe `$banners`, em que cada item tem `name`, `meta`, `link`, `target`, `images` (media query => URL) e `video` (URL ou `null`):

```blade
@foreach ($banners as $banner)
    <h2>{{ $banner['meta']['title'] ?? $banner['name'] }}</h2>
@endforeach
```

O template padrão usa o componente `<x-frontend::link>`, que precisa existir no projeto; caso contrário, informe o seu próprio `template`.

## Permissões

O `BannerResource` entra automaticamente no controle de acesso por Grupos do Admix, com as permissões de visualizar, criar, editar, excluir, restaurar e auditoria. Usuário sem grupo é administrador e tem acesso total. Não há permissões extras.

## Auditoria

O `BannerResource` inclui o relation manager `Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager`, exibindo o histórico de auditorias do registro (o `tapp/filament-auditing` é instalado pelo `filament-admix`).

## Publicação de assets

Publique o arquivo de configuração para definir as localizações do projeto:

```bash
php artisan vendor:publish --tag="filament-banners:config"
```

## Licença

Este pacote é software livre e está disponível nos termos da licença MIT.
