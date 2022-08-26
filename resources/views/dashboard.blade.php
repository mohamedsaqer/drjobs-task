@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    @can('isAdmin')
                        <div id="pagination_data">
                            @include("tables.user-pagination",["users"=>$users])
                        </div>
                    @else
                        <div id="pagination_data">
                            @include("tables.post-pagination",["tables"=>$posts])
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-control">
                        <input type="hidden" name="user_id" id="user_id">
                        <input type="text" name="name" id="name" class="form-control my-3" placeholder="Name" required>
                        <input type="email" name="email" id="email" class="form-control my-3" placeholder="Email" required>
                        <select name="status" id="status" class="form-control" required>
                            <option @if( '' == 'active') selected @endif value="active">Active</option>
                            <option @if( '' == 'inactive') selected @endif value="inactive">Inactive</option>
                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                    <button type="button" class="btn btn-primary" id="save-edits">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            $(document).on("click", "#pagination a", function () {
                //get url and make final url for ajax
                var url = $(this).attr("href");
                var append = url.indexOf("?") == -1 ? "?" : "&";
                var finalURL = url + append;
                $.ajax({
                    type:'GET',
                    url: finalURL,
                    success:function(data){
                        $("#mytable").find($("tr")).slice(1).remove();
                        $.each(data['data'],function (key, value){
                            $('#table-header').after(`<tr>
                <td>${value.id}</td>
                <td>${value.title}</td>
                <td style="max-width: 30%">${value.content}</td>
                <td>${value.post_category.title}</td>
                <td>${value.status}</td>
            </tr>`);
                        });
                        $('ul.pagination').empty();
                        if(!data['prev_page_url']){
                            $('ul.pagination').append(`<li class="page-item disabled"><span class="page-link">«</span></li>`);
                        }else{
                            $('ul.pagination').append(`<li><a class="page-link" href="${data['prev_page_url']}" rel="next">«</a></li>`);
                        }
                        for (let i = 1; i <= data['last_page']; i++) {
                            if(data['current_page'] === i){
                                $('ul.pagination').append(`<li class="page-item active"><span class="page-link">${i}</span></li>`);
                            } else{
                                $('ul.pagination').append(`<li class="page-item"><a class="page-link" href="${data['path']}?per_page=${data['per_page']}&amp;page=${i}">${i}</a></li>`);
                            }
                        }
                        if(!data['next_page_url']){
                            $('ul.pagination').append(`<li class="page-item disabled"><span class="page-link">»</span></li>`);
                        }else{
                            $('ul.pagination').append(`<li class="page-item"><a class="page-link" href="${data['next_page_url']}" rel="next">»</a></li>`);
                        }
                    },
                    error:function (data){
                        alert(data);
                    },
                });
                return false;
            })
        });
        // delete user
        $('.deleteUser').on('click', function (){
            let tr = $(this).closest('tr');
            $.ajax({
                type:'POST',
                url:"{{ url('users') }}" + '/' + $(this).attr('id'),
                data:{_method: 'delete'},
                success:function(data){
                    tr.remove();
                    alert('user deleted successfully');
                },
                error:function (data){
                    if(data['status'] === 403){
                        // console.log(data['responseJSON']['message']);
                        alert('You are not authorized to do this action, you can call your administrator');
                    }else{
                        alert(JSON.parse(data.responseText));
                    }
                },
            });
        });
        // edit user
        $('.editUser').on('click', function (){
            $.ajax({
                type:'GET',
                url:"{{ url('users') }}" + '/' + $(this).attr('id'),
                data:{user: $(this).attr('id')},
                success:function(data){
                    $('#staticBackdropLabel').text('Edit User: ' + data.name);
                    $('#user_id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#status').val(data.status);
                    $('#staticBackdrop').modal('show');
                },
                error:function (data){
                    alert(data);
                },
            });
        });
        //update user save-edits
        $('#save-edits').on('click', function (){
            $.ajax({
                type:'POST',
                url:"{{ url('users') }}" + '/' + $('#user_id').val(),
                data:{
                    _method: 'PUT',
                    name: $('#name').val(),
                    email: $('#email').val(),
                    status: $('#status').val(),
                },
                success:function(data){
                    clearModAL();
                    var objKeys = ["id", "name", "email", 'status'];
                    $('#tr_' + data.user.id + ' td').each(function(i) {
                        $(this).text(data.user[objKeys[i]]);
                    });
                    $('#staticBackdrop').modal('hide');
                },
                error:function (data){
                    if(data['status'] === 422){
                        var erroJson = JSON.parse(data.responseText);
                        //CLEAR ALL THE PREVIOUS ERRORS
                        $('div.alert-danger').remove();
                        for (var err in erroJson['errors']) {
                            for (var errstr of erroJson['errors'][err])
                            {
                                $("[name='" + err + "']").after("<div class='alert alert-danger'>" + errstr + "</div>");
                            }
                        }
                    } else{
                        var erroJson = JSON.parse(data.responseText);
                        //CLEAR ALL THE PREVIOUS ERRORS
                        $('div.alert-danger').remove();
                        $('#btn-submit').after("<div class='alert alert-danger'>" + erroJson + "</div>");
                    }
                },
            });
        });
        //clear modal
        function clearModAL()
        {
            $('#name').val('');
            $('#email').val('');
            $('#status').val('');
        }
    </script>
@endsection
