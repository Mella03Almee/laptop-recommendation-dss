    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <button href="#" class="btn btn-danger"  wire:click="$emit('triggerDelete',{{ $userId }})"><i class="bi bi-trash"></i> Hapus</button>

@push('deleteUser')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            @this.on('triggerDelete', userId => {
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: 'Data User akan dihapus!',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya Hapus!'
                }).then((result) => {
             //if user clicks on delete
                    if (result.value) {
                 // calling destroy method to delete
                        @this.call('destroy', userId)
                 // success response
                        Swal.fire({
                            title: 'Hapus Data Sukses!',
                            text: 'Data User telah dihapus!', 
                            icon: 'success',
                            showConfirmButton: true,
                            timer: 2500
                        });
                    } else {
                        Swal.fire({
                            title: 'Hapus Data dibatalkan!',
                            text: 'Data User tidak dihapus!',
                            icon: 'error',
                            timer: 2500
                        });
                    }
                });
            });
        })
    </script>
@endpush