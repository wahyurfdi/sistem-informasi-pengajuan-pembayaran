<div class="page-title">
    <div class="row mb-2">
        <div class="col-12">
            <div class="w-100 d-flex">
                <div class="ms-auto d-flex">
                    <i class="bi bi-person-circle text-danger fs-2"></i>
                    <div class="d-flex flex-column ms-4 my-auto">
                        <span class="text-black">{{ Auth::user()->name }} ({{ Auth::user()->code }})</span>
                        <small>{{ config('app.role')[Auth::user()->role] }}</small>
                    </div>
                    <div class="btn-group mb-1">
                        <div class="dropdown">
                            <button
                                class="btn btn-link btn-sm dropdown-toggle text-secondary pt-0"
                                type="button"
                                id="dropdownMenuButton"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            </button>
                            <div class="dropdown-menu border" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Profile</a>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button class="btn btn-link dropdown-item text-danger text-decoration-none">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3 class="text-body">{{ $title }}</h3>
            <nav aria-label="breadcrumb" class="breadcrumb-header">
                <ol class="breadcrumb">
                    @foreach($breadcrumbs as $idx => $breadcrumb)
                    <li class="breadcrumb-item {{ $idx == (count($breadcrumbs)-1) ? 'active' : '' }}">
                        @if(!empty($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                        @else
                            {{ $breadcrumb['name'] }}
                        @endif
                    </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
</div>