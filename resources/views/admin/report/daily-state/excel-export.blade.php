<div class="text-center mb-4">
    <h3>DAILY RECRUITMENT STATE</h3>
    <h3>EXAMINATION CENTER: {{ $team }}</h3>
    <h3>Date: {{ Carbon\Carbon::parse($startDate)->format('d M Y') }} to
        {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</h3>
</div>
<br>
@include('admin.report.daily-state.table')