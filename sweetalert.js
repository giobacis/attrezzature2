
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
function confirmAction(url, message){
    Swal.fire({
        title: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Conferma',
        cancelButtonText: 'Annulla'
    }).then((result)=>{
        if(result.isConfirmed){
            window.location.href = url;
        }
    });
}
</script>
