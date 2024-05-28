<?php

namespace Ospnko\Hush\Component\Menu;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Interface\ComponentInterface;

class Menu implements ComponentInterface
{
    /**
     * @param MenuItem[] $items
     */
    private array $items = [];

    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private bool $isSubmenu = false,
        private array $attributes = [],
    ) {
    }

    /**
     * @param array<string,string> $attributes
     * @param null|callable(menu:self):self $submenu
     */
    public function addItem(
        string $text,
        string $link = '',
        bool $isActive = false,
        array $attributes = [],
        ?callable $submenu = null,
    ): self {
        $this->items[] = new MenuItem(
            link: $link,
            text: $text,
            isActive: $isActive,
            attributes: $attributes,
            submenu: $submenu !== null
                ? $submenu(new self(isSubmenu: true))
                : null,
        );

        return $this;
    }

    public function render(): string
    {
        if ($this->isSubmenu) {
            $this->attributes['class'] = 'submenu ' . ($this->attributes['class'] ?? '');
        }

        $attributes = (new Attribute($this->attributes))->render();

        $renderedItems = implode("\n", array_map(
            array: $this->items,
            callback: fn (MenuItem $item) => $item->render(),
        ));

        if (!$this->isSubmenu) {
            return <<<HTML
            <menu $attributes>
                $renderedItems
            </menu>
            HTML;
        }

        if ($this->items === []) {
            return '';
        }

        return <<<HTML
        <ul $attributes>
            $renderedItems
        </ul>
        HTML;
    }
}
