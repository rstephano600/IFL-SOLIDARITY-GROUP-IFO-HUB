
<script>
function confirmDelete(event)
{
    event.preventDefault();
    let url = event.currentTarget.href;

    Swal.fire({
        icon: 'warning',
        title: 'Delete Record?',
        text: 'This action cannot be undone.',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary',
            actions: 'gap-3'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            window.location.href = url;
        }
    });
}
</script>

<script>

function confirmApprove(event)
{
    event.preventDefault();

    let url = event.currentTarget.href;

    Swal.fire({
        icon: 'question',
        title: 'Approve Record?',
        text: 'Please confirm approval.',
        showCancelButton: true,
        cancelButtonText: 'Cancel'
        confirmButtonText: 'Yes, Approve',
    }).then((result) => {

        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>

<script>
function confirmSubmit(formId)
{
    Swal.fire({
        icon: 'question',
        title: 'Submit Form?',
        text: 'Please confirm submission.',
        showCancelButton: true,
        confirmButtonText: 'Yes, Submit',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>