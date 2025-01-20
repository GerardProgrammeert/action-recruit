@extends('app')

@section('title', 'gerard')

@section('content')
    <div class="container-fluid">
        <div class="row">zvczxc
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            asd
                        </h3>
                        <div class="panel-body">
                            <table
                                    class="table dt-responsive | js-datatable table-striped table-condensed"
                                    id="hardware-table"
                                    data-source="{{ route('profiles.table') }}"
                                    data-per-page="100"
                            >
                                <thead>
                                    <tr class="js-table-columns">
                                        <th data-name="id" data-default-sort="true"
                                            data-default-sort-order="asc">id</th>
                                        <th data-name="type">name</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection