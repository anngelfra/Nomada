<?php

	class sourceKind{
		const none = 0;
		const youtube = 1;
		const facebook = 2;
	}
    class media{
        public $url;
        public $title;
        public $kind;
        public function __construct() {
            $this->url = "";
            $this->kind = sourceKind::none;
            $this->title = "";
        }
        public function __toString(){
            return '<iframe width="560" height="315" src="'.$this->url.'" frameborder="0" allowfullscreen></iframe>';
        }
    }
	class contentSourceBase{
		public $name;
		public $url;
		public $user;
		public $kind;
		public function __construct() {
			$this->name = "";
			$this->url = "";
			$this->kind = sourceKind::none;
		}
		public function retrieveMedia($apiObject){
		    return "";
		}
	}
	class youtubeContentSource extends contentSourceBase{
	    public $maxResults =10;
	    public function __construct() {
            $this->kind = sourceKind::youtube;
        }

        public function retrieveMedia($apiObject){
            $youtube = $apiObject;
            $userName = $this->user;
            $maxResults = $this->maxResults;
            $parameterList = array('forUsername' => $userName,);
            $channelsResponse = $youtube->channels->listChannels('contentDetails', $parameterList);

            $mediaList = array();

            $result = '';

            foreach ($channelsResponse['items'] as $channel) {
                $uploadsListId = $channel['contentDetails']['relatedPlaylists']['uploads'];

                $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
                    'playlistId' => $uploadsListId,
                    'maxResults' => 50
                ));

                $result .= "<h3>Videos in list $uploadsListId</h3><ul>";
                foreach ($playlistItemsResponse['items'] as $playlistItem) {
                    $result .= sprintf('<li>%s (%s)-(%s)</li>',
                        $playlistItem['snippet']['title'],
                        $playlistItem['snippet']['resourceId']['videoId'],
                        $playlistItem['snippet']['resourceId']['kind']
                    );
                    $tempMedia = new media();
                    $tempMedia->title = $playlistItem['snippet']['title'];
                    $tempMedia->url = "http://www.youtube.com/watch?v=".$playlistItem['snippet']['resourceId']['videoId'];
                    array_push($mediaList,$tempMedia);
                }
                $result .= '</ul>';

            }
            return $mediaList;
        }
	}
	class comunity{
		public $id;
		public $name;
		public $background;
		public $contentSourceList = array();
	    public function __construct() {
	        $this->id = 0;
	        $this->name = "";
	        $this->background = "";
	        $this->contentSourceList = array();
	    }
	}
?>