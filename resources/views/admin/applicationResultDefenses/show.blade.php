@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.applicationResultDefense.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-result-defenses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.application') }}
                        </th>
                        <td>
                            {{ $applicationResultDefense->application->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.result') }}
                        </th>
                        <td>
                            {{ App\Models\ApplicationResultDefense::RESULT_SELECT[$applicationResultDefense->result] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.note') }}
                        </th>
                        <td>
                            {{ $applicationResultDefense->note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.revision_deadline') }}
                        </th>
                        <td>
                            {{ $applicationResultDefense->revision_deadline }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.final_grade') }}
                        </th>
                        <td>
                            {{ $applicationResultDefense->final_grade }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.documentation') }}
                        </th>
                        <td>
                            @foreach($applicationResultDefense->documentation as $key => $media)
                                <button type="button" class="btn btn-sm btn-primary preview-doc mr-2 mb-1" data-url="{{ $media->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.invitation_document') }}
                        </th>
                        <td>
                            @foreach($applicationResultDefense->invitation_document as $key => $media)
                                <button type="button" class="btn btn-sm btn-primary preview-doc mr-2 mb-1" data-url="{{ $media->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.feedback_document') }}
                        </th>
                        <td>
                            @foreach($applicationResultDefense->feedback_document as $key => $media)
                                <button type="button" class="btn btn-sm btn-primary preview-doc mr-2 mb-1" data-url="{{ $media->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.minutes_document') }}
                        </th>
                        <td>
                            @if($applicationResultDefense->minutes_document)
                                <button type="button" class="btn btn-sm btn-primary preview-doc" data-url="{{ $applicationResultDefense->minutes_document->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.latest_script') }}
                        </th>
                        <td>
                            @if($applicationResultDefense->latest_script)
                                <button type="button" class="btn btn-sm btn-primary preview-doc" data-url="{{ $applicationResultDefense->latest_script->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.approval_page') }}
                        </th>
                        <td>
                            @if($applicationResultDefense->approval_page)
                                <button type="button" class="btn btn-sm btn-primary preview-doc" data-url="{{ $applicationResultDefense->approval_page->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.report_document') }}
                        </th>
                        <td>
                            @foreach($applicationResultDefense->report_document as $key => $media)
                                <button type="button" class="btn btn-sm btn-primary preview-doc mr-2 mb-1" data-url="{{ $media->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.revision_approval_sheet') }}
                        </th>
                        <td>
                            @foreach($applicationResultDefense->revision_approval_sheet as $key => $media)
                                <button type="button" class="btn btn-sm btn-primary preview-doc mr-2 mb-1" data-url="{{ $media->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Daftar Hadir
                        </th>
                        <td>
                            @if($applicationResultDefense->attendance_document)
                                <button type="button" class="btn btn-sm btn-primary preview-doc" data-url="{{ $applicationResultDefense->attendance_document->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Form Penilaian
                        </th>
                        <td>
                            @if($applicationResultDefense->form_document)
                                <button type="button" class="btn btn-sm btn-primary preview-doc" data-url="{{ $applicationResultDefense->form_document->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Lembar Pengesahan
                        </th>
                        <td>
                            @if($applicationResultDefense->certificate_document)
                                <button type="button" class="btn btn-sm btn-primary preview-doc" data-url="{{ $applicationResultDefense->certificate_document->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Bukti Publikasi
                        </th>
                        <td>
                            @if($applicationResultDefense->publication_document)
                                <button type="button" class="btn btn-sm btn-primary preview-doc" data-url="{{ $applicationResultDefense->publication_document->getUrl() }}">
                                    <i class="fas fa-eye mr-1"></i> {{ trans('global.view_file') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-result-defenses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-file-pdf mr-2"></i>Preview Dokumen
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(document).ready(function() {
    $('.preview-doc').on('click', function() {
        const url = $(this).data('url');
        $('#pdfViewer').attr('src', url);
        $('#previewModal').modal('show');
    });

    $('#previewModal').on('hidden.bs.modal', function () {
        $('#pdfViewer').attr('src', '');
    });
});
</script>
@endsection