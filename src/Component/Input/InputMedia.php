<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Image;
use Ospnko\Hush\Component\Video;
use Ospnko\Hush\Interface\ComponentInterface;

class InputMedia implements ComponentInterface
{
    /**
     * @param string[] $errors
     */
    public function __construct(
        private string $name,
        private string $media = '',
        private bool $isVideo = false,
        private string $placeholder = '',
        private string $asyncUrl = '',
        private array $errors = [],
    ) {
    }

    public function render(): string
    {
        $videoString = (new Video(
            source: $this->media,
            attributes: $this->isVideo ? [] : ['style' => 'display: none'],
        ))->render();

        $imageString = (new Image(
            link: $this->media,
            attributes: !$this->isVideo ? [] : ['style' => 'display: none'],
        ))->render();

        return (new InputFile(
            name: $this->name,
            value: $this->media,
            placeholder: $this->placeholder,
            asyncUrl: $this->asyncUrl,
            upperContent: $imageString . $videoString,
            errors: $this->errors,
            mainInputAttributes: [
                'data-is_video_available' => '1',
            ],
        ))->render();
    }
}
