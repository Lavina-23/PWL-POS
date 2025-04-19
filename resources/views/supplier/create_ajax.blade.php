<form action="{{ url('/supplier/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                {{-- Tempat untuk menampilkan pesan error umum dari server (opsional) --}}
                {{-- <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div> --}}
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input value="" type="text" name="supplier_nama" id="supplier_nama" class="form-control"
                        required>
                    <small id="error-supplier_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input value="" type="text" name="supplier_alamat" id="supplier_alamat"
                        class="form-control" required>
                    <small id="error-supplier_alamat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input value="" type="text" name="supplier_telp" id="supplier_telp" class="form-control"
                        required>
                    <small id="error-supplier_telp" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input value="" type="email" name="supplier_email" id="supplier_email" class="form-control"
                        required>
                    <small id="error-supplier_email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kontak Person</label>
                    <input value="" type="text" name="supplier_kontak" id="supplier_kontak"
                        class="form-control" required>
                    <small id="error-supplier_kontak" class="error-text form-text text-danger"></small>
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
                supplier_nama: {
                    required: true,
                    maxlength: 100
                },
                supplier_alamat: {
                    required: true,
                    maxlength: 255
                },
                supplier_telp: {
                    required: true,
                    maxlength: 15
                },
                supplier_email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                supplier_kontak: {
                    required: true,
                    maxlength: 50
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
                                if (typeof tableSupplier !== 'undefined' &&
                                    tableSupplier
                                    .ajax &&
                                    typeof tableSupplier.ajax.reload ===
                                    'function'
                                ) {
                                    tableSupplier.ajax.reload();
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
