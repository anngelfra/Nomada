<?php
    include_once "entity.php";


    function burnedContentSourceChinautla(){
        $source1 = new youtubeContentSource();
        $source1->name = "chinautla chanel";
        $source1->url = "cocacola";
        $source1->kind = sourceKind::youtube;

        $contentSourceList = array(1 => $source1);
        return $contentSourceList;
    }

    function burnedComunity($keyWord){
        $comunity = new comunity();
        switch ($keyWord){
            case "chinautla":
                $comunity->id = 1;
                $comunity->name = "chinautla";
                $comunity->background = "default.png";
                $comunity->contentSourceList = burnedContentSourceChinautla();
                break;
            case "mixco":
                $comunity->id = 2;
                $comunity->name = "source ";
                $comunity->background = "";
                $comunity->contentSourceList = array();
                break;
            case "pinula":
                $comunity->id = 3;
                $comunity->name = "source ";
                $comunity->background = "";
                $comunity->contentSourceList = array();
                break;
            default:
            break;
        }

        return $comunity;
    }
    function loadComunity($comunityName){
        //-_- query to database for comunities with that name
        $comunity = burnedComunity("chinautla");


        return $comunity;
    }

    function loadPosts($keyWord){

        $comunity = loadComunity($keyWord);
        return json_encode( $comunity->contentSourceList  );
        $jsonReslut = "json: ";

        $mediaList = array();
        foreach ($comunity->contentSourceList as $contentSource){
            $jsonReslut .= json_encode( $contentSource );
            array_push($mediaList, $contentSource->retrieveMedia());

        }

        return $jsonReslut;

        $result = "";
        $tempDiv = "";
        foreach ($mediaList as $post){
            $tempDiv = "<div>";
            $tempDiv .= $post->url;
            $tempDiv .= $post->title;
            $tempDiv .= "</div>";

            $result.=$tempDiv;
        }
        return $result;
    }

    /*
        parameters in post:
            keyWord
    */
    function getPosts(){
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if (!isset($_POST["keyWord"]))
                return "<div> emtpy </div>";

            $keyWord = $_POST["keyWord"];

            return loadPosts( $keyWord );
        }
        return "<div> invalid request</div>";
    }

    echo getPosts();
?>