<?php
/*
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */


  /**
   * The "activities" collection of methods.
   * Typical usage is:
   *  <code>
   *   $plusService = new Google_PlusService(...);
   *   $activities = $plusService->activities;
   *  </code>
   */
  class Google_ActivitiesServiceResource extends Google_ServiceResource {

    /**
     * Get an activity. (activities.get)
     *
     * @param string $activityId The ID of the activity to get.
     * @param array $optParams Optional parameters.
     * @return Google_Activity
     */
    public function get($activityId, $optParams = array()) {
      $params = array('activityId' => $activityId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Activity($data);
      } else {
        return $data;
      }
    }
    /**
     * List all of the activities in the specified collection for a particular user. (activities.list)
     *
     * @param string $userId The ID of the user to get activities for. The special value "me" can be used to indicate the authenticated user.
     * @param string $collection The collection of activities to list.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults The maximum number of activities to include in the response, which is used for paging. For any response, the actual number returned might be less than the specified maxResults.
     * @opt_param string pageToken The continuation token, which is used to page through large result sets. To get the next page of results, set this parameter to the value of "nextPageToken" from the previous response.
     * @return Google_ActivityFeed
     */
    public function listActivities($userId, $collection, $optParams = array()) {
      $params = array('userId' => $userId, 'collection' => $collection);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_ActivityFeed($data);
      } else {
        return $data;
      }
    }
    /**
     * Search public activities. (activities.search)
     *
     * @param string $query Full-text search query string.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string language Specify the preferred language to search with. See search language codes for available values.
     * @opt_param string maxResults The maximum number of activities to include in the response, which is used for paging. For any response, the actual number returned might be less than the specified maxResults.
     * @opt_param string orderBy Specifies how to order search results.
     * @opt_param string pageToken The continuation token, which is used to page through large result sets. To get the next page of results, set this parameter to the value of "nextPageToken" from the previous response. This token can be of any length.
     * @return Google_ActivityFeed
     */
    public function search($query, $optParams = array()) {
      $params = array('query' => $query);
      $params = array_merge($params, $optParams);
      $data = $this->__call('search', array($params));
      if ($this->useObjects()) {
        return new Google_ActivityFeed($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "comments" collection of methods.
   * Typical usage is:
   *  <code>
   *   $plusService = new Google_PlusService(...);
   *   $comments = $plusService->comments;
   *  </code>
   */
  class Google_CommentsServiceResource extends Google_ServiceResource {

    /**
     * Get a comment. (comments.get)
     *
     * @param string $commentId The ID of the comment to get.
     * @param array $optParams Optional parameters.
     * @return Google_Comment
     */
    public function get($commentId, $optParams = array()) {
      $params = array('commentId' => $commentId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Comment($data);
      } else {
        return $data;
      }
    }
    /**
     * List all of the comments for an activity. (comments.list)
     *
     * @param string $activityId The ID of the activity to get comments for.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults The maximum number of comments to include in the response, which is used for paging. For any response, the actual number returned might be less than the specified maxResults.
     * @opt_param string pageToken The continuation token, which is used to page through large result sets. To get the next page of results, set this parameter to the value of "nextPageToken" from the previous response.
     * @opt_param string sortOrder The order in which to sort the list of comments.
     * @return Google_CommentFeed
     */
    public function listComments($activityId, $optParams = array()) {
      $params = array('activityId' => $activityId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_CommentFeed($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "moments" collection of methods.
   * Typical usage is:
   *  <code>
   *   $plusService = new Google_PlusService(...);
   *   $moments = $plusService->moments;
   *  </code>
   */
  class Google_MomentsServiceResource extends Google_ServiceResource {

    /**
     * Record a moment representing a user's activity such as making a purchase or commenting on a blog.
     * (moments.insert)
     *
     * @param string $userId The ID of the user to record activities for. The only valid values are "me" and the ID of the authenticated user.
     * @param string $collection The collection to which to write moments.
     * @param Google_Moment $postBody
     * @param array $optParams Optional parameters.
     *
     * @opt_param bool debug Return the moment as written. Should be used only for debugging.
     * @return Google_Moment
     */
    public function insert($userId, $collection, Google_Moment $postBody, $optParams = array()) {
      $params = array('userId' => $userId, 'collection' => $collection, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Moment($data);
      } else {
        return $data;
      }
    }
    /**
     * List all of the moments for a particular user. (moments.list)
     *
     * @param string $userId The ID of the user to get moments for. The special value "me" can be used to indicate the authenticated user.
     * @param string $collection The collection of moments to list.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults The maximum number of moments to include in the response, which is used for paging. For any response, the actual number returned might be less than the specified maxResults.
     * @opt_param string pageToken The continuation token, which is used to page through large result sets. To get the next page of results, set this parameter to the value of "nextPageToken" from the previous response.
     * @opt_param string targetUrl Only moments containing this targetUrl will be returned.
     * @opt_param string type Only moments of this type will be returned.
     * @return Google_MomentsFeed
     */
    public function listMoments($userId, $collection, $optParams = array()) {
      $params = array('userId' => $userId, 'collection' => $collection);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_MomentsFeed($data);
      } else {
        return $data;
      }
    }
    /**
     * Delete a moment. (moments.remove)
     *
     * @param string $id The ID of the moment to delete.
     * @param array $optParams Optional parameters.
     */
    public function remove($id, $optParams = array()) {
      $params = array('id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('remove', array($params));
      return $data;
    }
  }

  /**
   * The "people" collection of methods.
   * Typical usage is:
   *  <code>
   *   $plusService = new Google_PlusService(...);
   *   $people = $plusService->people;
   *  </code>
   */
  class Google_PeopleServiceResource extends Google_ServiceResource {

    /**
     * Get a person's profile. If your app uses scope https://www.googleapis.com/auth/plus.login, this
     * method is guaranteed to return ageRange and language. (people.get)
     *
     * @param string $userId The ID of the person to get the profile for. The special value "me" can be used to indicate the authenticated user.
     * @param array $optParams Optional parameters.
     * @return Google_Person
     */
    public function get($userId, $optParams = array()) {
      $params = array('userId' => $userId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Person($data);
      } else {
        return $data;
      }
    }
    /**
     * List all of the people in the specified collection. (people.list)
     *
     * @param string $userId Get the collection of people for the person identified. Use "me" to indicate the authenticated user.
     * @param string $collection The collection of people to list.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults The maximum number of people to include in the response, which is used for paging. For any response, the actual number returned might be less than the specified maxResults.
     * @opt_param string orderBy The order to return people in.
     * @opt_param string pageToken The continuation token, which is used to page through large result sets. To get the next page of results, set this parameter to the value of "nextPageToken" from the previous response.
     * @return Google_PeopleFeed
     */
    public function listPeople($userId, $collection, $optParams = array()) {
      $params = array('userId' => $userId, 'collection' => $collection);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_PeopleFeed($data);
      } else {
        return $data;
      }
    }
    /**
     * List all of the people in the specified collection for a particular activity.
     * (people.listByActivity)
     *
     * @param string $activityId The ID of the activity to get the list of people for.
     * @param string $collection The collection of people to list.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults The maximum number of people to include in the response, which is used for paging. For any response, the actual number returned might be less than the specified maxResults.
     * @opt_param string pageToken The continuation token, which is used to page through large result sets. To get the next page of results, set this parameter to the value of "nextPageToken" from the previous response.
     * @return Google_PeopleFeed
     */
    public function listByActivity($activityId, $collection, $optParams = array()) {
      $params = array('activityId' => $activityId, 'collection' => $collection);
      $params = array_merge($params, $optParams);
      $data = $this->__call('listByActivity', array($params));
      if ($this->useObjects()) {
        return new Google_PeopleFeed($data);
      } else {
        return $data;
      }
    }
    /**
     * Search all public profiles. (people.search)
     *
     * @param string $query Specify a query string for full text search of public text in all profiles.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string language Specify the preferred language to search with. See search language codes for available values.
     * @opt_param string maxResults The maximum number of people to include in the response, which is used for paging. For any response, the actual number returned might be less than the specified maxResults.
     * @opt_param string pageToken The continuation token, which is used to page through large result sets. To get the next page of results, set this parameter to the value of "nextPageToken" from the previous response. This token can be of any length.
     * @return Google_PeopleFeed
     */
    public function search($query, $optParams = array()) {
      $params = array('query' => $query);
      $params = array_merge($params, $optParams);
      $data = $this->__call('search', array($params));
      if ($this->useObjects()) {
        return new Google_PeopleFeed($data);
      } else {
        return $data;
      }
    }
  }

/**
 * Service definition for Google_Plus (v1).
 *
 * <p>
 * The Google+ API enables developers to build on top of the Google+ platform.
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="https://developers.google.com/+/api/" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_PlusService extends Google_Service {
  public $activities;
  public $comments;
  public $moments;
  public $people;
  /**
   * Constructs the internal representation of the Plus service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client) {
    $this->servicePath = 'plus/v1/';
    $this->version = 'v1';
    $this->serviceName = 'plus';

    $client->addService($this->serviceName, $this->version);
    $this->activities = new Google_ActivitiesServiceResource($this, $this->serviceName, 'activities', json_decode('{"methods": {"get": {"id": "plus.activities.get", "path": "activities/{activityId}", "httpMethod": "GET", "parameters": {"activityId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Activity"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}, "list": {"id": "plus.activities.list", "path": "people/{userId}/activities/{collection}", "httpMethod": "GET", "parameters": {"collection": {"type": "string", "required": true, "enum": ["public"], "location": "path"}, "maxResults": {"type": "integer", "default": "20", "format": "uint32", "minimum": "1", "maximum": "100", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "userId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "ActivityFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}, "search": {"id": "plus.activities.search", "path": "activities", "httpMethod": "GET", "parameters": {"language": {"type": "string", "default": "en-US", "location": "query"}, "maxResults": {"type": "integer", "default": "10", "format": "uint32", "minimum": "1", "maximum": "20", "location": "query"}, "orderBy": {"type": "string", "default": "recent", "enum": ["best", "recent"], "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "query": {"type": "string", "required": true, "location": "query"}}, "response": {"$ref": "ActivityFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}}}', true));
    $this->comments = new Google_CommentsServiceResource($this, $this->serviceName, 'comments', json_decode('{"methods": {"get": {"id": "plus.comments.get", "path": "comments/{commentId}", "httpMethod": "GET", "parameters": {"commentId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Comment"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}, "list": {"id": "plus.comments.list", "path": "activities/{activityId}/comments", "httpMethod": "GET", "parameters": {"activityId": {"type": "string", "required": true, "location": "path"}, "maxResults": {"type": "integer", "default": "20", "format": "uint32", "minimum": "0", "maximum": "500", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "sortOrder": {"type": "string", "default": "ascending", "enum": ["ascending", "descending"], "location": "query"}}, "response": {"$ref": "CommentFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}}}', true));
    $this->moments = new Google_MomentsServiceResource($this, $this->serviceName, 'moments', json_decode('{"methods": {"insert": {"id": "plus.moments.insert", "path": "people/{userId}/moments/{collection}", "httpMethod": "POST", "parameters": {"collection": {"type": "string", "required": true, "enum": ["vault"], "location": "path"}, "debug": {"type": "boolean", "location": "query"}, "userId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Moment"}, "response": {"$ref": "Moment"}, "scopes": ["https://www.googleapis.com/auth/plus.login"]}, "list": {"id": "plus.moments.list", "path": "people/{userId}/moments/{collection}", "httpMethod": "GET", "parameters": {"collection": {"type": "string", "required": true, "enum": ["vault"], "location": "path"}, "maxResults": {"type": "integer", "default": "20", "format": "uint32", "minimum": "1", "maximum": "100", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "targetUrl": {"type": "string", "location": "query"}, "type": {"type": "string", "location": "query"}, "userId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "MomentsFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login"]}, "remove": {"id": "plus.moments.remove", "path": "moments/{id}", "httpMethod": "DELETE", "parameters": {"id": {"type": "string", "required": true, "location": "path"}}, "scopes": ["https://www.googleapis.com/auth/plus.login"]}}}', true));
    $this->people = new Google_PeopleServiceResource($this, $this->serviceName, 'people', json_decode('{"methods": {"get": {"id": "plus.people.get", "path": "people/{userId}", "httpMethod": "GET", "parameters": {"userId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Person"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}, "list": {"id": "plus.people.list", "path": "people/{userId}/people/{collection}", "httpMethod": "GET", "parameters": {"collection": {"type": "string", "required": true, "enum": ["visible"], "location": "path"}, "maxResults": {"type": "integer", "default": "100", "format": "uint32", "minimum": "1", "maximum": "100", "location": "query"}, "orderBy": {"type": "string", "enum": ["alphabetical", "best"], "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "userId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "PeopleFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login"]}, "listByActivity": {"id": "plus.people.listByActivity", "path": "activities/{activityId}/people/{collection}", "httpMethod": "GET", "parameters": {"activityId": {"type": "string", "required": true, "location": "path"}, "collection": {"type": "string", "required": true, "enum": ["plusoners", "resharers"], "location": "path"}, "maxResults": {"type": "integer", "default": "20", "format": "uint32", "minimum": "1", "maximum": "100", "location": "query"}, "pageToken": {"type": "string", "location": "query"}}, "response": {"$ref": "PeopleFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}, "search": {"id": "plus.people.search", "path": "people", "httpMethod": "GET", "parameters": {"language": {"type": "string", "default": "en-US", "location": "query"}, "maxResults": {"type": "integer", "default": "25", "format": "uint32", "minimum": "1", "maximum": "50", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "query": {"type": "string", "required": true, "location": "query"}}, "response": {"$ref": "PeopleFeed"}, "scopes": ["https://www.googleapis.com/auth/plus.login", "https://www.googleapis.com/auth/plus.me"]}}}', true));

  }
}



class Google_Acl extends Google_Model {
  public $description;
  public $items;
  public $kind;
  protected $__itemsType = 'Google_PlusAclentryResource';
  protected $__itemsDataType = 'array';

  public function getDescription() {
    return $this->description;
  }

  public function setDescription( $description) {
    $this->description = $description;
  }

  public function getItems() {
    return $this->items;
  }

  public function setItems(/* array(Google_PlusAclentryResource) */ $items) {
    $this->assertIsArray($items, 'Google_PlusAclentryResource', __METHOD__);
    $this->items = $items;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }
}

class Google_Activity extends Google_Model {
  public $access;
  public $actor;
  public $address;
  public $annotation;
  public $crosspostSource;
  public $etag;
  public $geocode;
  public $id;
  public $kind;
  public $location;
  public $object;
  public $placeId;
  public $placeName;
  public $provider;
  public $published;
  public $radius;
  public $title;
  public $updated;
  public $url;
  public $verb;
  protected $__accessType = 'Google_Acl';
  protected $__accessDataType = '';
  protected $__actorType = 'Google_ActivityActor';
  protected $__actorDataType = '';
  protected $__locationType = 'Google_Place';
  protected $__locationDataType = '';
  protected $__objectType = 'Google_ActivityObject';
  protected $__objectDataType = '';
  protected $__providerType = 'Google_ActivityProvider';
  protected $__providerDataType = '';

  public function getAccess() {
    return $this->access;
  }

  public function setAccess(Google_Acl $access) {
    $this->access = $access;
  }

  public function getActor() {
    return $this->actor;
  }

  public function setActor(Google_ActivityActor $actor) {
    $this->actor = $actor;
  }

  public function getAddress() {
    return $this->address;
  }

  public function setAddress( $address) {
    $this->address = $address;
  }

  public function getAnnotation() {
    return $this->annotation;
  }

  public function setAnnotation( $annotation) {
    $this->annotation = $annotation;
  }

  public function getCrosspostSource() {
    return $this->crosspostSource;
  }

  public function setCrosspostSource( $crosspostSource) {
    $this->crosspostSource = $crosspostSource;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getGeocode() {
    return $this->geocode;
  }

  public function setGeocode( $geocode) {
    $this->geocode = $geocode;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getLocation() {
    return $this->location;
  }

  public function setLocation(Google_Place $location) {
    $this->location = $location;
  }

  public function getObject() {
    return $this->object;
  }

  public function setObject(Google_ActivityObject $object) {
    $this->object = $object;
  }

  public function getPlaceId() {
    return $this->placeId;
  }

  public function setPlaceId( $placeId) {
    $this->placeId = $placeId;
  }

  public function getPlaceName() {
    return $this->placeName;
  }

  public function setPlaceName( $placeName) {
    $this->placeName = $placeName;
  }

  public function getProvider() {
    return $this->provider;
  }

  public function setProvider(Google_ActivityProvider $provider) {
    $this->provider = $provider;
  }

  public function getPublished() {
    return $this->published;
  }

  public function setPublished( $published) {
    $this->published = $published;
  }

  public function getRadius() {
    return $this->radius;
  }

  public function setRadius( $radius) {
    $this->radius = $radius;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle( $title) {
    $this->title = $title;
  }

  public function getUpdated() {
    return $this->updated;
  }

  public function setUpdated( $updated) {
    $this->updated = $updated;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }

  public function getVerb() {
    return $this->verb;
  }

  public function setVerb( $verb) {
    $this->verb = $verb;
  }
}

class Google_ActivityActor extends Google_Model {
  public $displayName;
  public $id;
  public $image;
  public $name;
  public $url;
  protected $__imageType = 'Google_ActivityActorImage';
  protected $__imageDataType = '';
  protected $__nameType = 'Google_ActivityActorName';
  protected $__nameDataType = '';

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getImage() {
    return $this->image;
  }

  public function setImage(Google_ActivityActorImage $image) {
    $this->image = $image;
  }

  public function getName() {
    return $this->name;
  }

  public function setName(Google_ActivityActorName $name) {
    $this->name = $name;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ActivityActorImage extends Google_Model {
  public $url;

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ActivityActorName extends Google_Model {
  public $familyName;
  public $givenName;

  public function getFamilyName() {
    return $this->familyName;
  }

  public function setFamilyName( $familyName) {
    $this->familyName = $familyName;
  }

  public function getGivenName() {
    return $this->givenName;
  }

  public function setGivenName( $givenName) {
    $this->givenName = $givenName;
  }
}

class Google_ActivityFeed extends Google_Model {
  public $etag;
  public $id;
  public $items;
  public $kind;
  public $nextLink;
  public $nextPageToken;
  public $selfLink;
  public $title;
  public $updated;
  protected $__itemsType = 'Google_Activity';
  protected $__itemsDataType = 'array';

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getItems() {
    return $this->items;
  }

  public function setItems(/* array(Google_Activity) */ $items) {
    $this->assertIsArray($items, 'Google_Activity', __METHOD__);
    $this->items = $items;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getNextLink() {
    return $this->nextLink;
  }

  public function setNextLink( $nextLink) {
    $this->nextLink = $nextLink;
  }

  public function getNextPageToken() {
    return $this->nextPageToken;
  }

  public function setNextPageToken( $nextPageToken) {
    $this->nextPageToken = $nextPageToken;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle( $title) {
    $this->title = $title;
  }

  public function getUpdated() {
    return $this->updated;
  }

  public function setUpdated( $updated) {
    $this->updated = $updated;
  }
}

class Google_ActivityObject extends Google_Model {
  public $actor;
  public $attachments;
  public $content;
  public $id;
  public $objectType;
  public $originalContent;
  public $plusoners;
  public $replies;
  public $resharers;
  public $url;
  protected $__actorType = 'Google_ActivityObjectActor';
  protected $__actorDataType = '';
  protected $__attachmentsType = 'Google_ActivityObjectAttachments';
  protected $__attachmentsDataType = 'array';
  protected $__plusonersType = 'Google_ActivityObjectPlusoners';
  protected $__plusonersDataType = '';
  protected $__repliesType = 'Google_ActivityObjectReplies';
  protected $__repliesDataType = '';
  protected $__resharersType = 'Google_ActivityObjectResharers';
  protected $__resharersDataType = '';

  public function getActor() {
    return $this->actor;
  }

  public function setActor(Google_ActivityObjectActor $actor) {
    $this->actor = $actor;
  }

  public function getAttachments() {
    return $this->attachments;
  }

  public function setAttachments(/* array(Google_ActivityObjectAttachments) */ $attachments) {
    $this->assertIsArray($attachments, 'Google_ActivityObjectAttachments', __METHOD__);
    $this->attachments = $attachments;
  }

  public function getContent() {
    return $this->content;
  }

  public function setContent( $content) {
    $this->content = $content;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getObjectType() {
    return $this->objectType;
  }

  public function setObjectType( $objectType) {
    $this->objectType = $objectType;
  }

  public function getOriginalContent() {
    return $this->originalContent;
  }

  public function setOriginalContent( $originalContent) {
    $this->originalContent = $originalContent;
  }

  public function getPlusoners() {
    return $this->plusoners;
  }

  public function setPlusoners(Google_ActivityObjectPlusoners $plusoners) {
    $this->plusoners = $plusoners;
  }

  public function getReplies() {
    return $this->replies;
  }

  public function setReplies(Google_ActivityObjectReplies $replies) {
    $this->replies = $replies;
  }

  public function getResharers() {
    return $this->resharers;
  }

  public function setResharers(Google_ActivityObjectResharers $resharers) {
    $this->resharers = $resharers;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ActivityObjectActor extends Google_Model {
  public $displayName;
  public $id;
  public $image;
  public $url;
  protected $__imageType = 'Google_ActivityObjectActorImage';
  protected $__imageDataType = '';

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getImage() {
    return $this->image;
  }

  public function setImage(Google_ActivityObjectActorImage $image) {
    $this->image = $image;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ActivityObjectActorImage extends Google_Model {
  public $url;

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ActivityObjectAttachments extends Google_Model {
  public $content;
  public $displayName;
  public $embed;
  public $fullImage;
  public $id;
  public $image;
  public $objectType;
  public $thumbnails;
  public $url;
  protected $__embedType = 'Google_ActivityObjectAttachmentsEmbed';
  protected $__embedDataType = '';
  protected $__fullImageType = 'Google_ActivityObjectAttachmentsFullImage';
  protected $__fullImageDataType = '';
  protected $__imageType = 'Google_ActivityObjectAttachmentsImage';
  protected $__imageDataType = '';
  protected $__thumbnailsType = 'Google_ActivityObjectAttachmentsThumbnails';
  protected $__thumbnailsDataType = 'array';

  public function getContent() {
    return $this->content;
  }

  public function setContent( $content) {
    $this->content = $content;
  }

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getEmbed() {
    return $this->embed;
  }

  public function setEmbed(Google_ActivityObjectAttachmentsEmbed $embed) {
    $this->embed = $embed;
  }

  public function getFullImage() {
    return $this->fullImage;
  }

  public function setFullImage(Google_ActivityObjectAttachmentsFullImage $fullImage) {
    $this->fullImage = $fullImage;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getImage() {
    return $this->image;
  }

  public function setImage(Google_ActivityObjectAttachmentsImage $image) {
    $this->image = $image;
  }

  public function getObjectType() {
    return $this->objectType;
  }

  public function setObjectType( $objectType) {
    $this->objectType = $objectType;
  }

  public function getThumbnails() {
    return $this->thumbnails;
  }

  public function setThumbnails(/* array(Google_ActivityObjectAttachmentsThumbnails) */ $thumbnails) {
    $this->assertIsArray($thumbnails, 'Google_ActivityObjectAttachmentsThumbnails', __METHOD__);
    $this->thumbnails = $thumbnails;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ActivityObjectAttachmentsEmbed extends Google_Model {
  public $type;
  public $url;

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ActivityObjectAttachmentsFullImage extends Google_Model {
  public $height;
  public $type;
  public $url;
  public $width;

  public function getHeight() {
    return $this->height;
  }

  public function setHeight( $height) {
    $this->height = $height;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }

  public function getWidth() {
    return $this->width;
  }

  public function setWidth( $width) {
    $this->width = $width;
  }
}

class Google_ActivityObjectAttachmentsImage extends Google_Model {
  public $height;
  public $type;
  public $url;
  public $width;

  public function getHeight() {
    return $this->height;
  }

  public function setHeight( $height) {
    $this->height = $height;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }

  public function getWidth() {
    return $this->width;
  }

  public function setWidth( $width) {
    $this->width = $width;
  }
}

class Google_ActivityObjectAttachmentsThumbnails extends Google_Model {
  public $description;
  public $image;
  public $url;
  protected $__imageType = 'Google_ActivityObjectAttachmentsThumbnailsImage';
  protected $__imageDataType = '';

  public function getDescription() {
    return $this->description;
  }

  public function setDescription( $description) {
    $this->description = $description;
  }

  public function getImage() {
    return $this->image;
  }

  public function setImage(Google_ActivityObjectAttachmentsThumbnailsImage $image) {
    $this->image = $image;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ActivityObjectAttachmentsThumbnailsImage extends Google_Model {
  public $height;
  public $type;
  public $url;
  public $width;

  public function getHeight() {
    return $this->height;
  }

  public function setHeight( $height) {
    $this->height = $height;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }

  public function getWidth() {
    return $this->width;
  }

  public function setWidth( $width) {
    $this->width = $width;
  }
}

class Google_ActivityObjectPlusoners extends Google_Model {
  public $selfLink;
  public $totalItems;

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}

class Google_ActivityObjectReplies extends Google_Model {
  public $selfLink;
  public $totalItems;

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}

class Google_ActivityObjectResharers extends Google_Model {
  public $selfLink;
  public $totalItems;

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}

class Google_ActivityProvider extends Google_Model {
  public $title;

  public function getTitle() {
    return $this->title;
  }

  public function setTitle( $title) {
    $this->title = $title;
  }
}

class Google_Comment extends Google_Model {
  public $actor;
  public $etag;
  public $id;
  public $inReplyTo;
  public $kind;
  public $object;
  public $plusoners;
  public $published;
  public $selfLink;
  public $updated;
  public $verb;
  protected $__actorType = 'Google_CommentActor';
  protected $__actorDataType = '';
  protected $__inReplyToType = 'Google_CommentInReplyTo';
  protected $__inReplyToDataType = 'array';
  protected $__objectType = 'Google_CommentObject';
  protected $__objectDataType = '';
  protected $__plusonersType = 'Google_CommentPlusoners';
  protected $__plusonersDataType = '';

  public function getActor() {
    return $this->actor;
  }

  public function setActor(Google_CommentActor $actor) {
    $this->actor = $actor;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getInReplyTo() {
    return $this->inReplyTo;
  }

  public function setInReplyTo(/* array(Google_CommentInReplyTo) */ $inReplyTo) {
    $this->assertIsArray($inReplyTo, 'Google_CommentInReplyTo', __METHOD__);
    $this->inReplyTo = $inReplyTo;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getObject() {
    return $this->object;
  }

  public function setObject(Google_CommentObject $object) {
    $this->object = $object;
  }

  public function getPlusoners() {
    return $this->plusoners;
  }

  public function setPlusoners(Google_CommentPlusoners $plusoners) {
    $this->plusoners = $plusoners;
  }

  public function getPublished() {
    return $this->published;
  }

  public function setPublished( $published) {
    $this->published = $published;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getUpdated() {
    return $this->updated;
  }

  public function setUpdated( $updated) {
    $this->updated = $updated;
  }

  public function getVerb() {
    return $this->verb;
  }

  public function setVerb( $verb) {
    $this->verb = $verb;
  }
}

class Google_CommentActor extends Google_Model {
  public $displayName;
  public $id;
  public $image;
  public $url;
  protected $__imageType = 'Google_CommentActorImage';
  protected $__imageDataType = '';

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getImage() {
    return $this->image;
  }

  public function setImage(Google_CommentActorImage $image) {
    $this->image = $image;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_CommentActorImage extends Google_Model {
  public $url;

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_CommentFeed extends Google_Model {
  public $etag;
  public $id;
  public $items;
  public $kind;
  public $nextLink;
  public $nextPageToken;
  public $title;
  public $updated;
  protected $__itemsType = 'Google_Comment';
  protected $__itemsDataType = 'array';

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getItems() {
    return $this->items;
  }

  public function setItems(/* array(Google_Comment) */ $items) {
    $this->assertIsArray($items, 'Google_Comment', __METHOD__);
    $this->items = $items;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getNextLink() {
    return $this->nextLink;
  }

  public function setNextLink( $nextLink) {
    $this->nextLink = $nextLink;
  }

  public function getNextPageToken() {
    return $this->nextPageToken;
  }

  public function setNextPageToken( $nextPageToken) {
    $this->nextPageToken = $nextPageToken;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle( $title) {
    $this->title = $title;
  }

  public function getUpdated() {
    return $this->updated;
  }

  public function setUpdated( $updated) {
    $this->updated = $updated;
  }
}

class Google_CommentInReplyTo extends Google_Model {
  public $id;
  public $url;

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_CommentObject extends Google_Model {
  public $content;
  public $objectType;
  public $originalContent;

  public function getContent() {
    return $this->content;
  }

  public function setContent( $content) {
    $this->content = $content;
  }

  public function getObjectType() {
    return $this->objectType;
  }

  public function setObjectType( $objectType) {
    $this->objectType = $objectType;
  }

  public function getOriginalContent() {
    return $this->originalContent;
  }

  public function setOriginalContent( $originalContent) {
    $this->originalContent = $originalContent;
  }
}

class Google_CommentPlusoners extends Google_Model {
  public $totalItems;

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}

class Google_ItemScope extends Google_Model {
  public $about;
  public $additionalName;
  public $address;
  public $addressCountry;
  public $addressLocality;
  public $addressRegion;
  public $associated_media;
  public $attendeeCount;
  public $attendees;
  public $audio;
  public $author;
  public $bestRating;
  public $birthDate;
  public $byArtist;
  public $caption;
  public $contentSize;
  public $contentUrl;
  public $contributor;
  public $dateCreated;
  public $dateModified;
  public $datePublished;
  public $description;
  public $duration;
  public $embedUrl;
  public $endDate;
  public $familyName;
  public $gender;
  public $geo;
  public $givenName;
  public $height;
  public $id;
  public $image;
  public $inAlbum;
  public $kind;
  public $latitude;
  public $location;
  public $longitude;
  public $name;
  public $partOfTVSeries;
  public $performers;
  public $playerType;
  public $postOfficeBoxNumber;
  public $postalCode;
  public $ratingValue;
  public $reviewRating;
  public $startDate;
  public $streetAddress;
  public $text;
  public $thumbnail;
  public $thumbnailUrl;
  public $tickerSymbol;
  public $type;
  public $url;
  public $width;
  public $worstRating;
  protected $__aboutType = 'Google_ItemScope';
  protected $__aboutDataType = '';
  protected $__addressType = 'Google_ItemScope';
  protected $__addressDataType = '';
  protected $__associated_mediaType = 'Google_ItemScope';
  protected $__associated_mediaDataType = 'array';
  protected $__attendeesType = 'Google_ItemScope';
  protected $__attendeesDataType = 'array';
  protected $__audioType = 'Google_ItemScope';
  protected $__audioDataType = '';
  protected $__authorType = 'Google_ItemScope';
  protected $__authorDataType = 'array';
  protected $__byArtistType = 'Google_ItemScope';
  protected $__byArtistDataType = '';
  protected $__contributorType = 'Google_ItemScope';
  protected $__contributorDataType = 'array';
  protected $__geoType = 'Google_ItemScope';
  protected $__geoDataType = '';
  protected $__inAlbumType = 'Google_ItemScope';
  protected $__inAlbumDataType = '';
  protected $__locationType = 'Google_ItemScope';
  protected $__locationDataType = '';
  protected $__partOfTVSeriesType = 'Google_ItemScope';
  protected $__partOfTVSeriesDataType = '';
  protected $__performersType = 'Google_ItemScope';
  protected $__performersDataType = 'array';
  protected $__reviewRatingType = 'Google_ItemScope';
  protected $__reviewRatingDataType = '';
  protected $__thumbnailType = 'Google_ItemScope';
  protected $__thumbnailDataType = '';

  public function getAbout() {
    return $this->about;
  }

  public function setAbout(Google_ItemScope $about) {
    $this->about = $about;
  }

  public function getAdditionalName() {
    return $this->additionalName;
  }

  public function setAdditionalName(/* array(Google_string) */ $additionalName) {
    $this->assertIsArray($additionalName, 'Google_string', __METHOD__);
    $this->additionalName = $additionalName;
  }

  public function getAddress() {
    return $this->address;
  }

  public function setAddress(Google_ItemScope $address) {
    $this->address = $address;
  }

  public function getAddressCountry() {
    return $this->addressCountry;
  }

  public function setAddressCountry( $addressCountry) {
    $this->addressCountry = $addressCountry;
  }

  public function getAddressLocality() {
    return $this->addressLocality;
  }

  public function setAddressLocality( $addressLocality) {
    $this->addressLocality = $addressLocality;
  }

  public function getAddressRegion() {
    return $this->addressRegion;
  }

  public function setAddressRegion( $addressRegion) {
    $this->addressRegion = $addressRegion;
  }

  public function getAssociated_media() {
    return $this->associated_media;
  }

  public function setAssociated_media(/* array(Google_ItemScope) */ $associated_media) {
    $this->assertIsArray($associated_media, 'Google_ItemScope', __METHOD__);
    $this->associated_media = $associated_media;
  }

  public function getAttendeeCount() {
    return $this->attendeeCount;
  }

  public function setAttendeeCount( $attendeeCount) {
    $this->attendeeCount = $attendeeCount;
  }

  public function getAttendees() {
    return $this->attendees;
  }

  public function setAttendees(/* array(Google_ItemScope) */ $attendees) {
    $this->assertIsArray($attendees, 'Google_ItemScope', __METHOD__);
    $this->attendees = $attendees;
  }

  public function getAudio() {
    return $this->audio;
  }

  public function setAudio(Google_ItemScope $audio) {
    $this->audio = $audio;
  }

  public function getAuthor() {
    return $this->author;
  }

  public function setAuthor(/* array(Google_ItemScope) */ $author) {
    $this->assertIsArray($author, 'Google_ItemScope', __METHOD__);
    $this->author = $author;
  }

  public function getBestRating() {
    return $this->bestRating;
  }

  public function setBestRating( $bestRating) {
    $this->bestRating = $bestRating;
  }

  public function getBirthDate() {
    return $this->birthDate;
  }

  public function setBirthDate( $birthDate) {
    $this->birthDate = $birthDate;
  }

  public function getByArtist() {
    return $this->byArtist;
  }

  public function setByArtist(Google_ItemScope $byArtist) {
    $this->byArtist = $byArtist;
  }

  public function getCaption() {
    return $this->caption;
  }

  public function setCaption( $caption) {
    $this->caption = $caption;
  }

  public function getContentSize() {
    return $this->contentSize;
  }

  public function setContentSize( $contentSize) {
    $this->contentSize = $contentSize;
  }

  public function getContentUrl() {
    return $this->contentUrl;
  }

  public function setContentUrl( $contentUrl) {
    $this->contentUrl = $contentUrl;
  }

  public function getContributor() {
    return $this->contributor;
  }

  public function setContributor(/* array(Google_ItemScope) */ $contributor) {
    $this->assertIsArray($contributor, 'Google_ItemScope', __METHOD__);
    $this->contributor = $contributor;
  }

  public function getDateCreated() {
    return $this->dateCreated;
  }

  public function setDateCreated( $dateCreated) {
    $this->dateCreated = $dateCreated;
  }

  public function getDateModified() {
    return $this->dateModified;
  }

  public function setDateModified( $dateModified) {
    $this->dateModified = $dateModified;
  }

  public function getDatePublished() {
    return $this->datePublished;
  }

  public function setDatePublished( $datePublished) {
    $this->datePublished = $datePublished;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription( $description) {
    $this->description = $description;
  }

  public function getDuration() {
    return $this->duration;
  }

  public function setDuration( $duration) {
    $this->duration = $duration;
  }

  public function getEmbedUrl() {
    return $this->embedUrl;
  }

  public function setEmbedUrl( $embedUrl) {
    $this->embedUrl = $embedUrl;
  }

  public function getEndDate() {
    return $this->endDate;
  }

  public function setEndDate( $endDate) {
    $this->endDate = $endDate;
  }

  public function getFamilyName() {
    return $this->familyName;
  }

  public function setFamilyName( $familyName) {
    $this->familyName = $familyName;
  }

  public function getGender() {
    return $this->gender;
  }

  public function setGender( $gender) {
    $this->gender = $gender;
  }

  public function getGeo() {
    return $this->geo;
  }

  public function setGeo(Google_ItemScope $geo) {
    $this->geo = $geo;
  }

  public function getGivenName() {
    return $this->givenName;
  }

  public function setGivenName( $givenName) {
    $this->givenName = $givenName;
  }

  public function getHeight() {
    return $this->height;
  }

  public function setHeight( $height) {
    $this->height = $height;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getImage() {
    return $this->image;
  }

  public function setImage( $image) {
    $this->image = $image;
  }

  public function getInAlbum() {
    return $this->inAlbum;
  }

  public function setInAlbum(Google_ItemScope $inAlbum) {
    $this->inAlbum = $inAlbum;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getLatitude() {
    return $this->latitude;
  }

  public function setLatitude( $latitude) {
    $this->latitude = $latitude;
  }

  public function getLocation() {
    return $this->location;
  }

  public function setLocation(Google_ItemScope $location) {
    $this->location = $location;
  }

  public function getLongitude() {
    return $this->longitude;
  }

  public function setLongitude( $longitude) {
    $this->longitude = $longitude;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getPartOfTVSeries() {
    return $this->partOfTVSeries;
  }

  public function setPartOfTVSeries(Google_ItemScope $partOfTVSeries) {
    $this->partOfTVSeries = $partOfTVSeries;
  }

  public function getPerformers() {
    return $this->performers;
  }

  public function setPerformers(/* array(Google_ItemScope) */ $performers) {
    $this->assertIsArray($performers, 'Google_ItemScope', __METHOD__);
    $this->performers = $performers;
  }

  public function getPlayerType() {
    return $this->playerType;
  }

  public function setPlayerType( $playerType) {
    $this->playerType = $playerType;
  }

  public function getPostOfficeBoxNumber() {
    return $this->postOfficeBoxNumber;
  }

  public function setPostOfficeBoxNumber( $postOfficeBoxNumber) {
    $this->postOfficeBoxNumber = $postOfficeBoxNumber;
  }

  public function getPostalCode() {
    return $this->postalCode;
  }

  public function setPostalCode( $postalCode) {
    $this->postalCode = $postalCode;
  }

  public function getRatingValue() {
    return $this->ratingValue;
  }

  public function setRatingValue( $ratingValue) {
    $this->ratingValue = $ratingValue;
  }

  public function getReviewRating() {
    return $this->reviewRating;
  }

  public function setReviewRating(Google_ItemScope $reviewRating) {
    $this->reviewRating = $reviewRating;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function setStartDate( $startDate) {
    $this->startDate = $startDate;
  }

  public function getStreetAddress() {
    return $this->streetAddress;
  }

  public function setStreetAddress( $streetAddress) {
    $this->streetAddress = $streetAddress;
  }

  public function getText() {
    return $this->text;
  }

  public function setText( $text) {
    $this->text = $text;
  }

  public function getThumbnail() {
    return $this->thumbnail;
  }

  public function setThumbnail(Google_ItemScope $thumbnail) {
    $this->thumbnail = $thumbnail;
  }

  public function getThumbnailUrl() {
    return $this->thumbnailUrl;
  }

  public function setThumbnailUrl( $thumbnailUrl) {
    $this->thumbnailUrl = $thumbnailUrl;
  }

  public function getTickerSymbol() {
    return $this->tickerSymbol;
  }

  public function setTickerSymbol( $tickerSymbol) {
    $this->tickerSymbol = $tickerSymbol;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }

  public function getWidth() {
    return $this->width;
  }

  public function setWidth( $width) {
    $this->width = $width;
  }

  public function getWorstRating() {
    return $this->worstRating;
  }

  public function setWorstRating( $worstRating) {
    $this->worstRating = $worstRating;
  }
}

class Google_Moment extends Google_Model {
  public $id;
  public $kind;
  public $result;
  public $startDate;
  public $target;
  public $type;
  protected $__resultType = 'Google_ItemScope';
  protected $__resultDataType = '';
  protected $__targetType = 'Google_ItemScope';
  protected $__targetDataType = '';

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getResult() {
    return $this->result;
  }

  public function setResult(Google_ItemScope $result) {
    $this->result = $result;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function setStartDate( $startDate) {
    $this->startDate = $startDate;
  }

  public function getTarget() {
    return $this->target;
  }

  public function setTarget(Google_ItemScope $target) {
    $this->target = $target;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }
}

class Google_MomentsFeed extends Google_Model {
  public $etag;
  public $items;
  public $kind;
  public $nextLink;
  public $nextPageToken;
  public $selfLink;
  public $title;
  public $updated;
  protected $__itemsType = 'Google_Moment';
  protected $__itemsDataType = 'array';

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getItems() {
    return $this->items;
  }

  public function setItems(/* array(Google_Moment) */ $items) {
    $this->assertIsArray($items, 'Google_Moment', __METHOD__);
    $this->items = $items;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getNextLink() {
    return $this->nextLink;
  }

  public function setNextLink( $nextLink) {
    $this->nextLink = $nextLink;
  }

  public function getNextPageToken() {
    return $this->nextPageToken;
  }

  public function setNextPageToken( $nextPageToken) {
    $this->nextPageToken = $nextPageToken;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle( $title) {
    $this->title = $title;
  }

  public function getUpdated() {
    return $this->updated;
  }

  public function setUpdated( $updated) {
    $this->updated = $updated;
  }
}

class Google_PeopleFeed extends Google_Model {
  public $etag;
  public $items;
  public $kind;
  public $nextPageToken;
  public $selfLink;
  public $title;
  public $totalItems;
  protected $__itemsType = 'Google_Person';
  protected $__itemsDataType = 'array';

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getItems() {
    return $this->items;
  }

  public function setItems(/* array(Google_Person) */ $items) {
    $this->assertIsArray($items, 'Google_Person', __METHOD__);
    $this->items = $items;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getNextPageToken() {
    return $this->nextPageToken;
  }

  public function setNextPageToken( $nextPageToken) {
    $this->nextPageToken = $nextPageToken;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle( $title) {
    $this->title = $title;
  }

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}

class Google_Person extends Google_Model {
  public $aboutMe;
  public $ageRange;
  public $birthday;
  public $braggingRights;
  public $circledByCount;
  public $cover;
  public $currentLocation;
  public $displayName;
  public $etag;
  public $gender;
  public $id;
  public $image;
  public $isPlusUser;
  public $kind;
  public $language;
  public $name;
  public $nickname;
  public $objectType;
  public $organizations;
  public $placesLived;
  public $plusOneCount;
  public $relationshipStatus;
  public $tagline;
  public $url;
  public $urls;
  public $verified;
  protected $__ageRangeType = 'Google_PersonAgeRange';
  protected $__ageRangeDataType = '';
  protected $__coverType = 'Google_PersonCover';
  protected $__coverDataType = '';
  protected $__imageType = 'Google_PersonImage';
  protected $__imageDataType = '';
  protected $__nameType = 'Google_PersonName';
  protected $__nameDataType = '';
  protected $__organizationsType = 'Google_PersonOrganizations';
  protected $__organizationsDataType = 'array';
  protected $__placesLivedType = 'Google_PersonPlacesLived';
  protected $__placesLivedDataType = 'array';
  protected $__urlsType = 'Google_PersonUrls';
  protected $__urlsDataType = 'array';

  public function getAboutMe() {
    return $this->aboutMe;
  }

  public function setAboutMe( $aboutMe) {
    $this->aboutMe = $aboutMe;
  }

  public function getAgeRange() {
    return $this->ageRange;
  }

  public function setAgeRange(Google_PersonAgeRange $ageRange) {
    $this->ageRange = $ageRange;
  }

  public function getBirthday() {
    return $this->birthday;
  }

  public function setBirthday( $birthday) {
    $this->birthday = $birthday;
  }

  public function getBraggingRights() {
    return $this->braggingRights;
  }

  public function setBraggingRights( $braggingRights) {
    $this->braggingRights = $braggingRights;
  }

  public function getCircledByCount() {
    return $this->circledByCount;
  }

  public function setCircledByCount( $circledByCount) {
    $this->circledByCount = $circledByCount;
  }

  public function getCover() {
    return $this->cover;
  }

  public function setCover(Google_PersonCover $cover) {
    $this->cover = $cover;
  }

  public function getCurrentLocation() {
    return $this->currentLocation;
  }

  public function setCurrentLocation( $currentLocation) {
    $this->currentLocation = $currentLocation;
  }

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getGender() {
    return $this->gender;
  }

  public function setGender( $gender) {
    $this->gender = $gender;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getImage() {
    return $this->image;
  }

  public function setImage(Google_PersonImage $image) {
    $this->image = $image;
  }

  public function getIsPlusUser() {
    return $this->isPlusUser;
  }

  public function setIsPlusUser( $isPlusUser) {
    $this->isPlusUser = $isPlusUser;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getLanguage() {
    return $this->language;
  }

  public function setLanguage( $language) {
    $this->language = $language;
  }

  public function getName() {
    return $this->name;
  }

  public function setName(Google_PersonName $name) {
    $this->name = $name;
  }

  public function getNickname() {
    return $this->nickname;
  }

  public function setNickname( $nickname) {
    $this->nickname = $nickname;
  }

  public function getObjectType() {
    return $this->objectType;
  }

  public function setObjectType( $objectType) {
    $this->objectType = $objectType;
  }

  public function getOrganizations() {
    return $this->organizations;
  }

  public function setOrganizations(/* array(Google_PersonOrganizations) */ $organizations) {
    $this->assertIsArray($organizations, 'Google_PersonOrganizations', __METHOD__);
    $this->organizations = $organizations;
  }

  public function getPlacesLived() {
    return $this->placesLived;
  }

  public function setPlacesLived(/* array(Google_PersonPlacesLived) */ $placesLived) {
    $this->assertIsArray($placesLived, 'Google_PersonPlacesLived', __METHOD__);
    $this->placesLived = $placesLived;
  }

  public function getPlusOneCount() {
    return $this->plusOneCount;
  }

  public function setPlusOneCount( $plusOneCount) {
    $this->plusOneCount = $plusOneCount;
  }

  public function getRelationshipStatus() {
    return $this->relationshipStatus;
  }

  public function setRelationshipStatus( $relationshipStatus) {
    $this->relationshipStatus = $relationshipStatus;
  }

  public function getTagline() {
    return $this->tagline;
  }

  public function setTagline( $tagline) {
    $this->tagline = $tagline;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }

  public function getUrls() {
    return $this->urls;
  }

  public function setUrls(/* array(Google_PersonUrls) */ $urls) {
    $this->assertIsArray($urls, 'Google_PersonUrls', __METHOD__);
    $this->urls = $urls;
  }

  public function getVerified() {
    return $this->verified;
  }

  public function setVerified( $verified) {
    $this->verified = $verified;
  }
}

class Google_PersonAgeRange extends Google_Model {
  public $max;
  public $min;

  public function getMax() {
    return $this->max;
  }

  public function setMax( $max) {
    $this->max = $max;
  }

  public function getMin() {
    return $this->min;
  }

  public function setMin( $min) {
    $this->min = $min;
  }
}

class Google_PersonCover extends Google_Model {
  public $coverInfo;
  public $coverPhoto;
  public $layout;
  protected $__coverInfoType = 'Google_PersonCoverCoverInfo';
  protected $__coverInfoDataType = '';
  protected $__coverPhotoType = 'Google_PersonCoverCoverPhoto';
  protected $__coverPhotoDataType = '';

  public function getCoverInfo() {
    return $this->coverInfo;
  }

  public function setCoverInfo(Google_PersonCoverCoverInfo $coverInfo) {
    $this->coverInfo = $coverInfo;
  }

  public function getCoverPhoto() {
    return $this->coverPhoto;
  }

  public function setCoverPhoto(Google_PersonCoverCoverPhoto $coverPhoto) {
    $this->coverPhoto = $coverPhoto;
  }

  public function getLayout() {
    return $this->layout;
  }

  public function setLayout( $layout) {
    $this->layout = $layout;
  }
}

class Google_PersonCoverCoverInfo extends Google_Model {
  public $leftImageOffset;
  public $topImageOffset;

  public function getLeftImageOffset() {
    return $this->leftImageOffset;
  }

  public function setLeftImageOffset( $leftImageOffset) {
    $this->leftImageOffset = $leftImageOffset;
  }

  public function getTopImageOffset() {
    return $this->topImageOffset;
  }

  public function setTopImageOffset( $topImageOffset) {
    $this->topImageOffset = $topImageOffset;
  }
}

class Google_PersonCoverCoverPhoto extends Google_Model {
  public $height;
  public $url;
  public $width;

  public function getHeight() {
    return $this->height;
  }

  public function setHeight( $height) {
    $this->height = $height;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }

  public function getWidth() {
    return $this->width;
  }

  public function setWidth( $width) {
    $this->width = $width;
  }
}

class Google_PersonImage extends Google_Model {
  public $url;

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_PersonName extends Google_Model {
  public $familyName;
  public $formatted;
  public $givenName;
  public $honorificPrefix;
  public $honorificSuffix;
  public $middleName;

  public function getFamilyName() {
    return $this->familyName;
  }

  public function setFamilyName( $familyName) {
    $this->familyName = $familyName;
  }

  public function getFormatted() {
    return $this->formatted;
  }

  public function setFormatted( $formatted) {
    $this->formatted = $formatted;
  }

  public function getGivenName() {
    return $this->givenName;
  }

  public function setGivenName( $givenName) {
    $this->givenName = $givenName;
  }

  public function getHonorificPrefix() {
    return $this->honorificPrefix;
  }

  public function setHonorificPrefix( $honorificPrefix) {
    $this->honorificPrefix = $honorificPrefix;
  }

  public function getHonorificSuffix() {
    return $this->honorificSuffix;
  }

  public function setHonorificSuffix( $honorificSuffix) {
    $this->honorificSuffix = $honorificSuffix;
  }

  public function getMiddleName() {
    return $this->middleName;
  }

  public function setMiddleName( $middleName) {
    $this->middleName = $middleName;
  }
}

class Google_PersonOrganizations extends Google_Model {
  public $department;
  public $description;
  public $endDate;
  public $location;
  public $name;
  public $primary;
  public $startDate;
  public $title;
  public $type;

  public function getDepartment() {
    return $this->department;
  }

  public function setDepartment( $department) {
    $this->department = $department;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription( $description) {
    $this->description = $description;
  }

  public function getEndDate() {
    return $this->endDate;
  }

  public function setEndDate( $endDate) {
    $this->endDate = $endDate;
  }

  public function getLocation() {
    return $this->location;
  }

  public function setLocation( $location) {
    $this->location = $location;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getPrimary() {
    return $this->primary;
  }

  public function setPrimary( $primary) {
    $this->primary = $primary;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function setStartDate( $startDate) {
    $this->startDate = $startDate;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle( $title) {
    $this->title = $title;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }
}

class Google_PersonPlacesLived extends Google_Model {
  public $primary;
  public $value;

  public function getPrimary() {
    return $this->primary;
  }

  public function setPrimary( $primary) {
    $this->primary = $primary;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_PersonUrls extends Google_Model {
  public $label;
  public $type;
  public $value;

  public function getLabel() {
    return $this->label;
  }

  public function setLabel( $label) {
    $this->label = $label;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_Place extends Google_Model {
  public $address;
  public $displayName;
  public $kind;
  public $position;
  protected $__addressType = 'Google_PlaceAddress';
  protected $__addressDataType = '';
  protected $__positionType = 'Google_PlacePosition';
  protected $__positionDataType = '';

  public function getAddress() {
    return $this->address;
  }

  public function setAddress(Google_PlaceAddress $address) {
    $this->address = $address;
  }

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getPosition() {
    return $this->position;
  }

  public function setPosition(Google_PlacePosition $position) {
    $this->position = $position;
  }
}

class Google_PlaceAddress extends Google_Model {
  public $formatted;

  public function getFormatted() {
    return $this->formatted;
  }

  public function setFormatted( $formatted) {
    $this->formatted = $formatted;
  }
}

class Google_PlacePosition extends Google_Model {
  public $latitude;
  public $longitude;

  public function getLatitude() {
    return $this->latitude;
  }

  public function setLatitude( $latitude) {
    $this->latitude = $latitude;
  }

  public function getLongitude() {
    return $this->longitude;
  }

  public function setLongitude( $longitude) {
    $this->longitude = $longitude;
  }
}

class Google_PlusAclentryResource extends Google_Model {
  public $displayName;
  public $id;
  public $type;

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }
}
