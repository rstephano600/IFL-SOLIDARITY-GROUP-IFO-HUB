@extends('layouts.app')
@section('title', 'Create a New Loan Details')
@section('page-title', 'Create a New Loan Details')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">New Loan Request (Continuing Client)</h5>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the errors below:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('loan_request_continueng_client.store') }}" method="POST">
                @csrf

                {{-- 🔍 Searchable Client Dropdown --}}
                <div class="client-select-container mb-3">
                    <label for="client_search">Select Client <span class="text-danger">*</span></label>

                    <div style="position: relative;">
                        <input 
                            type="text" 
                            id="client_search" 
                            class="search-box" 
                            placeholder="-- Search and Select Client --"
                            autocomplete="off"
                            readonly
                        >
                        <div class="dropdown-arrow"></div>
                    </div>

                    <div id="client_dropdown_list" class="dropdown-list"></div>

                    <select id="client_id" name="client_id" required>
                        <option value="">-- Search and Select Client --</option>
                    </select>
                </div>

                {{-- Loan Category --}}
                <div class="mb-3">
                    <label for="loan_category_id" class="form-label">Loan Category <span class="text-danger">*</span></label>
                    <select name="loan_category_id" id="loan_category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }} ({{ number_format($category->amount_disbursed, 2) }} {{ $category->currency ?? 'TZS' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-end">
                    <a href="{{ route('loan_request_continueng_client.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ Searchable Dropdown Styles --}}
<style>
    .client-select-container {
        position: relative;
        margin: 0 auto;
    }

    .search-box {
        width: 100%;
        padding: 12px 40px 12px 12px;
        font-size: 16px;
        border: 2px solid #ddd;
        border-radius: 6px;
        outline: none;
        cursor: pointer;
        background-color: white;
        transition: border-color 0.3s ease;
    }

    .search-box:focus {
        border-color: #007bff;
        cursor: text;
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
        pointer-events: none;
        transition: transform 0.3s ease;
    }

    .dropdown-arrow.open {
        transform: translateY(-50%) rotate(180deg);
    }

    .dropdown-list {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #ddd;
        border-top: none;
        border-radius: 0 0 6px 6px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .dropdown-list.show {
        display: block;
    }

    .dropdown-item {
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item.selected {
        background-color: #007bff;
        color: white;
    }

    .no-results {
        padding: 12px;
        text-align: center;
        color: #666;
        font-style: italic;
    }

    #client_id {
        display: none;
    }
</style>

{{-- ✅ Searchable Dropdown Script --}}
<script>
    const clients = @json($clients);

    class ClientSearchableSelect {
        constructor() {
            this.searchBox = document.getElementById('client_search');
            this.dropdownList = document.getElementById('client_dropdown_list');
            this.hiddenSelect = document.getElementById('client_id');
            this.dropdownArrow = document.querySelector('.dropdown-arrow');
            this.isOpen = false;
            this.selectedItem = null;
            this.filteredClients = [...clients];

            this.init();
        }

        init() {
            this.populateHiddenSelect();
            this.searchBox.addEventListener('click', () => this.toggleDropdown());
            this.searchBox.addEventListener('input', (e) => this.handleSearch(e));
            this.searchBox.addEventListener('keydown', (e) => this.handleKeydown(e));

            document.addEventListener('click', (e) => {
                if (!e.target.closest('.client-select-container')) this.closeDropdown();
            });

            this.renderDropdown();
        }

        populateHiddenSelect() {
            this.hiddenSelect.innerHTML = '<option value="">-- Search and Select Client --</option>';
            clients.forEach(client => {
                const option = document.createElement('option');
                option.value = client.id;
                option.textContent = `${client.first_name} ${client.last_name}`;
                this.hiddenSelect.appendChild(option);
            });
        }

        toggleDropdown() {
            this.isOpen ? this.closeDropdown() : this.openDropdown();
        }

        openDropdown() {
            this.isOpen = true;
            this.searchBox.removeAttribute('readonly');
            this.searchBox.focus();
            this.dropdownList.classList.add('show');
            this.dropdownArrow.classList.add('open');
            this.renderDropdown();
        }

        closeDropdown() {
            this.isOpen = false;
            this.searchBox.setAttribute('readonly', '');
            this.dropdownList.classList.remove('show');
            this.dropdownArrow.classList.remove('open');
            if (!this.selectedItem) this.searchBox.value = '';
        }

        handleSearch(e) {
            const searchTerm = e.target.value.toLowerCase();
            this.filteredClients = searchTerm === ''
                ? [...clients]
                : clients.filter(c =>
                    c.first_name.toLowerCase().includes(searchTerm) ||
                    c.last_name.toLowerCase().includes(searchTerm) ||
                    (c.group && c.group.group_name.toLowerCase().includes(searchTerm)) ||
                    (c.group_center && c.group_center.center_name.toLowerCase().includes(searchTerm))
                );
            this.renderDropdown();
        }

        renderDropdown() {
            this.dropdownList.innerHTML = '';

            if (this.filteredClients.length === 0) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.textContent = 'No clients found';
                this.dropdownList.appendChild(noResults);
                return;
            }

            this.filteredClients.forEach(client => {
                const item = document.createElement('div');
                item.className = 'dropdown-item';
                item.textContent = `${client.first_name} ${client.last_name}` +
                    (client.group ? ` (Group: ${client.group.group_name})` : '') +
                    (client.group_center ? ` - Center: ${client.group_center.center_name}` : '');
                item.addEventListener('click', () => this.selectItem(client));
                this.dropdownList.appendChild(item);
            });
        }

        selectItem(client) {
            this.selectedItem = client;
            this.searchBox.value = `${client.first_name} ${client.last_name}` +
                (client.group ? ` (Group: ${client.group.group_name})` : '') +
                (client.group_center ? ` - Center: ${client.group_center.center_name}` : '');
            this.hiddenSelect.value = client.id;
            this.closeDropdown();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        new ClientSearchableSelect();
    });
</script>
@endsection
