<table>
    <tr>
        <th>SL</th>
        <th>Exam date</th>
        <th>Serial no</th>
        <th>Branch</th>
        <th>Name</th>
        <th>District</th>
        <th>DOB</th>
        <th>Team</th>
        <th>SSC Board</th>
        <th>SSC Bangla</th>
        <th>SSC English</th>
        <th>SSC Math</th>
        <th>SSC Physics</th>
        <th>SSC Biology</th>
        <th>SSC GPA</th>
        <th>Primary Medical</th>
        <th>Bangla</th>
        <th>English</th>
        <th>Math</th>
        <th>Science</th>
        <th>GK</th>
        <th>GT</th>
        <th>Final Medical</th>
        <th>Height</th>
        <th>Viva</th>
        <th>SSC Group</th>
        <th>Remark</th>
    </tr>

    @foreach ($applications as $key => $application)
        @php
            $age = \Carbon\Carbon::parse($application->dob)->age;
        @endphp
        <tr>
            <td>{{ @$x = +1 }}</td>
            <td>{{ bdDate($application->exam_date) }}</td>
            <td>{{ $application->serial_no }}</td>
            <td>{{ $application->candidate_designation }}</td>
            <td>{{ $application->name }}</td>
            <td>{{ $application->eligible_district }}</td>
            <td>{{ $age > 20 ? bdDate($application->dob) : '' }}</td>
            <td>{{ $application->team }}</td>
            <td>{{ $application->ssc_edu_board }}</td>
            <td>{{ $application->ssc_bangla }}</td>
            <td>{{ $application->ssc_english }}</td>
            <td>{{ $application->ssc_math }}</td>
            <td>{{ $application->ssc_physics }}</td>
            <td>{{ $application->ssc_biology }}</td>
            <td>{{ $application->ssc_gpa }}</td>
            <td>{!! result($application->is_medical_pass, $application->p_m_remark) !!}</td>
            <td>{{ $application->bangla }}</td>
            <td>{{ $application->english }}</td>
            <td>{{ $application->math }}</td>
            <td>{{ $application->science }}</td>
            <td>{{ $application->general_knowledge }}</td>
            <td>{{ $application->bangla + $application->english + $application->math + $application->science + $application->general_knowledge }}
            </td>
            <td>{!! result($application->is_medical_pass, $application->f_m_remark) !!}</td>
            <td>{{ $application->height }}</td>
            <td>{{ $application->viva }}</td>
            <td>{{ $application->ssc_group }}</td>
            <td>{{ $application->viva_remark }}</td>
        </tr>
    @endforeach
</table>
