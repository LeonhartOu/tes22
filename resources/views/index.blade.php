@extends('layouts.app')

@section('content')
    {{-- Datatable --}}
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-1">Data Input Checker</h4>
                </div>
                <div class="card-body">
                    <div class="button-row" style="display: flex; justify-content:end; gap: 10px;">
                        <button id="btnAddData" type="button" class="btn btn-success mb-3" data-toggle="modal">
                            Add New Input
                        </button>
                    </div>

                    <table id="inputTable" width="100%" class="table table-striped nowrap m-1">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Input 1</th>
                                <th scope="col">Input 2</th>
                                <th scope="col">Matching Percentage</th>
                                <th scope="col">Edit Button</th>
                                <th scope="col">Delete Button</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot></tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add --}}
    <div class="modal fade" id="modalInput" tabindex="-1" aria-labelledby="modalInput" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Input</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="" method="POST" id="formInput">
                        @csrf
                        <div class="mb-3">
                            <label for="input1" class="form-label">Input 1</label>
                            <input type="text" name="input1" id="input1" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="input2" class="form-label">Input 2</label>
                            <input type="text" name="input2" id="input2" class="form-control" required>
                        </div>
                        <button type="button" class="btn btn-primary" id="btnSave">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetail" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Input</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="" method="POST" id="formDetail">
                        @csrf
                        <input type="hidden" id="input_id" name="input_id">
                        <div class="mb-3">
                            <label for="input1_detail" class="form-label">Input 1</label>
                            <input type="text" name="input1_detail" id="input1_detail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="input2_detail" class="form-label">Input 2</label>
                            <input type="text" name="input2_detail" id="input2_detail" class="form-control" required>
                        </div>
                        <button type="button" class="btn btn-primary" id="btnSaveEdit">Save Changes</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            table = $('#inputTable').DataTable({
                "responsive": true,
                "paging": true,
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true,
                "scroller": true,
                "order": [],
                "autoWidth": true,
                "searching": false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10', '25', '50', 'All']
                ],
                "ajax": {
                    // "url": "{{ route('indexData') }}",
                    url: "https://tes22-production-725b.up.railway.app/indexData",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "type": "GET",
                    "contentType": 'application/json',
                    "dataSrc": function(json) {
                        return json.data;
                    }

                }
            });

            $("#btnAddData").click(function() {
                $("#modalInput").modal('show');
            });

            // ADD DATA
            $("#btnSave").click(function(e) {
                e.preventDefault();
                var formData = new FormData($('#formInput')[0]);

                $(this).prop('disabled', true).text('Please wait...');

                $.ajax({
                    type: "POST",
                    // url: "{{ url('/') }}/saveData",
                    url: "https://tes22-production-725b.up.railway.app/saveData",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.code == 200) {
                            swal.fire({
                                title: 'Success',
                                text: data.message,
                                icon: 'success',
                            });
                        } else {
                            swal.fire({
                                title: 'Insert Failed!',
                                text: 'Internal Server Error!',
                                icon: 'error',
                            });
                        }
                        $('#modalInput').modal('hide');
                        clearform();
                        ReloadTable();

                        $('#btnSave').text('Submit');
                        $('#btnSave').prop('disabled', false);
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        var myText = errorThrown;
                        swal.fire({
                            title: 'Warning',
                            icon: 'warning',
                            target: '#modalInput'
                        });
                        $('#btnSave').text('Submit');
                        $('#btnSave').prop('disabled', false);
                    },

                });
            });

            // DETAIL BUTTON
            $('#inputTable').on('click', '.showData', function() {
                var id = $(this).data('id');
                $('#input_id').val(id);
                $('#modalDetail').modal('show');

                $.ajax({
                    url: "{{ url('/detailData') }}/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(response) {
                        // console.log('detail id:', response.data);

                        $('[name="input1_detail"]').val(response.data.input1);
                        $('[name="input2_detail"]').val(response.data.input2);
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        var myText = errorThrown;
                        swal.fire({
                            title: 'Warning',
                            icon: 'warning',
                        });
                    }
                });
            });

            // EDIT DATA
            $('#btnSaveEdit').on('click', function() {
                var id = parseInt($('#input_id').val());
                var formData = new FormData($('#formDetail')[0]);

                $.ajax({
                    // url: "{{ url('/saveEdit') }}/" + id,
                    url: "https://tes22-production-725b.up.railway.app/saveEdit/" + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.code == 200) {
                            swal.fire({
                                title: 'Success',
                                text: 'Data Updated Successfully!',
                                icon: 'success',
                            });
                            $('#modalDetail').modal('hide');
                        } else {
                            swal.fire('Error: ' + data.message);
                        }
                        ReloadTable();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var myText = errorThrown;
                        swal.fire(myText);
                        $('#btnSaveEdit').text('Submit');
                        $('#btnSaveEdit').prop('disabled', false);
                    }
                });
            });

            // DELETE DATA
            $('#inputTable').on('click', '.deleteData', function() {
                var id = $(this).data('id');

                $.ajax({
                    // url: "{{ url('/deleteData') }}/" + id,
                    url: "https://tes22-production-725b.up.railway.app/deleteData/" + id,

                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.code == 200) {
                            swal.fire({
                                title: 'Success',
                                text: 'Data Updated Successfully!',
                                icon: 'success',
                            });

                        } else {
                            swal.fire('Error: ' + data.message);
                        }
                        ReloadTable();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var myText = errorThrown;
                        swal.fire(myText);
                        $('#btnSaveEdit').text('Submit');
                        $('#btnSaveEdit').prop('disabled', false);
                    }
                });
            });
        });

        function clearform() {
            $("#input1").val('');
            $('#input2').val('');
            $("#btnSave").prop("disabled", false);
        }

        function ReloadTable() {
            table.ajax.reload(null, false);
        }
    </script>
@endsection
