@extends('layouts.admin')
@section('content')
@can('announcement_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.announcements.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.announcement.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.announcement.title') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Announcement">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>{{ trans('cruds.announcement.fields.title') }}</th>
                    <th>{{ trans('cruds.announcement.fields.audience') }}</th>
                    <th>{{ trans('cruds.announcement.fields.status') }}</th>
                    <th>{{ trans('cruds.announcement.fields.is_pinned') }}</th>
                    <th>{{ trans('cruds.announcement.fields.published_at') }}</th>
                    <th>{{ trans('cruds.announcement.fields.expires_at') }}</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('announcement_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.announcements.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                    return entry.id
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    }).done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
@endcan

        let dtOverrideGlobals = {
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.announcements.index') }}",
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'title', name: 'title' },
                { data: 'audience', name: 'audience' },
                { data: 'status', name: 'status' },
                { data: 'is_pinned', name: 'is_pinned' },
                { data: 'published_at', name: 'published_at' },
                { data: 'expires_at', name: 'expires_at' },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 5, 'desc' ]],
            pageLength: 50,
        };

        $('.datatable-Announcement').DataTable(dtOverrideGlobals);
    });
</script>
@endsection
