<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Pagination implements ComponentInterface
{
    /**
     * @param array<string,mixed> $params
     */
    public function __construct(
        private int $currentPage,
        private int $pages,
        private string $baseLink,
        private string $paramName = 'page',
        private array $params = [],
    ) {
    }

    public function render(): string
    {
        if ($this->pages <= 1) {
            return '';
        }

        $pages = $this->renderPages();

        return <<<HTML
        <div class="pagination">
            $pages
        </div>
        HTML;
    }

    private function renderPages(): string
    {
        $result = [];

        foreach ($this->buildPages() as $page) {
            $params = $this->params;
            $params['page'] = $page;

            $result[] = (new Link(
                link: $page !== $this->currentPage
                    ? $this->baseLink . '?' . http_build_query($params)
                    : '#',
                content: $page,
            ))->render();
        }

        return implode("\n", $result);
    }

    /**
     * @return array<int|null>
     */
    private function buildPages(): array
    {
        if ($this->pages <= 10) {
            return range(1, $this->pages);
        }

        $isCurrentAtStart = $this->currentPage <= 3;

        $pages = $isCurrentAtStart
            ? [1, 2, 3, 4, 5]
            : [1, 2, 3];

        $pages[$isCurrentAtStart ? 5 : 3] = '...';

        if ($this->currentPage > 3) {
            for ($i = -1; $i <= 1; $i++) {
                if ($this->currentPage + $i < $this->pages) {
                    $pages[$this->currentPage + ($i - 1)] = $this->currentPage + $i;
                }
            }

            if ($this->currentPage + 1 < $this->pages) {
                $pages[$this->currentPage + 1] = '...';
            }
        }

        $pages[$this->pages - 2] = $this->pages - 1;
        $pages[$this->pages - 1] = $this->pages - 0;

        return $pages;
    }
}
