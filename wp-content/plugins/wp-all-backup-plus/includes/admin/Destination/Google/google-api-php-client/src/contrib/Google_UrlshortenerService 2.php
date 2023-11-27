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
   * The "url" collection of methods.
   * Typical usage is:
   *  <code>
   *   $urlshortenerService = new Google_UrlshortenerService(...);
   *   $url = $urlshortenerService->url;
   *  </code>
   */
  class Google_UrlServiceResource extends Google_ServiceResource {

    /**
     * Expands a short URL or gets creation time and analytics. (url.get)
     *
     * @param string $shortUrl The short URL, including the protocol.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string projection Additional information to return.
     * @return Google_Url
     */
    public function get($shortUrl, $optParams = array()) {
      $params = array('shortUrl' => $shortUrl);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Url($data);
      } else {
        return $data;
      }
    }
    /**
     * Creates a new short URL. (url.insert)
     *
     * @param Google_Url $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Url
     */
    public function insert(Google_Url $postBody, $optParams = array()) {
      $params = array('postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Url($data);
      } else {
        return $data;
      }
    }
    /**
     * Retrieves a list of URLs shortened by a user. (url.list)
     *
     * @param array $optParams Optional parameters.
     *
     * @opt_param string projection Additional information to return.
     * @opt_param string start-token Token for requesting successive pages of results.
     * @return Google_UrlHistory
     */
    public function listUrl($optParams = array()) {
      $params = array();
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_UrlHistory($data);
      } else {
        return $data;
      }
    }
  }

/**
 * Service definition for Google_Urlshortener (v1).
 *
 * <p>
 * Lets you create, inspect, and manage goo.gl short URLs
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="http://code.google.com/apis/urlshortener/v1/getting_started.html" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_UrlshortenerService extends Google_Service {
  public $url;
  /**
   * Constructs the internal representation of the Urlshortener service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client) {
    $this->servicePath = 'urlshortener/v1/';
    $this->version = 'v1';
    $this->serviceName = 'urlshortener';

    $client->addService($this->serviceName, $this->version);
    $this->url = new Google_UrlServiceResource($this, $this->serviceName, 'url', json_decode('{"methods": {"get": {"id": "urlshortener.url.get", "path": "url", "httpMethod": "GET", "parameters": {"projection": {"type": "string", "enum": ["ANALYTICS_CLICKS", "ANALYTICS_TOP_STRINGS", "FULL"], "location": "query"}, "shortUrl": {"type": "string", "required": true, "location": "query"}}, "response": {"$ref": "Url"}}, "insert": {"id": "urlshortener.url.insert", "path": "url", "httpMethod": "POST", "request": {"$ref": "Url"}, "response": {"$ref": "Url"}, "scopes": ["https://www.googleapis.com/auth/urlshortener"]}, "list": {"id": "urlshortener.url.list", "path": "url/history", "httpMethod": "GET", "parameters": {"projection": {"type": "string", "enum": ["ANALYTICS_CLICKS", "FULL"], "location": "query"}, "start-token": {"type": "string", "location": "query"}}, "response": {"$ref": "UrlHistory"}, "scopes": ["https://www.googleapis.com/auth/urlshortener"]}}}', true));

  }
}



class Google_AnalyticsSnapshot extends Google_Model {
  public $browsers;
  public $countries;
  public $longUrlClicks;
  public $platforms;
  public $referrers;
  public $shortUrlClicks;
  protected $__browsersType = 'Google_StringCount';
  protected $__browsersDataType = 'array';
  protected $__countriesType = 'Google_StringCount';
  protected $__countriesDataType = 'array';
  protected $__platformsType = 'Google_StringCount';
  protected $__platformsDataType = 'array';
  protected $__referrersType = 'Google_StringCount';
  protected $__referrersDataType = 'array';

  public function getBrowsers() {
    return $this->browsers;
  }

  public function setBrowsers(/* array(Google_StringCount) */ $browsers) {
    $this->assertIsArray($browsers, 'Google_StringCount', __METHOD__);
    $this->browsers = $browsers;
  }

  public function getCountries() {
    return $this->countries;
  }

  public function setCountries(/* array(Google_StringCount) */ $countries) {
    $this->assertIsArray($countries, 'Google_StringCount', __METHOD__);
    $this->countries = $countries;
  }

  public function getLongUrlClicks() {
    return $this->longUrlClicks;
  }

  public function setLongUrlClicks( $longUrlClicks) {
    $this->longUrlClicks = $longUrlClicks;
  }

  public function getPlatforms() {
    return $this->platforms;
  }

  public function setPlatforms(/* array(Google_StringCount) */ $platforms) {
    $this->assertIsArray($platforms, 'Google_StringCount', __METHOD__);
    $this->platforms = $platforms;
  }

  public function getReferrers() {
    return $this->referrers;
  }

  public function setReferrers(/* array(Google_StringCount) */ $referrers) {
    $this->assertIsArray($referrers, 'Google_StringCount', __METHOD__);
    $this->referrers = $referrers;
  }

  public function getShortUrlClicks() {
    return $this->shortUrlClicks;
  }

  public function setShortUrlClicks( $shortUrlClicks) {
    $this->shortUrlClicks = $shortUrlClicks;
  }
}

class Google_AnalyticsSummary extends Google_Model {
  public $allTime;
  public $day;
  public $month;
  public $twoHours;
  public $week;
  protected $__allTimeType = 'Google_AnalyticsSnapshot';
  protected $__allTimeDataType = '';
  protected $__dayType = 'Google_AnalyticsSnapshot';
  protected $__dayDataType = '';
  protected $__monthType = 'Google_AnalyticsSnapshot';
  protected $__monthDataType = '';
  protected $__twoHoursType = 'Google_AnalyticsSnapshot';
  protected $__twoHoursDataType = '';
  protected $__weekType = 'Google_AnalyticsSnapshot';
  protected $__weekDataType = '';

  public function getAllTime() {
    return $this->allTime;
  }

  public function setAllTime(Google_AnalyticsSnapshot $allTime) {
    $this->allTime = $allTime;
  }

  public function getDay() {
    return $this->day;
  }

  public function setDay(Google_AnalyticsSnapshot $day) {
    $this->day = $day;
  }

  public function getMonth() {
    return $this->month;
  }

  public function setMonth(Google_AnalyticsSnapshot $month) {
    $this->month = $month;
  }

  public function getTwoHours() {
    return $this->twoHours;
  }

  public function setTwoHours(Google_AnalyticsSnapshot $twoHours) {
    $this->twoHours = $twoHours;
  }

  public function getWeek() {
    return $this->week;
  }

  public function setWeek(Google_AnalyticsSnapshot $week) {
    $this->week = $week;
  }
}

class Google_StringCount extends Google_Model {
  public $count;
  public $id;

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }
}

class Google_Url extends Google_Model {
  public $analytics;
  public $created;
  public $id;
  public $kind;
  public $longUrl;
  public $status;
  protected $__analyticsType = 'Google_AnalyticsSummary';
  protected $__analyticsDataType = '';

  public function getAnalytics() {
    return $this->analytics;
  }

  public function setAnalytics(Google_AnalyticsSummary $analytics) {
    $this->analytics = $analytics;
  }

  public function getCreated() {
    return $this->created;
  }

  public function setCreated( $created) {
    $this->created = $created;
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

  public function getLongUrl() {
    return $this->longUrl;
  }

  public function setLongUrl( $longUrl) {
    $this->longUrl = $longUrl;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus( $status) {
    $this->status = $status;
  }
}

class Google_UrlHistory extends Google_Model {
  public $items;
  public $itemsPerPage;
  public $kind;
  public $nextPageToken;
  public $totalItems;
  protected $__itemsType = 'Google_Url';
  protected $__itemsDataType = 'array';

  public function getItems() {
    return $this->items;
  }

  public function setItems(/* array(Google_Url) */ $items) {
    $this->assertIsArray($items, 'Google_Url', __METHOD__);
    $this->items = $items;
  }

  public function getItemsPerPage() {
    return $this->itemsPerPage;
  }

  public function setItemsPerPage( $itemsPerPage) {
    $this->itemsPerPage = $itemsPerPage;
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

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}
