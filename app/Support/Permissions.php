<?php

namespace App\Support;

/**
 * Canonical list of admin-panel permissions. Single source of truth shared by
 * the roles seeder and the Inertia prop sharing (kept in app/ so it autoloads
 * in production, unlike database/seeders under --no-dev).
 */
class Permissions
{
    public const ALL = [
        'access admin',
        'manage products',
        'manage categories',
        'manage services',
        'manage blog',
        'manage orders',
        'manage quotes',
        'manage users',
        'view audit',
    ];
}
