@extends('core/base::layouts.master')
<script>
    $.extend(true, $.fn.dataTable.defaults, {
        ajax: {
            type: 'GET'
        }
    });
</script>

@section('content')
    {!! $table->renderTable() !!}
@stop

