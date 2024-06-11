<?php

namespace Ospnko\Hush;

use Closure;
use Ospnko\Hush\Component\Block;
use Ospnko\Hush\Component\Breadcrumb\Breadcrumb;
use Ospnko\Hush\Component\Button;
use Ospnko\Hush\Component\Div;
use Ospnko\Hush\Component\Error;
use Ospnko\Hush\Component\Form;
use Ospnko\Hush\Component\Heading;
use Ospnko\Hush\Component\Image;
use Ospnko\Hush\Component\Input\Checkbox;
use Ospnko\Hush\Component\Input\Input;
use Ospnko\Hush\Component\Input\InputFile;
use Ospnko\Hush\Component\Input\InputImage;
use Ospnko\Hush\Component\Input\InputImageMultiple;
use Ospnko\Hush\Component\Input\InputMedia;
use Ospnko\Hush\Component\Input\InputSearch;
use Ospnko\Hush\Component\Input\Select;
use Ospnko\Hush\Component\Input\SelectMultiple;
use Ospnko\Hush\Component\Input\Textarea;
use Ospnko\Hush\Component\Label;
use Ospnko\Hush\Component\Layout;
use Ospnko\Hush\Component\Link;
use Ospnko\Hush\Component\Menu\Menu;
use Ospnko\Hush\Component\Modal;
use Ospnko\Hush\Component\Pagination;
use Ospnko\Hush\Component\RawHtml;
use Ospnko\Hush\Component\RawText;
use Ospnko\Hush\Component\Style;
use Ospnko\Hush\Component\Table\Table;
use Ospnko\Hush\Component\Text;
use Ospnko\Hush\Component\Video;
use Ospnko\Hush\Interface\ComponentInterface;
use Ospnko\Hush\Interface\RenderableInterface;

class HushBuilder implements RenderableInterface
{
    private ?Layout $layout = null;

    /** @var ComponentInterface[] */
    private array $components = [];

    /** @var ComponentInterface[] */
    private array $modals = [];

    /**
     * @param array<string,string> $attributes
     * @param callable($builder:self):self $content
     */
    public function block(
        callable $content,
        string $headline = '',
        array $attributes = []
    ): self {
        $this->components[] = new Block(
            headline: (new RawText($headline))->render(),
            content: $content(new self())->render(),
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param callable($builder:Breadcrumb):Breadcrumb $crumbs
     */
    public function breadcrumbs(callable $crumbs): self
    {
        $this->components[] = $crumbs(new Breadcrumb([]));

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     * @param callable($builder:self):self $content
     */
    public function button(
        string|callable $content,
        string $link = '',
        bool $isAsyncModal = false,
        array $attributes = [],
    ): self {
        if ($link !== '') {
            $attributes['class'] = 'button' . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

            $this->link(
                link: $link,
                content: $content,
                isAsyncModal: $isAsyncModal,
                attributes: $attributes,
            );

            return $this;
        }

        $attributes['data-is_form'] = '1';

        $this->components[] = new Button(
            content: is_string($content)
                ? (new RawText($content))->render()
                : $content(new self())->render(),
            isAsyncModal: $isAsyncModal,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     */
    public function checkbox(
        string $name,
        bool $isChecked = false,
        string $label = '',
        int|string $value = 1,
        array $attributes = [],
    ): self {
        $this->components[] = new Checkbox(
            name: $name,
            isChecked: $isChecked,
            label: $label,
            value: $value,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param string[] $errors
     */
    public function errors(array $errors): self
    {
        $this->components[] = new Error($errors);

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     * @param array<int,array<string,string>> $childAttributes
     * @param callable($builder:self):self ...$contents
     */
    public function flex(
        array $attributes = [],
        array $childAttributes = [],
        callable ...$contents,
    ): self {
        $toRender = [];

        foreach ($contents as $index => $content) {
            $toRender[] = new Div(
                attributes: $childAttributes[$index] ?? [],
                content: $content(new self())->render(),
            );
        }

        $attributes['class'] = 'flex' . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

        $this->components[] = new Div(
            content: $this->renderComponentsArray($toRender),
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     * @param callable($builder:self):self $content
     */
    public function form(
        string $method,
        string $action,
        callable $content,
        string $csrfField = '',
        array $attributes = [],
    ): self {
        $this->components[] = new Form(
            method: $method,
            action: $action,
            content: $content(new self())->render(),
            csrfField: $csrfField,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     */
    public function heading(string $text, array $attributes = []): self
    {
        $this->components[] = new Heading($text, $attributes);

        return $this;
    }

    public function html(string $content): self
    {
        $this->components[] = new RawHtml($content);

        return $this;
    }

    /**
     * @param callable($builder:self):self $content
     */
    public function if(
        bool $condition,
        callable $content,
    ): self {
        if (!$condition) {
            return $this;
        }

        $hushBuilder = $content(new self());

        if ($hushBuilder->components === []) {
            $this->modals[] = $hushBuilder;

            return $this;
        }

        $this->components[] = $hushBuilder;

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     */
    public function image(
        string $link,
        string $alt = '',
        array $attributes = [],
    ): self {
        $this->components[] = new Image(
            link: $link,
            alt: $alt,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     * @param string[] $errors
     */
    public function input(
        string $name,
        string $type = 'text',
        string $placeholder = '',
        string $value = '',
        bool $isRequired = false,
        array $attributes = [],
        array $errors = [],
    ): self {
        $this->components[] = new Input(
            type: $type,
            name: $name,
            placeholder: $placeholder,
            value: $value,
            isRequired: $isRequired,
            attributes: $attributes,
            errors: $errors,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     * @param array<string,string> $mainInputAttributes
     * @param callable(hush:HushBuilder):string $upperContent
     */
    public function inputFile(
        string $name,
        string $value,
        string $placeholder = '',
        string $asyncUrl = '',
        ?callable $upperContent = null,
        bool $isMultiple = false,
        array $errors = [],
        array $attributes = [],
        array $mainInputAttributes = [],
    ): self {
        $this->components[] = new InputFile(
            name: $name,
            value: $value,
            placeholder: $placeholder,
            asyncUrl: $asyncUrl,
            upperContent: $upperContent !== null
                ? $upperContent(new HushBuilder())
                : '',
            isMultiple: $isMultiple,
            errors: $errors,
            attributes: $attributes,
            mainInputAttributes: $mainInputAttributes,
        );

        return $this;
    }

    /**
     * @param string[] $errors
     */
    public function inputImage(
        string $name,
        string $image = '',
        string $placeholder = '',
        string $asyncUrl = '',
        array $errors = [],
    ): self {
        $this->components[] = new InputImage(
            name: $name,
            image: $image,
            placeholder: $placeholder,
            asyncUrl: $asyncUrl,
            errors: $errors,
        );

        return $this;
    }

    /**
     * @param string[] $images
     * @param string[] $deleted only for async inputs
     * @param string[] $errors
     */
    public function inputImageMultiple(
        string $name,
        array $images = [],
        array $deleted = [],
        string $placeholder = '',
        string $asyncUrl = '',
        bool $isReorderingEnabled = false,
        array $errors = [],
    ): self {
        $this->components[] = new InputImageMultiple(
            name: $name,
            images: $images,
            deleted: $deleted,
            placeholder: $placeholder,
            asyncUrl: $asyncUrl,
            isReorderingEnabled: $isReorderingEnabled,
            errors: $errors,
        );

        return $this;
    }

    /**
     * @param string[] $errors
     */
    public function inputMedia(
        string $name,
        string $media = '',
        bool $isVideo = false,
        string $placeholder = '',
        string $asyncUrl = '',
        array $errors = [],
    ): self {
        $this->components[] = new InputMedia(
            name: $name,
            media: $media,
            isVideo: $isVideo,
            placeholder: $placeholder,
            asyncUrl: $asyncUrl,
            errors: $errors,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     */
    public function inputSearch(
        string $name,
        string $type = 'text',
        string $placeholder = '',
        string $value = '',
        array $attributes = [],
    ): self {
        $this->components[] = new InputSearch(
            type: $type,
            name: $name,
            placeholder: $placeholder,
            value: $value,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     * @param string|callable($builder:self):self $content
     */
    public function label(
        string|callable $content,
        array $attributes = [],
    ): self {
        $this->components[] = new Label(
            content: is_string($content)
                ? (new RawText($content))->render()
                : $content(new self())->render(),
            attributes: $attributes,
        );

        return $this;
    }

    public function layout(
        string $csrfToken,
        string $cssPath,
        string $cssColorsPath,
        string $faviconPath,
        string $faviconType,
        string $fontsPath,
        string $jsPath,
        string $locale,
        string $title,
        string $customEndBodyHtml = '',
        string $customEndHeadHtml = '',
    ): self {
        $this->layout = new Layout(
            csrfToken: $csrfToken,
            cssPath: $cssPath,
            cssColorsPath: $cssColorsPath,
            faviconPath: $faviconPath,
            faviconType: $faviconType,
            fontsPath: $fontsPath,
            jsPath: $jsPath,
            locale: $locale,
            title: $title,
            customEndBodyHtml: $customEndBodyHtml,
            customEndHeadHtml: $customEndHeadHtml,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     * @param string|callable($builder:self):self $content
     */
    public function link(
        string $link,
        string|callable $content,
        bool $isAsyncModal = false,
        array $attributes = [],
    ): self {
        $this->components[] = new Link(
            link: $link,
            content: is_string($content)
                ? (new RawText($content))->render()
                : $content(new self())->render(),
            isAsyncModal: $isAsyncModal,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @template T
     *
     * @param T[] $rows
     * @param array<string,string> $attributes
     * @param string|callable($builder:self,$item:T):self $content
     */
    public function loop(array $rows, callable $content, array $attributes = []): self
    {
        $contents = [];

        foreach ($rows as $row) {
            $contents[] = $content(new self(), $row)->render();
        }

        $this->components[] = new Div(
            content: \implode("\n", $contents),
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     * @param string|callable($builder:Menu):Menu $content
     */
    public function menu(callable $content, array $attributes = []): self
    {
        $menu = new Menu(attributes: $attributes);

        $this->components[] = $content($menu);

        return $this;
    }

    /**
     * @param callable($builder:self):self $content
     * @param ?callable($builder:self):self $footer
     */
    public function modal(
        callable $content,
        string $title = '',
        ?callable $footer = null,
        bool $isHeadingShown = true,
    ): self {
        $this->modals[] = new Modal(
            content: $content(new self())->render(),
            title: $title,
            footer: $footer !== null
                ? $footer(new self())->render()
                : '',
            isHeadingShown: $isHeadingShown,
        );

        return $this;
    }

    public function pagination(
        int $currentPage,
        int $pages,
        string $baseLink,
        string $paramName = 'page',
        array $params = [],
    ): self {
        $this->components[] = new Pagination(
            currentPage: $currentPage,
            pages: $pages,
            baseLink: $baseLink,
            paramName: $paramName,
            params: $params,
        );

        return $this;
    }

    public function render(): string
    {
        $content = $this->renderRaw();

        return preg_replace([
            "/\n/",
            '/>(  )*/',
            '/[ ]*>/',
            '/[ ]*</',
        ], [
            '',
            '>',
            '>',
            '<',
        ], $content);
    }

    public function renderRaw(): string
    {
        $content = $this->renderComponentsArray($this->components);
        $modalContent = $this->renderComponentsArray($this->modals);

        if ($this->layout !== null) {
            return $this
                ->layout
                ->setContent($content)
                ->setModalContent($modalContent)
                ->render();
        }

        return $content !== ''
            ? $content
            : $modalContent;
    }

    /**
     * @param array<string|int,string> $options
     * @param string[] $errors
     */
    public function select(
        string $name,
        array $options = [],
        string|int $value = '',
        array $errors = [],
        string $placeholder = '',
        string $searchPlaceholder = '',
        bool $isRequired = false,
        bool $isSearchable = false,
        string $asyncSearchUrl = '',
    ): self {
        $this->components[] = new Select(
            name: $name,
            options: $options,
            value: $value,
            errors: $errors,
            placeholder: $placeholder,
            searchPlaceholder: $searchPlaceholder,
            isRequired: $isRequired,
            isSearchable: $isSearchable,
            asyncSearchUrl: $asyncSearchUrl,
        );

        return $this;
    }

    /**
     * @param array<string|int,string> $options
     * @param int[]|string[] $values
     * @param string[] $errors
     */
    public function selectMultiple(
        string $name,
        array $options = [],
        array $values = [],
        array $errors = [],
        string $placeholder = '',
        string $searchPlaceholder = '',
        bool $isRequired = false,
        bool $isSearchable = false,
    ): self {
        $this->components[] = new SelectMultiple(
            name: $name,
            options: $options,
            values: $values,
            errors: $errors,
            placeholder: $placeholder,
            searchPlaceholder: $searchPlaceholder,
            isRequired: $isRequired,
            isSearchable: $isSearchable,
        );

        return $this;
    }

    public function style(string $content): self
    {
        $this->components[] = new Style(content: $content);

        return $this;
    }

    /**
     * @template T
     *
     * @param T[] $rows
     * @param callable($table:Table):Table $table
     * @param null|(Closure(row:T):int|string) $identifier
     * @param array<string,string> $attributes
     * @return self
     */
    public function table(
        array $rows,
        callable $table,
        bool $isCheckboxColumnEnabled = false,
        ?Closure $checkboxName = null,
        string $actionsHeader = '',
        array $attributes = [],
    ): self {
        $this->components[] = $table(
            new Table(
                rows: $rows,
                isCheckboxColumnEnabled: $isCheckboxColumnEnabled,
                checkboxName: $checkboxName,
                actionsHeader: $actionsHeader,
                attributes: $attributes,
            ),
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     */
    public function text(
        string $text,
        array $attributes = [],
    ): self {
        $this->components[] = new Text(
            text: $text,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param string[] $errors
     * @param array<string,string> $attributes
     */
    public function textarea(
        string $name,
        string $value = '',
        string $placeholder = '',
        array $errors = [],
        array $attributes = [],
    ): self {
        $this->components[] = new Textarea(
            name: $name,
            value: $value,
            placeholder: $placeholder,
            errors: $errors,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param array<string,string> $attributes
     */
    public function video(
        string $source,
        array $attributes = [],
    ): self {
        $this->components[] = new Video(
            source: $source,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * @param ComponentInterface[] $components
     */
    private function renderComponentsArray(array $components): string
    {
        $result = '';

        foreach ($components as $component) {
            $result .= $component->render() . "\n";
        }

        return $result;
    }
}
