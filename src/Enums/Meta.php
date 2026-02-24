<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Enums;

enum Meta: string
{
    case TEXT = 'text';
    case SELECT = 'select';
    case REPEATER = 'repeater';
}
