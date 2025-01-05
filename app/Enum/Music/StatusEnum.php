<?php

namespace App\Enum\Music;

enum StatusEnum: string
{
    case APPROVED = "approved";
    case PENDING = "pending";
    case REJECTED = "rejected";

    public static function options() {
        return [
            self::APPROVED->value => 'Aprovado',
            self::PENDING->value => 'Pendente',
            self::REJECTED->value => 'Rejeitado',
        ];
    }
}
