<div class="col-md-12 mb-2">
    <div class="row justify-content-center filter align-items-end">
        <div class="col">
            <div class="form-group">
                <label class="form-label" for="district">District</label>
                <select name="district" class="form-control w-100 district" id="district">
                </select>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label class="form-label" for="exam_date">Exam Date</label>
                <select name="exam_date" class="form-control w-100 exam_date" id="exam_date">
                </select>
            </div>
        </div>
        @if (user()->role_id == 1)
            <div class="col">
                <div class="form-group">
                    <label class="form-label" for="team">@lang('Team')</label>
                    <select name="team" class="form-control w-100 team" id="team">
                        <option value="">All</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
            </div>
        @endif
        <div class="col">
            <div class="form-group">
                <a href="" class="btn btn-danger">Clear</a>
            </div>
        </div>
    </div>
</div>
