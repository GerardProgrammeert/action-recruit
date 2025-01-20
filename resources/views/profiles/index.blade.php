@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="card d-flex">
            <div class="card-header">Manage Profiles</div>
            <div class="card-body">
                {!! $dataTable->table(['class' => 'table table-bordered']) !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush


@push('scripts')
<script>
    function setIsDone(id) {
        var url = '{{ route('profiles.done', ':profile') }}';
        url = url.replace(':profile', id);

        axios.post(url)
            .then(function (response) {
                window.location.reload(true);
                //alert(response.data.message);
                //$('#profileDataTable').DataTable().ajax.reload(); // Reload DataTable
            })
            .catch(function (error) {
                console.error(error);
            });
    }
</script>
@endpush