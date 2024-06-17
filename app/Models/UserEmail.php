<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    use HasFactory;

    public const TYPE_SMTP = 1;
    public const TYPE_IMAP = 2;
    public const TYPE_POP3 = 3;
}
