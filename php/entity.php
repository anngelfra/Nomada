<?php
    class responseMedia{
        public $items;
    }

	class sourceKind{
		const none = 0;
		const youtube = 1;
		const facebook = 2;
	}
    class media{
        public $url;
        public $title;
        public $kind;
        public $albumTitle;
        public $date;
        public function __construct() {
            $this->url = "";
            $this->kind = sourceKind::none;
            $this->title = "";
            $this->albumTitle = "";
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

            require_once 'API/google-api-php-client/src/Google_Client.php';
            require_once 'API/google-api-php-client/src/contrib/Google_YouTubeService.php';


            $DEVELOPER_KEY = 'AIzaSyDCRA3GnWNa8UBUfrkxpSe0BVcVFMwE7ug';
            $client = new Google_Client();
            $client->setDeveloperKey($DEVELOPER_KEY);

            $youtube = new Google_YoutubeService($client);

            $userName = $this->user;
            $maxResults = $this->maxResults;

            try {
                $channelsResponse = $youtube->channels->listChannels('contentDetails', array("forUsername" => $userName,));

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
                    'maxResults' => $maxResults
                ));

                foreach ($playlistItemsResponse['items'] as $playlistItem) {
                    $result .= sprintf('<div>%s (%s)-(%s)</div>',
                        $playlistItem['snippet']['title'],
                        $playlistItem['snippet']['resourceId']['videoId'],
                        $playlistItem['snippet']['resourceId']['kind']
                    );
                    $tempMedia = new media();
                    $tempMedia->title = $playlistItem['snippet']['title'];
                    $tempMedia->kind = $this->kind;
                    $tempMedia->url = "www.youtube.com/embed/".$playlistItem['snippet']['resourceId']['videoId'];
                    $tempMedia->date = date('y/m/d', strtotime($playlistItem['snippet']['publishedAt']));
                    array_push($mediaList,$tempMedia);
                }

            }
            return $mediaList;
        }
	}
    class facebookContentSource extends contentSourceBase{
        public $maxResults =10;
        public function __construct() {
            $this->kind = sourceKind::facebook;
        }

        public function retrieveMedia(){
            $facebookUrl = "https://graph.facebook.com/".$this->user."?fields=albums.limit(5).fields(name,%20photos.limit(5).fields(name,%20picture))";

            try {
                $page = file_get_contents($facebookUrl);
            } catch (Exception $e) {
                return "<div>".$e->getMessage()."</div>";
            }
            $fbResponse =json_decode($page);

            $mediaList = array();

            foreach ($fbResponse->albums->data as $album){
                //echo ">>>>>>".json_encode($album)."<<<<<<<";

                foreach($album->photos->data as $postObject){
                    $tempMedia = new media();
                    $tempMedia->albumTitle = $album->name;
                    $tempMedia->kind = $this->kind;
                    $tempMedia->title = "fake fb";
                    $tempMedia->url = $postObject->picture;
                    $tempMedia->date = date('y/m/d', strtotime($postObject->created_time));
                    array_push($mediaList, $tempMedia);
                }
            }

            return $mediaList;
        }
    }
	class community{
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