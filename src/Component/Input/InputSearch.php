<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Enum\Svg;

class InputSearch extends Input
{
    public function render(): string
    {
        $input = parent::render();

        $clearSvg = Svg::Clear->render();
        $searchSvg = Svg::Search->render();

        return <<<HTML
        <div class="search-input margin-bottom-0">
            <div class="search">
                $searchSvg
            </div>
            $input
            <div class="clear">
                $clearSvg
            </div>
        </div>
        HTML;
    }
}
