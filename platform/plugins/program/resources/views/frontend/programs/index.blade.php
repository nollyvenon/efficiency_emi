<div class="program-filters mb-4">
    <form action="{{ route('public.programs.index') }}" method="GET">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                       placeholder="Search programs..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control"
                       value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>
</div>
