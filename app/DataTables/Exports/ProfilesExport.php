<?php

declare(strict_types=1);

namespace App\DataTables\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class ProfilesExport extends DataTablesCollectionExport implements WithMapping
{
    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->created_at->format('Y-m-d'),
        ];
    }
}
