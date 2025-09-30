@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.skripsiDefense.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.skripsi-defenses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.application') }}
                        </th>
                        <td>
                            {{ $skripsiDefense->application->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.title') }}
                        </th>
                        <td>
                            {{ $skripsiDefense->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.abstract') }}
                        </th>
                        <td>
                            {{ $skripsiDefense->abstract }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.defence_document') }}
                        </th>
                        <td>
                            @if($skripsiDefense->defence_document)
                                <a href="{{ $skripsiDefense->defence_document->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.plagiarism_report') }}
                        </th>
                        <td>
                            @if($skripsiDefense->plagiarism_report)
                                <a href="{{ $skripsiDefense->plagiarism_report->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.ethics_statement') }}
                        </th>
                        <td>
                            @foreach($skripsiDefense->ethics_statement as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.research_instruments') }}
                        </th>
                        <td>
                            @foreach($skripsiDefense->research_instruments as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.data_collection_letter') }}
                        </th>
                        <td>
                            @foreach($skripsiDefense->data_collection_letter as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.research_module') }}
                        </th>
                        <td>
                            @foreach($skripsiDefense->research_module as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.mbkm_recommendation_letter') }}
                        </th>
                        <td>
                            @if($skripsiDefense->mbkm_recommendation_letter)
                                <a href="{{ $skripsiDefense->mbkm_recommendation_letter->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.publication_statement') }}
                        </th>
                        <td>
                            @if($skripsiDefense->publication_statement)
                                <a href="{{ $skripsiDefense->publication_statement->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.defense_approval_page') }}
                        </th>
                        <td>
                            @foreach($skripsiDefense->defense_approval_page as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.spp_receipt') }}
                        </th>
                        <td>
                            @if($skripsiDefense->spp_receipt)
                                <a href="{{ $skripsiDefense->spp_receipt->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.krs_latest') }}
                        </th>
                        <td>
                            @if($skripsiDefense->krs_latest)
                                <a href="{{ $skripsiDefense->krs_latest->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.eap_certificate') }}
                        </th>
                        <td>
                            @if($skripsiDefense->eap_certificate)
                                <a href="{{ $skripsiDefense->eap_certificate->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.transcript') }}
                        </th>
                        <td>
                            @if($skripsiDefense->transcript)
                                <a href="{{ $skripsiDefense->transcript->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.mbkm_report') }}
                        </th>
                        <td>
                            @foreach($skripsiDefense->mbkm_report as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.research_poster') }}
                        </th>
                        <td>
                            @foreach($skripsiDefense->research_poster as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.siakad_supervisor_screenshot') }}
                        </th>
                        <td>
                            @if($skripsiDefense->siakad_supervisor_screenshot)
                                <a href="{{ $skripsiDefense->siakad_supervisor_screenshot->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.skripsiDefense.fields.supervision_logbook') }}
                        </th>
                        <td>
                            @foreach($skripsiDefense->supervision_logbook as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.skripsi-defenses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection