<?php

declare(strict_types=1);

namespace App\Helpers\CSV;

use Closure;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class CSVExporter
{
    private bool $isHeaderSet = false;

    public function __construct(
        private readonly iterable $data,
        private readonly string $path,
        private readonly ?Closure $transformer = null
    )
    {
    }

    public function export(): void
    {
        $handle = fopen($this->path, 'w');
        foreach ($this->data as $row) {
            if (empty($row)) {
                continue;
            }
            if (!$this->isHeaderSet) {
               $this->addHeader($handle, $this->transform($row));
            }
            fputcsv($handle, $this->transform($row));
        }
        fclose($handle);
    }

    private function getHeader(array $data): array
    {
        return array_keys($data);
    }

    private function addHeader($handle, $data): void
    {
        $header = $this->getHeader($data);
        fputcsv($handle, $header);
        $this->isHeaderSet = true;
    }

    private function transform(mixed $row): array
    {
        if ($this->transformer) {
           return call_user_func($this->transformer, $row);
        }

        if ($row instanceof Model) {
            return $row->toArray();
        }

        if (is_array($row)) {
            return $row;
        }

        throw new InvalidArgumentException('The provided data is not supported. Use the supply a transformer instead.');
    }
}
