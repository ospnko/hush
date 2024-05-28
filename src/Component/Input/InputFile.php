<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Component\Error;
use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class InputFile implements ComponentInterface
{
    /**
     * @param string[] $errors
     */
    public function __construct(
        private string $name,
        private string|array $value,
        private string $placeholder = '',
        private string $asyncUrl = '',
        private string $upperContent = '',
        private bool $isMultiple = false,
        private array $errors = [],
        private array $attributes = [],
        private array $mainInputAttributes = [],
    ) {
    }

    public function render(): string
    {
        $uploadSvg = Svg::Upload->render();
        $errorsString = (new Error($this->errors))->render();

        $this->attributes['class'] = 'file ' . ($this->attributes['class'] ?? '');
        $attributes = (new Attribute($this->attributes))->render();

        $mainInputAttributes = (new Attribute($this->mainInputAttributes))->render();

        $name = $this->asyncUrl === '' ? $this->name : '';
        $formNoValidateTag = $this->asyncUrl === '' ? '' : 'formnovalidate';

        $asyncInput = $this->asyncUrl !== ''
            ? $this->buildAsyncInputRecursively($this->name, $this->value)
            : '';

        $multiple = $this->isMultiple ? 'multiple' : '';

        return <<<HTML
        <div $attributes>
            $this->upperContent
            <label class="input">
                $asyncInput
                <input
                    type="file"
                    name="$name"
                    data-async_url="$this->asyncUrl"
                    $formNoValidateTag
                    $multiple
                    $mainInputAttributes>
                $this->placeholder
                $uploadSvg
            </label>
        </div>
        $errorsString
        HTML;
    }

    private function buildAsyncInputRecursively(string $name, string|array $value): string
    {
        if (!is_array($value)) {
            return (new InputHidden($name, $value))->render() . PHP_EOL;
        }

        $result = '';

        foreach ($value as $key => $subValue) {
            $result .= $this->buildAsyncInputRecursively(
                name: $name . '[' . $key . ']',
                value: $subValue,
            );
        }

        return $result;
    }
}
