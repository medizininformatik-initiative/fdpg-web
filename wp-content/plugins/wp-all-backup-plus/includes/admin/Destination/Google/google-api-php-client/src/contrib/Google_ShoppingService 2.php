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
   * The "products" collection of methods.
   * Typical usage is:
   *  <code>
   *   $shoppingService = new Google_ShoppingService(...);
   *   $products = $shoppingService->products;
   *  </code>
   */
  class Google_ProductsServiceResource extends Google_ServiceResource {

    /**
     * Returns a single product (products.get)
     *
     * @param string $source Query source
     * @param string $accountId Merchant center account id
     * @param string $productIdType Type of productId
     * @param string $productId Id of product
     * @param array $optParams Optional parameters.
     *
     * @opt_param string attributeFilter Comma separated list of attributes to return
     * @opt_param bool categories.enabled Whether to return category information
     * @opt_param string categories.include Category specification
     * @opt_param bool categories.useGcsConfig This parameter is currently ignored
     * @opt_param string location Location used to determine tax and shipping
     * @opt_param bool recommendations.enabled Whether to return recommendation information
     * @opt_param string recommendations.include Recommendation specification
     * @opt_param bool recommendations.useGcsConfig This parameter is currently ignored
     * @opt_param string taxonomy Merchant taxonomy
     * @opt_param string thumbnails Thumbnail specification
     * @return Google_Product
     */
    public function get($source, $accountId, $productIdType, $productId, $optParams = array()) {
      $params = array('source' => $source, 'accountId' => $accountId, 'productIdType' => $productIdType, 'productId' => $productId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Product($data);
      } else {
        return $data;
      }
    }
    /**
     * Returns a list of products and content modules (products.list)
     *
     * @param string $source Query source
     * @param array $optParams Optional parameters.
     *
     * @opt_param string attributeFilter Comma separated list of attributes to return
     * @opt_param string availability Comma separated list of availabilities (outOfStock, limited, inStock, backOrder, preOrder, onDisplayToOrder) to return
     * @opt_param string boostBy Boosting specification
     * @opt_param bool categories.enabled Whether to return category information
     * @opt_param string categories.include Category specification
     * @opt_param bool categories.useGcsConfig This parameter is currently ignored
     * @opt_param string categoryRecommendations.category Category for which to retrieve recommendations
     * @opt_param bool categoryRecommendations.enabled Whether to return category recommendation information
     * @opt_param string categoryRecommendations.include Category recommendation specification
     * @opt_param bool categoryRecommendations.useGcsConfig This parameter is currently ignored
     * @opt_param string channels Channels specification
     * @opt_param bool clickTracking Whether to add a click tracking parameter to offer URLs
     * @opt_param string country Country restriction (ISO 3166)
     * @opt_param string crowdBy Crowding specification
     * @opt_param string currency Currency restriction (ISO 4217)
     * @opt_param bool extras.enabled Whether to return extra information.
     * @opt_param string extras.info What extra information to return.
     * @opt_param string facets.discover Facets to discover
     * @opt_param bool facets.enabled Whether to return facet information
     * @opt_param string facets.include Facets to include (applies when useGcsConfig == false)
     * @opt_param bool facets.includeEmptyBuckets Return empty facet buckets.
     * @opt_param bool facets.useGcsConfig Whether to return facet information as configured in the GCS account
     * @opt_param string language Language restriction (BCP 47)
     * @opt_param string location Location used to determine tax and shipping
     * @opt_param string maxResults Maximum number of results to return
     * @opt_param int maxVariants Maximum number of variant results to return per result
     * @opt_param bool promotions.enabled Whether to return promotion information
     * @opt_param bool promotions.useGcsConfig Whether to return promotion information as configured in the GCS account
     * @opt_param string q Search query
     * @opt_param string rankBy Ranking specification
     * @opt_param bool redirects.enabled Whether to return redirect information
     * @opt_param bool redirects.useGcsConfig Whether to return redirect information as configured in the GCS account
     * @opt_param bool relatedQueries.enabled Whether to return related queries
     * @opt_param bool relatedQueries.useGcsConfig This parameter is currently ignored
     * @opt_param string restrictBy Restriction specification
     * @opt_param bool safe Whether safe search is enabled. Default: true
     * @opt_param bool spelling.enabled Whether to return spelling suggestions
     * @opt_param bool spelling.useGcsConfig This parameter is currently ignored
     * @opt_param string startIndex Index (1-based) of first product to return
     * @opt_param string taxonomy Taxonomy name
     * @opt_param string thumbnails Image thumbnails specification
     * @opt_param string useCase One of CommerceSearchUseCase, ShoppingApiUseCase
     * @return Google_Products
     */
    public function listProducts($source, $optParams = array()) {
      $params = array('source' => $source);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_Products($data);
      } else {
        return $data;
      }
    }
  }

/**
 * Service definition for Google_Shopping (v1).
 *
 * <p>
 * Lets you search over product data.
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="https://developers.google.com/shopping-search/v1/getting_started" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_ShoppingService extends Google_Service {
  public $products;
  /**
   * Constructs the internal representation of the Shopping service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client) {
    $this->servicePath = 'shopping/search/v1/';
    $this->version = 'v1';
    $this->serviceName = 'shopping';

    $client->addService($this->serviceName, $this->version);
    $this->products = new Google_ProductsServiceResource($this, $this->serviceName, 'products', json_decode('{"methods": {"get": {"id": "shopping.products.get", "path": "{source}/products/{accountId}/{productIdType}/{productId}", "httpMethod": "GET", "parameters": {"accountId": {"type": "integer", "required": true, "format": "uint32", "location": "path"}, "attributeFilter": {"type": "string", "location": "query"}, "categories.enabled": {"type": "boolean", "location": "query"}, "categories.include": {"type": "string", "location": "query"}, "categories.useGcsConfig": {"type": "boolean", "location": "query"}, "location": {"type": "string", "location": "query"}, "productId": {"type": "string", "required": true, "location": "path"}, "productIdType": {"type": "string", "required": true, "location": "path"}, "recommendations.enabled": {"type": "boolean", "location": "query"}, "recommendations.include": {"type": "string", "location": "query"}, "recommendations.useGcsConfig": {"type": "boolean", "location": "query"}, "source": {"type": "string", "required": true, "location": "path"}, "taxonomy": {"type": "string", "location": "query"}, "thumbnails": {"type": "string", "location": "query"}}, "response": {"$ref": "Product"}, "scopes": ["https://www.googleapis.com/auth/shoppingapi"]}, "list": {"id": "shopping.products.list", "path": "{source}/products", "httpMethod": "GET", "parameters": {"attributeFilter": {"type": "string", "location": "query"}, "availability": {"type": "string", "location": "query"}, "boostBy": {"type": "string", "location": "query"}, "categories.enabled": {"type": "boolean", "location": "query"}, "categories.include": {"type": "string", "location": "query"}, "categories.useGcsConfig": {"type": "boolean", "location": "query"}, "categoryRecommendations.category": {"type": "string", "location": "query"}, "categoryRecommendations.enabled": {"type": "boolean", "location": "query"}, "categoryRecommendations.include": {"type": "string", "location": "query"}, "categoryRecommendations.useGcsConfig": {"type": "boolean", "location": "query"}, "channels": {"type": "string", "location": "query"}, "clickTracking": {"type": "boolean", "location": "query"}, "country": {"type": "string", "location": "query"}, "crowdBy": {"type": "string", "location": "query"}, "currency": {"type": "string", "location": "query"}, "extras.enabled": {"type": "boolean", "location": "query"}, "extras.info": {"type": "string", "location": "query"}, "facets.discover": {"type": "string", "location": "query"}, "facets.enabled": {"type": "boolean", "location": "query"}, "facets.include": {"type": "string", "location": "query"}, "facets.includeEmptyBuckets": {"type": "boolean", "location": "query"}, "facets.useGcsConfig": {"type": "boolean", "location": "query"}, "language": {"type": "string", "location": "query"}, "location": {"type": "string", "location": "query"}, "maxResults": {"type": "integer", "format": "uint32", "location": "query"}, "maxVariants": {"type": "integer", "format": "int32", "location": "query"}, "promotions.enabled": {"type": "boolean", "location": "query"}, "promotions.useGcsConfig": {"type": "boolean", "location": "query"}, "q": {"type": "string", "location": "query"}, "rankBy": {"type": "string", "location": "query"}, "redirects.enabled": {"type": "boolean", "location": "query"}, "redirects.useGcsConfig": {"type": "boolean", "location": "query"}, "relatedQueries.enabled": {"type": "boolean", "location": "query"}, "relatedQueries.useGcsConfig": {"type": "boolean", "location": "query"}, "restrictBy": {"type": "string", "location": "query"}, "safe": {"type": "boolean", "location": "query"}, "source": {"type": "string", "required": true, "location": "path"}, "spelling.enabled": {"type": "boolean", "location": "query"}, "spelling.useGcsConfig": {"type": "boolean", "location": "query"}, "startIndex": {"type": "integer", "format": "uint32", "location": "query"}, "taxonomy": {"type": "string", "location": "query"}, "thumbnails": {"type": "string", "location": "query"}, "useCase": {"type": "string", "location": "query"}}, "response": {"$ref": "Products"}, "scopes": ["https://www.googleapis.com/auth/shoppingapi"]}}}', true));

  }
}



class Google_Product extends Google_Model {
  public $categories;
  public $debug;
  public $id;
  public $kind;
  public $product;
  public $recommendations;
  public $requestId;
  public $selfLink;
  protected $__categoriesType = 'Google_ShoppingModelCategoryJsonV1';
  protected $__categoriesDataType = 'array';
  protected $__debugType = 'Google_ShoppingModelDebugJsonV1';
  protected $__debugDataType = '';
  protected $__productType = 'Google_ShoppingModelProductJsonV1';
  protected $__productDataType = '';
  protected $__recommendationsType = 'Google_ShoppingModelRecommendationsJsonV1';
  protected $__recommendationsDataType = 'array';

  public function getCategories() {
    return $this->categories;
  }

  public function setCategories(/* array(Google_ShoppingModelCategoryJsonV1) */ $categories) {
    $this->assertIsArray($categories, 'Google_ShoppingModelCategoryJsonV1', __METHOD__);
    $this->categories = $categories;
  }

  public function getDebug() {
    return $this->debug;
  }

  public function setDebug(Google_ShoppingModelDebugJsonV1 $debug) {
    $this->debug = $debug;
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

  public function getProduct() {
    return $this->product;
  }

  public function setProduct(Google_ShoppingModelProductJsonV1 $product) {
    $this->product = $product;
  }

  public function getRecommendations() {
    return $this->recommendations;
  }

  public function setRecommendations(/* array(Google_ShoppingModelRecommendationsJsonV1) */ $recommendations) {
    $this->assertIsArray($recommendations, 'Google_ShoppingModelRecommendationsJsonV1', __METHOD__);
    $this->recommendations = $recommendations;
  }

  public function getRequestId() {
    return $this->requestId;
  }

  public function setRequestId( $requestId) {
    $this->requestId = $requestId;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }
}

class Google_Products extends Google_Model {
  public $categories;
  public $categoryRecommendations;
  public $currentItemCount;
  public $debug;
  public $etag;
  public $extras;
  public $facets;
  public $id;
  public $items;
  public $itemsPerPage;
  public $kind;
  public $nextLink;
  public $previousLink;
  public $promotions;
  public $redirects;
  public $relatedQueries;
  public $requestId;
  public $selfLink;
  public $spelling;
  public $startIndex;
  public $stores;
  public $totalItems;
  protected $__categoriesType = 'Google_ShoppingModelCategoryJsonV1';
  protected $__categoriesDataType = 'array';
  protected $__categoryRecommendationsType = 'Google_ShoppingModelRecommendationsJsonV1';
  protected $__categoryRecommendationsDataType = 'array';
  protected $__debugType = 'Google_ShoppingModelDebugJsonV1';
  protected $__debugDataType = '';
  protected $__extrasType = 'Google_ShoppingModelExtrasJsonV1';
  protected $__extrasDataType = '';
  protected $__facetsType = 'Google_ProductsFacets';
  protected $__facetsDataType = 'array';
  protected $__itemsType = 'Google_Product';
  protected $__itemsDataType = 'array';
  protected $__promotionsType = 'Google_ProductsPromotions';
  protected $__promotionsDataType = 'array';
  protected $__spellingType = 'Google_ProductsSpelling';
  protected $__spellingDataType = '';
  protected $__storesType = 'Google_ProductsStores';
  protected $__storesDataType = 'array';

  public function getCategories() {
    return $this->categories;
  }

  public function setCategories(/* array(Google_ShoppingModelCategoryJsonV1) */ $categories) {
    $this->assertIsArray($categories, 'Google_ShoppingModelCategoryJsonV1', __METHOD__);
    $this->categories = $categories;
  }

  public function getCategoryRecommendations() {
    return $this->categoryRecommendations;
  }

  public function setCategoryRecommendations(/* array(Google_ShoppingModelRecommendationsJsonV1) */ $categoryRecommendations) {
    $this->assertIsArray($categoryRecommendations, 'Google_ShoppingModelRecommendationsJsonV1', __METHOD__);
    $this->categoryRecommendations = $categoryRecommendations;
  }

  public function getCurrentItemCount() {
    return $this->currentItemCount;
  }

  public function setCurrentItemCount( $currentItemCount) {
    $this->currentItemCount = $currentItemCount;
  }

  public function getDebug() {
    return $this->debug;
  }

  public function setDebug(Google_ShoppingModelDebugJsonV1 $debug) {
    $this->debug = $debug;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getExtras() {
    return $this->extras;
  }

  public function setExtras(Google_ShoppingModelExtrasJsonV1 $extras) {
    $this->extras = $extras;
  }

  public function getFacets() {
    return $this->facets;
  }

  public function setFacets(/* array(Google_ProductsFacets) */ $facets) {
    $this->assertIsArray($facets, 'Google_ProductsFacets', __METHOD__);
    $this->facets = $facets;
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

  public function setItems(/* array(Google_Product) */ $items) {
    $this->assertIsArray($items, 'Google_Product', __METHOD__);
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

  public function getNextLink() {
    return $this->nextLink;
  }

  public function setNextLink( $nextLink) {
    $this->nextLink = $nextLink;
  }

  public function getPreviousLink() {
    return $this->previousLink;
  }

  public function setPreviousLink( $previousLink) {
    $this->previousLink = $previousLink;
  }

  public function getPromotions() {
    return $this->promotions;
  }

  public function setPromotions(/* array(Google_ProductsPromotions) */ $promotions) {
    $this->assertIsArray($promotions, 'Google_ProductsPromotions', __METHOD__);
    $this->promotions = $promotions;
  }

  public function getRedirects() {
    return $this->redirects;
  }

  public function setRedirects(/* array(Google_string) */ $redirects) {
    $this->assertIsArray($redirects, 'Google_string', __METHOD__);
    $this->redirects = $redirects;
  }

  public function getRelatedQueries() {
    return $this->relatedQueries;
  }

  public function setRelatedQueries(/* array(Google_string) */ $relatedQueries) {
    $this->assertIsArray($relatedQueries, 'Google_string', __METHOD__);
    $this->relatedQueries = $relatedQueries;
  }

  public function getRequestId() {
    return $this->requestId;
  }

  public function setRequestId( $requestId) {
    $this->requestId = $requestId;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getSpelling() {
    return $this->spelling;
  }

  public function setSpelling(Google_ProductsSpelling $spelling) {
    $this->spelling = $spelling;
  }

  public function getStartIndex() {
    return $this->startIndex;
  }

  public function setStartIndex( $startIndex) {
    $this->startIndex = $startIndex;
  }

  public function getStores() {
    return $this->stores;
  }

  public function setStores(/* array(Google_ProductsStores) */ $stores) {
    $this->assertIsArray($stores, 'Google_ProductsStores', __METHOD__);
    $this->stores = $stores;
  }

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}

class Google_ProductsFacets extends Google_Model {
  public $buckets;
  public $count;
  public $displayName;
  public $name;
  public $property;
  public $type;
  public $unit;
  protected $__bucketsType = 'Google_ProductsFacetsBuckets';
  protected $__bucketsDataType = 'array';

  public function getBuckets() {
    return $this->buckets;
  }

  public function setBuckets(/* array(Google_ProductsFacetsBuckets) */ $buckets) {
    $this->assertIsArray($buckets, 'Google_ProductsFacetsBuckets', __METHOD__);
    $this->buckets = $buckets;
  }

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getProperty() {
    return $this->property;
  }

  public function setProperty( $property) {
    $this->property = $property;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }

  public function getUnit() {
    return $this->unit;
  }

  public function setUnit( $unit) {
    $this->unit = $unit;
  }
}

class Google_ProductsFacetsBuckets extends Google_Model {
  public $count;
  public $max;
  public $maxExclusive;
  public $min;
  public $minExclusive;
  public $value;

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }

  public function getMax() {
    return $this->max;
  }

  public function setMax( $max) {
    $this->max = $max;
  }

  public function getMaxExclusive() {
    return $this->maxExclusive;
  }

  public function setMaxExclusive( $maxExclusive) {
    $this->maxExclusive = $maxExclusive;
  }

  public function getMin() {
    return $this->min;
  }

  public function setMin( $min) {
    $this->min = $min;
  }

  public function getMinExclusive() {
    return $this->minExclusive;
  }

  public function setMinExclusive( $minExclusive) {
    $this->minExclusive = $minExclusive;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_ProductsPromotions extends Google_Model {
  public $customFields;
  public $customHtml;
  public $description;
  public $destLink;
  public $imageLink;
  public $name;
  public $product;
  public $type;
  protected $__customFieldsType = 'Google_ProductsPromotionsCustomFields';
  protected $__customFieldsDataType = 'array';
  protected $__productType = 'Google_ShoppingModelProductJsonV1';
  protected $__productDataType = '';

  public function getCustomFields() {
    return $this->customFields;
  }

  public function setCustomFields(/* array(Google_ProductsPromotionsCustomFields) */ $customFields) {
    $this->assertIsArray($customFields, 'Google_ProductsPromotionsCustomFields', __METHOD__);
    $this->customFields = $customFields;
  }

  public function getCustomHtml() {
    return $this->customHtml;
  }

  public function setCustomHtml( $customHtml) {
    $this->customHtml = $customHtml;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription( $description) {
    $this->description = $description;
  }

  public function getDestLink() {
    return $this->destLink;
  }

  public function setDestLink( $destLink) {
    $this->destLink = $destLink;
  }

  public function getImageLink() {
    return $this->imageLink;
  }

  public function setImageLink( $imageLink) {
    $this->imageLink = $imageLink;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getProduct() {
    return $this->product;
  }

  public function setProduct(Google_ShoppingModelProductJsonV1 $product) {
    $this->product = $product;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }
}

class Google_ProductsPromotionsCustomFields extends Google_Model {
  public $name;
  public $value;

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_ProductsSpelling extends Google_Model {
  public $suggestion;

  public function getSuggestion() {
    return $this->suggestion;
  }

  public function setSuggestion( $suggestion) {
    $this->suggestion = $suggestion;
  }
}

class Google_ProductsStores extends Google_Model {
  public $address;
  public $location;
  public $name;
  public $storeCode;
  public $storeId;
  public $storeName;
  public $telephone;

  public function getAddress() {
    return $this->address;
  }

  public function setAddress( $address) {
    $this->address = $address;
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

  public function getStoreCode() {
    return $this->storeCode;
  }

  public function setStoreCode( $storeCode) {
    $this->storeCode = $storeCode;
  }

  public function getStoreId() {
    return $this->storeId;
  }

  public function setStoreId( $storeId) {
    $this->storeId = $storeId;
  }

  public function getStoreName() {
    return $this->storeName;
  }

  public function setStoreName( $storeName) {
    $this->storeName = $storeName;
  }

  public function getTelephone() {
    return $this->telephone;
  }

  public function setTelephone( $telephone) {
    $this->telephone = $telephone;
  }
}

class Google_ShoppingModelCategoryJsonV1 extends Google_Model {
  public $id;
  public $parents;
  public $shortName;
  public $url;

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getParents() {
    return $this->parents;
  }

  public function setParents(/* array(Google_string) */ $parents) {
    $this->assertIsArray($parents, 'Google_string', __METHOD__);
    $this->parents = $parents;
  }

  public function getShortName() {
    return $this->shortName;
  }

  public function setShortName( $shortName) {
    $this->shortName = $shortName;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl( $url) {
    $this->url = $url;
  }
}

class Google_ShoppingModelDebugJsonV1 extends Google_Model {
  public $backendTimes;
  public $elapsedMillis;
  public $facetsRequest;
  public $facetsResponse;
  public $rdcResponse;
  public $recommendedItemsRequest;
  public $recommendedItemsResponse;
  public $searchRequest;
  public $searchResponse;
  protected $__backendTimesType = 'Google_ShoppingModelDebugJsonV1BackendTimes';
  protected $__backendTimesDataType = 'array';

  public function getBackendTimes() {
    return $this->backendTimes;
  }

  public function setBackendTimes(/* array(Google_ShoppingModelDebugJsonV1BackendTimes) */ $backendTimes) {
    $this->assertIsArray($backendTimes, 'Google_ShoppingModelDebugJsonV1BackendTimes', __METHOD__);
    $this->backendTimes = $backendTimes;
  }

  public function getElapsedMillis() {
    return $this->elapsedMillis;
  }

  public function setElapsedMillis( $elapsedMillis) {
    $this->elapsedMillis = $elapsedMillis;
  }

  public function getFacetsRequest() {
    return $this->facetsRequest;
  }

  public function setFacetsRequest( $facetsRequest) {
    $this->facetsRequest = $facetsRequest;
  }

  public function getFacetsResponse() {
    return $this->facetsResponse;
  }

  public function setFacetsResponse( $facetsResponse) {
    $this->facetsResponse = $facetsResponse;
  }

  public function getRdcResponse() {
    return $this->rdcResponse;
  }

  public function setRdcResponse( $rdcResponse) {
    $this->rdcResponse = $rdcResponse;
  }

  public function getRecommendedItemsRequest() {
    return $this->recommendedItemsRequest;
  }

  public function setRecommendedItemsRequest( $recommendedItemsRequest) {
    $this->recommendedItemsRequest = $recommendedItemsRequest;
  }

  public function getRecommendedItemsResponse() {
    return $this->recommendedItemsResponse;
  }

  public function setRecommendedItemsResponse( $recommendedItemsResponse) {
    $this->recommendedItemsResponse = $recommendedItemsResponse;
  }

  public function getSearchRequest() {
    return $this->searchRequest;
  }

  public function setSearchRequest( $searchRequest) {
    $this->searchRequest = $searchRequest;
  }

  public function getSearchResponse() {
    return $this->searchResponse;
  }

  public function setSearchResponse( $searchResponse) {
    $this->searchResponse = $searchResponse;
  }
}

class Google_ShoppingModelDebugJsonV1BackendTimes extends Google_Model {
  public $elapsedMillis;
  public $hostName;
  public $name;
  public $serverMillis;

  public function getElapsedMillis() {
    return $this->elapsedMillis;
  }

  public function setElapsedMillis( $elapsedMillis) {
    $this->elapsedMillis = $elapsedMillis;
  }

  public function getHostName() {
    return $this->hostName;
  }

  public function setHostName( $hostName) {
    $this->hostName = $hostName;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getServerMillis() {
    return $this->serverMillis;
  }

  public function setServerMillis( $serverMillis) {
    $this->serverMillis = $serverMillis;
  }
}

class Google_ShoppingModelExtrasJsonV1 extends Google_Model {
  public $facetRules;
  public $rankingRules;
  protected $__facetRulesType = 'Google_ShoppingModelExtrasJsonV1FacetRules';
  protected $__facetRulesDataType = 'array';
  protected $__rankingRulesType = 'Google_ShoppingModelExtrasJsonV1RankingRules';
  protected $__rankingRulesDataType = 'array';

  public function getFacetRules() {
    return $this->facetRules;
  }

  public function setFacetRules(/* array(Google_ShoppingModelExtrasJsonV1FacetRules) */ $facetRules) {
    $this->assertIsArray($facetRules, 'Google_ShoppingModelExtrasJsonV1FacetRules', __METHOD__);
    $this->facetRules = $facetRules;
  }

  public function getRankingRules() {
    return $this->rankingRules;
  }

  public function setRankingRules(/* array(Google_ShoppingModelExtrasJsonV1RankingRules) */ $rankingRules) {
    $this->assertIsArray($rankingRules, 'Google_ShoppingModelExtrasJsonV1RankingRules', __METHOD__);
    $this->rankingRules = $rankingRules;
  }
}

class Google_ShoppingModelExtrasJsonV1FacetRules extends Google_Model {
  public $name;

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }
}

class Google_ShoppingModelExtrasJsonV1RankingRules extends Google_Model {
  public $name;

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }
}

class Google_ShoppingModelProductJsonV1 extends Google_Model {
  public $attributes;
  public $author;
  public $brand;
  public $categories;
  public $condition;
  public $country;
  public $creationTime;
  public $description;
  public $googleId;
  public $gtin;
  public $gtins;
  public $images;
  public $internal1;
  public $internal10;
  public $internal12;
  public $internal13;
  public $internal14;
  public $internal15;
  public $internal16;
  public $internal3;
  public $internal4;
  public $internal6;
  public $internal7;
  public $internal8;
  public $inventories;
  public $language;
  public $link;
  public $modificationTime;
  public $mpns;
  public $plusOne;
  public $providedId;
  public $queryMatched;
  public $score;
  public $title;
  public $totalMatchingVariants;
  public $variants;
  protected $__attributesType = 'Google_ShoppingModelProductJsonV1Attributes';
  protected $__attributesDataType = 'array';
  protected $__authorType = 'Google_ShoppingModelProductJsonV1Author';
  protected $__authorDataType = '';
  protected $__imagesType = 'Google_ShoppingModelProductJsonV1Images';
  protected $__imagesDataType = 'array';
  protected $__internal16Type = 'Google_ShoppingModelProductJsonV1Internal16';
  protected $__internal16DataType = '';
  protected $__internal4Type = 'Google_ShoppingModelProductJsonV1Internal4';
  protected $__internal4DataType = 'array';
  protected $__inventoriesType = 'Google_ShoppingModelProductJsonV1Inventories';
  protected $__inventoriesDataType = 'array';
  protected $__variantsType = 'Google_ShoppingModelProductJsonV1Variants';
  protected $__variantsDataType = 'array';

  public function getAttributes() {
    return $this->attributes;
  }

  public function setAttributes(/* array(Google_ShoppingModelProductJsonV1Attributes) */ $attributes) {
    $this->assertIsArray($attributes, 'Google_ShoppingModelProductJsonV1Attributes', __METHOD__);
    $this->attributes = $attributes;
  }

  public function getAuthor() {
    return $this->author;
  }

  public function setAuthor(Google_ShoppingModelProductJsonV1Author $author) {
    $this->author = $author;
  }

  public function getBrand() {
    return $this->brand;
  }

  public function setBrand( $brand) {
    $this->brand = $brand;
  }

  public function getCategories() {
    return $this->categories;
  }

  public function setCategories(/* array(Google_string) */ $categories) {
    $this->assertIsArray($categories, 'Google_string', __METHOD__);
    $this->categories = $categories;
  }

  public function getCondition() {
    return $this->condition;
  }

  public function setCondition( $condition) {
    $this->condition = $condition;
  }

  public function getCountry() {
    return $this->country;
  }

  public function setCountry( $country) {
    $this->country = $country;
  }

  public function getCreationTime() {
    return $this->creationTime;
  }

  public function setCreationTime( $creationTime) {
    $this->creationTime = $creationTime;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription( $description) {
    $this->description = $description;
  }

  public function getGoogleId() {
    return $this->googleId;
  }

  public function setGoogleId( $googleId) {
    $this->googleId = $googleId;
  }

  public function getGtin() {
    return $this->gtin;
  }

  public function setGtin( $gtin) {
    $this->gtin = $gtin;
  }

  public function getGtins() {
    return $this->gtins;
  }

  public function setGtins(/* array(Google_string) */ $gtins) {
    $this->assertIsArray($gtins, 'Google_string', __METHOD__);
    $this->gtins = $gtins;
  }

  public function getImages() {
    return $this->images;
  }

  public function setImages(/* array(Google_ShoppingModelProductJsonV1Images) */ $images) {
    $this->assertIsArray($images, 'Google_ShoppingModelProductJsonV1Images', __METHOD__);
    $this->images = $images;
  }

  public function getInternal1() {
    return $this->internal1;
  }

  public function setInternal1(/* array(Google_string) */ $internal1) {
    $this->assertIsArray($internal1, 'Google_string', __METHOD__);
    $this->internal1 = $internal1;
  }

  public function getInternal10() {
    return $this->internal10;
  }

  public function setInternal10(/* array(Google_string) */ $internal10) {
    $this->assertIsArray($internal10, 'Google_string', __METHOD__);
    $this->internal10 = $internal10;
  }

  public function getInternal12() {
    return $this->internal12;
  }

  public function setInternal12( $internal12) {
    $this->internal12 = $internal12;
  }

  public function getInternal13() {
    return $this->internal13;
  }

  public function setInternal13( $internal13) {
    $this->internal13 = $internal13;
  }

  public function getInternal14() {
    return $this->internal14;
  }

  public function setInternal14( $internal14) {
    $this->internal14 = $internal14;
  }

  public function getInternal15() {
    return $this->internal15;
  }

  public function setInternal15( $internal15) {
    $this->internal15 = $internal15;
  }

  public function getInternal16() {
    return $this->internal16;
  }

  public function setInternal16(Google_ShoppingModelProductJsonV1Internal16 $internal16) {
    $this->internal16 = $internal16;
  }

  public function getInternal3() {
    return $this->internal3;
  }

  public function setInternal3( $internal3) {
    $this->internal3 = $internal3;
  }

  public function getInternal4() {
    return $this->internal4;
  }

  public function setInternal4(/* array(Google_ShoppingModelProductJsonV1Internal4) */ $internal4) {
    $this->assertIsArray($internal4, 'Google_ShoppingModelProductJsonV1Internal4', __METHOD__);
    $this->internal4 = $internal4;
  }

  public function getInternal6() {
    return $this->internal6;
  }

  public function setInternal6( $internal6) {
    $this->internal6 = $internal6;
  }

  public function getInternal7() {
    return $this->internal7;
  }

  public function setInternal7( $internal7) {
    $this->internal7 = $internal7;
  }

  public function getInternal8() {
    return $this->internal8;
  }

  public function setInternal8(/* array(Google_string) */ $internal8) {
    $this->assertIsArray($internal8, 'Google_string', __METHOD__);
    $this->internal8 = $internal8;
  }

  public function getInventories() {
    return $this->inventories;
  }

  public function setInventories(/* array(Google_ShoppingModelProductJsonV1Inventories) */ $inventories) {
    $this->assertIsArray($inventories, 'Google_ShoppingModelProductJsonV1Inventories', __METHOD__);
    $this->inventories = $inventories;
  }

  public function getLanguage() {
    return $this->language;
  }

  public function setLanguage( $language) {
    $this->language = $language;
  }

  public function getLink() {
    return $this->link;
  }

  public function setLink( $link) {
    $this->link = $link;
  }

  public function getModificationTime() {
    return $this->modificationTime;
  }

  public function setModificationTime( $modificationTime) {
    $this->modificationTime = $modificationTime;
  }

  public function getMpns() {
    return $this->mpns;
  }

  public function setMpns(/* array(Google_string) */ $mpns) {
    $this->assertIsArray($mpns, 'Google_string', __METHOD__);
    $this->mpns = $mpns;
  }

  public function getPlusOne() {
    return $this->plusOne;
  }

  public function setPlusOne( $plusOne) {
    $this->plusOne = $plusOne;
  }

  public function getProvidedId() {
    return $this->providedId;
  }

  public function setProvidedId( $providedId) {
    $this->providedId = $providedId;
  }

  public function getQueryMatched() {
    return $this->queryMatched;
  }

  public function setQueryMatched( $queryMatched) {
    $this->queryMatched = $queryMatched;
  }

  public function getScore() {
    return $this->score;
  }

  public function setScore( $score) {
    $this->score = $score;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle( $title) {
    $this->title = $title;
  }

  public function getTotalMatchingVariants() {
    return $this->totalMatchingVariants;
  }

  public function setTotalMatchingVariants( $totalMatchingVariants) {
    $this->totalMatchingVariants = $totalMatchingVariants;
  }

  public function getVariants() {
    return $this->variants;
  }

  public function setVariants(/* array(Google_ShoppingModelProductJsonV1Variants) */ $variants) {
    $this->assertIsArray($variants, 'Google_ShoppingModelProductJsonV1Variants', __METHOD__);
    $this->variants = $variants;
  }
}

class Google_ShoppingModelProductJsonV1Attributes extends Google_Model {
  public $displayName;
  public $name;
  public $type;
  public $unit;
  public $value;

  public function getDisplayName() {
    return $this->displayName;
  }

  public function setDisplayName( $displayName) {
    $this->displayName = $displayName;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }

  public function getUnit() {
    return $this->unit;
  }

  public function setUnit( $unit) {
    $this->unit = $unit;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_ShoppingModelProductJsonV1Author extends Google_Model {
  public $accountId;
  public $name;

  public function getAccountId() {
    return $this->accountId;
  }

  public function setAccountId( $accountId) {
    $this->accountId = $accountId;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }
}

class Google_ShoppingModelProductJsonV1Images extends Google_Model {
  public $link;
  public $status;
  public $thumbnails;
  protected $__thumbnailsType = 'Google_ShoppingModelProductJsonV1ImagesThumbnails';
  protected $__thumbnailsDataType = 'array';

  public function getLink() {
    return $this->link;
  }

  public function setLink( $link) {
    $this->link = $link;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus( $status) {
    $this->status = $status;
  }

  public function getThumbnails() {
    return $this->thumbnails;
  }

  public function setThumbnails(/* array(Google_ShoppingModelProductJsonV1ImagesThumbnails) */ $thumbnails) {
    $this->assertIsArray($thumbnails, 'Google_ShoppingModelProductJsonV1ImagesThumbnails', __METHOD__);
    $this->thumbnails = $thumbnails;
  }
}

class Google_ShoppingModelProductJsonV1ImagesThumbnails extends Google_Model {
  public $content;
  public $height;
  public $link;
  public $width;

  public function getContent() {
    return $this->content;
  }

  public function setContent( $content) {
    $this->content = $content;
  }

  public function getHeight() {
    return $this->height;
  }

  public function setHeight( $height) {
    $this->height = $height;
  }

  public function getLink() {
    return $this->link;
  }

  public function setLink( $link) {
    $this->link = $link;
  }

  public function getWidth() {
    return $this->width;
  }

  public function setWidth( $width) {
    $this->width = $width;
  }
}

class Google_ShoppingModelProductJsonV1Internal16 extends Google_Model {
  public $length;
  public $number;
  public $size;

  public function getLength() {
    return $this->length;
  }

  public function setLength( $length) {
    $this->length = $length;
  }

  public function getNumber() {
    return $this->number;
  }

  public function setNumber( $number) {
    $this->number = $number;
  }

  public function getSize() {
    return $this->size;
  }

  public function setSize( $size) {
    $this->size = $size;
  }
}

class Google_ShoppingModelProductJsonV1Internal4 extends Google_Model {
  public $confidence;
  public $node;

  public function getConfidence() {
    return $this->confidence;
  }

  public function setConfidence( $confidence) {
    $this->confidence = $confidence;
  }

  public function getNode() {
    return $this->node;
  }

  public function setNode( $node) {
    $this->node = $node;
  }
}

class Google_ShoppingModelProductJsonV1Inventories extends Google_Model {
  public $availability;
  public $channel;
  public $currency;
  public $distance;
  public $distanceUnit;
  public $installmentMonths;
  public $installmentPrice;
  public $originalPrice;
  public $price;
  public $saleEndDate;
  public $salePrice;
  public $saleStartDate;
  public $shipping;
  public $storeId;
  public $tax;

  public function getAvailability() {
    return $this->availability;
  }

  public function setAvailability( $availability) {
    $this->availability = $availability;
  }

  public function getChannel() {
    return $this->channel;
  }

  public function setChannel( $channel) {
    $this->channel = $channel;
  }

  public function getCurrency() {
    return $this->currency;
  }

  public function setCurrency( $currency) {
    $this->currency = $currency;
  }

  public function getDistance() {
    return $this->distance;
  }

  public function setDistance( $distance) {
    $this->distance = $distance;
  }

  public function getDistanceUnit() {
    return $this->distanceUnit;
  }

  public function setDistanceUnit( $distanceUnit) {
    $this->distanceUnit = $distanceUnit;
  }

  public function getInstallmentMonths() {
    return $this->installmentMonths;
  }

  public function setInstallmentMonths( $installmentMonths) {
    $this->installmentMonths = $installmentMonths;
  }

  public function getInstallmentPrice() {
    return $this->installmentPrice;
  }

  public function setInstallmentPrice( $installmentPrice) {
    $this->installmentPrice = $installmentPrice;
  }

  public function getOriginalPrice() {
    return $this->originalPrice;
  }

  public function setOriginalPrice( $originalPrice) {
    $this->originalPrice = $originalPrice;
  }

  public function getPrice() {
    return $this->price;
  }

  public function setPrice( $price) {
    $this->price = $price;
  }

  public function getSaleEndDate() {
    return $this->saleEndDate;
  }

  public function setSaleEndDate( $saleEndDate) {
    $this->saleEndDate = $saleEndDate;
  }

  public function getSalePrice() {
    return $this->salePrice;
  }

  public function setSalePrice( $salePrice) {
    $this->salePrice = $salePrice;
  }

  public function getSaleStartDate() {
    return $this->saleStartDate;
  }

  public function setSaleStartDate( $saleStartDate) {
    $this->saleStartDate = $saleStartDate;
  }

  public function getShipping() {
    return $this->shipping;
  }

  public function setShipping( $shipping) {
    $this->shipping = $shipping;
  }

  public function getStoreId() {
    return $this->storeId;
  }

  public function setStoreId( $storeId) {
    $this->storeId = $storeId;
  }

  public function getTax() {
    return $this->tax;
  }

  public function setTax( $tax) {
    $this->tax = $tax;
  }
}

class Google_ShoppingModelProductJsonV1Variants extends Google_Model {
  public $variant;
  protected $__variantType = 'Google_ShoppingModelProductJsonV1';
  protected $__variantDataType = '';

  public function getVariant() {
    return $this->variant;
  }

  public function setVariant(Google_ShoppingModelProductJsonV1 $variant) {
    $this->variant = $variant;
  }
}

class Google_ShoppingModelRecommendationsJsonV1 extends Google_Model {
  public $recommendationList;
  public $type;
  protected $__recommendationListType = 'Google_ShoppingModelRecommendationsJsonV1RecommendationList';
  protected $__recommendationListDataType = 'array';

  public function getRecommendationList() {
    return $this->recommendationList;
  }

  public function setRecommendationList(/* array(Google_ShoppingModelRecommendationsJsonV1RecommendationList) */ $recommendationList) {
    $this->assertIsArray($recommendationList, 'Google_ShoppingModelRecommendationsJsonV1RecommendationList', __METHOD__);
    $this->recommendationList = $recommendationList;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }
}

class Google_ShoppingModelRecommendationsJsonV1RecommendationList extends Google_Model {
  public $product;
  protected $__productType = 'Google_ShoppingModelProductJsonV1';
  protected $__productDataType = '';

  public function getProduct() {
    return $this->product;
  }

  public function setProduct(Google_ShoppingModelProductJsonV1 $product) {
    $this->product = $product;
  }
}
