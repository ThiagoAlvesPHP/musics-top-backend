<?php

namespace App\Enum\Music;

enum StatusEnum: string
{
    case APPROVED = "approved";
    case PENDING = "pending";
    case REJECTED = "rejected";
}
