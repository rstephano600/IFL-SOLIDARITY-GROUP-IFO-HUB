<!-- ================================================================ -->
<!-- FILE: resources/views/employees/index.blade.php -->
<!-- ================================================================ -->
@extends('layouts.app')

@section('title', 'Wafanyakazi')
@section('page-title', 'Orodha ya Wafanyakazi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashibodi</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Wafanyakazi</a></li>
    <li class="breadcrumb-item active">{{ $employee->full_name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Rudi
            </a>
            <div>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Hariri
                </a>
                <form action="{{ route('employees.toggle-status', $employee) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-{{ $employee->is_active ? 'secondary' : 'success' }}">
                        <i class="bi bi-toggle-{{ $employee->is_active ? 'off' : 'on' }}"></i>
                        {{ $employee->is_active ? 'Simamisha' : 'Anzisha' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $employee->profile_picture_url }}" 
                         alt="{{ $employee->full_name }}" 
                         class="rounded-circle mb-3" 
                         width="150" height="150"
                         style="object-fit: cover; border: 5px solid #ddd;">
                    <h4 class="mb-1">{{ $employee->full_name }}</h4>
                    <p class="text-muted mb-2">{{ $employee->position }}</p>
                    <span class="badge bg-info mb-3">{{ $employee->department }}</span>
                    
                    @if($employee->is_active)
                        <span class="badge bg-success d-block mb-2">
                            <i class="bi bi-check-circle"></i> Hai
                        </span>
                    @else
                        <span class="badge bg-secondary d-block mb-2">
                            <i class="bi bi-x-circle"></i> Hayupo Kazini
                        </span>
                    @endif

                    <hr>

                    <div class="text-start">
                        <p class="mb-2">
                            <i class="bi bi-person-badge text-primary"></i>
                            <strong>Namba:</strong> {{ $employee->employee_id }}
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-envelope text-primary"></i>
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $employee->user->email }}">{{ $employee->user->email }}</a>
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-phone text-primary"></i>
                            <strong>Simu:</strong><br>
                            {{ $employee->user->phone ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-calendar text-primary"></i>
                            <strong>Umri:</strong> {{ $employee->age }} miaka
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-briefcase text-primary"></i>
                            <strong>Muda wa Kazi:</strong> {{ $employee->years_of_service }} miaka
                        </p>
                    </div>

                    @if($employee->cv_url)
                        <hr>
                        <a href="{{ $employee->cv_url }}" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Pakua CV
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Takwimu</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Cheo:</span>
                        <strong>{{ ucfirst(str_replace('_', ' ', $employee->user->role)) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Hali ya Akaunti:</span>
                        <span class="badge bg-{{ $employee->user->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($employee->user->status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tarehe ya Kuajiriwa:</span>
                        <strong>{{ $employee->date_of_hire->format('d/m/Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Alisajiliwa:</span>
                        <strong>{{ $employee->created_at->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Taarifa Binafsi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Jinsia</label>
                            <p class="mb-0"><strong>{{ ucfirst($employee->gender) }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tarehe ya Kuzaliwa</label>
                            <p class="mb-0"><strong>{{ $employee->date_of_birth->format('d/m/Y') }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Hali ya Ndoa</label>
                            <p class="mb-0"><strong>{{ $employee->marital_status ? ucfirst($employee->marital_status) : 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">NIDA</label>
                            <p class="mb-0"><strong>{{ $employee->nida ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Kabila</label>
                            <p class="mb-0"><strong>{{ $employee->tribe ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Dini</label>
                            <p class="mb-0"><strong>{{ $employee->religion ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Anwani</label>
                            <p class="mb-0"><strong>{{ $employee->address ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Kiwango cha Elimu</label>
                            <p class="mb-0"><strong>{{ $employee->education_level ?? 'N/A' }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-briefcase"></i> Taarifa za Kazi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nafasi</label>
                            <p class="mb-0"><strong>{{ $employee->position }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Idara</label>
                            <p class="mb-0"><strong>{{ $employee->department }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tarehe ya Kuajiriwa</label>
                            <p class="mb-0"><strong>{{ $employee->date_of_hire->format('d/m/Y') }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Miaka ya Huduma</label>
                            <p class="mb-0"><strong>{{ $employee->years_of_service }} miaka</strong></p>
                        </div>
                        @if($employee->other_information)
                        <div class="col-12">
                            <label class="text-muted small">Maelezo Mengine</label>
                            <p class="mb-0">{{ $employee->other_information }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Next of Kin -->
            @if($employee->nextOfKin)
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-person-hearts"></i> Jamaa wa Karibu (Next of Kin)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Jina Kamili</label>
                            <p class="mb-0"><strong>{{ $employee->nextOfKin->full_name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Jinsia</label>
                            <p class="mb-0"><strong>{{ $employee->nextOfKin->gender ? ucfirst($employee->nextOfKin->gender) : 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Barua Pepe</label>
                            <p class="mb-0">
                                <strong>
                                    @if($employee->nextOfKin->email)
                                        <a href="mailto:{{ $employee->nextOfKin->email }}">{{ $employee->nextOfKin->email }}</a>
                                    @else
                                        N/A
                                    @endif
                                </strong>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Simu</label>
                            <p class="mb-0"><strong>{{ $employee->nextOfKin->phone ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Anwani</label>
                            <p class="mb-0"><strong>{{ $employee->nextOfKin->address ?? 'N/A' }}</strong></p>
                        </div>
                        @if($employee->nextOfKin->other_informations)
                        <div class="col-12">
                            <label class="text-muted small">Maelezo Mengine</label>
                            <p class="mb-0">{{ $employee->nextOfKin->other_informations }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Referees -->
            <div class="card">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Washauri (Referees)</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addRefereeModal">
                        <i class="bi bi-plus"></i> Ongeza
                    </button>
                </div>
                <div class="card-body">
                    @forelse($employee->referees as $referee)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-2">{{ $referee->full_name }}</h6>
                                <p class="mb-1 small">
                                    <i class="bi bi-gender-{{ $referee->gender == 'male' ? 'male' : 'female' }}"></i>
                                    {{ ucfirst($referee->gender ?? 'N/A') }}
                                </p>
                                <p class="mb-1 small">
                                    <i class="bi bi-envelope"></i> 
                                    @if($referee->email)
                                        <a href="mailto:{{ $referee->email }}">{{ $referee->email }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="mb-1 small">
                                    <i class="bi bi-phone"></i> {{ $referee->phone ?? 'N/A' }}
                                </p>
                                @if($referee->address)
                                <p class="mb-1 small">
                                    <i class="bi bi-geo-alt"></i> {{ $referee->address }}
                                </p>
                                @endif
                                @if($referee->other_informations)
                                <p class="mb-0 small text-muted">{{ $referee->other_informations }}</p>
                                @endif
                            </div>
                            <form action="{{ route('referees.destroy', $referee) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Je, una uhakika unataka kufuta referee huyu?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-4">
                        <i class="bi bi-info-circle"></i> Hakuna washauri waliosajiliwa
                    </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Taarifa za Mfumo</h6>
                </div>
                <div class="card-body">
                    <div class="row small">
                        <div class="col-md-3">
                            <label class="text-muted">Alisajiliwa na:</label>
                            <p class="mb-0"><strong>{{ $employee->creator->username ?? 'System' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Tarehe ya Usajili:</label>
                            <p class="mb-0"><strong>{{ $employee->created_at->format('d/m/Y H:i') }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Ilisasishwa na:</label>
                            <p class="mb-0"><strong>{{ $employee->updater->username ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Tarehe ya Kusasishwa:</label>
                            <p class="mb-0"><strong>{{ $employee->updated_at->format('d/m/Y H:i') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Referee Modal -->
<div class="modal fade" id="addRefereeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('employees.referees.store', $employee) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ongeza Mshauri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Jina la Kwanza <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jina la Mwisho <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jinsia <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">Chagua</option>
                                <option value="male">Mwanaume</option>
                                <option value="female">Mwanamke</option>
                                <option value="other">Nyingine</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Barua Pepe <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Simu <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Anwani</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Maelezo Mengine</label>
                            <textarea name="other_informations" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ghairi</button>
                    <button type="submit" class="btn btn-primary">Hifadhi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


<!-- ================================================================ -->
<!-- FILE: resources/views/employees/edit.blade.php -->
<!-- ================================================================ -->
@extends('layouts.app')

@section('title', 'Hariri Mfanyakazi')
@section('page-title', 'Hariri Taarifa za Mfanyakazi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashibodi</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Wafanyakazi</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}">{{ $employee->full_name }}</a></li>
    <li class="breadcrumb-item active">Hariri</li>
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- User Account Information -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-lock"></i> Taarifa za Akaunti</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jina la Mtumiaji <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                               value="{{ old('username', $employee->user->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Barua Pepe <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $employee->user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Namba ya Simu</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $employee->user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cheo/Daraja <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Chagua Cheo</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role', $employee->user->role) == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hali ya Mfanyakazi <span class="text-danger">*</span></label>
                        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                            <option value="1" {{ old('is_active', $employee->is_active) == 1 ? 'selected' : '' }}>Hai</option>
                            <option value="0" {{ old('is_active', $employee->is_active) == 0 ? 'selected' : '' }}>Hayupo Kazini</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Neno la Siri Jipya (Acha tupu ikiwa haubadilishi)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Thibitisha Neno la Siri</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Taarifa Binafsi</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jina la Kwanza <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name', $employee->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jina la Kati</label>
                        <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" 
                               value="{{ old('middle_name', $employee->middle_name) }}">
                        @error('middle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jina la Mwisho <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name', $employee->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jinsia <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Chagua</option>
                            <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Mwanaume</option>
                            <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Mwanamke</option>
                            <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Nyingine</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tarehe ya Kuzaliwa <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                               value="{{ old('date_of_birth', $employee->date_of_birth->format('Y-m-d')) }}" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hali ya Ndoa</label>
                        <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                            <option value="">Chagua</option>
                            <option value="single" {{ old('marital_status', $employee->marital_status) == 'single' ? 'selected' : '' }}>Si Ameolewa/Ameoa</option>
                            <option value="married" {{ old('marital_status', $employee->marital_status) == 'married' ? 'selected' : '' }}>Ameolewa/Ameoa</option>
                            <option value="divorced" {{ old('marital_status', $employee->marital_status) == 'divorced' ? 'selected' : '' }}>Ametaliki</option>
                            <option value="widowed" {{ old('marital_status', $employee->marital_status) == 'widowed' ? 'selected' : '' }}>Mjane</option>
                        </select>
                        @error('marital_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Namba ya NIDA</label>
                        <input type="text" name="nida" class="form-control @error('nida') is-invalid @enderror" 
                               value="{{ old('nida', $employee->nida) }}" placeholder="19XXXXXXXXXX">
                        @error('nida')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kabila</label>
                        <input type="text" name="tribe" class="form-control @error('tribe') is-invalid @enderror" 
                               value="{{ old('tribe', $employee->tribe) }}">
                        @error('tribe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dini</label>
                        <input type="text" name="religion" class="form-control @error('religion') is-invalid @enderror" 
                               value="{{ old('religion', $employee->religion) }}">
                        @error('religion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Anwani</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Information -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-briefcase"></i> Taarifa za Kazi</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nafasi <span class="text-danger">*</span></label>
                        <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" 
                               value="{{ old('position', $employee->position) }}" required>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Idara <span class="text-danger">*</span></label>
                        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" 
                               value="{{ old('department', $employee->department) }}" required>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tarehe ya Kuajiriwa <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_hire" class="form-control @error('date_of_hire') is-invalid @enderror" 
                               value="{{ old('date_of_hire', $employee->date_of_hire->format('Y-m-d')) }}" required>
                        @error('date_of_hire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kiwango cha Elimu</label>
                        <input type="text" name="education_level" class="form-control @error('education_level') is-invalid @enderror" 
                               value="{{ old('education_level', $employee->education_level) }}" placeholder="e.g., Degree, Diploma">
                        @error('education_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Picha ya Wasifu</label>
                        @if($employee->profile_picture)
                            <div class="mb-2">
                                <img src="{{ $employee->profile_picture_url }}" alt="Current" width="80" height="80" class="rounded">
                                <small class="d-block text-muted">Picha ya sasa</small>
                            </div>
                        @endif
                        <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">JPG, PNG. Max: 2MB</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CV (Wasifu wa Maisha)</label>
                        @if($employee->cv)
                            <div class="mb-2">
                                <a href="{{ $employee->cv_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf"></i> Tazama CV ya Sasa
                                </a>
                            </div>
                        @endif
                        <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf,.doc,.docx">
                        @error('cv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">PDF, DOC, DOCX. Max: 5MB</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Maelezo Mengine</label>
                        <textarea name="other_information" class="form-control @error('other_information') is-invalid @enderror" 
                                  rows="3">{{ old('other_information', $employee->other_information) }}</textarea>
                        @error('other_information')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Next of Kin Information -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-person-hearts"></i> Taarifa za Jamaa wa Karibu (Next of Kin)</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jina la Kwanza</label>
                        <input type="text" name="nok_first_name" class="form-control @error('nok_first_name') is-invalid @enderror" 
                               value="{{ old('nok_first_name', $employee->nextOfKin->first_name ?? '') }}">
                        @error('nok_first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jina la Mwisho</label>
                        <input type="text" name="nok_last_name" class="form-control @error('nok_last_name') is-invalid @enderror" 
                               value="{{ old('nok_last_name', $employee->nextOfKin->last_name ?? '') }}">
                        @error('nok_last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jinsia</label>
                        <select name="nok_gender" class="form-select @error('nok_gender') is-invalid @enderror">
                            <option value="">Chagua</option>
                            <option value="male" {{ old('nok_gender', $employee->nextOfKin->gender ?? '') == 'male' ? 'selected' : '' }}>Mwanaume</option>
                            <option value="female" {{ old('nok_gender', $employee->nextOfKin->gender ?? '') == 'female' ? 'selected' : '' }}>Mwanamke</option>
                            <option value="other" {{ old('nok_gender', $employee->nextOfKin->gender ?? '') == 'other' ? 'selected' : '' }}>Nyingine</option>
                        </select>
                        @error('nok_gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Barua Pepe</label>
                        <input type="email" name="nok_email" class="form-control @error('nok_email') is-invalid @enderror" 
                               value="{{ old('nok_email', $employee->nextOfKin->email ?? '') }}">
                        @error('nok_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Namba ya Simu</label>
                        <input type="text" name="nok_phone" class="form-control @error('nok_phone') is-invalid @enderror" 
                               value="{{ old('nok_phone', $employee->nextOfKin->phone ?? '') }}">
                        @error('nok_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Anwani</label>
                        <input type="text" name="nok_address" class="form-control @error('nok_address') is-invalid @enderror" 
                               value="{{ old('nok_address', $employee->nextOfKin->address ?? '') }}">
                        @error('nok_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Maelezo Mengine</label>
                        <textarea name="nok_other_informations" class="form-control @error('nok_other_informations') is-invalid @enderror" 
                                  rows="2">{{ old('nok_other_informations', $employee->nextOfKin->other_informations ?? '') }}</textarea>
                        @error('nok_other_informations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Referees Information -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Taarifa za Washauri (Referees)</h5>
            </div>
            <div class="card-body">
                <!-- Existing Referees -->
                @if($employee->referees->count() > 0)
                    <h6 class="mb-3">Washauri Waliopo</h6>
                    @foreach($employee->referees as $index => $referee)
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-2">{{ $referee->full_name }}</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small><i class="bi bi-gender-{{ $referee->gender == 'male' ? 'male' : 'female' }}"></i> {{ ucfirst($referee->gender ?? 'N/A') }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <small><i class="bi bi-envelope"></i> {{ $referee->email ?? 'N/A' }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <small><i class="bi bi-phone"></i> {{ $referee->phone ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('referees.destroy', $referee) }}" method="POST" class="ms-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Je, una uhakika unataka kufuta referee huyu?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    <hr class="my-4">
                @endif

                <!-- Add New Referee Form -->
                <h6 class="mb-3">Ongeza Mshauri Mpya</h6>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Unaweza kuongeza washauri zaidi baada ya kuhifadhi mabadiliko
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Ghairi
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Hifadhi Mabadiliko
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsectionitem active">Wafanyakazi</li>
@endsection


<!-- ================================================================ -->
<!-- FILE: resources/views/employees/show.blade.php -->
<!-- ================================================================ -->
@extends('layouts.app')

@section('title', 'Taarifa za Mfanyakazi')
@section('page-title', $employee->full_name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashibodi</a></li>
    <li class="breadcrumb-

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="mb-0">
                <i class="bi bi-person-badge text-primary"></i>
                Orodha ya Wafanyakazi
            </h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employees.export.options') }}" class="btn btn-success me-2">
                <i class="bi bi-download"></i> Hamisha
            </a>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Ongeza Mfanyakazi
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('employees.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tafuta</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Jina, Namba, NIDA, Email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Idara</label>
                    <select name="department" class="form-select">
                        <option value="">Zote</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hali</label>
                    <select name="status" class="form-select">
                        <option value="">Zote</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            Hai
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            Hayupo Kazini
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tafuta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Idara</h6>
                    <h2>{{ $departments->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Picha</th>
                            <th>Namba ya Mfanyakazi</th>
                            <th>Jina Kamili</th>
                            <th>Email/Simu</th>
                            <th>Nafasi</th>
                            <th>Idara</th>
                            <th>Tarehe ya Kuajiriwa</th>
                            <th>Hali</th>
                            <th>Vitendo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                            <td>
                                <img src="{{ $employee->profile_picture_url }}" 
                                     alt="{{ $employee->full_name }}" 
                                     class="rounded-circle" 
                                     width="40" height="40"
                                     style="object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $employee->employee_id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $employee->full_name }}</strong><br>
                                    <small class="text-muted">{{ $employee->gender }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="bi bi-envelope"></i> {{ $employee->user->email }}<br>
                                    <i class="bi bi-phone"></i> {{ $employee->user->phone ?? 'N/A' }}
                                </div>
                            </td>
                            <td>{{ $employee->position }}</td>
                            <td>
                                <span class="badge bg-info">{{ $employee->department }}</span>
                            </td>
                            <td>{{ $employee->date_of_hire->format('d/m/Y') }}</td>
                            <td>
                                @if($employee->is_active)
                                    <span class="badge bg-success">Hai</span>
                                @else
                                    <span class="badge bg-secondary">Hayupo Kazini</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('employees.show', $employee) }}" 
                                       class="btn btn-info" 
                                       title="Tazama">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" 
                                       class="btn btn-warning" 
                                       title="Hariri">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('employees.toggle-status', $employee) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-{{ $employee->is_active ? 'secondary' : 'success' }}" 
                                                title="{{ $employee->is_active ? 'Simamisha' : 'Anzisha' }}">
                                            <i class="bi bi-toggle-{{ $employee->is_active ? 'off' : 'on' }}"></i>
                                        </button>
                                    </form>
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $employee->id }}"
                                            title="Futa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $employee->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Thibitisha Kufuta</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Je, una uhakika unataka kumfuta {{ $employee->full_name }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Ghairi
                                                </button>
                                                <form action="{{ route('employees.destroy', $employee) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Futa</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Hakuna wafanyakazi waliopatikana</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


<!-- ================================================================ -->
<!-- FILE: resources/views/employees/create.blade.php -->
<!-- ================================================================ -->
@extends('layouts.app')

@section('title', 'Ongeza Mfanyakazi')
@section('page-title', 'Ongeza Mfanyakazi Mpya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashibodi</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Wafanyakazi</a></li>
    <li class="breadcrumb-item active">Ongeza Mpya</li>
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- User Account Information -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-lock"></i> Taarifa za Akaunti</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jina la Mtumiaji <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                               value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Barua Pepe <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Namba ya Simu</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" placeholder="+255...">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cheo/Daraja <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Chagua Cheo</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Neno la Siri <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Thibitisha Neno la Siri <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Taarifa Binafsi</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jina la Kwanza <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jina la Kati</label>
                        <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" 
                               value="{{ old('middle_name') }}">
                        @error('middle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jina la Mwisho <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jinsia <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Chagua</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Mwanaume</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Mwanamke</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Nyingine</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tarehe ya Kuzaliwa <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                               value="{{ old('date_of_birth') }}" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hali ya Ndoa</label>
                        <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                            <option value="">Chagua</option>
                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Si Ameolewa/Ameoa</option>
                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Ameolewa/Ameoa</option>
                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Ametaliki</option>
                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Mjane</option>
                        </select>
                        @error('marital_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Namba ya NIDA</label>
                        <input type="text" name="nida" class="form-control @error('nida') is-invalid @enderror" 
                               value="{{ old('nida') }}" placeholder="19XXXXXXXXXX">
                        @error('nida')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kabila</label>
                        <input type="text" name="tribe" class="form-control @error('tribe') is-invalid @enderror" 
                               value="{{ old('tribe') }}">
                        @error('tribe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dini</label>
                        <input type="text" name="religion" class="form-control @error('religion') is-invalid @enderror" 
                               value="{{ old('religion') }}">
                        @error('religion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Anwani</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Information -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-briefcase"></i> Taarifa za Kazi</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nafasi <span class="text-danger">*</span></label>
                        <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" 
                               value="{{ old('position') }}" required>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Idara <span class="text-danger">*</span></label>
                        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" 
                               value="{{ old('department') }}" required>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tarehe ya Kuajiriwa <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_hire" class="form-control @error('date_of_hire') is-invalid @enderror" 
                               value="{{ old('date_of_hire') }}" required>
                        @error('date_of_hire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kiwango cha Elimu</label>
                        <input type="text" name="education_level" class="form-control @error('education_level') is-invalid @enderror" 
                               value="{{ old('education_level') }}" placeholder="e.g., Degree, Diploma">
                        @error('education_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Picha ya Wasifu</label>
                        <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">JPG, PNG. Max: 2MB</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CV (Wasifu wa Maisha)</label>
                        <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf,.doc,.docx">
                        @error('cv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">PDF, DOC, DOCX. Max: 5MB</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Maelezo Mengine</label>
                        <textarea name="other_information" class="form-control @error('other_information') is-invalid @enderror" 
                                  rows="3">{{ old('other_information') }}</textarea>
                        @error('other_information')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Next of Kin Information -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-person-hearts"></i> Taarifa za Jamaa wa Karibu (Next of Kin)</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jina la Kwanza</label>
                        <input type="text" name="nok_first_name" class="form-control @error('nok_first_name') is-invalid @enderror" 
                               value="{{ old('nok_first_name') }}">
                        @error('nok_first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jina la Mwisho</label>
                        <input type="text" name="nok_last_name" class="form-control @error('nok_last_name') is-invalid @enderror" 
                               value="{{ old('nok_last_name') }}">
                        @error('nok_last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jinsia</label>
                        <select name="nok_gender" class="form-select @error('nok_gender') is-invalid @enderror">
                            <option value="">Chagua</option>
                            <option value="male" {{ old('nok_gender') == 'male' ? 'selected' : '' }}>Mwanaume</option>
                            <option value="female" {{ old('nok_gender') == 'female' ? 'selected' : '' }}>Mwanamke</option>
                            <option value="other" {{ old('nok_gender') == 'other' ? 'selected' : '' }}>Nyingine</option>
                        </select>
                        @error('nok_gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Barua Pepe</label>
                        <input type="email" name="nok_email" class="form-control @error('nok_email') is-invalid @enderror" 
                               value="{{ old('nok_email') }}">
                        @error('nok_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Namba ya Simu</label>
                        <input type="text" name="nok_phone" class="form-control @error('nok_phone') is-invalid @enderror" 
                               value="{{ old('nok_phone') }}">
                        @error('nok_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Anwani</label>
                        <input type="text" name="nok_address" class="form-control @error('nok_address') is-invalid @enderror" 
                               value="{{ old('nok_address') }}">
                        @error('nok_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Maelezo Mengine</label>
                        <textarea name="nok_other_informations" class="form-control @error('nok_other_informations') is-invalid @enderror" 
                                  rows="2">{{ old('nok_other_informations') }}</textarea>
                        @error('nok_other_informations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Referees Information -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Taarifa za Washauri (Referees)</h5>
            </div>
            <div class="card-body">
                <h6 class="mb-3">Mshauri wa Kwanza</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Jina la Kwanza</label>
                        <input type="text" name="ref1_first_name" class="form-control" value="{{ old('ref1_first_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jina la Mwisho</label>
                        <input type="text" name="ref1_last_name" class="form-control" value="{{ old('ref1_last_name') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jinsia</label>
                        <select name="ref1_gender" class="form-select">
                            <option value="">Chagua</option>
                            <option value="male">Mwanaume</option>
                            <option value="female">Mwanamke</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Barua Pepe</label>
                        <input type="email" name="ref1_email" class="form-control" value="{{ old('ref1_email') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Simu</label>
                        <input type="text" name="ref1_phone" class="form-control" value="{{ old('ref1_phone') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Anwani & Maelezo</label>
                        <textarea name="ref1_other_informations" class="form-control" rows="2">{{ old('ref1_other_informations') }}</textarea>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">Mshauri wa Pili</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jina la Kwanza</label>
                        <input type="text" name="ref2_first_name" class="form-control" value="{{ old('ref2_first_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jina la Mwisho</label>
                        <input type="text" name="ref2_last_name" class="form-control" value="{{ old('ref2_last_name') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jinsia</label>
                        <select name="ref2_gender" class="form-select">
                            <option value="">Chagua</option>
                            <option value="male">Mwanaume</option>
                            <option value="female">Mwanamke</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Barua Pepe</label>
                        <input type="email" name="ref2_email" class="form-control" value="{{ old('ref2_email') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Simu</label>
                        <input type="text" name="ref2_phone" class="form-control" value="{{ old('ref2_phone') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Anwani & Maelezo</label>
                        <textarea name="ref2_other_informations" class="form-control" rows="2">{{ old('ref2_other_informations') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Ghairi
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Hifadhi Mfanyakazi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsectionJumla</h6>
                    <h2>{{ $employees->total() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Wanaoajiriwa</h6>
                    <h2>{{ $employees->where('is_active', true)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Hawako Kazini</h6>
                    <h2>{{ $employees->where('is_active', false)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">