<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Component\Error;
use Ospnko\Hush\Enum\Svg;

class InputSearch extends Input
{
    public function render(): string
    {
        $inputAttributes = $this->attributes;
        unset($inputAttributes['class']); // Prevent double classing

        $attributes = (new Attribute($inputAttributes))->render();
        $errorsString = (new Error($this->errors))->render();
        $escapedValue = htmlspecialchars($this->value);
        $escapedPlaceholder = htmlspecialchars($this->placeholder);

        $clearSvg = Svg::Clear->render();
        $searchSvg = Svg::Search->render();

        $wrapperAttributes = (new Attribute($this->attributes))->render();

        return <<<HTML
        <div $wrapperAttributes>
            <div class="search">
                $searchSvg
            </div>
            <input type="$this->type" name="$this->name" value="$escapedValue" placeholder="$escapedPlaceholder" $attributes>
            $errorsString
            <div class="clear">
                $clearSvg
            </div>
        </div>
        HTML;
    }
}
