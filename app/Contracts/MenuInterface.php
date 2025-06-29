<?php

namespace App\Contracts;

interface MenuInterface
{
    public function isMenuActive(Menu $menu): bool;
    public function hasActiveDescendant(Menu $menu): bool;
    public function setActiveMenu(int $menuId): void;
    public function handleMenuClick(int $menuId, ?string $url = null, string $target = '_self'): void;
    public function toggleMobileMenu(): void;
}