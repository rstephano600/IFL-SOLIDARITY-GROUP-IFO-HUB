@extends('layouts.app')
@section('title', 'Add Group')
@section('page-title', 'Group Creation')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
                {{-- Card Header: Strong Primary Color --}}
                <div class="card-header bg-primary text-white py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i> Register New Group</h4>
                </div>

        <div class="card-body">

            <form action="{{ route('groups.store') }}" method="POST">
                @csrf
                <div class="row g-4">

                    {{-- ✅ Searchable Group Center --}}
                    <div class="col-md-6">
                        <label for="group_center_search" class="form-label fw-bold">Select Group Center</label>
                        <div class="custom-search-select" data-type="center">
                            <input type="text" id="group_center_search" placeholder="Search group center..." readonly>
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-list"></div>
                            <select id="group_center_id" name="group_center_id" required hidden></select>
                        </div>
                    </div>

                    {{-- ✅ Searchable Credit Officer --}}
                    <div class="col-md-6">
                        <label for="credit_officer_search" class="form-label fw-bold">Select Credit Officer</label>
                        <div class="custom-search-select" data-type="officer">
                            <input type="text" id="credit_officer_search" placeholder="Search officer..." readonly>
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-list"></div>
                            <select id="credit_officer_id" name="credit_officer_id" required hidden></select>
                        </div>
                    </div>

                    {{-- Other Inputs --}}
                    <div class="col-md-6">
                        <label for="group_name" class="form-label fw-bold">Group Name</label>
                        <input type="text" name="group_name" id="group_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="group_type" class="form-label fw-bold">Group Type</label>
                        <input type="text" name="group_type" id="group_type" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="location" class="form-label fw-bold">Location</label>
                        <input type="text" name="location" id="location" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="registration_date" class="form-label fw-bold">Registration Date</label>
                        <input type="date" name="registration_date" id="registration_date" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.custom-search-select {
    position: relative;
}

.custom-search-select input {
    width: 100%;
    padding: 10px 35px 10px 10px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    background-color: white;
}

.dropdown-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 6px solid #666;
    transition: transform 0.3s;
}

.dropdown-arrow.open {
    transform: translateY(-50%) rotate(180deg);
}

.dropdown-list {
    display: none;
    position: absolute;
    z-index: 10;
    background: #fff;
    border: 2px solid #ddd;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    border-top: none;
    border-radius: 0 0 6px 6px;
}

.dropdown-list.show {
    display: block;
}

.dropdown-item {
    padding: 10px;
    cursor: pointer;
}

.dropdown-item:hover {
    background: #f1f1f1;
}
</style>
<script>
const groupCenters = @json($groupCenters);
const creditOfficers = @json($creditOfficers);

class SearchableSelect {
    constructor(container, data) {
        this.container = container;
        this.data = data;
        this.input = container.querySelector('input');
        this.dropdown = container.querySelector('.dropdown-list');
        this.arrow = container.querySelector('.dropdown-arrow');
        this.hiddenSelect = container.querySelector('select');
        this.isOpen = false;
        this.filtered = [...data];

        this.init();
    }

    init() {
        this.populateHiddenSelect();
        this.input.addEventListener('click', () => this.toggleDropdown());
        this.input.addEventListener('input', e => this.handleSearch(e));
        document.addEventListener('click', e => {
            if (!this.container.contains(e.target)) this.closeDropdown();
        });
        this.renderDropdown();
    }

    populateHiddenSelect() {
        this.hiddenSelect.innerHTML = '<option value="">Select...</option>';
        this.data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.center_name || (item.first_name + ' ' + item.last_name);
            this.hiddenSelect.appendChild(opt);
        });
    }

    toggleDropdown() {
        this.isOpen ? this.closeDropdown() : this.openDropdown();
    }

    openDropdown() {
        this.isOpen = true;
        this.input.removeAttribute('readonly');
        this.dropdown.classList.add('show');
        this.arrow.classList.add('open');
        this.renderDropdown();
    }

    closeDropdown() {
        this.isOpen = false;
        this.input.setAttribute('readonly', '');
        this.dropdown.classList.remove('show');
        this.arrow.classList.remove('open');
    }

    handleSearch(e) {
        const term = e.target.value.toLowerCase();
        this.filtered = this.data.filter(item =>
            (item.center_name && item.center_name.toLowerCase().includes(term)) ||
            (item.first_name && item.first_name.toLowerCase().includes(term)) ||
            (item.last_name && item.last_name.toLowerCase().includes(term))
        );
        this.renderDropdown();
    }

    renderDropdown() {
        this.dropdown.innerHTML = '';
        if (this.filtered.length === 0) {
            this.dropdown.innerHTML = '<div class="dropdown-item text-muted">No results found</div>';
            return;
        }

        this.filtered.forEach(item => {
            const div = document.createElement('div');
            div.className = 'dropdown-item';
            div.textContent = item.center_name || (item.first_name + ' ' + item.last_name);
            div.addEventListener('click', () => this.selectItem(item));
            this.dropdown.appendChild(div);
        });
    }

    selectItem(item) {
        const label = item.center_name || (item.first_name + ' ' + item.last_name);
        this.input.value = label;
        this.hiddenSelect.value = item.id;
        this.closeDropdown();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const centerContainer = document.querySelector('[data-type="center"]');
    const officerContainer = document.querySelector('[data-type="officer"]');
    new SearchableSelect(centerContainer, groupCenters);
    new SearchableSelect(officerContainer, creditOfficers);
});
</script>

@endsection
