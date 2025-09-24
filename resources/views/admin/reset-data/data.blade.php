<input type="hidden" name="id" value="{{ $applicant->id }}">
<tr>
    <td>{{ bdDate($applicant->exam_date) }}</td>
    <td>{{ $applicant->serial_no }}</td>
    <td>{{ $applicant->candidate_designation }}</td>
    <td>{{ $applicant->name }}</td>
    <td>{{ $applicant->eligible_district }}</td>
    <td>
        <span>Med:
            @if (is_null($applicant->is_medical_pass))
                Pending
            @elseif ($applicant->is_medical_pass == 1)
                Fit
            @elseif ($applicant->is_medical_pass == 0)
                Unfit
            @endif
        </span> <br>
        <span>Written Mark: {!! written($applicant->examMark) !!}</span> <br>
        <span>Final Med:
            @if (empty($applicant->is_final_pass))
                Pending
            @elseif ($applicant->is_final_pass == 1)
                Fit
            @elseif ($applicant->is_final_pass == 0)
                Unfit
            @endif
        </span> <br>
        <span>Viva Mark:
            @if (empty($applicant->examMark->viva))
                Pending
            @else
                {{ $applicant->examMark->viva }}
                {{-- @elseif ($applicant->examMark->viva == 1)
                    Fit
                @elseif ($applicant->examMark->viva == 0)
                    Unfit --}}
            @endif
        </span>
    </td>
</tr>
