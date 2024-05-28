<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Layout implements ComponentInterface
{
    public function __construct(
        private string $csrfToken,
        private string $cssPath,
        private string $cssColorsPath,
        private string $faviconPath,
        private string $faviconType,
        private string $fontsPath,
        private string $jsPath,
        private string $locale,
        private string $title,
        private string $content = '',
        private string $customEndBodyHtml = '',
        private string $customEndHeadHtml = '',
        private string $modalContent = '',
    ) {
        $this->csrfToken = htmlspecialchars($this->csrfToken);
        $this->cssPath = htmlspecialchars($this->cssPath);
        $this->cssColorsPath = htmlspecialchars($this->cssColorsPath);
        $this->faviconPath = htmlspecialchars($this->faviconPath);
        $this->faviconType = htmlspecialchars($this->faviconType);
        $this->jsPath = htmlspecialchars($this->jsPath);
        $this->locale = htmlspecialchars($this->locale);
        $this->title = htmlspecialchars($this->title);
    }

    public function render(): string
    {
        $fonts = '<style>' . $this->renderFontsCss() . '</style>';

        $modalContainerAttributes = (new Attribute(
            $this->modalContent !== ''
                ? ['style' => 'display: block']
                : []
        ))->render();

        return <<<HTML
        <!DOCTYPE html>
        <html lang="$this->locale">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=1280px, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="csrf-token" content="$this->csrfToken">

            <title>$this->title</title>

            <link rel="icon" type="$this->faviconType" href="$this->faviconPath">

            $fonts

            <link href="$this->cssColorsPath" rel="stylesheet">
            <link href="$this->cssPath" rel="stylesheet">

            $this->customEndHeadHtml
        </head>

        <body>
            <div>
                $this->content
            </div>

            <div class="modal-container" $modalContainerAttributes>
                <div>
                    $this->modalContent
                </div>
            </div>

            <script src="$this->jsPath/app.js"></script>
            $this->customEndBodyHtml
        </body>

        </html>
        HTML;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setModalContent(string $content): self
    {
        $this->modalContent = $content;

        return $this;
    }

    private function renderFontsCss(): string
    {
        return <<<CSS
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 100;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-Thin.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-Thin.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 100;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-ThinItalic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-ThinItalic.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 200;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-ExtraLight.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-ExtraLight.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 200;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-ExtraLightItalic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-ExtraLightItalic.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 300;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-Light.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-Light.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 300;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-LightItalic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-LightItalic.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 400;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-Regular.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-Regular.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 400;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-Italic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-Italic.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 500;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-Medium.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-Medium.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 500;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-MediumItalic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-MediumItalic.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 600;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-SemiBold.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-SemiBold.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 600;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-SemiBoldItalic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-SemiBoldItalic.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 700;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-Bold.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-Bold.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 700;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-BoldItalic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-BoldItalic.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 800;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-ExtraBold.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-ExtraBold.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 800;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-ExtraBoldItalic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-ExtraBoldItalic.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 900;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-Black.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-Black.woff?v=3.12") format("woff");
        }
        @font-face {
            font-family: 'Inter';
            font-style:  italic;
            font-weight: 900;
            font-display: swap;
            src: url("$this->fontsPath/Inter/Inter-BlackItalic.woff2?v=3.12") format("woff2"),
                url("$this->fontsPath/Inter/Inter-BlackItalic.woff?v=3.12") format("woff");
        }
        CSS;
    }
}
