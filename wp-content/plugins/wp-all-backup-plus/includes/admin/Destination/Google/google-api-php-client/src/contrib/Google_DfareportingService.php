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
   * The "dimensionValues" collection of methods.
   * Typical usage is:
   *  <code>
   *   $dfareportingService = new Google_DfareportingService(...);
   *   $dimensionValues = $dfareportingService->dimensionValues;
   *  </code>
   */
  class Google_DimensionValuesServiceResource extends Google_ServiceResource {

    /**
     * Retrieves list of report dimension values for a list of filters. (dimensionValues.query)
     *
     * @param string $profileId The DFA user profile ID.
     * @param Google_DimensionValueRequest $postBody
     * @param array $optParams Optional parameters.
     *
     * @opt_param int maxResults Maximum number of results to return.
     * @opt_param string pageToken The value of the nextToken from the previous result page.
     * @return Google_DimensionValueList
     */
    public function query($profileId, Google_DimensionValueRequest $postBody, $optParams = array()) {
      $params = array('profileId' => $profileId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('query', array($params));
      if ($this->useObjects()) {
        return new Google_DimensionValueList($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "files" collection of methods.
   * Typical usage is:
   *  <code>
   *   $dfareportingService = new Google_DfareportingService(...);
   *   $files = $dfareportingService->files;
   *  </code>
   */
  class Google_FilesServiceResource extends Google_ServiceResource {

    /**
     * Retrieves a report file by its report ID and file ID. (files.get)
     *
     * @param string $reportId The ID of the report.
     * @param string $fileId The ID of the report file.
     * @param array $optParams Optional parameters.
     * @return Google_DfareportingFile
     */
    public function get($reportId, $fileId, $optParams = array()) {
      $params = array('reportId' => $reportId, 'fileId' => $fileId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_DfareportingFile($data);
      } else {
        return $data;
      }
    }
    /**
     * Lists files for a user profile. (files.list)
     *
     * @param string $profileId The DFA profile ID.
     * @param array $optParams Optional parameters.
     *
     * @opt_param int maxResults Maximum number of results to return.
     * @opt_param string pageToken The value of the nextToken from the previous result page.
     * @opt_param string scope The scope that defines which results are returned, default is 'MINE'.
     * @opt_param string sortField The field by which to sort the list.
     * @opt_param string sortOrder Order of sorted results, default is 'DESCENDING'.
     * @return Google_FileList
     */
    public function listFiles($profileId, $optParams = array()) {
      $params = array('profileId' => $profileId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_FileList($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "reports" collection of methods.
   * Typical usage is:
   *  <code>
   *   $dfareportingService = new Google_DfareportingService(...);
   *   $reports = $dfareportingService->reports;
   *  </code>
   */
  class Google_ReportsServiceResource extends Google_ServiceResource {

    /**
     * Deletes a report by its ID. (reports.delete)
     *
     * @param string $profileId The DFA user profile ID.
     * @param string $reportId The ID of the report.
     * @param array $optParams Optional parameters.
     */
    public function delete($profileId, $reportId, $optParams = array()) {
      $params = array('profileId' => $profileId, 'reportId' => $reportId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('delete', array($params));
      return $data;
    }
    /**
     * Retrieves a report by its ID. (reports.get)
     *
     * @param string $profileId The DFA user profile ID.
     * @param string $reportId The ID of the report.
     * @param array $optParams Optional parameters.
     * @return Google_Report
     */
    public function get($profileId, $reportId, $optParams = array()) {
      $params = array('profileId' => $profileId, 'reportId' => $reportId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Report($data);
      } else {
        return $data;
      }
    }
    /**
     * Creates a report. (reports.insert)
     *
     * @param string $profileId The DFA user profile ID.
     * @param Google_Report $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Report
     */
    public function insert($profileId, Google_Report $postBody, $optParams = array()) {
      $params = array('profileId' => $profileId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Report($data);
      } else {
        return $data;
      }
    }
    /**
     * Retrieves list of reports. (reports.list)
     *
     * @param string $profileId The DFA user profile ID.
     * @param array $optParams Optional parameters.
     *
     * @opt_param int maxResults Maximum number of results to return.
     * @opt_param string pageToken The value of the nextToken from the previous result page.
     * @opt_param string scope The scope that defines which results are returned, default is 'MINE'.
     * @opt_param string sortField The field by which to sort the list.
     * @opt_param string sortOrder Order of sorted results, default is 'DESCENDING'.
     * @return Google_ReportList
     */
    public function listReports($profileId, $optParams = array()) {
      $params = array('profileId' => $profileId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_ReportList($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates a report. This method supports patch semantics. (reports.patch)
     *
     * @param string $profileId The DFA user profile ID.
     * @param string $reportId The ID of the report.
     * @param Google_Report $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Report
     */
    public function patch($profileId, $reportId, Google_Report $postBody, $optParams = array()) {
      $params = array('profileId' => $profileId, 'reportId' => $reportId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('patch', array($params));
      if ($this->useObjects()) {
        return new Google_Report($data);
      } else {
        return $data;
      }
    }
    /**
     * Runs a report. (reports.run)
     *
     * @param string $profileId The DFA profile ID.
     * @param string $reportId The ID of the report.
     * @param array $optParams Optional parameters.
     *
     * @opt_param bool synchronous If set and true, tries to run the report synchronously.
     * @return Google_DfareportingFile
     */
    public function run($profileId, $reportId, $optParams = array()) {
      $params = array('profileId' => $profileId, 'reportId' => $reportId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('run', array($params));
      if ($this->useObjects()) {
        return new Google_DfareportingFile($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates a report. (reports.update)
     *
     * @param string $profileId The DFA user profile ID.
     * @param string $reportId The ID of the report.
     * @param Google_Report $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Report
     */
    public function update($profileId, $reportId, Google_Report $postBody, $optParams = array()) {
      $params = array('profileId' => $profileId, 'reportId' => $reportId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('update', array($params));
      if ($this->useObjects()) {
        return new Google_Report($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "compatibleFields" collection of methods.
   * Typical usage is:
   *  <code>
   *   $dfareportingService = new Google_DfareportingService(...);
   *   $compatibleFields = $dfareportingService->compatibleFields;
   *  </code>
   */
  class Google_ReportsCompatibleFieldsServiceResource extends Google_ServiceResource {

    /**
     * Returns the fields that are compatible to be selected in the respective sections of a report
     * criteria, given the fields already selected in the input report and user permissions.
     * (compatibleFields.query)
     *
     * @param string $profileId The DFA user profile ID.
     * @param Google_Report $postBody
     * @param array $optParams Optional parameters.
     * @return Google_CompatibleFields
     */
    public function query($profileId, Google_Report $postBody, $optParams = array()) {
      $params = array('profileId' => $profileId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('query', array($params));
      if ($this->useObjects()) {
        return new Google_CompatibleFields($data);
      } else {
        return $data;
      }
    }
  }
  /**
   * The "files" collection of methods.
   * Typical usage is:
   *  <code>
   *   $dfareportingService = new Google_DfareportingService(...);
   *   $files = $dfareportingService->files;
   *  </code>
   */
  class Google_ReportsFilesServiceResource extends Google_ServiceResource {

    /**
     * Retrieves a report file. (files.get)
     *
     * @param string $profileId The DFA profile ID.
     * @param string $reportId The ID of the report.
     * @param string $fileId The ID of the report file.
     * @param array $optParams Optional parameters.
     * @return Google_DfareportingFile
     */
    public function get($profileId, $reportId, $fileId, $optParams = array()) {
      $params = array('profileId' => $profileId, 'reportId' => $reportId, 'fileId' => $fileId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_DfareportingFile($data);
      } else {
        return $data;
      }
    }
    /**
     * Lists files for a report. (files.list)
     *
     * @param string $profileId The DFA profile ID.
     * @param string $reportId The ID of the parent report.
     * @param array $optParams Optional parameters.
     *
     * @opt_param int maxResults Maximum number of results to return.
     * @opt_param string pageToken The value of the nextToken from the previous result page.
     * @opt_param string sortField The field by which to sort the list.
     * @opt_param string sortOrder Order of sorted results, default is 'DESCENDING'.
     * @return Google_FileList
     */
    public function listReportsFiles($profileId, $reportId, $optParams = array()) {
      $params = array('profileId' => $profileId, 'reportId' => $reportId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_FileList($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "userProfiles" collection of methods.
   * Typical usage is:
   *  <code>
   *   $dfareportingService = new Google_DfareportingService(...);
   *   $userProfiles = $dfareportingService->userProfiles;
   *  </code>
   */
  class Google_UserProfilesServiceResource extends Google_ServiceResource {

    /**
     * Gets one user profile by ID. (userProfiles.get)
     *
     * @param string $profileId The user profile ID.
     * @param array $optParams Optional parameters.
     * @return Google_UserProfile
     */
    public function get($profileId, $optParams = array()) {
      $params = array('profileId' => $profileId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_UserProfile($data);
      } else {
        return $data;
      }
    }
    /**
     * Retrieves list of user profiles for a user. (userProfiles.list)
     *
     * @param array $optParams Optional parameters.
     * @return Google_UserProfileList
     */
    public function listUserProfiles($optParams = array()) {
      $params = array();
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_UserProfileList($data);
      } else {
        return $data;
      }
    }
  }

/**
 * Service definition for Google_Dfareporting (v1.3).
 *
 * <p>
 * Lets you create, run and download reports.
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="https://developers.google.com/doubleclick-advertisers/reporting/" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_DfareportingService extends Google_Service {
  public $dimensionValues;
  public $files;
  public $reports;
  public $reports_compatibleFields;
  public $reports_files;
  public $userProfiles;
  /**
   * Constructs the internal representation of the Dfareporting service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client) {
    $this->servicePath = 'dfareporting/v1.3/';
    $this->version = 'v1.3';
    $this->serviceName = 'dfareporting';

    $client->addService($this->serviceName, $this->version);
    $this->dimensionValues = new Google_DimensionValuesServiceResource($this, $this->serviceName, 'dimensionValues', json_decode('{"methods": {"query": {"id": "dfareporting.dimensionValues.query", "path": "userprofiles/{profileId}/dimensionvalues/query", "httpMethod": "POST", "parameters": {"maxResults": {"type": "integer", "format": "int32", "minimum": "0", "maximum": "100", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "request": {"$ref": "DimensionValueRequest"}, "response": {"$ref": "DimensionValueList"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}}}', true));
    $this->files = new Google_FilesServiceResource($this, $this->serviceName, 'files', json_decode('{"methods": {"get": {"id": "dfareporting.files.get", "path": "reports/{reportId}/files/{fileId}", "httpMethod": "GET", "parameters": {"fileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "reportId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "response": {"$ref": "File"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"], "supportsMediaDownload": true}, "list": {"id": "dfareporting.files.list", "path": "userprofiles/{profileId}/files", "httpMethod": "GET", "parameters": {"maxResults": {"type": "integer", "format": "int32", "minimum": "0", "maximum": "10", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "scope": {"type": "string", "default": "MINE", "enum": ["ALL", "MINE", "SHARED_WITH_ME"], "location": "query"}, "sortField": {"type": "string", "default": "LAST_MODIFIED_TIME", "enum": ["ID", "LAST_MODIFIED_TIME"], "location": "query"}, "sortOrder": {"type": "string", "default": "DESCENDING", "enum": ["ASCENDING", "DESCENDING"], "location": "query"}}, "response": {"$ref": "FileList"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}}}', true));
    $this->reports = new Google_ReportsServiceResource($this, $this->serviceName, 'reports', json_decode('{"methods": {"delete": {"id": "dfareporting.reports.delete", "path": "userprofiles/{profileId}/reports/{reportId}", "httpMethod": "DELETE", "parameters": {"profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "reportId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}, "get": {"id": "dfareporting.reports.get", "path": "userprofiles/{profileId}/reports/{reportId}", "httpMethod": "GET", "parameters": {"profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "reportId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "response": {"$ref": "Report"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}, "insert": {"id": "dfareporting.reports.insert", "path": "userprofiles/{profileId}/reports", "httpMethod": "POST", "parameters": {"profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "request": {"$ref": "Report"}, "response": {"$ref": "Report"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}, "list": {"id": "dfareporting.reports.list", "path": "userprofiles/{profileId}/reports", "httpMethod": "GET", "parameters": {"maxResults": {"type": "integer", "format": "int32", "minimum": "0", "maximum": "10", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "scope": {"type": "string", "default": "MINE", "enum": ["ALL", "MINE"], "location": "query"}, "sortField": {"type": "string", "default": "LAST_MODIFIED_TIME", "enum": ["ID", "LAST_MODIFIED_TIME", "NAME"], "location": "query"}, "sortOrder": {"type": "string", "default": "DESCENDING", "enum": ["ASCENDING", "DESCENDING"], "location": "query"}}, "response": {"$ref": "ReportList"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}, "patch": {"id": "dfareporting.reports.patch", "path": "userprofiles/{profileId}/reports/{reportId}", "httpMethod": "PATCH", "parameters": {"profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "reportId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "request": {"$ref": "Report"}, "response": {"$ref": "Report"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}, "run": {"id": "dfareporting.reports.run", "path": "userprofiles/{profileId}/reports/{reportId}/run", "httpMethod": "POST", "parameters": {"profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "reportId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "synchronous": {"type": "boolean", "location": "query"}}, "response": {"$ref": "File"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}, "update": {"id": "dfareporting.reports.update", "path": "userprofiles/{profileId}/reports/{reportId}", "httpMethod": "PUT", "parameters": {"profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "reportId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "request": {"$ref": "Report"}, "response": {"$ref": "Report"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}}}', true));
    $this->reports_compatibleFields = new Google_ReportsCompatibleFieldsServiceResource($this, $this->serviceName, 'compatibleFields', json_decode('{"methods": {"query": {"id": "dfareporting.reports.compatibleFields.query", "path": "userprofiles/{profileId}/reports/compatiblefields/query", "httpMethod": "POST", "parameters": {"profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "request": {"$ref": "Report"}, "response": {"$ref": "CompatibleFields"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}}}', true));
    $this->reports_files = new Google_ReportsFilesServiceResource($this, $this->serviceName, 'files', json_decode('{"methods": {"get": {"id": "dfareporting.reports.files.get", "path": "userprofiles/{profileId}/reports/{reportId}/files/{fileId}", "httpMethod": "GET", "parameters": {"fileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "reportId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "response": {"$ref": "File"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"], "supportsMediaDownload": true}, "list": {"id": "dfareporting.reports.files.list", "path": "userprofiles/{profileId}/reports/{reportId}/files", "httpMethod": "GET", "parameters": {"maxResults": {"type": "integer", "format": "int32", "minimum": "0", "maximum": "10", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "reportId": {"type": "string", "required": true, "format": "int64", "location": "path"}, "sortField": {"type": "string", "default": "LAST_MODIFIED_TIME", "enum": ["ID", "LAST_MODIFIED_TIME"], "location": "query"}, "sortOrder": {"type": "string", "default": "DESCENDING", "enum": ["ASCENDING", "DESCENDING"], "location": "query"}}, "response": {"$ref": "FileList"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}}}', true));
    $this->userProfiles = new Google_UserProfilesServiceResource($this, $this->serviceName, 'userProfiles', json_decode('{"methods": {"get": {"id": "dfareporting.userProfiles.get", "path": "userprofiles/{profileId}", "httpMethod": "GET", "parameters": {"profileId": {"type": "string", "required": true, "format": "int64", "location": "path"}}, "response": {"$ref": "UserProfile"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}, "list": {"id": "dfareporting.userProfiles.list", "path": "userprofiles", "httpMethod": "GET", "response": {"$ref": "UserProfileList"}, "scopes": ["https://www.googleapis.com/auth/dfareporting"]}}}', true));

  }
}



class Google_Activities extends Google_Model {
  public $filters;
  public $kind;
  public $metricNames;
  protected $__filtersType = 'Google_DimensionValue';
  protected $__filtersDataType = 'array';

  public function getFilters() {
    return $this->filters;
  }

  public function setFilters(/* array(Google_DimensionValue) */ $filters) {
    $this->assertIsArray($filters, 'Google_DimensionValue', __METHOD__);
    $this->filters = $filters;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getMetricNames() {
    return $this->metricNames;
  }

  public function setMetricNames(/* array(Google_string) */ $metricNames) {
    $this->assertIsArray($metricNames, 'Google_string', __METHOD__);
    $this->metricNames = $metricNames;
  }
}

class Google_CompatibleFields extends Google_Model {
  public $crossDimensionReachReportCompatibleFields;
  public $floodlightReportCompatibleFields;
  public $kind;
  public $pathToConversionReportCompatibleFields;
  public $reachReportCompatibleFields;
  public $reportCompatibleFields;
  protected $__crossDimensionReachReportCompatibleFieldsType = 'Google_CrossDimensionReachReportCompatibleFields';
  protected $__crossDimensionReachReportCompatibleFieldsDataType = '';
  protected $__floodlightReportCompatibleFieldsType = 'Google_FloodlightReportCompatibleFields';
  protected $__floodlightReportCompatibleFieldsDataType = '';
  protected $__pathToConversionReportCompatibleFieldsType = 'Google_PathToConversionReportCompatibleFields';
  protected $__pathToConversionReportCompatibleFieldsDataType = '';
  protected $__reachReportCompatibleFieldsType = 'Google_ReachReportCompatibleFields';
  protected $__reachReportCompatibleFieldsDataType = '';
  protected $__reportCompatibleFieldsType = 'Google_ReportCompatibleFields';
  protected $__reportCompatibleFieldsDataType = '';

  public function getCrossDimensionReachReportCompatibleFields() {
    return $this->crossDimensionReachReportCompatibleFields;
  }

  public function setCrossDimensionReachReportCompatibleFields(Google_CrossDimensionReachReportCompatibleFields $crossDimensionReachReportCompatibleFields) {
    $this->crossDimensionReachReportCompatibleFields = $crossDimensionReachReportCompatibleFields;
  }

  public function getFloodlightReportCompatibleFields() {
    return $this->floodlightReportCompatibleFields;
  }

  public function setFloodlightReportCompatibleFields(Google_FloodlightReportCompatibleFields $floodlightReportCompatibleFields) {
    $this->floodlightReportCompatibleFields = $floodlightReportCompatibleFields;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getPathToConversionReportCompatibleFields() {
    return $this->pathToConversionReportCompatibleFields;
  }

  public function setPathToConversionReportCompatibleFields(Google_PathToConversionReportCompatibleFields $pathToConversionReportCompatibleFields) {
    $this->pathToConversionReportCompatibleFields = $pathToConversionReportCompatibleFields;
  }

  public function getReachReportCompatibleFields() {
    return $this->reachReportCompatibleFields;
  }

  public function setReachReportCompatibleFields(Google_ReachReportCompatibleFields $reachReportCompatibleFields) {
    $this->reachReportCompatibleFields = $reachReportCompatibleFields;
  }

  public function getReportCompatibleFields() {
    return $this->reportCompatibleFields;
  }

  public function setReportCompatibleFields(Google_ReportCompatibleFields $reportCompatibleFields) {
    $this->reportCompatibleFields = $reportCompatibleFields;
  }
}

class Google_CrossDimensionReachReportCompatibleFields extends Google_Model {
  public $breakdown;
  public $dimensionFilters;
  public $kind;
  public $metrics;
  public $overlapMetrics;
  protected $__breakdownType = 'Google_Dimension';
  protected $__breakdownDataType = 'array';
  protected $__dimensionFiltersType = 'Google_Dimension';
  protected $__dimensionFiltersDataType = 'array';
  protected $__metricsType = 'Google_Metric';
  protected $__metricsDataType = 'array';
  protected $__overlapMetricsType = 'Google_Metric';
  protected $__overlapMetricsDataType = 'array';

  public function getBreakdown() {
    return $this->breakdown;
  }

  public function setBreakdown(/* array(Google_Dimension) */ $breakdown) {
    $this->assertIsArray($breakdown, 'Google_Dimension', __METHOD__);
    $this->breakdown = $breakdown;
  }

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_Dimension) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_Dimension', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getMetrics() {
    return $this->metrics;
  }

  public function setMetrics(/* array(Google_Metric) */ $metrics) {
    $this->assertIsArray($metrics, 'Google_Metric', __METHOD__);
    $this->metrics = $metrics;
  }

  public function getOverlapMetrics() {
    return $this->overlapMetrics;
  }

  public function setOverlapMetrics(/* array(Google_Metric) */ $overlapMetrics) {
    $this->assertIsArray($overlapMetrics, 'Google_Metric', __METHOD__);
    $this->overlapMetrics = $overlapMetrics;
  }
}

class Google_CustomRichMediaEvents extends Google_Model {
  public $filteredEventIds;
  public $kind;
  protected $__filteredEventIdsType = 'Google_DimensionValue';
  protected $__filteredEventIdsDataType = 'array';

  public function getFilteredEventIds() {
    return $this->filteredEventIds;
  }

  public function setFilteredEventIds(/* array(Google_DimensionValue) */ $filteredEventIds) {
    $this->assertIsArray($filteredEventIds, 'Google_DimensionValue', __METHOD__);
    $this->filteredEventIds = $filteredEventIds;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }
}

class Google_DateRange extends Google_Model {
  public $endDate;
  public $kind;
  public $relativeDateRange;
  public $startDate;

  public function getEndDate() {
    return $this->endDate;
  }

  public function setEndDate( $endDate) {
    $this->endDate = $endDate;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getRelativeDateRange() {
    return $this->relativeDateRange;
  }

  public function setRelativeDateRange( $relativeDateRange) {
    $this->relativeDateRange = $relativeDateRange;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function setStartDate( $startDate) {
    $this->startDate = $startDate;
  }
}

class Google_DfareportingFile extends Google_Model {
  public $dateRange;
  public $etag;
  public $fileName;
  public $format;
  public $id;
  public $kind;
  public $lastModifiedTime;
  public $reportId;
  public $status;
  public $urls;
  protected $__dateRangeType = 'Google_DateRange';
  protected $__dateRangeDataType = '';
  protected $__urlsType = 'Google_DfareportingFileUrls';
  protected $__urlsDataType = '';

  public function getDateRange() {
    return $this->dateRange;
  }

  public function setDateRange(Google_DateRange $dateRange) {
    $this->dateRange = $dateRange;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getFileName() {
    return $this->fileName;
  }

  public function setFileName( $fileName) {
    $this->fileName = $fileName;
  }

  public function getFormat() {
    return $this->format;
  }

  public function setFormat( $format) {
    $this->format = $format;
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

  public function getLastModifiedTime() {
    return $this->lastModifiedTime;
  }

  public function setLastModifiedTime( $lastModifiedTime) {
    $this->lastModifiedTime = $lastModifiedTime;
  }

  public function getReportId() {
    return $this->reportId;
  }

  public function setReportId( $reportId) {
    $this->reportId = $reportId;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus( $status) {
    $this->status = $status;
  }

  public function getUrls() {
    return $this->urls;
  }

  public function setUrls(Google_DfareportingFileUrls $urls) {
    $this->urls = $urls;
  }
}

class Google_DfareportingFileUrls extends Google_Model {
  public $apiUrl;
  public $browserUrl;

  public function getApiUrl() {
    return $this->apiUrl;
  }

  public function setApiUrl( $apiUrl) {
    $this->apiUrl = $apiUrl;
  }

  public function getBrowserUrl() {
    return $this->browserUrl;
  }

  public function setBrowserUrl( $browserUrl) {
    $this->browserUrl = $browserUrl;
  }
}

class Google_Dimension extends Google_Model {
  public $kind;
  public $name;

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }
}

class Google_DimensionFilter extends Google_Model {
  public $dimensionName;
  public $kind;
  public $value;

  public function getDimensionName() {
    return $this->dimensionName;
  }

  public function setDimensionName( $dimensionName) {
    $this->dimensionName = $dimensionName;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_DimensionValue extends Google_Model {
  public $dimensionName;
  public $etag;
  public $id;
  public $kind;
  public $matchType;
  public $value;

  public function getDimensionName() {
    return $this->dimensionName;
  }

  public function setDimensionName( $dimensionName) {
    $this->dimensionName = $dimensionName;
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

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getMatchType() {
    return $this->matchType;
  }

  public function setMatchType( $matchType) {
    $this->matchType = $matchType;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_DimensionValueList extends Google_Model {
  public $etag;
  public $items;
  public $kind;
  public $nextPageToken;
  protected $__itemsType = 'Google_DimensionValue';
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

  public function setItems(/* array(Google_DimensionValue) */ $items) {
    $this->assertIsArray($items, 'Google_DimensionValue', __METHOD__);
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
}

class Google_DimensionValueRequest extends Google_Model {
  public $dimensionName;
  public $endDate;
  public $filters;
  public $kind;
  public $startDate;
  protected $__filtersType = 'Google_DimensionFilter';
  protected $__filtersDataType = 'array';

  public function getDimensionName() {
    return $this->dimensionName;
  }

  public function setDimensionName( $dimensionName) {
    $this->dimensionName = $dimensionName;
  }

  public function getEndDate() {
    return $this->endDate;
  }

  public function setEndDate( $endDate) {
    $this->endDate = $endDate;
  }

  public function getFilters() {
    return $this->filters;
  }

  public function setFilters(/* array(Google_DimensionFilter) */ $filters) {
    $this->assertIsArray($filters, 'Google_DimensionFilter', __METHOD__);
    $this->filters = $filters;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function setStartDate( $startDate) {
    $this->startDate = $startDate;
  }
}

class Google_FileList extends Google_Model {
  public $etag;
  public $items;
  public $kind;
  public $nextPageToken;
  protected $__itemsType = 'Google_DfareportingFile';
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

  public function setItems(/* array(Google_DfareportingFile) */ $items) {
    $this->assertIsArray($items, 'Google_DfareportingFile', __METHOD__);
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
}

class Google_FloodlightReportCompatibleFields extends Google_Model {
  public $dimensionFilters;
  public $dimensions;
  public $kind;
  public $metrics;
  protected $__dimensionFiltersType = 'Google_Dimension';
  protected $__dimensionFiltersDataType = 'array';
  protected $__dimensionsType = 'Google_Dimension';
  protected $__dimensionsDataType = 'array';
  protected $__metricsType = 'Google_Metric';
  protected $__metricsDataType = 'array';

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_Dimension) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_Dimension', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getDimensions() {
    return $this->dimensions;
  }

  public function setDimensions(/* array(Google_Dimension) */ $dimensions) {
    $this->assertIsArray($dimensions, 'Google_Dimension', __METHOD__);
    $this->dimensions = $dimensions;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getMetrics() {
    return $this->metrics;
  }

  public function setMetrics(/* array(Google_Metric) */ $metrics) {
    $this->assertIsArray($metrics, 'Google_Metric', __METHOD__);
    $this->metrics = $metrics;
  }
}

class Google_Metric extends Google_Model {
  public $kind;
  public $name;

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }
}

class Google_PathToConversionReportCompatibleFields extends Google_Model {
  public $conversionDimensions;
  public $customFloodlightVariables;
  public $kind;
  public $metrics;
  public $perInteractionDimensions;
  protected $__conversionDimensionsType = 'Google_Dimension';
  protected $__conversionDimensionsDataType = 'array';
  protected $__customFloodlightVariablesType = 'Google_Dimension';
  protected $__customFloodlightVariablesDataType = 'array';
  protected $__metricsType = 'Google_Metric';
  protected $__metricsDataType = 'array';
  protected $__perInteractionDimensionsType = 'Google_Dimension';
  protected $__perInteractionDimensionsDataType = 'array';

  public function getConversionDimensions() {
    return $this->conversionDimensions;
  }

  public function setConversionDimensions(/* array(Google_Dimension) */ $conversionDimensions) {
    $this->assertIsArray($conversionDimensions, 'Google_Dimension', __METHOD__);
    $this->conversionDimensions = $conversionDimensions;
  }

  public function getCustomFloodlightVariables() {
    return $this->customFloodlightVariables;
  }

  public function setCustomFloodlightVariables(/* array(Google_Dimension) */ $customFloodlightVariables) {
    $this->assertIsArray($customFloodlightVariables, 'Google_Dimension', __METHOD__);
    $this->customFloodlightVariables = $customFloodlightVariables;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getMetrics() {
    return $this->metrics;
  }

  public function setMetrics(/* array(Google_Metric) */ $metrics) {
    $this->assertIsArray($metrics, 'Google_Metric', __METHOD__);
    $this->metrics = $metrics;
  }

  public function getPerInteractionDimensions() {
    return $this->perInteractionDimensions;
  }

  public function setPerInteractionDimensions(/* array(Google_Dimension) */ $perInteractionDimensions) {
    $this->assertIsArray($perInteractionDimensions, 'Google_Dimension', __METHOD__);
    $this->perInteractionDimensions = $perInteractionDimensions;
  }
}

class Google_ReachReportCompatibleFields extends Google_Model {
  public $dimensionFilters;
  public $dimensions;
  public $kind;
  public $metrics;
  public $pivotedActivityMetrics;
  public $reachByFrequencyMetrics;
  protected $__dimensionFiltersType = 'Google_Dimension';
  protected $__dimensionFiltersDataType = 'array';
  protected $__dimensionsType = 'Google_Dimension';
  protected $__dimensionsDataType = 'array';
  protected $__metricsType = 'Google_Metric';
  protected $__metricsDataType = 'array';
  protected $__pivotedActivityMetricsType = 'Google_Metric';
  protected $__pivotedActivityMetricsDataType = 'array';
  protected $__reachByFrequencyMetricsType = 'Google_Metric';
  protected $__reachByFrequencyMetricsDataType = 'array';

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_Dimension) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_Dimension', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getDimensions() {
    return $this->dimensions;
  }

  public function setDimensions(/* array(Google_Dimension) */ $dimensions) {
    $this->assertIsArray($dimensions, 'Google_Dimension', __METHOD__);
    $this->dimensions = $dimensions;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getMetrics() {
    return $this->metrics;
  }

  public function setMetrics(/* array(Google_Metric) */ $metrics) {
    $this->assertIsArray($metrics, 'Google_Metric', __METHOD__);
    $this->metrics = $metrics;
  }

  public function getPivotedActivityMetrics() {
    return $this->pivotedActivityMetrics;
  }

  public function setPivotedActivityMetrics(/* array(Google_Metric) */ $pivotedActivityMetrics) {
    $this->assertIsArray($pivotedActivityMetrics, 'Google_Metric', __METHOD__);
    $this->pivotedActivityMetrics = $pivotedActivityMetrics;
  }

  public function getReachByFrequencyMetrics() {
    return $this->reachByFrequencyMetrics;
  }

  public function setReachByFrequencyMetrics(/* array(Google_Metric) */ $reachByFrequencyMetrics) {
    $this->assertIsArray($reachByFrequencyMetrics, 'Google_Metric', __METHOD__);
    $this->reachByFrequencyMetrics = $reachByFrequencyMetrics;
  }
}

class Google_Recipient extends Google_Model {
  public $deliveryType;
  public $email;
  public $kind;

  public function getDeliveryType() {
    return $this->deliveryType;
  }

  public function setDeliveryType( $deliveryType) {
    $this->deliveryType = $deliveryType;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setEmail( $email) {
    $this->email = $email;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }
}

class Google_Report extends Google_Model {
  public $accountId;
  public $activeGrpCriteria;
  public $criteria;
  public $crossDimensionReachCriteria;
  public $delivery;
  public $etag;
  public $fileName;
  public $floodlightCriteria;
  public $format;
  public $id;
  public $kind;
  public $lastModifiedTime;
  public $name;
  public $ownerProfileId;
  public $pathToConversionCriteria;
  public $reachCriteria;
  public $schedule;
  public $subAccountId;
  public $type;
  protected $__activeGrpCriteriaType = 'Google_ReportActiveGrpCriteria';
  protected $__activeGrpCriteriaDataType = '';
  protected $__criteriaType = 'Google_ReportCriteria';
  protected $__criteriaDataType = '';
  protected $__crossDimensionReachCriteriaType = 'Google_ReportCrossDimensionReachCriteria';
  protected $__crossDimensionReachCriteriaDataType = '';
  protected $__deliveryType = 'Google_ReportDelivery';
  protected $__deliveryDataType = '';
  protected $__floodlightCriteriaType = 'Google_ReportFloodlightCriteria';
  protected $__floodlightCriteriaDataType = '';
  protected $__pathToConversionCriteriaType = 'Google_ReportPathToConversionCriteria';
  protected $__pathToConversionCriteriaDataType = '';
  protected $__reachCriteriaType = 'Google_ReportReachCriteria';
  protected $__reachCriteriaDataType = '';
  protected $__scheduleType = 'Google_ReportSchedule';
  protected $__scheduleDataType = '';

  public function getAccountId() {
    return $this->accountId;
  }

  public function setAccountId( $accountId) {
    $this->accountId = $accountId;
  }

  public function getActiveGrpCriteria() {
    return $this->activeGrpCriteria;
  }

  public function setActiveGrpCriteria(Google_ReportActiveGrpCriteria $activeGrpCriteria) {
    $this->activeGrpCriteria = $activeGrpCriteria;
  }

  public function getCriteria() {
    return $this->criteria;
  }

  public function setCriteria(Google_ReportCriteria $criteria) {
    $this->criteria = $criteria;
  }

  public function getCrossDimensionReachCriteria() {
    return $this->crossDimensionReachCriteria;
  }

  public function setCrossDimensionReachCriteria(Google_ReportCrossDimensionReachCriteria $crossDimensionReachCriteria) {
    $this->crossDimensionReachCriteria = $crossDimensionReachCriteria;
  }

  public function getDelivery() {
    return $this->delivery;
  }

  public function setDelivery(Google_ReportDelivery $delivery) {
    $this->delivery = $delivery;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getFileName() {
    return $this->fileName;
  }

  public function setFileName( $fileName) {
    $this->fileName = $fileName;
  }

  public function getFloodlightCriteria() {
    return $this->floodlightCriteria;
  }

  public function setFloodlightCriteria(Google_ReportFloodlightCriteria $floodlightCriteria) {
    $this->floodlightCriteria = $floodlightCriteria;
  }

  public function getFormat() {
    return $this->format;
  }

  public function setFormat( $format) {
    $this->format = $format;
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

  public function getLastModifiedTime() {
    return $this->lastModifiedTime;
  }

  public function setLastModifiedTime( $lastModifiedTime) {
    $this->lastModifiedTime = $lastModifiedTime;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getOwnerProfileId() {
    return $this->ownerProfileId;
  }

  public function setOwnerProfileId( $ownerProfileId) {
    $this->ownerProfileId = $ownerProfileId;
  }

  public function getPathToConversionCriteria() {
    return $this->pathToConversionCriteria;
  }

  public function setPathToConversionCriteria(Google_ReportPathToConversionCriteria $pathToConversionCriteria) {
    $this->pathToConversionCriteria = $pathToConversionCriteria;
  }

  public function getReachCriteria() {
    return $this->reachCriteria;
  }

  public function setReachCriteria(Google_ReportReachCriteria $reachCriteria) {
    $this->reachCriteria = $reachCriteria;
  }

  public function getSchedule() {
    return $this->schedule;
  }

  public function setSchedule(Google_ReportSchedule $schedule) {
    $this->schedule = $schedule;
  }

  public function getSubAccountId() {
    return $this->subAccountId;
  }

  public function setSubAccountId( $subAccountId) {
    $this->subAccountId = $subAccountId;
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type) {
    $this->type = $type;
  }
}

class Google_ReportActiveGrpCriteria extends Google_Model {
  public $dateRange;
  public $dimensionFilters;
  public $dimensions;
  public $metricNames;
  protected $__dateRangeType = 'Google_DateRange';
  protected $__dateRangeDataType = '';
  protected $__dimensionFiltersType = 'Google_DimensionValue';
  protected $__dimensionFiltersDataType = 'array';
  protected $__dimensionsType = 'Google_SortedDimension';
  protected $__dimensionsDataType = 'array';

  public function getDateRange() {
    return $this->dateRange;
  }

  public function setDateRange(Google_DateRange $dateRange) {
    $this->dateRange = $dateRange;
  }

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_DimensionValue) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_DimensionValue', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getDimensions() {
    return $this->dimensions;
  }

  public function setDimensions(/* array(Google_SortedDimension) */ $dimensions) {
    $this->assertIsArray($dimensions, 'Google_SortedDimension', __METHOD__);
    $this->dimensions = $dimensions;
  }

  public function getMetricNames() {
    return $this->metricNames;
  }

  public function setMetricNames(/* array(Google_string) */ $metricNames) {
    $this->assertIsArray($metricNames, 'Google_string', __METHOD__);
    $this->metricNames = $metricNames;
  }
}

class Google_ReportCompatibleFields extends Google_Model {
  public $dimensionFilters;
  public $dimensions;
  public $kind;
  public $metrics;
  public $pivotedActivityMetrics;
  protected $__dimensionFiltersType = 'Google_Dimension';
  protected $__dimensionFiltersDataType = 'array';
  protected $__dimensionsType = 'Google_Dimension';
  protected $__dimensionsDataType = 'array';
  protected $__metricsType = 'Google_Metric';
  protected $__metricsDataType = 'array';
  protected $__pivotedActivityMetricsType = 'Google_Metric';
  protected $__pivotedActivityMetricsDataType = 'array';

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_Dimension) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_Dimension', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getDimensions() {
    return $this->dimensions;
  }

  public function setDimensions(/* array(Google_Dimension) */ $dimensions) {
    $this->assertIsArray($dimensions, 'Google_Dimension', __METHOD__);
    $this->dimensions = $dimensions;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getMetrics() {
    return $this->metrics;
  }

  public function setMetrics(/* array(Google_Metric) */ $metrics) {
    $this->assertIsArray($metrics, 'Google_Metric', __METHOD__);
    $this->metrics = $metrics;
  }

  public function getPivotedActivityMetrics() {
    return $this->pivotedActivityMetrics;
  }

  public function setPivotedActivityMetrics(/* array(Google_Metric) */ $pivotedActivityMetrics) {
    $this->assertIsArray($pivotedActivityMetrics, 'Google_Metric', __METHOD__);
    $this->pivotedActivityMetrics = $pivotedActivityMetrics;
  }
}

class Google_ReportCriteria extends Google_Model {
  public $activities;
  public $customRichMediaEvents;
  public $dateRange;
  public $dimensionFilters;
  public $dimensions;
  public $metricNames;
  protected $__activitiesType = 'Google_Activities';
  protected $__activitiesDataType = '';
  protected $__customRichMediaEventsType = 'Google_CustomRichMediaEvents';
  protected $__customRichMediaEventsDataType = '';
  protected $__dateRangeType = 'Google_DateRange';
  protected $__dateRangeDataType = '';
  protected $__dimensionFiltersType = 'Google_DimensionValue';
  protected $__dimensionFiltersDataType = 'array';
  protected $__dimensionsType = 'Google_SortedDimension';
  protected $__dimensionsDataType = 'array';

  public function getActivities() {
    return $this->activities;
  }

  public function setActivities(Google_Activities $activities) {
    $this->activities = $activities;
  }

  public function getCustomRichMediaEvents() {
    return $this->customRichMediaEvents;
  }

  public function setCustomRichMediaEvents(Google_CustomRichMediaEvents $customRichMediaEvents) {
    $this->customRichMediaEvents = $customRichMediaEvents;
  }

  public function getDateRange() {
    return $this->dateRange;
  }

  public function setDateRange(Google_DateRange $dateRange) {
    $this->dateRange = $dateRange;
  }

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_DimensionValue) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_DimensionValue', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getDimensions() {
    return $this->dimensions;
  }

  public function setDimensions(/* array(Google_SortedDimension) */ $dimensions) {
    $this->assertIsArray($dimensions, 'Google_SortedDimension', __METHOD__);
    $this->dimensions = $dimensions;
  }

  public function getMetricNames() {
    return $this->metricNames;
  }

  public function setMetricNames(/* array(Google_string) */ $metricNames) {
    $this->assertIsArray($metricNames, 'Google_string', __METHOD__);
    $this->metricNames = $metricNames;
  }
}

class Google_ReportCrossDimensionReachCriteria extends Google_Model {
  public $breakdown;
  public $dateRange;
  public $dimension;
  public $dimensionFilters;
  public $metricNames;
  public $overlapMetricNames;
  public $pivoted;
  protected $__breakdownType = 'Google_SortedDimension';
  protected $__breakdownDataType = 'array';
  protected $__dateRangeType = 'Google_DateRange';
  protected $__dateRangeDataType = '';
  protected $__dimensionFiltersType = 'Google_DimensionValue';
  protected $__dimensionFiltersDataType = 'array';

  public function getBreakdown() {
    return $this->breakdown;
  }

  public function setBreakdown(/* array(Google_SortedDimension) */ $breakdown) {
    $this->assertIsArray($breakdown, 'Google_SortedDimension', __METHOD__);
    $this->breakdown = $breakdown;
  }

  public function getDateRange() {
    return $this->dateRange;
  }

  public function setDateRange(Google_DateRange $dateRange) {
    $this->dateRange = $dateRange;
  }

  public function getDimension() {
    return $this->dimension;
  }

  public function setDimension( $dimension) {
    $this->dimension = $dimension;
  }

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_DimensionValue) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_DimensionValue', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getMetricNames() {
    return $this->metricNames;
  }

  public function setMetricNames(/* array(Google_string) */ $metricNames) {
    $this->assertIsArray($metricNames, 'Google_string', __METHOD__);
    $this->metricNames = $metricNames;
  }

  public function getOverlapMetricNames() {
    return $this->overlapMetricNames;
  }

  public function setOverlapMetricNames(/* array(Google_string) */ $overlapMetricNames) {
    $this->assertIsArray($overlapMetricNames, 'Google_string', __METHOD__);
    $this->overlapMetricNames = $overlapMetricNames;
  }

  public function getPivoted() {
    return $this->pivoted;
  }

  public function setPivoted( $pivoted) {
    $this->pivoted = $pivoted;
  }
}

class Google_ReportDelivery extends Google_Model {
  public $emailOwner;
  public $emailOwnerDeliveryType;
  public $message;
  public $recipients;
  protected $__recipientsType = 'Google_Recipient';
  protected $__recipientsDataType = 'array';

  public function getEmailOwner() {
    return $this->emailOwner;
  }

  public function setEmailOwner( $emailOwner) {
    $this->emailOwner = $emailOwner;
  }

  public function getEmailOwnerDeliveryType() {
    return $this->emailOwnerDeliveryType;
  }

  public function setEmailOwnerDeliveryType( $emailOwnerDeliveryType) {
    $this->emailOwnerDeliveryType = $emailOwnerDeliveryType;
  }

  public function getMessage() {
    return $this->message;
  }

  public function setMessage( $message) {
    $this->message = $message;
  }

  public function getRecipients() {
    return $this->recipients;
  }

  public function setRecipients(/* array(Google_Recipient) */ $recipients) {
    $this->assertIsArray($recipients, 'Google_Recipient', __METHOD__);
    $this->recipients = $recipients;
  }
}

class Google_ReportFloodlightCriteria extends Google_Model {
  public $customRichMediaEvents;
  public $dateRange;
  public $dimensionFilters;
  public $dimensions;
  public $floodlightConfigId;
  public $metricNames;
  public $reportProperties;
  protected $__customRichMediaEventsType = 'Google_DimensionValue';
  protected $__customRichMediaEventsDataType = 'array';
  protected $__dateRangeType = 'Google_DateRange';
  protected $__dateRangeDataType = '';
  protected $__dimensionFiltersType = 'Google_DimensionValue';
  protected $__dimensionFiltersDataType = 'array';
  protected $__dimensionsType = 'Google_SortedDimension';
  protected $__dimensionsDataType = 'array';
  protected $__floodlightConfigIdType = 'Google_DimensionValue';
  protected $__floodlightConfigIdDataType = '';
  protected $__reportPropertiesType = 'Google_ReportFloodlightCriteriaReportProperties';
  protected $__reportPropertiesDataType = '';

  public function getCustomRichMediaEvents() {
    return $this->customRichMediaEvents;
  }

  public function setCustomRichMediaEvents(/* array(Google_DimensionValue) */ $customRichMediaEvents) {
    $this->assertIsArray($customRichMediaEvents, 'Google_DimensionValue', __METHOD__);
    $this->customRichMediaEvents = $customRichMediaEvents;
  }

  public function getDateRange() {
    return $this->dateRange;
  }

  public function setDateRange(Google_DateRange $dateRange) {
    $this->dateRange = $dateRange;
  }

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_DimensionValue) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_DimensionValue', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getDimensions() {
    return $this->dimensions;
  }

  public function setDimensions(/* array(Google_SortedDimension) */ $dimensions) {
    $this->assertIsArray($dimensions, 'Google_SortedDimension', __METHOD__);
    $this->dimensions = $dimensions;
  }

  public function getFloodlightConfigId() {
    return $this->floodlightConfigId;
  }

  public function setFloodlightConfigId(Google_DimensionValue $floodlightConfigId) {
    $this->floodlightConfigId = $floodlightConfigId;
  }

  public function getMetricNames() {
    return $this->metricNames;
  }

  public function setMetricNames(/* array(Google_string) */ $metricNames) {
    $this->assertIsArray($metricNames, 'Google_string', __METHOD__);
    $this->metricNames = $metricNames;
  }

  public function getReportProperties() {
    return $this->reportProperties;
  }

  public function setReportProperties(Google_ReportFloodlightCriteriaReportProperties $reportProperties) {
    $this->reportProperties = $reportProperties;
  }
}

class Google_ReportFloodlightCriteriaReportProperties extends Google_Model {
  public $includeAttributedIPConversions;
  public $includeUnattributedCookieConversions;
  public $includeUnattributedIPConversions;

  public function getIncludeAttributedIPConversions() {
    return $this->includeAttributedIPConversions;
  }

  public function setIncludeAttributedIPConversions( $includeAttributedIPConversions) {
    $this->includeAttributedIPConversions = $includeAttributedIPConversions;
  }

  public function getIncludeUnattributedCookieConversions() {
    return $this->includeUnattributedCookieConversions;
  }

  public function setIncludeUnattributedCookieConversions( $includeUnattributedCookieConversions) {
    $this->includeUnattributedCookieConversions = $includeUnattributedCookieConversions;
  }

  public function getIncludeUnattributedIPConversions() {
    return $this->includeUnattributedIPConversions;
  }

  public function setIncludeUnattributedIPConversions( $includeUnattributedIPConversions) {
    $this->includeUnattributedIPConversions = $includeUnattributedIPConversions;
  }
}

class Google_ReportList extends Google_Model {
  public $etag;
  public $items;
  public $kind;
  public $nextPageToken;
  protected $__itemsType = 'Google_Report';
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

  public function setItems(/* array(Google_Report) */ $items) {
    $this->assertIsArray($items, 'Google_Report', __METHOD__);
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
}

class Google_ReportPathToConversionCriteria extends Google_Model {
  public $activityFilters;
  public $conversionDimensions;
  public $customFloodlightVariables;
  public $customRichMediaEvents;
  public $dateRange;
  public $floodlightConfigId;
  public $metricNames;
  public $perInteractionDimensions;
  public $reportProperties;
  protected $__activityFiltersType = 'Google_DimensionValue';
  protected $__activityFiltersDataType = 'array';
  protected $__conversionDimensionsType = 'Google_SortedDimension';
  protected $__conversionDimensionsDataType = 'array';
  protected $__customFloodlightVariablesType = 'Google_SortedDimension';
  protected $__customFloodlightVariablesDataType = 'array';
  protected $__customRichMediaEventsType = 'Google_DimensionValue';
  protected $__customRichMediaEventsDataType = 'array';
  protected $__dateRangeType = 'Google_DateRange';
  protected $__dateRangeDataType = '';
  protected $__floodlightConfigIdType = 'Google_DimensionValue';
  protected $__floodlightConfigIdDataType = '';
  protected $__perInteractionDimensionsType = 'Google_SortedDimension';
  protected $__perInteractionDimensionsDataType = 'array';
  protected $__reportPropertiesType = 'Google_ReportPathToConversionCriteriaReportProperties';
  protected $__reportPropertiesDataType = '';

  public function getActivityFilters() {
    return $this->activityFilters;
  }

  public function setActivityFilters(/* array(Google_DimensionValue) */ $activityFilters) {
    $this->assertIsArray($activityFilters, 'Google_DimensionValue', __METHOD__);
    $this->activityFilters = $activityFilters;
  }

  public function getConversionDimensions() {
    return $this->conversionDimensions;
  }

  public function setConversionDimensions(/* array(Google_SortedDimension) */ $conversionDimensions) {
    $this->assertIsArray($conversionDimensions, 'Google_SortedDimension', __METHOD__);
    $this->conversionDimensions = $conversionDimensions;
  }

  public function getCustomFloodlightVariables() {
    return $this->customFloodlightVariables;
  }

  public function setCustomFloodlightVariables(/* array(Google_SortedDimension) */ $customFloodlightVariables) {
    $this->assertIsArray($customFloodlightVariables, 'Google_SortedDimension', __METHOD__);
    $this->customFloodlightVariables = $customFloodlightVariables;
  }

  public function getCustomRichMediaEvents() {
    return $this->customRichMediaEvents;
  }

  public function setCustomRichMediaEvents(/* array(Google_DimensionValue) */ $customRichMediaEvents) {
    $this->assertIsArray($customRichMediaEvents, 'Google_DimensionValue', __METHOD__);
    $this->customRichMediaEvents = $customRichMediaEvents;
  }

  public function getDateRange() {
    return $this->dateRange;
  }

  public function setDateRange(Google_DateRange $dateRange) {
    $this->dateRange = $dateRange;
  }

  public function getFloodlightConfigId() {
    return $this->floodlightConfigId;
  }

  public function setFloodlightConfigId(Google_DimensionValue $floodlightConfigId) {
    $this->floodlightConfigId = $floodlightConfigId;
  }

  public function getMetricNames() {
    return $this->metricNames;
  }

  public function setMetricNames(/* array(Google_string) */ $metricNames) {
    $this->assertIsArray($metricNames, 'Google_string', __METHOD__);
    $this->metricNames = $metricNames;
  }

  public function getPerInteractionDimensions() {
    return $this->perInteractionDimensions;
  }

  public function setPerInteractionDimensions(/* array(Google_SortedDimension) */ $perInteractionDimensions) {
    $this->assertIsArray($perInteractionDimensions, 'Google_SortedDimension', __METHOD__);
    $this->perInteractionDimensions = $perInteractionDimensions;
  }

  public function getReportProperties() {
    return $this->reportProperties;
  }

  public function setReportProperties(Google_ReportPathToConversionCriteriaReportProperties $reportProperties) {
    $this->reportProperties = $reportProperties;
  }
}

class Google_ReportPathToConversionCriteriaReportProperties extends Google_Model {
  public $clicksLookbackWindow;
  public $impressionsLookbackWindow;
  public $includeAttributedIPConversions;
  public $includeUnattributedCookieConversions;
  public $includeUnattributedIPConversions;
  public $maximumClickInteractions;
  public $maximumImpressionInteractions;
  public $maximumInteractionGap;
  public $pivotOnInteractionPath;

  public function getClicksLookbackWindow() {
    return $this->clicksLookbackWindow;
  }

  public function setClicksLookbackWindow( $clicksLookbackWindow) {
    $this->clicksLookbackWindow = $clicksLookbackWindow;
  }

  public function getImpressionsLookbackWindow() {
    return $this->impressionsLookbackWindow;
  }

  public function setImpressionsLookbackWindow( $impressionsLookbackWindow) {
    $this->impressionsLookbackWindow = $impressionsLookbackWindow;
  }

  public function getIncludeAttributedIPConversions() {
    return $this->includeAttributedIPConversions;
  }

  public function setIncludeAttributedIPConversions( $includeAttributedIPConversions) {
    $this->includeAttributedIPConversions = $includeAttributedIPConversions;
  }

  public function getIncludeUnattributedCookieConversions() {
    return $this->includeUnattributedCookieConversions;
  }

  public function setIncludeUnattributedCookieConversions( $includeUnattributedCookieConversions) {
    $this->includeUnattributedCookieConversions = $includeUnattributedCookieConversions;
  }

  public function getIncludeUnattributedIPConversions() {
    return $this->includeUnattributedIPConversions;
  }

  public function setIncludeUnattributedIPConversions( $includeUnattributedIPConversions) {
    $this->includeUnattributedIPConversions = $includeUnattributedIPConversions;
  }

  public function getMaximumClickInteractions() {
    return $this->maximumClickInteractions;
  }

  public function setMaximumClickInteractions( $maximumClickInteractions) {
    $this->maximumClickInteractions = $maximumClickInteractions;
  }

  public function getMaximumImpressionInteractions() {
    return $this->maximumImpressionInteractions;
  }

  public function setMaximumImpressionInteractions( $maximumImpressionInteractions) {
    $this->maximumImpressionInteractions = $maximumImpressionInteractions;
  }

  public function getMaximumInteractionGap() {
    return $this->maximumInteractionGap;
  }

  public function setMaximumInteractionGap( $maximumInteractionGap) {
    $this->maximumInteractionGap = $maximumInteractionGap;
  }

  public function getPivotOnInteractionPath() {
    return $this->pivotOnInteractionPath;
  }

  public function setPivotOnInteractionPath( $pivotOnInteractionPath) {
    $this->pivotOnInteractionPath = $pivotOnInteractionPath;
  }
}

class Google_ReportReachCriteria extends Google_Model {
  public $activities;
  public $customRichMediaEvents;
  public $dateRange;
  public $dimensionFilters;
  public $dimensions;
  public $metricNames;
  public $reachByFrequencyMetricNames;
  protected $__activitiesType = 'Google_Activities';
  protected $__activitiesDataType = '';
  protected $__customRichMediaEventsType = 'Google_CustomRichMediaEvents';
  protected $__customRichMediaEventsDataType = '';
  protected $__dateRangeType = 'Google_DateRange';
  protected $__dateRangeDataType = '';
  protected $__dimensionFiltersType = 'Google_DimensionValue';
  protected $__dimensionFiltersDataType = 'array';
  protected $__dimensionsType = 'Google_SortedDimension';
  protected $__dimensionsDataType = 'array';

  public function getActivities() {
    return $this->activities;
  }

  public function setActivities(Google_Activities $activities) {
    $this->activities = $activities;
  }

  public function getCustomRichMediaEvents() {
    return $this->customRichMediaEvents;
  }

  public function setCustomRichMediaEvents(Google_CustomRichMediaEvents $customRichMediaEvents) {
    $this->customRichMediaEvents = $customRichMediaEvents;
  }

  public function getDateRange() {
    return $this->dateRange;
  }

  public function setDateRange(Google_DateRange $dateRange) {
    $this->dateRange = $dateRange;
  }

  public function getDimensionFilters() {
    return $this->dimensionFilters;
  }

  public function setDimensionFilters(/* array(Google_DimensionValue) */ $dimensionFilters) {
    $this->assertIsArray($dimensionFilters, 'Google_DimensionValue', __METHOD__);
    $this->dimensionFilters = $dimensionFilters;
  }

  public function getDimensions() {
    return $this->dimensions;
  }

  public function setDimensions(/* array(Google_SortedDimension) */ $dimensions) {
    $this->assertIsArray($dimensions, 'Google_SortedDimension', __METHOD__);
    $this->dimensions = $dimensions;
  }

  public function getMetricNames() {
    return $this->metricNames;
  }

  public function setMetricNames(/* array(Google_string) */ $metricNames) {
    $this->assertIsArray($metricNames, 'Google_string', __METHOD__);
    $this->metricNames = $metricNames;
  }

  public function getReachByFrequencyMetricNames() {
    return $this->reachByFrequencyMetricNames;
  }

  public function setReachByFrequencyMetricNames(/* array(Google_string) */ $reachByFrequencyMetricNames) {
    $this->assertIsArray($reachByFrequencyMetricNames, 'Google_string', __METHOD__);
    $this->reachByFrequencyMetricNames = $reachByFrequencyMetricNames;
  }
}

class Google_ReportSchedule extends Google_Model {
  public $active;
  public $every;
  public $expirationDate;
  public $repeats;
  public $repeatsOnWeekDays;
  public $runsOnDayOfMonth;
  public $startDate;

  public function getActive() {
    return $this->active;
  }

  public function setActive( $active) {
    $this->active = $active;
  }

  public function getEvery() {
    return $this->every;
  }

  public function setEvery( $every) {
    $this->every = $every;
  }

  public function getExpirationDate() {
    return $this->expirationDate;
  }

  public function setExpirationDate( $expirationDate) {
    $this->expirationDate = $expirationDate;
  }

  public function getRepeats() {
    return $this->repeats;
  }

  public function setRepeats( $repeats) {
    $this->repeats = $repeats;
  }

  public function getRepeatsOnWeekDays() {
    return $this->repeatsOnWeekDays;
  }

  public function setRepeatsOnWeekDays(/* array(Google_string) */ $repeatsOnWeekDays) {
    $this->assertIsArray($repeatsOnWeekDays, 'Google_string', __METHOD__);
    $this->repeatsOnWeekDays = $repeatsOnWeekDays;
  }

  public function getRunsOnDayOfMonth() {
    return $this->runsOnDayOfMonth;
  }

  public function setRunsOnDayOfMonth( $runsOnDayOfMonth) {
    $this->runsOnDayOfMonth = $runsOnDayOfMonth;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function setStartDate( $startDate) {
    $this->startDate = $startDate;
  }
}

class Google_SortedDimension extends Google_Model {
  public $kind;
  public $name;
  public $sortOrder;

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getName() {
    return $this->name;
  }

  public function setName( $name) {
    $this->name = $name;
  }

  public function getSortOrder() {
    return $this->sortOrder;
  }

  public function setSortOrder( $sortOrder) {
    $this->sortOrder = $sortOrder;
  }
}

class Google_UserProfile extends Google_Model {
  public $accountId;
  public $accountName;
  public $etag;
  public $kind;
  public $profileId;
  public $subAccountId;
  public $subAccountName;
  public $userName;

  public function getAccountId() {
    return $this->accountId;
  }

  public function setAccountId( $accountId) {
    $this->accountId = $accountId;
  }

  public function getAccountName() {
    return $this->accountName;
  }

  public function setAccountName( $accountName) {
    $this->accountName = $accountName;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getProfileId() {
    return $this->profileId;
  }

  public function setProfileId( $profileId) {
    $this->profileId = $profileId;
  }

  public function getSubAccountId() {
    return $this->subAccountId;
  }

  public function setSubAccountId( $subAccountId) {
    $this->subAccountId = $subAccountId;
  }

  public function getSubAccountName() {
    return $this->subAccountName;
  }

  public function setSubAccountName( $subAccountName) {
    $this->subAccountName = $subAccountName;
  }

  public function getUserName() {
    return $this->userName;
  }

  public function setUserName( $userName) {
    $this->userName = $userName;
  }
}

class Google_UserProfileList extends Google_Model {
  public $etag;
  public $items;
  public $kind;
  protected $__itemsType = 'Google_UserProfile';
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

  public function setItems(/* array(Google_UserProfile) */ $items) {
    $this->assertIsArray($items, 'Google_UserProfile', __METHOD__);
    $this->items = $items;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }
}
