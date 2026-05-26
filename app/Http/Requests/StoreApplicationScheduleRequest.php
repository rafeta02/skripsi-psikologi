<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_schedule_create');
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('date') && $this->filled('time')) {
            $dt = Carbon::createFromFormat('Y-m-d H:i', $this->input('date') . ' ' . $this->input('time'));
            $this->merge([
                'waktu' => $dt->format(config('panel.date_format') . ' ' . config('panel.time_format')),
            ]);
        } elseif ($this->filled('waktu') && preg_match('/^\d{4}-\d{2}-\d{2}/', $this->input('waktu'))) {
            $this->merge([
                'waktu' => Carbon::parse($this->input('waktu'))->format(config('panel.date_format') . ' ' . config('panel.time_format')),
            ]);
        }

        if ($this->filled('online_link') && !$this->filled('online_meeting')) {
            $this->merge(['online_meeting' => $this->input('online_link')]);
        }

        if ($this->filled('notes') && !$this->filled('note')) {
            $this->merge(['note' => $this->input('notes')]);
        }

        if ($this->input('location_type') === 'online') {
            $this->merge(['ruang_id' => null]);
        }
    }

    public function rules()
    {
        return [
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'schedule_type' => [
                'required',
                'in:seminar,defense,skripsi_seminar,mbkm_seminar,skripsi_defense',
            ],
            'waktu' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'custom_place' => [
                'string',
                'nullable',
            ],
            'online_meeting' => [
                'string',
                'nullable',
            ],
        ];
    }

    public function messages()
    {
        return [
            'application_id.required' => 'Aplikasi skripsi wajib dipilih.',
            'application_id.exists' => 'Aplikasi skripsi tidak valid.',
            'schedule_type.required' => 'Tipe jadwal wajib dipilih.',
            'schedule_type.in' => 'Tipe jadwal tidak valid. Pilih salah satu: Seminar atau Sidang.',
            'waktu.required' => 'Waktu pelaksanaan wajib diisi.',
            'waktu.date_format' => 'Format waktu tidak valid.',
        ];
    }
}
