<?php

namespace App\Models\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait UserScopes
{
    public function scopeAdmins(Builder $builder): Builder
    {
        return $builder->whereHas('roles', fn ($query) => $query->whereIn('name', ['Super Admin', 'Admin']));
    }
}
