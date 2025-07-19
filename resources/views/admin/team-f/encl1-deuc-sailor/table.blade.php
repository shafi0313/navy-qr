<div class="text-center mb-4">
    <h5>{{ strtoupper('Confidential') }}</h5>
    <h4>NOMINAL LIST OF DEUC SAILORS - B-2025 BATCH</h4>
    <h4>CENTER: BNS DHAKA, KHILKHET, DHAKA</h4>
</div>
<table class="table table-bordered mb-0 w-100">
    <thead>
        <tr>
            <th rowspan="2">Ser</th>
            <th rowspan="2">District</th>
            <th rowspan="2">Roll No</th>
            <th rowspan="2">Local No</th>
            <th rowspan="2">Name (English & Bangla)</th>
            <th rowspan="2">Rank (As Per Branch Seniority)</th>
            <th rowspan="2">GPA (SSC)</th>
            <th rowspan="2">Hight (Inch)</th>
            <th colspan="3">SSC Result</th>
            <th rowspan="2">Mobile No</th>
            <th colspan="2">HSC Pass</th>
            <th rowspan="2">Documents to be Submitted to BNS SHER-E-BANGLA</th>
        </tr>
        <tr>
            <th>Eng</th>
            <th>Math</th>
            <th>Phy</th>
            <th>Yes/ No</th>
            <th>GPA (If Applicable)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applications as $application)
            <tr>
                <td>{{ @$i += 1 }}</td>
                <td>{{ ucfirst($application->eligible_district) }}</td>
                <td>{{ $application->serial_no }}</td>
                <td></td>
                <td>{{ $application->name }}</td>
                <td>{{ $application->candidate_designation }}</td>
                <td>{{ $application->ssc_gpa }}</td>
                <td>{{ $application->height }}</td>
                <td>{{ str_replace('English : ', '', $application->ssc_english ?? '') }}</td>
                <td>{{ str_replace('Math : ', '', $application->ssc_math ?? '') }}</td>
                <td>{{ str_replace('Physics : ', '', $application->ssc_physics ?? '') }}</td>
                <td>{{ $application->current_phone }}</td>
                <td>{{ $application->hsc_dip_group ? 'Yes' : 'No' }}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
