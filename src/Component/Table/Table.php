<?php

namespace Ospnko\Hush\Component\Table;

use Closure;
use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Component\Input\Checkbox;
use Ospnko\Hush\HushBuilder;
use Ospnko\Hush\Interface\ComponentInterface;

/**
 * @template T
 */
class Table implements ComponentInterface
{
    /**
     * @param iterable<T> $rows
     * @param null|(Closure(row:T):string) $checkboxName
     * @param array<string,string> $attributes
     * @param array<
     *   array{
     *     condition:Closure(row:T),
     *     note:string,
     *     link:string|Closure(row:T):string,
     *     icon: string,
     *     isAsyncModal: bool
     *   }
     * > $actions
     * @param array<
     *   array{
     *     content:string|Closure(row:T,hush:HushBuilder):string
     *   }
     * > $columns
     * @param TableColumnHeader[] $headers
     */
    public function __construct(
        private iterable $rows,
        private bool $isCheckboxColumnEnabled = false,
        private ?Closure $checkboxName = null,
        private string $actionsHeader = '',
        private array $attributes = [],
        private array $actions = [],
        private array $columns = [],
        private array $headers = [],
    ) {
    }

    /**
     * @param string|Closure(row:T):string $link
     * @param Closure(row:T):bool $condition
     */
    public function addAction(
        string $note,
        string|callable $link,
        ?callable $condition = null,
        string $icon = '',
        bool $isAsyncModal = false,
    ): self {
        $this->actions[] = [
            'condition' => $condition,
            'note' => $note,
            'link' => $link,
            'icon' => $icon,
            'isAsyncModal' => $isAsyncModal,
        ];

        return $this;
    }

    /**
     * @param string|Closure(row:T,hush:HushBuilder):string $content
     */
    public function addColumn(string $header, string|callable $content): self
    {
        $this->columns[] = ['content' => $content];

        $this->headers[] = new TableColumnHeader(
            htmlspecialchars($header),
        );

        return $this;
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes))->render();

        $head = $this->renderHead();
        $body = $this->renderBody();

        return <<<HTML
        <table $attributes>
            <thead>
                <tr>
                    $head
                </tr>
            </thead>
            <tbody>
                $body
            </tbody>
        </table>
        HTML;
    }

    private function renderBody(): string
    {
        $result = [];

        foreach ($this->rows as $row) {
            $result[] = $this->renderBodyRow($row);
        }

        return implode("\n", $result);
    }

    /**
     * @param T $row
     */
    public function renderBodyRow(mixed $row): string
    {
        $result = [];

        if ($this->isCheckboxColumnEnabled) {
            $checkboxName = $this->checkboxName->__invoke($row);

            $result[] = (new TableColumn(
                content: (new Checkbox(
                    name: $checkboxName,
                    value: 1,
                    attributes: ['class' => 'margin-bottom-0'],
                ))->render()
            ))->render();
        }

        foreach ($this->columns as $column) {
            $result[] = (new TableColumn(
                !is_string($column['content'])
                    ? $column['content']($row, new HushBuilder())
                    : $column['content']
            ))->render();
        }

        if ($this->actions !== []) {
            $result[] = (new TableColumn(
                $this->renderBodyRowActions($row),
            ))->render();
        }

        return (new TableRow(implode("\n", $result)))->render();
    }

    /**
     * @param T $row
     */
    private function renderBodyRowActions(mixed $row): string
    {
        $result = [];

        foreach ($this->actions as $action) {
            if ($action['condition'] !== null && !$action['condition']($row)) {
                continue;
            }

            $result[] = (new TableAction(
                note: $action['note'],
                link: !is_string($action['link'])
                    ? $action['link']($row)
                    : $action['link'],
                isAsyncModal: $action['isAsyncModal'],
                icon: $action['icon'],
            ))->render();
        }

        $actionsString = implode("\n", $result);

        return <<<HTML
        <div class="actions">
            $actionsString
        </div>
        HTML;
    }

    private function renderHead(): string
    {
        $result = $this->isCheckboxColumnEnabled
            ? [
                (new TableColumnHeader(
                    (new Checkbox(
                        name: 'check-all',
                        attributes: ['class' => 'margin-bottom-0'],
                    ))->render(),
                ))->render(),
            ]
            : [];

        foreach ($this->headers as $header) {
            $result[] = $header->render();
        }

        if ($this->actionsHeader !== '') {
            $result[] = (new TableColumnHeader($this->actionsHeader))->render();
        }

        return implode("\n", $result);
    }
}
