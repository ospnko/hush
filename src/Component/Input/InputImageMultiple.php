<?php

namespace Ospnko\Hush\Component\Input;

use LogicException;
use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class InputImageMultiple implements ComponentInterface
{
    /**
     * @param string[] $images
     * @param string[] $deleted
     * @param string[] $errors
     */
    public function __construct(
        private string $name,
        private array $images = [],
        private array $deleted = [],
        private string $placeholder = '',
        private string $asyncUrl = '',
        private bool $isReorderingEnabled = false,
        private array $errors = [],
    ) {
    }

    public function render(): string
    {
        if ($this->isReorderingEnabled && $this->asyncUrl === '') {
            throw new LogicException('Reordering works only with async uploading');
        }

        $imagesString = $this->renderImages();

        return (new InputFile(
            name: $this->name,
            value: [
                'images' => array_diff($this->images, $this->deleted),
                'order' => $this->images,
                'deleted' => $this->deleted,
            ],
            placeholder: $this->placeholder,
            asyncUrl: $this->asyncUrl,
            upperContent: <<<HTML
                <div class="images">
                    $imagesString
                </div>
            HTML,
            isMultiple: true,
            errors: $this->errors,
            attributes: [
                'class' => 'multiple',
                'data-name' => $this->name,
                'data-template' => $this->renderControllableImage(),
                'data-value' => $this->buildOrderedJson(),
            ],
        ))->render();
    }

    private function buildOrderedJson(): string
    {
        $order = [];

        foreach ($this->images as $position => $image) {
            $order[$image] = $position;
        }

        $deleted = $this->deleted !== [] ? [] : (object) [];

        foreach ($this->deleted as $image) {
            $deleted[$image] = true;
        }

        return json_encode([
            'order' => $order === [] ? (object) [] : $order,
            'deleted' => $deleted ?? (object) [],
        ]);
    }

    private function renderImages(): string
    {
        $result = [];

        foreach ($this->images as $order => $image) {
            $html = str_replace(
                ['{image}', '{order}'],
                [$image, $order],
                $this->renderControllableImage(),
            );

            if (in_array($image, $this->deleted)) {
                $html = str_replace(
                    'class="container"',
                    'class="container removed"',
                    $html,
                );
            }

            $result[] = $html;
        }

        return implode("\n", $result);
    }

    private function renderControllableImage(): string
    {
        $arrowSvg = Svg::Arrow->render();
        $deleteSvg = Svg::Delete->render();

        $buttons = '';

        if ($this->asyncUrl !== '') {
            $buttons .= <<<HTML
            <a class="delete-image">$deleteSvg</a>
            HTML;
        }

        if ($this->isReorderingEnabled) {
            $buttons .= <<<HTML
            <a class="move-image-left">$arrowSvg</a>
            <a class="move-image-right">$arrowSvg</a>
            HTML;
        }

        return <<<HTML
        <div class="container" style="order: {order}">
            <div class="image-container">
                <img src="{image}" alt="">
            </div>
            <div class="controls">
                $buttons
            </div>
        </div>
        HTML;
    }
}
