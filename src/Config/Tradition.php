<?php

declare(strict_types=1);

namespace App\Config;

enum Tradition : string {
    case european = 'european';
    case african = 'african';
    case latin_american = 'latin_american';
    case chinese = 'chinese';
    case indian = 'indian';
    case islamicate = 'islamicate';
    case other = 'other';

    public function label() : string {
        return match ($this) {
            self::european => 'European',
            self::african => 'African',
            self::latin_american => 'Latin American',
            self::chinese => 'Chinese',
            self::indian => 'Indian',
            self::islamicate => 'Islamicate',
            self::other => 'Other',
        };
    }
}
