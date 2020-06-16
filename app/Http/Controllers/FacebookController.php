<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacebookController extends Controller
{
    protected $fb;
    protected $helper;
    public function __construct(){
        if (!session_id()) {
            session_start();
        }
        $this->fb = new  \Facebook\Facebook([
            'app_id' => '190991432110496',
            'app_secret' => 'c4f4b63930fa29fb967d40fa577bf126',
            'default_graph_version' => 'v7.0',
           // 'default_access_token' =>'EAACttKh3UaABAJ1tfPZBbX9VstSioMUZAiRysFJmIUS7pHYhG47u2H17oXZCHjZC5hhwbGxZBOQqBc4xAx0nhAKlUX2ZBckOgELfQ2eHH6ixWhZCcsssZCbTLVM9Wt1IXhqG5X8nIp9s8CcHUNX6z2KN40IZC3WbyZBaafLyP9BD7sDQZDZD'
            ]);
        $this->helper = $this->fb->getRedirectLoginHelper();
    }
    public function getPageToken(){
    
            //dd($this->fb);
            //$this->fb->getOAuth2Client();
           
           // $accessToken = $this->fb->getAccessToken();
           // dd($helper);
            try {
                // Get the \Facebook\GraphNodes\GraphUser object for the current user.
                // If you provided a 'default_access_token', the '{access-token}' is optional.
                $response = $this->fb->get(`/me` );
              } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
              } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
              }
              $me = $response->getGraphUser();
              echo 'Logged in as ' . $me->getName();
    }

    public function callBack(){
    
          
          try {
            $accessToken = $this->helper->getAccessToken();
           // dd($accessToken);
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          
          if (! isset($accessToken)) {
            if ($helper->getError()) {
              header('HTTP/1.0 401 Unauthorized');
              echo "Error: " . $helper->getError() . "\n";
              echo "Error Code: " . $helper->getErrorCode() . "\n";
              echo "Error Reason: " . $helper->getErrorReason() . "\n";
              echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
              header('HTTP/1.0 400 Bad Request');
              echo 'Bad request';
            }
            exit;
          }
          
          // Logged in
          echo '<h3>Access Token</h3>';
          var_dump($accessToken->getValue());
          
          // The OAuth 2.0 client handler helps us manage access tokens
          $oAuth2Client = $this->fb->getOAuth2Client();
          
          // Get the access token metadata from /debug_token
          $tokenMetadata = $oAuth2Client->debugToken($accessToken);
          echo '<h3>Metadata</h3>';
          dd($tokenMetadata);
          
          // Validation (these will throw FacebookSDKException's when they fail)
         // $tokenMetadata->validateAppId($config['app_id']);
          // If you know the user ID this access token belongs to, you can validate it here
          //$tokenMetadata->validateUserId('123');
          $tokenMetadata->validateExpiration();
          
          if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
              $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
              echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
              exit;
            }
          
            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
          }
          
          $_SESSION['fb_access_token'] = (string) $accessToken;
          
          // User is logged in with a long-lived access token.
          // You can redirect them to a members-only page.
          //header('Location: https://example.com/members.php');
    }
    public function login(){
        // $this->fb = new Facebook\Facebook([
        //     'app_id' => '{app-id}',
        //     'app_secret' => '{app-secret}',
        //     'default_graph_version' => 'v2.10',
        //     ]);
          
        //   $helper = $this->fb->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
         // dd(route('facebook.callback'));
        $url =route('facebook.callback');
        $loginUrl = $this->helper->getLoginUrl($url, $permissions);
        return view('login_php',['url'=> $loginUrl]);
    }

    public function postPage(){
        $token = $_SESSION['fb_access_token'];
        $linkData = [
            'link' => 'http://www.example.com',
            'message' => 'User provided message',
            ];
          
          try {
            // Returns a `Facebook\FacebookResponse` object
            //$response = $this->fb->get('/me/accounts', $token);
            $response = $this->fb->get('/me?fields=name,email,birthday', $token);
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          //dd(json_decode($response->getBody())->data[0]->access_token);
          //dd(($response->getHeaders()));get header
          dd($response);
          $graphNode = $response->getGraphNode();
          dd($graphNode);
          echo 'Posted with id: ' . $graphNode['id'];
    }

    public function getJavascript(){
        $jsHelper = $this->fb->getJavaScriptHelper();
       // dd($jsHelper->getRawSignedRequest());
        //dd($jsHelper->getUserId());//get id user
       // dd($jsHelper->getSignedRequest());
        dd($jsHelper->getAccessToken());//short token 
    }

    public function postVideo(){
        //dd(asset('assets/img/videoquandoi.mp4'));
        //$token = $_SESSION['fb_access_token'];
        $data = [
            'title' => 'My awesome video',
            'description' => 'More info about my awesome video.22',
            'source' => $this->fb->videoToUpload(asset('assets/img/videoquandoi.mp4')),
            
          ];
        //$token=``;
        
        try {
        $response = $this->fb->post('/100644248350802/videos', $data);
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Error: ' . $e->getMessage();
        exit;
        }
        dd($response);
        $graphNode = $response->getGraphNode();
        
        echo 'Video ID: ' . $graphNode['id'];
    }

    public function postContent(){
        //$this->fb->setDefaultAccessToken();//set token default
        //$status = $this->fb->post('/' . $user_details[0]->net_id . '/photos', array('url' => $img['body'], 'published' => FALSE), $token);//
        $token = `EAACttKh3UaABAJ1tfPZBbX9VstSioMUZAiRysFJmIUS7pHYhG47u2H17oXZCHjZC5hhwbGxZBOQqBc4xAx0nhAKlUX2ZBckOgELfQ2eHH6ixWhZCcsssZCbTLVM9Wt1IXhqG5X8nIp9s8CcHUNX6z2KN40IZC3WbyZBaafLyP9BD7sDQZDZD`;
        $photos = array();
        $photos['attached_media[' . 0 . ']'] = '{"media_fbid":"' ."104156024666291". '"}';
        $photos['attached_media[' . 1 . ']'] = '{"media_fbid":"' ."104156137999613". '"}';
        $photos['message'] = 'User provided message';
        $linkData = [
            'link' => 'http://www.example.com',
            'message' => 'User provided message',
            ];
          
          try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->post('/100644248350802/feed', $photos, $token);
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          var_dump($response);
          $graphNode = $response->getGraphNode();
          
          echo 'Posted with id: ' . $graphNode['id'];
    }

    public function getProfile(){
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->get('/me?fields=id,name', '{access-token}');
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          
          $user = $response->getGraphUser();
          
          echo 'Name: ' . $user['name'];
    }

    public function postPhotos(){
        $data = [
            'message' => 'My awesome photo upload example.',
            'source' => $this->fb->fileToUpload('/path/to/photo.jpg'),
          ];
          
          try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->post('/me/photos', $data, '{access-token}');
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          
          $graphNode = $response->getGraphNode();
          
          echo 'Photo ID: ' . $graphNode['id'];
    }

    public function getPermissionUser(){
        $token = $_SESSION['fb_access_token'];
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->get(
              '/554721628578292/permissions',
              $token
            );
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
          dd($response->getDecodedBody());
          $graphNode = $response->getGraphNode();
          dd($graphNode);
    }
    
}
