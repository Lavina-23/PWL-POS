<form action="{{ url('/kategori/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kategori</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                {{-- Tempat untuk menampilkan pesan error umum dari server (opsional) --}}
                {{-- <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div> --}}
                <div class="form-group">
                    <label>Kode Kategori</label>
                    <input value="" type="text" name="kategori_kode" id="kategori_kode" class="form-control"
                        required>
                    <small id="error-kategori_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input value="" type="text" name="kategori_nama" id="kategori_nama" class="form-control"
                        required>
                    <small id="error-kategori_nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                kategori_kode: {
                    required: true,
                    maxlength: 10
                },
                kategori_nama: {
                    required: true,
                    maxlength: 100
                },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide'); // Mulai tutup modal
                            $('#myModal').on('hidden.bs.modal', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                if (typeof tableKategori !== 'undefined' &&
                                    tableKategori
                                    .ajax &&
                                    typeof tableKategori.ajax.reload ===
                                    'function'
                                ) {
                                    tableKategori.ajax.reload();
                                }
                            });
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                            // Tampilkan pesan error umum (jika ada)
                            // if (response.message_umum) {
                            //     $('.print-error-msg ul').html('');
                            //     $('.print-error-msg').find('ul').append('<li>' + response.message_umum + '</li>');
                            //     $('.print-error-msg').css('display','block');
                            // }
                        }
                    },
                    error: function(xhr, status, error) { // Tambahkan penanganan error AJAX
                        console.error("Terjadi kesalahan AJAX:", xhr, status, error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Gagal',
                            text: 'Terjadi kesalahan saat menghubungi server.'
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
