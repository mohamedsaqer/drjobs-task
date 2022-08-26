<table class="table table-striped table-dark table-bordered">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>created</th>
        <th>Action</th>
    </tr>
    @foreach($users as $user)
        <tr id="tr_{{$user->id}}">
            <td>{{$user->id}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->status}}</td>
            <td>{{$user->created_at->diffForHumans()}}</td>
            <td class="text-center d-flex justify-content-evenly">
                <a class="btn btn-warning editUser" id="{{ $user->id }}">Edit</a>
                <a class="btn btn-danger deleteUser" id="{{ $user->id }}">Delete</a>
            </td>
        </tr>
    @endforeach
</table>
<div id="pagination">
    {!! $users->appends(['per_page' => '10'])->links("pagination::bootstrap-4") !!}
</div>
