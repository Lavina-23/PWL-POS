<form action="{{ url('/barang/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                {{-- Tempat untuk menampilkan pesan error umum dari server (opsional) --}}
                {{-- <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div> --}}
                <div class="form-group">
                    <label>Kategori Barang</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->kategori_id }}">{{ $k->kategori_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input value="" type="text" name="barang_nama" id="barang_nama" class="form-control"
                        required>
                    <small id="error-barang_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Beli</label>
                    <input value="" type="number" name="harga_beli" id="harga_beli" class="form-control"
                        required>
                    <small id="error-harga_beli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Jual</label>
                    <input value="" type="number" name="harga_jual" id="harga_jual" class="form-control"
                        required>
                    <small id="error-harga_jual" class="error-text form-text text-danger"></small>
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
                level_id: {
                    required: true,
                    // number: true // Hapus jika level_id adalah string
                },
                username: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                }
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
                                if (typeof tableBarang !== 'undefined' &&
                                    tableBarang
                                    .ajax &&
                                    typeof tableBarang.ajax.reload ===
                                    'function'
                                ) {
                                    tableBarang.ajax.reload();
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
