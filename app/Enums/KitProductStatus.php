<?php

namespace App\Enums;

enum KitProductStatus
{
    case DRAFT;
    case ACTIVE;
    case ARCHIVED;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => trans('Draft'),
            self::ACTIVE => trans('Active'),
            self::ARCHIVED => trans('Archived'),
        };
    }
}