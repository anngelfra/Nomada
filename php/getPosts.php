<?php
    include_once "entity.php";


    function burnedContentSourceChinautla(){
        $source1 = new youtubeContentSource();
        $source1->name = "MuniPatzun chanel";
        $source1->url = "MuniPatzun";
        $source1->user = "MuniPatzun";
        $source1->kind = sourceKind::youtube;

        $source2 = new facebookContentSource();
        $source2->name = "Patzun fan page";
        $source2->url = "municipalidadpatzun";
        $source2->user = "municipalidadpatzun";
        $source2->kind = sourceKind::facebook;

        $contentSourceList = array(1 => $source1, 2 => $source2,);
        //$contentSourceList = array(2 => $source2,);
        return $contentSourceList;
    }

    function burnedContentSourceLosAngeles(){
        $source1 = new youtubeContentSource();
        $source1->name = "los angeles";
        $source1->url = "LatinosForHire";
        $source1->user = "LatinosForHire";
        $source1->kind = sourceKind::youtube;

        $contentSourceList = array(1 => $source1);
        return $contentSourceList;
    }

    function burnedCommunity($keyWord){
        $community = new community();
        switch (strtolower($keyWord)){
            case "patzun":
                $community->id = 1;
                $community->name = "Patzun";
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
            case "los angeles":
                //http://www.youtube.com/user/LatinosForHire
                $community->id = 4;
                $community->name = "Los Angeles";
                $community->background = "default.png";
                $community->contentSourceList = burnedContentSourceLosAngeles();
                break;
            default:
            break;
        }

        return $community;
    }
    function loadCommunity($communityName){
        //-_- query to database for comunities with that name
        $community = burnedCommunity($communityName);


        return $community;
    }

    function loadPosts($keyWord, $random){

        $community = loadCommunity($keyWord);

        $mediaList = array();

        foreach ($community->contentSourceList as $contentSource){

            $mediaResult = $contentSource->retrieveMedia();

            foreach($mediaResult as $mediaItem){
                array_push($mediaList, $mediaItem);
            }
        }

        if ($random == 1) shuffle($mediaList);

        $objectResult = new responseMedia();
        $objectResult->items = $mediaList;
        return json_encode($objectResult);
    }

    /*
        parameters in post:
            keyWord
    */
    function getPosts(){
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $keyWord = "Patzun";
            $random = 1;

            if (isset($_POST["keyWord"])) $keyWord = $_POST["keyWord"];
            if (isset($_POST["isRandom"])) $random = $_POST["isRandom"];

            return loadPosts( $keyWord, $random );
        }
        return "<div> invalid request</div>";
    }

    echo getPosts();
?>