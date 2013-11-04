$(document).ready(function(){

    var rightSpinnerTarget = document.getElementById('right-panel-loading');
    var leftSpinnerTarget = document.getElementById('left-panel-loading');

    //we suppose you are checking our awesome code for more than the spinner... anyway here it is, enjoy :D http://fgnass.github.io/spin.js/
    var opts = {
        lines: 8, // The number of lines to draw
        length: 6, // The length of each line
        width: 7, // The line thickness
        radius: 8, // The radius of the inner circle
        corners: 1, // Corner roundness (0..1)
        rotate: 0, // The rotation offset
        direction: 1, // 1: clockwise, -1: counterclockwise
        color: '#000', // #rgb or #rrggbb or array of colors
        speed: 1, // Rounds per second
        trail: 60, // Afterglow percentage
        shadow: false, // Whether to render a shadow
        hwaccel: false, // Whether to use hardware acceleration
        className: 'absolute-center', // The CSS class to assign to the spinner
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        top: '0px', // Top position relative to parent in px
        left: '0px' // Left position relative to parent in px
    };
    var rightSpinner = new Spinner(opts).spin(rightSpinnerTarget);
    var leftSpinner = new Spinner(opts).spin(leftSpinnerTarget);


    $("#right-panel-loading").show();
    var sName = "patzun";
    var jqxhr = $.post("php/getPosts.php",{
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
                    content += fillFacebookPost(post.albumTitle, post.url, color);
                }
            }
            $("#right-feed").html(content);
        }).always(function() {
            $("#right-panel-loading").hide();
        }
    );

    $("#left-panel-loading").show();
    sName = "los angeles";
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
            $("#left-feed").html(content);
        }).always(function() {
            $("#left-panel-loading").hide();
        }
    );
    //});
});

function fillYouTubePost(title, URL, color){
    var width = $("#right-panel").width()*0.85;
    var height = width*0.5625;
    var template = '<div class="post video-post">';
    template += '<iframe width="'+width+'" height="'+height+'" src="//'+URL+'" frameborder="0" allowfullscreen></iframe>';
    template += '<p style="padding-left: 2em; text-align: left; color:'+ color +'">'+title+'</p>';
    template += '</div>';
    return template;
}

function fillFacebookPost(albumTitle, URL, color){
    var template = '<div class="post photo-post">';
    template += '<div style="width: 90%; float: left"><a href="'+URL+'"><img src="'+URL+'"></a></div>';
    template += '<div style="float: left"><p style="color:'+ color +'">'+albumTitle+'</p></div>';
    template += '</div>';
    return template;
}

function feedColor(){
    var colors = ["#1255B2", "#3388FF", "#A5CC14", "#92B21B", "#FF604C"];
    return colors[Math.floor(Math.random()*4)];
}