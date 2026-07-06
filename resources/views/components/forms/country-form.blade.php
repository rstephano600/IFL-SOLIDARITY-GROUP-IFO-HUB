<form id="globalForm" method="POST" data-ajax="true" data-reload="true">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Country Code</label>
            <input type="text" 
                   name="CountryCode" 
                   class="form-control" 
                   value="{{ old('CountryCode', $country->CountryCode ?? '') }}"
                   maxlength="5" 
                   required>
        </div>
        <div class="mb-3">
            <label class="form-label">Country Name</label>
            <input type="text" 
                   name="CountryName" 
                   class="form-control" 
                   value="{{ old('CountryName', $country->CountryName ?? '') }}"
                   required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary" id="submitBtn">
            <i class="bi bi-check2"></i> {{ $country ? 'Update' : 'Save' }} Country
        </button>
    </div>
</form>