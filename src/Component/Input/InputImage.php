<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Image;
use Ospnko\Hush\Interface\ComponentInterface;

class InputImage implements ComponentInterface
{
    /**
     * @param string[] $errors
     */
    public function __construct(
        private string $name,
        private string $image = '',
        private string $placeholder = '',
        private string $asyncUrl = '',
        private array $errors = [],
    ) {
    }

    public function render(): string
    {
        $imageString = (new Image(link: $this->image))->render();

        return (new InputFile(
            name: $this->name,
            value: $this->image,
            placeholder: $this->placeholder,
            asyncUrl: $this->asyncUrl,
            upperContent: $imageString,
            errors: $this->errors,
        ))->render();
    }
}
