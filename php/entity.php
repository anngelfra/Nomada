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
		public function retrieveMedia(){
		    return "";
		}
	}
	class youtubeContentSource extends contentSourceBase{
	    public $maxResults =10;
	    public function __construct() {
            $this->kind = sourceKind::youtube;
        }

        public function retrieveMedia(){
            $tempMedia = new media();
            $tempMedia->title = "my title";
            $tempMedia->url = "http://www.youtube.com/watch?v=jQ0P9ls_ZcE";


            require_once 'API/google-api-php-client/src/Google_Client.php';
            require_once 'API/google-api-php-client/src/contrib/Google_YouTubeService.php';


            $DEVELOPER_KEY = 'AIzaSyDCRA3GnWNa8UBUfrkxpSe0BVcVFMwE7ug';
            $client = new Google_Client();
            $client->setDeveloperKey($DEVELOPER_KEY);

            $youtube = new Google_YoutubeService($client);

            $userName = $this->user;
            $maxResults = $this->maxResults;

            try {
                //$parameterList = ;
                $channelsResponse = $youtube->channels->listChannels('contentDetails', array("forUsername" => "cocacola",));

            } catch (Google_ServiceException $e) {
                //return "<p>A service error occurred: <code>%s</code></p>".htmlspecialchars($e->getMessage());
                return "<div></div>";
            } catch (Google_Exception $e) {
                //return "<p>An client error occurred: <code>%s</code></p>".htmlspecialchars($e->getMessage());
                return "<div></div>";
            }

            $mediaList = array();
            $result = '';

            foreach ($channelsResponse['items'] as $channel) {
                $uploadsListId = $channel['contentDetails']['relatedPlaylists']['uploads'];

                $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
                    'playlistId' => $uploadsListId,
                    'maxResults' => 50
                ));

                foreach ($playlistItemsResponse['items'] as $playlistItem) {
                    $result .= sprintf('<div>%s (%s)-(%s)</div>',
                        $playlistItem['snippet']['title'],
                        $playlistItem['snippet']['resourceId']['videoId'],
                        $playlistItem['snippet']['resourceId']['kind']
                    );
                    $tempMedia = new media();
                    $tempMedia->title = $playlistItem['snippet']['title'];
                    $tempMedia->url = "http://www.youtube.com/watch?v=".$playlistItem['snippet']['resourceId']['videoId'];
                    array_push($mediaList,$tempMedia);
                }

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