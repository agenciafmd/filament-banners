<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Enums;

enum Meta: string
{
    case Text = 'text';
    case Select = 'select';
    case Repeater = 'repeater';
}
