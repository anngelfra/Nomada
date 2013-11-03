$(document).ready(function(){
    $( "#contact" ).submit(function( event ) {
        event.preventDefault();
        var sName = "chinautla";
        $.post("php/getPosts.php",{
                keyWord:sName
            },
            function(data){
                var obj = JSON.parse(data);
                var firstPost = obj.items[1];
                $("#contactResult").html(fillYouTubePost(firstPost.title, firstPost.url));
                /*var key, count = 0;
                for(key in obj.url) {
                    if(obj.url.hasOwnProperty(key)) {
                        count++;
                    }
                }*/
            });

    });
});

function fillYouTubePost(title, URL){
    var template = '<div class="post">';
    template += '<iframe width="560" height="315" src="'+URL+'" frameborder="0" allowfullscreen></iframe>';
    template += '<p>'+title+'</p><hr/>';
    template += '</div>';
    alert(template);
    return template;
}

function fillFacebookPost(title, URL){
    var template = '<div class="post">';
    template += '<img src="'+URL+'"</img>';
    template += '<p>'+title+'</p><hr/>';
    template += '</div>';
    return template;
}