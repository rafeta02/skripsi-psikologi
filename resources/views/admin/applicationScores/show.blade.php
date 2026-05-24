@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.applicationScore.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-scores.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationScore.fields.application_result_defence') }}
                        </th>
                        <td>
                            {{ $applicationScore->application_result_defence->result ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationScore.fields.examiner') }}
                        </th>
                        <td>
                            {{ $applicationScore->examiner->nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Penulisan
                        </th>
                        <td>
                            {{ $applicationScore->penulisan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Isi
                        </th>
                        <td>
                            {{ $applicationScore->isi }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Analisis
                        </th>
                        <td>
                            {{ $applicationScore->analisis }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Teoritis
                        </th>
                        <td>
                            {{ $applicationScore->teoritis }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Faktual
                        </th>
                        <td>
                            {{ $applicationScore->faktual }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Pemecahan Masalah
                        </th>
                        <td>
                            {{ $applicationScore->pemecahan_masalah }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Penyampaian
                        </th>
                        <td>
                            {{ $applicationScore->penyampaian }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Sum
                        </th>
                        <td>
                            {{ $applicationScore->sum }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationScore.fields.score') }}
                        </th>
                        <td>
                            {{ $applicationScore->score }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationScore.fields.note') }}
                        </th>
                        <td>
                            {{ $applicationScore->note }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-scores.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection