<footer class="bg-white border-top py-3 mt-auto shadow-sm"> <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 order-md-1 order-2 mt-2 mt-md-0">
                <small class="text-muted">
                    &copy; {{ date('Y') }} IFL Solidarity Group Management system - All right reserved
                </small>
            </div>
            <div class="col-md-6 text-md-end order-md-2 order-1">
                <small class="text-muted">
                    User: <strong>{{ Auth::user()->username }}</strong> | 
                    <!-- Role: <strong>{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</strong> | -->
                    A. Status: <span class="badge bg-success">{{ ucfirst(Auth::user()->status) }}</span> </small>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col text-center">
                <small class="text-muted">
                    Version: 1.0.0 | 
                    <a href="#" class="text-decoration-none text-primary">Support</a> | <a href="#" class="text-decoration-none text-primary">Privacy</a> | 
                    <a href="#" class="text-decoration-none text-primary">Our Policies</a>
                </small>
            </div>
        </div>
    </div>
</footer>

@push('scripts')
<script>
function openCreateForm() {
    document.getElementById('dataFormFill').reset();
    const modal = new bootstrap.Modal(document.getElementById('addFormModal'));
    modal.show();
}
</script>
@endpush