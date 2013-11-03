$(document).ready(function(){
    $( "#contact" ).submit(function( event ) {
        event.preventDefault();
        var sName = "chinautla";
        $.post("php/getPosts.php",{
                keyWord:sName
            },
            function(data){
                var obj = JSON.parse(data);
                alert(obj.length);
                $("#contactResult").html(data);
            });

    });
});

function addPosts(data){
    var obj = $.parseJSON(data);
    alert(obj.length);
    $("#contactResult").html(data);
    var template = '<div class="post">';
    template += '<iframe width="560" height="315" src="//www.youtube.com/embed/DfOAgO70jG8" frameborder="0" allowfullscreen></iframe>';
    template += '<p>Description</p><hr/>';
    template += '<p>Author</p>';
    template += '</div>';
}