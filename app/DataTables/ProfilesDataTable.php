<?php

namespace App\DataTables;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProfilesDataTable extends DataTable
{
    protected array $actions = ['print', 'excel', 'myCustomAction'];

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'profiles.action')
            ->addColumn('hireable', function ($row) {
                $html = '<i class="bi bi-x text-danger fs-1"></i>';
                if ($row->hireable) {
                    $html = '<i class="bi bi-check fs-1"></i>';
                }
                return $html;
            })
            ->addColumn('linkedin', function ($row) {
                if (!$row->linkedin_links) {
                    return '';
                }

                return implode('<br/>', array_filter(array_map([$this, 'getFilteredLink'], $row->linkedin_links)));
            })
            ->addColumn('github', function ($row) {
                if (!$row->html_url) {
                    return '';
                }

                return $this->getLink($row->html_url);
            })
            ->addColumn('twitter', function ($row) {
                if (!$row->twitter_username) {
                    return '';
                }

                return $this->getLink('https://twitter.com/' . $row->twitter_username);
            })
            ->addColumn('blog', function ($row) {
                if (!$row->blog) {
                    return '';
                }

                return $this->getLink($row->blog);
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-primary" onClick="setIsDone(' . $row->id . ')">Done</button>';
            })
            ->setRowId('id')
            ->rawColumns(['linkedin', 'github', 'twitter', 'blog', 'hireable', 'linkedin', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Profile $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('is_done', false)
            ->orderBy('id', 'ASC');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('profiles-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    //->orderBy(1)
                    ->selectStyleSingle()
            ->parameters([
                             'dom' => 'Bfrtip',
                             'buttons' => ['reload', 'csv'],
                         ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                  ->exportable()
                  ->printable()
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('name'),
            Column::make('location'),
            Column::make('hireable')
                ->addClass('text-center'),
            Column::make('linkedin'),
            Column::make('github'),
            Column::make('twitter'),
            Column::make('email'),
            Column::make('blog'),
            Column::make('action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Profiles_' . date('YmdHis');
    }

    private function getFilteredLink(string $url): ?string
    {
        if (Str::contains($url, '/nl.', true)) {
            return $this->getLink($url);
        }

        return null;
    }

    private function getLink(string $url): string
    {
        return '<a href="' . $url . '" target="_blank">' . $url . '</a>';
    }
}
