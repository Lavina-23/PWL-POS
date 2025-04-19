@empty($supplier)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>

                <button type="button" class="close" data-dismiss="modal" aria- label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/supplier') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/supplier/' . $supplier->supplier_id . '/update_ajax') }}" method="POST" id="form-edit">

        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria- label="Close"
                        aria-hidden="true"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Supplier</label>
                        <input value="{{ $supplier->supplier_nama }}" type="text" name="supplier_nama" id="supplier_nama"
                            class="form-control" required>

                        <small id="error-supplier_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input value="{{ $supplier->supplier_alamat }}" type="text" name="supplier_alamat"
                            id="supplier_alamat" class="form-control" required>

                        <small id="error-supplier_alamat" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input value="{{ $supplier->supplier_telp }}" type="text" name="supplier_telp" id="supplier_telp"
                            class="form-control" required>

                        <small id="error-supplier_telp" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input value="{{ $supplier->supplier_email }}" type="email" name="supplier_email"
                            id="supplier_email" class="form-control" required>

                        <small id="error-supplier_email" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Kontak Person</label>
                        <input value="{{ $supplier->supplier_kontak }}" type="text" name="supplier_kontak"
                            id="supplier_kontak" class="form-control" required>

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
            $("#form-edit").validate({
                rules: {
                    supplier_nama: {
                        required: true,
                        maxlength: 10
                    },
                    supplier_alamat: {
                        required: true,
                        maxlength: 100
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
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                tableSupplier.ajax.reload();
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
                            }
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
@endempty
