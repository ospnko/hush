<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Component\Breadcrumb\Breadcrumb;
use Ospnko\Hush\Component\Menu\Menu;
use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class TitleBlock implements ComponentInterface
{
    public function __construct(
        private readonly string $menuHtml,
        private readonly string $username,
        private readonly string $userRole,
        private readonly string $breadcrumbsHtml,
        private readonly string $logoutLink,
        private readonly string $csrfToken,
        private readonly string $themeToggleLink = '',
        private readonly string $themeToggleLabel = '',
        private readonly string $themeToggleClass = '',
    ) {
    }

    private function renderThemeToggle(): string
    {
        if ($this->themeToggleLink === '') {
            return '';
        }

        $link  = htmlspecialchars($this->themeToggleLink);
        $label = htmlspecialchars($this->themeToggleLabel);
        $class = htmlspecialchars($this->themeToggleClass);

        return <<<HTML
        <a href="{$link}" class="theme-toggle {$class}" title="Toggle Theme">
            <span class="toggle-thumb">{$label}</span>
        </a>
        HTML;
    }

    public function render(): string
    {
        $logoutIcon = Svg::Logout->render();
        $logoutLink = htmlspecialchars($this->logoutLink);
        $username = htmlspecialchars($this->username);
        $userRole = htmlspecialchars($this->userRole);
        $csrfToken = htmlspecialchars($this->csrfToken);

        return <<<HTML
        {$this->menuHtml}
        <section class="breadcrumb-block block">
            <div class="breadcrumb-container">
                {$this->breadcrumbsHtml}
                <div class="breadcrumb-user-actions">
                    <div class="action-pill">
                        {$this->renderThemeToggle()}
                        <div class="user-actions">
                            <div class="user-info">
                                <span class="user-name">{$username}</span>
                                <span class="user-role">{$userRole}</span>
                            </div>
                            <form action="{$logoutLink}" method="POST" style="display:inline">
                                <input type="hidden" name="_token" value="{$csrfToken}">
                                <button type="submit" class="logout-btn button-red" title="Logout">
                                    {$logoutIcon}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        HTML;
    }
}
