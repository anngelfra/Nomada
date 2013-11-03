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

function fillYouTubePost(title, URL){
    var template = '<div class="post">';
    template += '<iframe width="560" height="315" src="'+URL+'" frameborder="0" allowfullscreen></iframe>';
    template += '<p>'+title+'</p><hr/>';
    template += '</div>';
    return template;
}

function fillFacebookPost(title, URL){
    var template = '<div class="post">';

}