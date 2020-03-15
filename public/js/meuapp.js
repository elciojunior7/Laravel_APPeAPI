function action(e){ 
    let id = $(e).parent().siblings("#code").html();
    let image = $(e).parent().siblings("#image").data( "image" );
    $("#btnOk").attr("onclick", "remove("+id+","+image+")");
    let book = $(e).parent().siblings("#booktitle").html();
    $("#book").html(book);
    $('#indexModal').modal('show');
}
function remove(id){ 
    $.ajax({
        type: "post",
        url: "{{route('book.delete')}}",
        async: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: 'json',
        data : { id: id, image: image },
        success: function (data) {
            console.log(data);
            window.location.href = data.html;
        },
        error: function(data) {
            console.log(data);
        }
    });
}
function enlargeCover(e){ 
    $('#imagepreview').attr('src', $(e).attr('src'));
    $('#myModal').html($(e).parent().siblings("#booktitle").html());
    $('#imagemodal').modal('show'); 
};