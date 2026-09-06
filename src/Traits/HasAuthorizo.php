<?php

declare(strict_types=1);

namespace Authorizo\Authorizo\Traits;

use Authorizo\Authorizo\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAuthorizo
{
    public static function bootHasAuthorizo(): void
    {
        static::creating(function (Model $model): void {
            $roleId = Role::query()->where('slug', config('authorizo.roles.user', 'user'))->value('id');

            if ($roleId !== null) {
                $model->setAttribute('role_id', $roleId);
            }
        });
    }

    public function initializeHasAuthorizo(): void
    {
        $this->fillable[] = 'role_id';
    }

    /**
     * @return BelongsTo<Role, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
