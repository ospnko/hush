<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Attribute implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private array $attributes,
        private string $delimiter = ' ',
    ) {
    }

    public function render(): string
    {
        $results = [];

        foreach ($this->attributes as $attribute => $value) {
            $results[] = $attribute . '="' . htmlspecialchars($value) . '"';
        }

        return implode($this->delimiter, $results);
    }
}
