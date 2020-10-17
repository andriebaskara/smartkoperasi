<script>

    function BuatBaru(){

        $('#paramhidden').html("<input type='hidden' name='simpan' value = 'baru'><input type='hidden' name='password' value = '123456'>");
        $('#modal-title').html("Buat Data User");
        $('#pesan').html("");
        $('#name').val("");
        $('#email').val("");

        $('#overlay-modal').hide();
        $('#btnSimpanData').show();

        $('#TambahData').modal('show');
    }

    function EditData(id){
        $('#pesan').html("");
        $('#name').val("");
        $('#email').val("");

        var v_url = '{{ route('admin.users.edit', ['user' => '-id-']) }}';
        v_url = v_url.replace('-id-', id);

        $.ajax({
            type : 'GET',
            url  : v_url,
            data : '',
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                if (success) {

                    $('#modal-title').html("Edit Data Pengguna");
                    $('#paramhidden').html("<input type='hidden' name='simpan' value = 'edit'><input type='hidden' name='id' value = " + id + ">");

                    $('#name').val(data.name);
                    $('#email').val(data.email);

                    $('#overlay-modal').hide();
                    $('#btnSimpanData').show();

                    $('#TambahData').modal('show');

                } else {

                    alert(pesan);
                }
            }
        })
    }

    function HapusData(id){
        var confirmation = confirm(" @lang('global.app_confirm') ");
        if (!confirmation) {
            return false;
        }

        $('#overlaytablearea').show();

        var datanya = "halaman=" + halaman_aktif + "&_token=" + _token + "&id=" + id;

        var kriteria = document.getElementById("kriteria").value;
        if (kriteria.trim() != "") {
            datanya = datanya + "&kriteria=" + kriteria;
        }
        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.users.hapusdata') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";
                $('#overlaytablearea').hide();
              
                if (success) {
                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);
                } else {
                    alert(pesan);
                }
            }
        })   

    }

    function HapusTerpilih(){
        if (confirm('  @lang('global.app_confirm')  ')) {

            $('#overlaytablearea').show()

            var ids = [];

            $("input[name='ids[]']:checked").each(function ()
            {
                ids.push(parseInt($(this).val()));
            });

            var kriteria = document.getElementById("kriteria").value;

            $.ajax({
                method: 'POST',
                url: '{{ route('admin.users.hapusdipilih') }}',
                data: {
                    _token: _token,
                    halaman: halaman_aktif,
                    kriteria: kriteria,
                    ids: ids
                }
            }).done(function (rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                $('#overlaytablearea').hide();

                if (success) {
                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);
                } else {
                    alert(pesan);
                }
            });
        }

        return false;
    }

    function LoadData(halaman) {

        $('#overlaytablearea').show()

        var datanya = "halaman=" + halaman + "&_token=" + _token;

        var kriteria = document.getElementById("kriteria").value;
        if (kriteria.trim() != "") {
            datanya = datanya + "&kriteria=" + kriteria;
        }
        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.users.loaddatatable') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlaytablearea').hide();

                if (success) {
                    halaman_aktif = halaman;
                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);

                } else {

                    alert(pesan);
                }
            }
        })   

    }

    function SimpanData(){
        var name = document.getElementById("name");
        if (name.value.trim() == "") {
            alert("Nama User belum diisi");
            name.focus();
            return;
        }
        var email = document.getElementById("email");
        if (email.value.trim() == "") {
            alert("Email User belum diisi");
            email.focus();
            return;
        }

        var confirmation = confirm(" @lang('global.app_confirm') ");
        if (!confirmation) {
            return false;
        }

        $('#overlay-modal').show();
        $('#btnSimpanData').hide();

        var datanya = $("#form_data").serialize();
        datanya = datanya + "&halaman=" + halaman_aktif;
        
        var kriteria = document.getElementById("kriteria").value;
        if (kriteria.trim() != "") {
            datanya = datanya + "&kriteria=" + kriteria;
        }

        $.ajax({
            type : 'POST',
            url  : '{{ route('admin.users.store') }}',
            data : datanya,
            success: function(rv) {
                var myObj = rv;

                var pesan = myObj.message;
                var success = myObj.success;
                var data = myObj.data;

                var html = "";

                $('#overlay-modal').hide();
                $('#btnSimpanData').show();

                if (success) {
                    html = " <div class='alert alert-success alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";

                    html = html +  data.pesan;

                    html = html +  "</div>";
                    $('#pesan').html(html);
                    $('#tablearea').html(data.table);
                    $('#pagination').html(data.pagination);

                    $('#TambahData').modal('hide');

                } else {

                    html = " <div class='alert alert-danger alert-dismissable'>";
                    html = html +  "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    html = html +  pesan;
                    html = html +  "</div>";

                    $('#pesan').html(html);
                    $('#btnSimpanData').prop('disabled', false);
                }
            }
        })
        return false ;
    }
</script>


