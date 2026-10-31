    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <button href="#" class="btn btn-danger"  wire:click="$emit('triggerDelete',{{ $alternatifId }})"><i class="bi bi-trash"></i> Hapus</button>

@push('delete')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        @this.on('triggerDelete', alternatifId => {
            Swal.fire({
                title: 'Anda Yakin?',
                text: 'Data Alternatif akan dihapus!',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya Hapus!'
            }).then((result) => {
         //if user clicks on delete
                if (result.value) {
             // calling destroy method to delete
                    @this.call('destroy', alternatifId)
             // success response
                    Swal.fire({
                        title: 'Hapus Data Sukses!',
                        text: 'Data Alternatif telah dihapus!', 
                        icon: 'success',
                        showConfirmButton: true,
                        timer: 2500
                    });
                } else {
                    Swal.fire({
                        title: 'Hapus Data dibatalkan!',
                        text: 'Data Alternatif tidak dihapus!',
                        icon: 'error',
                        timer: 2500
                    });
                }
            });
        });
    })
</script>
@endpush