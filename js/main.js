$(document).ready(function(){
    //$( "#contact" ).submit(function( event ) {
        //event.preventDefault();
        var sName = "chinautla";
        $.post("php/getPosts.php",{
                keyWord:sName
            },
            function(data){
                var obj = JSON.parse(data);
                var content = "";
                var color = feedColor();
                for(var i=0; i<obj.items.length; i++){
                    var post = obj.items[i];
                    if (post.kind == 1) {
                        content += fillYouTubePost(post.title, post.url, color);
                    }
                    if (post.kind == 2){
                        content += fillFacebookPost(post.title, post.url, color);
                    }
                }
                $("#right-feed").html(content);
            });

    //});
});

function fillYouTubePost(title, URL, color){
    var width = $("#right-panel").width()*0.43;
    var height = width*0.5625;
    var template = '<div class="post">';
    template += '<iframe width="'+width+'" height="'+height+'" src="//'+URL+'" frameborder="0" allowfullscreen></iframe>';
    template += '<p style="color:'+ color +'">'+title+'</p>';
    template += '</div>';
    return template;
}

function fillFacebookPost(title, URL, color){
    var template = '<div class="post">';
    template += '<img src="'+URL+'">';
    template += '<p style="color:'+ color +'">'+title+'</p>';
    template += '</div>';
    return template;
}

function feedColor(){
    var colors = ["#1255B2", "#3388FF", "#A5CC14", "#92B21B", "#FF604C"];
    return colors[Math.floor(Math.random()*4)];
}