<?php

namespace App\Services;

use App\Models\User;

class MenuService
{
    public function filter(array $menus, ?User $user = null): array
    {
        return collect($menus)
            ->map(function ($menu) use ($user) {
                if (isset($menu->submenu)) {
                    $menu->submenu = $this->filter(
                        $menu->submenu,
                        $user
                    );
                }

                return $menu;
            })
            ->filter(function ($menu) use ($user) {
                // Restricted menu
                if (isset($menu->roles)) {
                    return $user &&
                        collect($menu->roles)
                            ->contains(
                                fn ($role) => $user->isRole($role)
                            );
                }

                // Remove empty parent
                if (isset($menu->submenu)) {
                    return count($menu->submenu) > 0;
                }

                return true;
            })
            ->values()
            ->all();
    }
}