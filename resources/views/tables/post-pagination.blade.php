<table id="mytable" class="table table-striped table-dark table-bordered">
    <tr id="table-header">
        <th>ID</th>
        <th>Title</th>
        <th>Body</th>
        <th>Category</th>
        <th>Status</th>
    </tr>
        @foreach($posts as $post)
            <tr>
                <td>{{$post->id}}</td>
                <td>{{$post->title}}</td>
                <td style="max-width: 30%">{{$post->content}}</td>
                <td>{{$post->postCategory->title}}</td>
                <td>{{$post->status}}</td>
            </tr>
        @endforeach
</table>
<div id="pagination">
    {!! $posts->appends(['per_page' => '30'])->links("pagination::bootstrap-4") !!}
</div>
