<?php
declare(strict_types=1);

namespace model\enum;

enum MessageStatus : string
{
    case READ = 'read';
    case UNREAD = 'unread';

    public function label() : string
    {
        return match($this) {
            self::READ => 'Lu',
            self::UNREAD => 'Unread'
        };
    }
}