<?php
    include_once "entity.php";


    function burnedContentSourceChinautla(){
        $source1 = new youtubeContentSource();
        $source1->name = "MuniPatzun channel";
        $source1->url = "MuniPatzun";
        $source1->user = "MuniPatzun";

        $source2 = new facebookContentSource();
        $source2->name = "fan page patzun";
        $source2->user = "municipalidadpatzun";
        //1 => $source1,
        $contentSourceList = array(1 => $source1);
        //$contentSourceList = array(2 => $source2);
        return $contentSourceList;
    }

    function burnedCommunity($keyWord){
        $community = new community();
        switch ($keyWord){
            case "chinautla":
                $community->id = 1;
                $community->name = "chinautla";
                $community->background = "default.png";
                $community->contentSourceList = burnedContentSourceChinautla();
                break;
            case "mixco":
                $community->id = 2;
                $community->name = "source ";
                $community->background = "";
                $community->contentSourceList = array();
                break;
            case "pinula":
                $community->id = 3;
                $community->name = "source ";
                $community->background = "";
                $community->contentSourceList = array();
                break;
            default:
            break;
        }

        return $community;
    }
    function loadCommunity($communityName){
        //-_- query to database for comunities with that name
        $community = burnedCommunity("chinautla");


        return $community;
    }

    function loadPosts($keyWord){
        $community = loadCommunity($keyWord);


        $mediaList = array();
        foreach ($community->contentSourceList as $contentSource){

            $mediaResult = $contentSource->retrieveMedia();

            //return $mediaResult;

            foreach($mediaResult as $mediaItem){
                array_push($mediaList, $mediaItem);
            }
        }
        $response = new mediaResponse();
        $response->items = $mediaList;

        $result = json_encode($response);

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