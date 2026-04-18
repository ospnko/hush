<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class TrendChart implements ComponentInterface
{
    /**
     * @param array<array{month: string, count: int}> $data
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $id,
        private string $label,
        private array $data,
        private string $color = '#3b82f6',
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $this->attributes['class'] = 'trend-chart-container' . (isset($this->attributes['class']) ? ' ' . $this->attributes['class'] : '');
        $attributes = (new Attribute($this->attributes))->render();

        $label = htmlspecialchars($this->label);
        $id = htmlspecialchars($this->id);
        $months = json_encode(array_column($this->data, 'month'));
        $counts = json_encode(array_column($this->data, 'count'));
        $color = htmlspecialchars($this->color);
        $bgColor = $this->hexToRgba($this->color, 0.1);

        return <<<HTML
        <script>
            if (!window.__chartJsLoaded) {
                window.__chartJsLoaded = true;
                var s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js';
                s.onload = function() { document.dispatchEvent(new Event('chartjs:ready')); };
                document.head.appendChild(s);
            }
        </script>
        <div $attributes>
            <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 600;">$label</h3>
            <canvas id="$id"></canvas>
            <script>
                (function() {
                    function initChart$id() {
                        new Chart(document.getElementById('$id'), {
                        type: 'line',
                        data: {
                            labels: $months,
                            datasets: [{
                                data: $counts,
                                borderColor: '$color',
                                backgroundColor: '$bgColor',
                                pointBackgroundColor: '$color',
                                fill: true,
                                tension: 0.4,
                            }]
                        },
                        options: {
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { grid: { display: false } },
                                y: { grid: { display: false }, min: 0, ticks: { precision: 0 } }
                            }
                        }
                    });
                    }
                    if (typeof Chart !== 'undefined') {
                        initChart$id();
                    } else {
                        document.addEventListener('chartjs:ready', initChart$id, { once: true });
                    }
                })();
            </script>
        </div>
        HTML;
    }

    private function hexToRgba(string $hex, float $alpha): string
    {
        $hex = ltrim($hex, '#');
        [$r, $g, $b] = array_map('hexdec', str_split($hex, 2));
        return "rgba($r, $g, $b, $alpha)";
    }
}
