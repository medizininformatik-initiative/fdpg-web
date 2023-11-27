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
   * The "datasets" collection of methods.
   * Typical usage is:
   *  <code>
   *   $bigqueryService = new Google_BigqueryService(...);
   *   $datasets = $bigqueryService->datasets;
   *  </code>
   */
  class Google_DatasetsServiceResource extends Google_ServiceResource {

    /**
     * Deletes the dataset specified by datasetId value. Before you can delete a dataset, you must
     * delete all its tables, either manually or by specifying deleteContents. Immediately after
     * deletion, you can create another dataset with the same name. (datasets.delete)
     *
     * @param string $projectId Project ID of the dataset being deleted
     * @param string $datasetId Dataset ID of dataset being deleted
     * @param array $optParams Optional parameters.
     *
     * @opt_param bool deleteContents If True, delete all the tables in the dataset. If False and the dataset contains tables, the request will fail. Default is False
     */
    public function delete($projectId, $datasetId, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('delete', array($params));
      return $data;
    }
    /**
     * Returns the dataset specified by datasetID. (datasets.get)
     *
     * @param string $projectId Project ID of the requested dataset
     * @param string $datasetId Dataset ID of the requested dataset
     * @param array $optParams Optional parameters.
     * @return Google_Dataset
     */
    public function get($projectId, $datasetId, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Dataset($data);
      } else {
        return $data;
      }
    }
    /**
     * Creates a new empty dataset. (datasets.insert)
     *
     * @param string $projectId Project ID of the new dataset
     * @param Google_Dataset $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Dataset
     */
    public function insert($projectId, Google_Dataset $postBody, $optParams = array()) {
      $params = array('projectId' => $projectId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Dataset($data);
      } else {
        return $data;
      }
    }
    /**
     * Lists all the datasets in the specified project to which the caller has read access; however, a
     * project owner can list (but not necessarily get) all datasets in his project. (datasets.list)
     *
     * @param string $projectId Project ID of the datasets to be listed
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults The maximum number of results to return
     * @opt_param string pageToken Page token, returned by a previous call, to request the next page of results
     * @return Google_DatasetList
     */
    public function listDatasets($projectId, $optParams = array()) {
      $params = array('projectId' => $projectId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_DatasetList($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates information in an existing dataset, specified by datasetId. Properties not included in
     * the submitted resource will not be changed. If you include the access property without any values
     * assigned, the request will fail as you must specify at least one owner for a dataset. This method
     * supports patch semantics. (datasets.patch)
     *
     * @param string $projectId Project ID of the dataset being updated
     * @param string $datasetId Dataset ID of the dataset being updated
     * @param Google_Dataset $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Dataset
     */
    public function patch($projectId, $datasetId, Google_Dataset $postBody, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('patch', array($params));
      if ($this->useObjects()) {
        return new Google_Dataset($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates information in an existing dataset, specified by datasetId. Properties not included in
     * the submitted resource will not be changed. If you include the access property without any values
     * assigned, the request will fail as you must specify at least one owner for a dataset.
     * (datasets.update)
     *
     * @param string $projectId Project ID of the dataset being updated
     * @param string $datasetId Dataset ID of the dataset being updated
     * @param Google_Dataset $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Dataset
     */
    public function update($projectId, $datasetId, Google_Dataset $postBody, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('update', array($params));
      if ($this->useObjects()) {
        return new Google_Dataset($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "jobs" collection of methods.
   * Typical usage is:
   *  <code>
   *   $bigqueryService = new Google_BigqueryService(...);
   *   $jobs = $bigqueryService->jobs;
   *  </code>
   */
  class Google_JobsServiceResource extends Google_ServiceResource {

    /**
     * Retrieves the specified job by ID. (jobs.get)
     *
     * @param string $projectId Project ID of the requested job
     * @param string $jobId Job ID of the requested job
     * @param array $optParams Optional parameters.
     * @return Google_Job
     */
    public function get($projectId, $jobId, $optParams = array()) {
      $params = array('projectId' => $projectId, 'jobId' => $jobId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Job($data);
      } else {
        return $data;
      }
    }
    /**
     * Retrieves the results of a query job. (jobs.getQueryResults)
     *
     * @param string $projectId Project ID of the query job
     * @param string $jobId Job ID of the query job
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults Maximum number of results to read
     * @opt_param string pageToken Page token, returned by a previous call, to request the next page of results
     * @opt_param string startIndex Zero-based index of the starting row
     * @opt_param string timeoutMs How long to wait for the query to complete, in milliseconds, before returning. Default is to return immediately. If the timeout passes before the job completes, the request will fail with a TIMEOUT error
     * @return Google_GetQueryResultsResponse
     */
    public function getQueryResults($projectId, $jobId, $optParams = array()) {
      $params = array('projectId' => $projectId, 'jobId' => $jobId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('getQueryResults', array($params));
      if ($this->useObjects()) {
        return new Google_GetQueryResultsResponse($data);
      } else {
        return $data;
      }
    }
    /**
     * Starts a new asynchronous job. (jobs.insert)
     *
     * @param string $projectId Project ID of the project that will be billed for the job
     * @param Google_Job $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Job
     */
    public function insert($projectId, Google_Job $postBody, $optParams = array()) {
      $params = array('projectId' => $projectId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Job($data);
      } else {
        return $data;
      }
    }
    /**
     * Lists all the Jobs in the specified project that were started by the user. (jobs.list)
     *
     * @param string $projectId Project ID of the jobs to list
     * @param array $optParams Optional parameters.
     *
     * @opt_param bool allUsers Whether to display jobs owned by all users in the project. Default false
     * @opt_param string maxResults Maximum number of results to return
     * @opt_param string pageToken Page token, returned by a previous call, to request the next page of results
     * @opt_param string projection Restrict information returned to a set of selected fields
     * @opt_param string stateFilter Filter for job state
     * @return Google_JobList
     */
    public function listJobs($projectId, $optParams = array()) {
      $params = array('projectId' => $projectId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_JobList($data);
      } else {
        return $data;
      }
    }
    /**
     * Runs a BigQuery SQL query synchronously and returns query results if the query completes within a
     * specified timeout. (jobs.query)
     *
     * @param string $projectId Project ID of the project billed for the query
     * @param Google_QueryRequest $postBody
     * @param array $optParams Optional parameters.
     * @return Google_QueryResponse
     */
    public function query($projectId, Google_QueryRequest $postBody, $optParams = array()) {
      $params = array('projectId' => $projectId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('query', array($params));
      if ($this->useObjects()) {
        return new Google_QueryResponse($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "projects" collection of methods.
   * Typical usage is:
   *  <code>
   *   $bigqueryService = new Google_BigqueryService(...);
   *   $projects = $bigqueryService->projects;
   *  </code>
   */
  class Google_ProjectsServiceResource extends Google_ServiceResource {

    /**
     * Lists the projects to which you have at least read access. (projects.list)
     *
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults Maximum number of results to return
     * @opt_param string pageToken Page token, returned by a previous call, to request the next page of results
     * @return Google_ProjectList
     */
    public function listProjects($optParams = array()) {
      $params = array();
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_ProjectList($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "tabledata" collection of methods.
   * Typical usage is:
   *  <code>
   *   $bigqueryService = new Google_BigqueryService(...);
   *   $tabledata = $bigqueryService->tabledata;
   *  </code>
   */
  class Google_TabledataServiceResource extends Google_ServiceResource {

    /**
     * Retrieves table data from a specified set of rows. (tabledata.list)
     *
     * @param string $projectId Project ID of the table to read
     * @param string $datasetId Dataset ID of the table to read
     * @param string $tableId Table ID of the table to read
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults Maximum number of results to return
     * @opt_param string pageToken Page token, returned by a previous call, identifying the result set
     * @opt_param string startIndex Zero-based index of the starting row to read
     * @return Google_TableDataList
     */
    public function listTabledata($projectId, $datasetId, $tableId, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId, 'tableId' => $tableId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_TableDataList($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "tables" collection of methods.
   * Typical usage is:
   *  <code>
   *   $bigqueryService = new Google_BigqueryService(...);
   *   $tables = $bigqueryService->tables;
   *  </code>
   */
  class Google_TablesServiceResource extends Google_ServiceResource {

    /**
     * Deletes the table specified by tableId from the dataset. If the table contains data, all the data
     * will be deleted. (tables.delete)
     *
     * @param string $projectId Project ID of the table to delete
     * @param string $datasetId Dataset ID of the table to delete
     * @param string $tableId Table ID of the table to delete
     * @param array $optParams Optional parameters.
     */
    public function delete($projectId, $datasetId, $tableId, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId, 'tableId' => $tableId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('delete', array($params));
      return $data;
    }
    /**
     * Gets the specified table resource by table ID. This method does not return the data in the table,
     * it only returns the table resource, which describes the structure of this table. (tables.get)
     *
     * @param string $projectId Project ID of the requested table
     * @param string $datasetId Dataset ID of the requested table
     * @param string $tableId Table ID of the requested table
     * @param array $optParams Optional parameters.
     * @return Google_Table
     */
    public function get($projectId, $datasetId, $tableId, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId, 'tableId' => $tableId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Table($data);
      } else {
        return $data;
      }
    }
    /**
     * Creates a new, empty table in the dataset. (tables.insert)
     *
     * @param string $projectId Project ID of the new table
     * @param string $datasetId Dataset ID of the new table
     * @param Google_Table $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Table
     */
    public function insert($projectId, $datasetId, Google_Table $postBody, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Table($data);
      } else {
        return $data;
      }
    }
    /**
     * Lists all tables in the specified dataset. (tables.list)
     *
     * @param string $projectId Project ID of the tables to list
     * @param string $datasetId Dataset ID of the tables to list
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults Maximum number of results to return
     * @opt_param string pageToken Page token, returned by a previous call, to request the next page of results
     * @return Google_TableList
     */
    public function listTables($projectId, $datasetId, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_TableList($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates information in an existing table, specified by tableId. This method supports patch
     * semantics. (tables.patch)
     *
     * @param string $projectId Project ID of the table to update
     * @param string $datasetId Dataset ID of the table to update
     * @param string $tableId Table ID of the table to update
     * @param Google_Table $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Table
     */
    public function patch($projectId, $datasetId, $tableId, Google_Table $postBody, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId, 'tableId' => $tableId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('patch', array($params));
      if ($this->useObjects()) {
        return new Google_Table($data);
      } else {
        return $data;
      }
    }
    /**
     * Updates information in an existing table, specified by tableId. (tables.update)
     *
     * @param string $projectId Project ID of the table to update
     * @param string $datasetId Dataset ID of the table to update
     * @param string $tableId Table ID of the table to update
     * @param Google_Table $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Table
     */
    public function update($projectId, $datasetId, $tableId, Google_Table $postBody, $optParams = array()) {
      $params = array('projectId' => $projectId, 'datasetId' => $datasetId, 'tableId' => $tableId, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('update', array($params));
      if ($this->useObjects()) {
        return new Google_Table($data);
      } else {
        return $data;
      }
    }
  }

/**
 * Service definition for Google_Bigquery (v2).
 *
 * <p>
 * A data platform for customers to create, manage, share and query data.
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="https://developers.google.com/bigquery/docs/overview" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_BigqueryService extends Google_Service {
  public $datasets;
  public $jobs;
  public $projects;
  public $tabledata;
  public $tables;
  /**
   * Constructs the internal representation of the Bigquery service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client) {
    $this->servicePath = 'bigquery/v2/';
    $this->version = 'v2';
    $this->serviceName = 'bigquery';

    $client->addService($this->serviceName, $this->version);
    $this->datasets = new Google_DatasetsServiceResource($this, $this->serviceName, 'datasets', json_decode('{"methods": {"delete": {"id": "bigquery.datasets.delete", "path": "projects/{projectId}/datasets/{datasetId}", "httpMethod": "DELETE", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "deleteContents": {"type": "boolean", "location": "query"}, "projectId": {"type": "string", "required": true, "location": "path"}}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "get": {"id": "bigquery.datasets.get", "path": "projects/{projectId}/datasets/{datasetId}", "httpMethod": "GET", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Dataset"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "insert": {"id": "bigquery.datasets.insert", "path": "projects/{projectId}/datasets", "httpMethod": "POST", "parameters": {"projectId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Dataset"}, "response": {"$ref": "Dataset"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "list": {"id": "bigquery.datasets.list", "path": "projects/{projectId}/datasets", "httpMethod": "GET", "parameters": {"maxResults": {"type": "integer", "format": "uint32", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "projectId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "DatasetList"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "patch": {"id": "bigquery.datasets.patch", "path": "projects/{projectId}/datasets/{datasetId}", "httpMethod": "PATCH", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Dataset"}, "response": {"$ref": "Dataset"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "update": {"id": "bigquery.datasets.update", "path": "projects/{projectId}/datasets/{datasetId}", "httpMethod": "PUT", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Dataset"}, "response": {"$ref": "Dataset"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}}}', true));
    $this->jobs = new Google_JobsServiceResource($this, $this->serviceName, 'jobs', json_decode('{"methods": {"get": {"id": "bigquery.jobs.get", "path": "projects/{projectId}/jobs/{jobId}", "httpMethod": "GET", "parameters": {"jobId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Job"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "getQueryResults": {"id": "bigquery.jobs.getQueryResults", "path": "projects/{projectId}/queries/{jobId}", "httpMethod": "GET", "parameters": {"jobId": {"type": "string", "required": true, "location": "path"}, "maxResults": {"type": "integer", "format": "uint32", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "projectId": {"type": "string", "required": true, "location": "path"}, "startIndex": {"type": "string", "format": "uint64", "location": "query"}, "timeoutMs": {"type": "integer", "format": "uint32", "location": "query"}}, "response": {"$ref": "GetQueryResultsResponse"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "insert": {"id": "bigquery.jobs.insert", "path": "projects/{projectId}/jobs", "httpMethod": "POST", "parameters": {"projectId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Job"}, "response": {"$ref": "Job"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform", "https://www.googleapis.com/auth/devstorage.full_control", "https://www.googleapis.com/auth/devstorage.read_only", "https://www.googleapis.com/auth/devstorage.read_write"], "supportsMediaUpload": true, "mediaUpload": {"accept": ["application/octet-stream"], "protocols": {"simple": {"multipart": true, "path": "/upload/bigquery/v2/projects/{projectId}/jobs"}, "resumable": {"multipart": true, "path": "/resumable/upload/bigquery/v2/projects/{projectId}/jobs"}}}}, "list": {"id": "bigquery.jobs.list", "path": "projects/{projectId}/jobs", "httpMethod": "GET", "parameters": {"allUsers": {"type": "boolean", "location": "query"}, "maxResults": {"type": "integer", "format": "uint32", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "projectId": {"type": "string", "required": true, "location": "path"}, "projection": {"type": "string", "enum": ["full", "minimal"], "location": "query"}, "stateFilter": {"type": "string", "enum": ["done", "pending", "running"], "repeated": true, "location": "query"}}, "response": {"$ref": "JobList"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "query": {"id": "bigquery.jobs.query", "path": "projects/{projectId}/queries", "httpMethod": "POST", "parameters": {"projectId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "QueryRequest"}, "response": {"$ref": "QueryResponse"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}}}', true));
    $this->projects = new Google_ProjectsServiceResource($this, $this->serviceName, 'projects', json_decode('{"methods": {"list": {"id": "bigquery.projects.list", "path": "projects", "httpMethod": "GET", "parameters": {"maxResults": {"type": "integer", "format": "uint32", "location": "query"}, "pageToken": {"type": "string", "location": "query"}}, "response": {"$ref": "ProjectList"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}}}', true));
    $this->tabledata = new Google_TabledataServiceResource($this, $this->serviceName, 'tabledata', json_decode('{"methods": {"list": {"id": "bigquery.tabledata.list", "path": "projects/{projectId}/datasets/{datasetId}/tables/{tableId}/data", "httpMethod": "GET", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "maxResults": {"type": "integer", "format": "uint32", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "projectId": {"type": "string", "required": true, "location": "path"}, "startIndex": {"type": "string", "format": "uint64", "location": "query"}, "tableId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "TableDataList"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}}}', true));
    $this->tables = new Google_TablesServiceResource($this, $this->serviceName, 'tables', json_decode('{"methods": {"delete": {"id": "bigquery.tables.delete", "path": "projects/{projectId}/datasets/{datasetId}/tables/{tableId}", "httpMethod": "DELETE", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}, "tableId": {"type": "string", "required": true, "location": "path"}}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "get": {"id": "bigquery.tables.get", "path": "projects/{projectId}/datasets/{datasetId}/tables/{tableId}", "httpMethod": "GET", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}, "tableId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Table"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "insert": {"id": "bigquery.tables.insert", "path": "projects/{projectId}/datasets/{datasetId}/tables", "httpMethod": "POST", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Table"}, "response": {"$ref": "Table"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "list": {"id": "bigquery.tables.list", "path": "projects/{projectId}/datasets/{datasetId}/tables", "httpMethod": "GET", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "maxResults": {"type": "integer", "format": "uint32", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "projectId": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "TableList"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "patch": {"id": "bigquery.tables.patch", "path": "projects/{projectId}/datasets/{datasetId}/tables/{tableId}", "httpMethod": "PATCH", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}, "tableId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Table"}, "response": {"$ref": "Table"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}, "update": {"id": "bigquery.tables.update", "path": "projects/{projectId}/datasets/{datasetId}/tables/{tableId}", "httpMethod": "PUT", "parameters": {"datasetId": {"type": "string", "required": true, "location": "path"}, "projectId": {"type": "string", "required": true, "location": "path"}, "tableId": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Table"}, "response": {"$ref": "Table"}, "scopes": ["https://www.googleapis.com/auth/bigquery", "https://www.googleapis.com/auth/cloud-platform"]}}}', true));

  }
}



class Google_Dataset extends Google_Model {
  public $access;
  public $creationTime;
  public $datasetReference;
  public $description;
  public $etag;
  public $friendlyName;
  public $id;
  public $kind;
  public $lastModifiedTime;
  public $selfLink;
  protected $__accessType = 'Google_DatasetAccess';
  protected $__accessDataType = 'array';
  protected $__datasetReferenceType = 'Google_DatasetReference';
  protected $__datasetReferenceDataType = '';

  public function getAccess() {
    return $this->access;
  }

  public function setAccess(/* array(Google_DatasetAccess) */ $access) {
    $this->assertIsArray($access, 'Google_DatasetAccess', __METHOD__);
    $this->access = $access;
  }

  public function getCreationTime() {
    return $this->creationTime;
  }

  public function setCreationTime( $creationTime) {
    $this->creationTime = $creationTime;
  }

  public function getDatasetReference() {
    return $this->datasetReference;
  }

  public function setDatasetReference(Google_DatasetReference $datasetReference) {
    $this->datasetReference = $datasetReference;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription( $description) {
    $this->description = $description;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getFriendlyName() {
    return $this->friendlyName;
  }

  public function setFriendlyName( $friendlyName) {
    $this->friendlyName = $friendlyName;
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

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }
}

class Google_DatasetAccess extends Google_Model {
  public $domain;
  public $groupByEmail;
  public $role;
  public $specialGroup;
  public $userByEmail;

  public function getDomain() {
    return $this->domain;
  }

  public function setDomain( $domain) {
    $this->domain = $domain;
  }

  public function getGroupByEmail() {
    return $this->groupByEmail;
  }

  public function setGroupByEmail( $groupByEmail) {
    $this->groupByEmail = $groupByEmail;
  }

  public function getRole() {
    return $this->role;
  }

  public function setRole( $role) {
    $this->role = $role;
  }

  public function getSpecialGroup() {
    return $this->specialGroup;
  }

  public function setSpecialGroup( $specialGroup) {
    $this->specialGroup = $specialGroup;
  }

  public function getUserByEmail() {
    return $this->userByEmail;
  }

  public function setUserByEmail( $userByEmail) {
    $this->userByEmail = $userByEmail;
  }
}

class Google_DatasetList extends Google_Model {
  public $datasets;
  public $etag;
  public $kind;
  public $nextPageToken;
  protected $__datasetsType = 'Google_DatasetListDatasets';
  protected $__datasetsDataType = 'array';

  public function getDatasets() {
    return $this->datasets;
  }

  public function setDatasets(/* array(Google_DatasetListDatasets) */ $datasets) {
    $this->assertIsArray($datasets, 'Google_DatasetListDatasets', __METHOD__);
    $this->datasets = $datasets;
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

  public function getNextPageToken() {
    return $this->nextPageToken;
  }

  public function setNextPageToken( $nextPageToken) {
    $this->nextPageToken = $nextPageToken;
  }
}

class Google_DatasetListDatasets extends Google_Model {
  public $datasetReference;
  public $friendlyName;
  public $id;
  public $kind;
  protected $__datasetReferenceType = 'Google_DatasetReference';
  protected $__datasetReferenceDataType = '';

  public function getDatasetReference() {
    return $this->datasetReference;
  }

  public function setDatasetReference(Google_DatasetReference $datasetReference) {
    $this->datasetReference = $datasetReference;
  }

  public function getFriendlyName() {
    return $this->friendlyName;
  }

  public function setFriendlyName( $friendlyName) {
    $this->friendlyName = $friendlyName;
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
}

class Google_DatasetReference extends Google_Model {
  public $datasetId;
  public $projectId;

  public function getDatasetId() {
    return $this->datasetId;
  }

  public function setDatasetId( $datasetId) {
    $this->datasetId = $datasetId;
  }

  public function getProjectId() {
    return $this->projectId;
  }

  public function setProjectId( $projectId) {
    $this->projectId = $projectId;
  }
}

class Google_ErrorProto extends Google_Model {
  public $debugInfo;
  public $location;
  public $message;
  public $reason;

  public function getDebugInfo() {
    return $this->debugInfo;
  }

  public function setDebugInfo( $debugInfo) {
    $this->debugInfo = $debugInfo;
  }

  public function getLocation() {
    return $this->location;
  }

  public function setLocation( $location) {
    $this->location = $location;
  }

  public function getMessage() {
    return $this->message;
  }

  public function setMessage( $message) {
    $this->message = $message;
  }

  public function getReason() {
    return $this->reason;
  }

  public function setReason( $reason) {
    $this->reason = $reason;
  }
}

class Google_GetQueryResultsResponse extends Google_Model {
  public $cacheHit;
  public $etag;
  public $jobComplete;
  public $jobReference;
  public $kind;
  public $pageToken;
  public $rows;
  public $schema;
  public $totalRows;
  protected $__jobReferenceType = 'Google_JobReference';
  protected $__jobReferenceDataType = '';
  protected $__rowsType = 'Google_TableRow';
  protected $__rowsDataType = 'array';
  protected $__schemaType = 'Google_TableSchema';
  protected $__schemaDataType = '';

  public function getCacheHit() {
    return $this->cacheHit;
  }

  public function setCacheHit( $cacheHit) {
    $this->cacheHit = $cacheHit;
  }

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getJobComplete() {
    return $this->jobComplete;
  }

  public function setJobComplete( $jobComplete) {
    $this->jobComplete = $jobComplete;
  }

  public function getJobReference() {
    return $this->jobReference;
  }

  public function setJobReference(Google_JobReference $jobReference) {
    $this->jobReference = $jobReference;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getPageToken() {
    return $this->pageToken;
  }

  public function setPageToken( $pageToken) {
    $this->pageToken = $pageToken;
  }

  public function getRows() {
    return $this->rows;
  }

  public function setRows(/* array(Google_TableRow) */ $rows) {
    $this->assertIsArray($rows, 'Google_TableRow', __METHOD__);
    $this->rows = $rows;
  }

  public function getSchema() {
    return $this->schema;
  }

  public function setSchema(Google_TableSchema $schema) {
    $this->schema = $schema;
  }

  public function getTotalRows() {
    return $this->totalRows;
  }

  public function setTotalRows( $totalRows) {
    $this->totalRows = $totalRows;
  }
}

class Google_Job extends Google_Model {
  public $configuration;
  public $etag;
  public $id;
  public $jobReference;
  public $kind;
  public $selfLink;
  public $statistics;
  public $status;
  protected $__configurationType = 'Google_JobConfiguration';
  protected $__configurationDataType = '';
  protected $__jobReferenceType = 'Google_JobReference';
  protected $__jobReferenceDataType = '';
  protected $__statisticsType = 'Google_JobStatistics';
  protected $__statisticsDataType = '';
  protected $__statusType = 'Google_JobStatus';
  protected $__statusDataType = '';

  public function getConfiguration() {
    return $this->configuration;
  }

  public function setConfiguration(Google_JobConfiguration $configuration) {
    $this->configuration = $configuration;
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

  public function getJobReference() {
    return $this->jobReference;
  }

  public function setJobReference(Google_JobReference $jobReference) {
    $this->jobReference = $jobReference;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getStatistics() {
    return $this->statistics;
  }

  public function setStatistics(Google_JobStatistics $statistics) {
    $this->statistics = $statistics;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus(Google_JobStatus $status) {
    $this->status = $status;
  }
}

class Google_JobConfiguration extends Google_Model {
  public $copy;
  public $dryRun;
  public $extract;
  public $link;
  public $load;
  public $query;
  protected $__copyType = 'Google_JobConfigurationTableCopy';
  protected $__copyDataType = '';
  protected $__extractType = 'Google_JobConfigurationExtract';
  protected $__extractDataType = '';
  protected $__linkType = 'Google_JobConfigurationLink';
  protected $__linkDataType = '';
  protected $__loadType = 'Google_JobConfigurationLoad';
  protected $__loadDataType = '';
  protected $__queryType = 'Google_JobConfigurationQuery';
  protected $__queryDataType = '';

  public function getCopy() {
    return $this->copy;
  }

  public function setCopy(Google_JobConfigurationTableCopy $copy) {
    $this->copy = $copy;
  }

  public function getDryRun() {
    return $this->dryRun;
  }

  public function setDryRun( $dryRun) {
    $this->dryRun = $dryRun;
  }

  public function getExtract() {
    return $this->extract;
  }

  public function setExtract(Google_JobConfigurationExtract $extract) {
    $this->extract = $extract;
  }

  public function getLink() {
    return $this->link;
  }

  public function setLink(Google_JobConfigurationLink $link) {
    $this->link = $link;
  }

  public function getLoad() {
    return $this->load;
  }

  public function setLoad(Google_JobConfigurationLoad $load) {
    $this->load = $load;
  }

  public function getQuery() {
    return $this->query;
  }

  public function setQuery(Google_JobConfigurationQuery $query) {
    $this->query = $query;
  }
}

class Google_JobConfigurationExtract extends Google_Model {
  public $destinationFormat;
  public $destinationUri;
  public $fieldDelimiter;
  public $printHeader;
  public $sourceTable;
  protected $__sourceTableType = 'Google_TableReference';
  protected $__sourceTableDataType = '';

  public function getDestinationFormat() {
    return $this->destinationFormat;
  }

  public function setDestinationFormat( $destinationFormat) {
    $this->destinationFormat = $destinationFormat;
  }

  public function getDestinationUri() {
    return $this->destinationUri;
  }

  public function setDestinationUri( $destinationUri) {
    $this->destinationUri = $destinationUri;
  }

  public function getFieldDelimiter() {
    return $this->fieldDelimiter;
  }

  public function setFieldDelimiter( $fieldDelimiter) {
    $this->fieldDelimiter = $fieldDelimiter;
  }

  public function getPrintHeader() {
    return $this->printHeader;
  }

  public function setPrintHeader( $printHeader) {
    $this->printHeader = $printHeader;
  }

  public function getSourceTable() {
    return $this->sourceTable;
  }

  public function setSourceTable(Google_TableReference $sourceTable) {
    $this->sourceTable = $sourceTable;
  }
}

class Google_JobConfigurationLink extends Google_Model {
  public $createDisposition;
  public $destinationTable;
  public $sourceUri;
  public $writeDisposition;
  protected $__destinationTableType = 'Google_TableReference';
  protected $__destinationTableDataType = '';

  public function getCreateDisposition() {
    return $this->createDisposition;
  }

  public function setCreateDisposition( $createDisposition) {
    $this->createDisposition = $createDisposition;
  }

  public function getDestinationTable() {
    return $this->destinationTable;
  }

  public function setDestinationTable(Google_TableReference $destinationTable) {
    $this->destinationTable = $destinationTable;
  }

  public function getSourceUri() {
    return $this->sourceUri;
  }

  public function setSourceUri(/* array(Google_string) */ $sourceUri) {
    $this->assertIsArray($sourceUri, 'Google_string', __METHOD__);
    $this->sourceUri = $sourceUri;
  }

  public function getWriteDisposition() {
    return $this->writeDisposition;
  }

  public function setWriteDisposition( $writeDisposition) {
    $this->writeDisposition = $writeDisposition;
  }
}

class Google_JobConfigurationLoad extends Google_Model {
  public $allowJaggedRows;
  public $allowQuotedNewlines;
  public $createDisposition;
  public $destinationTable;
  public $encoding;
  public $fieldDelimiter;
  public $maxBadRecords;
  public $quote;
  public $schema;
  public $schemaInline;
  public $schemaInlineFormat;
  public $skipLeadingRows;
  public $sourceFormat;
  public $sourceUris;
  public $writeDisposition;
  protected $__destinationTableType = 'Google_TableReference';
  protected $__destinationTableDataType = '';
  protected $__schemaType = 'Google_TableSchema';
  protected $__schemaDataType = '';

  public function getAllowJaggedRows() {
    return $this->allowJaggedRows;
  }

  public function setAllowJaggedRows( $allowJaggedRows) {
    $this->allowJaggedRows = $allowJaggedRows;
  }

  public function getAllowQuotedNewlines() {
    return $this->allowQuotedNewlines;
  }

  public function setAllowQuotedNewlines( $allowQuotedNewlines) {
    $this->allowQuotedNewlines = $allowQuotedNewlines;
  }

  public function getCreateDisposition() {
    return $this->createDisposition;
  }

  public function setCreateDisposition( $createDisposition) {
    $this->createDisposition = $createDisposition;
  }

  public function getDestinationTable() {
    return $this->destinationTable;
  }

  public function setDestinationTable(Google_TableReference $destinationTable) {
    $this->destinationTable = $destinationTable;
  }

  public function getEncoding() {
    return $this->encoding;
  }

  public function setEncoding( $encoding) {
    $this->encoding = $encoding;
  }

  public function getFieldDelimiter() {
    return $this->fieldDelimiter;
  }

  public function setFieldDelimiter( $fieldDelimiter) {
    $this->fieldDelimiter = $fieldDelimiter;
  }

  public function getMaxBadRecords() {
    return $this->maxBadRecords;
  }

  public function setMaxBadRecords( $maxBadRecords) {
    $this->maxBadRecords = $maxBadRecords;
  }

  public function getQuote() {
    return $this->quote;
  }

  public function setQuote( $quote) {
    $this->quote = $quote;
  }

  public function getSchema() {
    return $this->schema;
  }

  public function setSchema(Google_TableSchema $schema) {
    $this->schema = $schema;
  }

  public function getSchemaInline() {
    return $this->schemaInline;
  }

  public function setSchemaInline( $schemaInline) {
    $this->schemaInline = $schemaInline;
  }

  public function getSchemaInlineFormat() {
    return $this->schemaInlineFormat;
  }

  public function setSchemaInlineFormat( $schemaInlineFormat) {
    $this->schemaInlineFormat = $schemaInlineFormat;
  }

  public function getSkipLeadingRows() {
    return $this->skipLeadingRows;
  }

  public function setSkipLeadingRows( $skipLeadingRows) {
    $this->skipLeadingRows = $skipLeadingRows;
  }

  public function getSourceFormat() {
    return $this->sourceFormat;
  }

  public function setSourceFormat( $sourceFormat) {
    $this->sourceFormat = $sourceFormat;
  }

  public function getSourceUris() {
    return $this->sourceUris;
  }

  public function setSourceUris(/* array(Google_string) */ $sourceUris) {
    $this->assertIsArray($sourceUris, 'Google_string', __METHOD__);
    $this->sourceUris = $sourceUris;
  }

  public function getWriteDisposition() {
    return $this->writeDisposition;
  }

  public function setWriteDisposition( $writeDisposition) {
    $this->writeDisposition = $writeDisposition;
  }
}

class Google_JobConfigurationQuery extends Google_Model {
  public $allowLargeResults;
  public $createDisposition;
  public $defaultDataset;
  public $destinationTable;
  public $minCompletionRatio;
  public $preserveNulls;
  public $priority;
  public $query;
  public $useQueryCache;
  public $writeDisposition;
  protected $__defaultDatasetType = 'Google_DatasetReference';
  protected $__defaultDatasetDataType = '';
  protected $__destinationTableType = 'Google_TableReference';
  protected $__destinationTableDataType = '';

  public function getAllowLargeResults() {
    return $this->allowLargeResults;
  }

  public function setAllowLargeResults( $allowLargeResults) {
    $this->allowLargeResults = $allowLargeResults;
  }

  public function getCreateDisposition() {
    return $this->createDisposition;
  }

  public function setCreateDisposition( $createDisposition) {
    $this->createDisposition = $createDisposition;
  }

  public function getDefaultDataset() {
    return $this->defaultDataset;
  }

  public function setDefaultDataset(Google_DatasetReference $defaultDataset) {
    $this->defaultDataset = $defaultDataset;
  }

  public function getDestinationTable() {
    return $this->destinationTable;
  }

  public function setDestinationTable(Google_TableReference $destinationTable) {
    $this->destinationTable = $destinationTable;
  }

  public function getMinCompletionRatio() {
    return $this->minCompletionRatio;
  }

  public function setMinCompletionRatio( $minCompletionRatio) {
    $this->minCompletionRatio = $minCompletionRatio;
  }

  public function getPreserveNulls() {
    return $this->preserveNulls;
  }

  public function setPreserveNulls( $preserveNulls) {
    $this->preserveNulls = $preserveNulls;
  }

  public function getPriority() {
    return $this->priority;
  }

  public function setPriority( $priority) {
    $this->priority = $priority;
  }

  public function getQuery() {
    return $this->query;
  }

  public function setQuery( $query) {
    $this->query = $query;
  }

  public function getUseQueryCache() {
    return $this->useQueryCache;
  }

  public function setUseQueryCache( $useQueryCache) {
    $this->useQueryCache = $useQueryCache;
  }

  public function getWriteDisposition() {
    return $this->writeDisposition;
  }

  public function setWriteDisposition( $writeDisposition) {
    $this->writeDisposition = $writeDisposition;
  }
}

class Google_JobConfigurationTableCopy extends Google_Model {
  public $createDisposition;
  public $destinationTable;
  public $sourceTable;
  public $writeDisposition;
  protected $__destinationTableType = 'Google_TableReference';
  protected $__destinationTableDataType = '';
  protected $__sourceTableType = 'Google_TableReference';
  protected $__sourceTableDataType = '';

  public function getCreateDisposition() {
    return $this->createDisposition;
  }

  public function setCreateDisposition( $createDisposition) {
    $this->createDisposition = $createDisposition;
  }

  public function getDestinationTable() {
    return $this->destinationTable;
  }

  public function setDestinationTable(Google_TableReference $destinationTable) {
    $this->destinationTable = $destinationTable;
  }

  public function getSourceTable() {
    return $this->sourceTable;
  }

  public function setSourceTable(Google_TableReference $sourceTable) {
    $this->sourceTable = $sourceTable;
  }

  public function getWriteDisposition() {
    return $this->writeDisposition;
  }

  public function setWriteDisposition( $writeDisposition) {
    $this->writeDisposition = $writeDisposition;
  }
}

class Google_JobList extends Google_Model {
  public $etag;
  public $jobs;
  public $kind;
  public $nextPageToken;
  public $totalItems;
  protected $__jobsType = 'Google_JobListJobs';
  protected $__jobsDataType = 'array';

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getJobs() {
    return $this->jobs;
  }

  public function setJobs(/* array(Google_JobListJobs) */ $jobs) {
    $this->assertIsArray($jobs, 'Google_JobListJobs', __METHOD__);
    $this->jobs = $jobs;
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

class Google_JobListJobs extends Google_Model {
  public $configuration;
  public $errorResult;
  public $id;
  public $jobReference;
  public $kind;
  public $state;
  public $statistics;
  public $status;
  protected $__configurationType = 'Google_JobConfiguration';
  protected $__configurationDataType = '';
  protected $__errorResultType = 'Google_ErrorProto';
  protected $__errorResultDataType = '';
  protected $__jobReferenceType = 'Google_JobReference';
  protected $__jobReferenceDataType = '';
  protected $__statisticsType = 'Google_JobStatistics';
  protected $__statisticsDataType = '';
  protected $__statusType = 'Google_JobStatus';
  protected $__statusDataType = '';

  public function getConfiguration() {
    return $this->configuration;
  }

  public function setConfiguration(Google_JobConfiguration $configuration) {
    $this->configuration = $configuration;
  }

  public function getErrorResult() {
    return $this->errorResult;
  }

  public function setErrorResult(Google_ErrorProto $errorResult) {
    $this->errorResult = $errorResult;
  }

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getJobReference() {
    return $this->jobReference;
  }

  public function setJobReference(Google_JobReference $jobReference) {
    $this->jobReference = $jobReference;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getState() {
    return $this->state;
  }

  public function setState( $state) {
    $this->state = $state;
  }

  public function getStatistics() {
    return $this->statistics;
  }

  public function setStatistics(Google_JobStatistics $statistics) {
    $this->statistics = $statistics;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus(Google_JobStatus $status) {
    $this->status = $status;
  }
}

class Google_JobReference extends Google_Model {
  public $jobId;
  public $projectId;

  public function getJobId() {
    return $this->jobId;
  }

  public function setJobId( $jobId) {
    $this->jobId = $jobId;
  }

  public function getProjectId() {
    return $this->projectId;
  }

  public function setProjectId( $projectId) {
    $this->projectId = $projectId;
  }
}

class Google_JobStatistics extends Google_Model {
  public $endTime;
  public $load;
  public $query;
  public $startTime;
  public $totalBytesProcessed;
  protected $__loadType = 'Google_JobStatistics3';
  protected $__loadDataType = '';
  protected $__queryType = 'Google_JobStatistics2';
  protected $__queryDataType = '';

  public function getEndTime() {
    return $this->endTime;
  }

  public function setEndTime( $endTime) {
    $this->endTime = $endTime;
  }

  public function getLoad() {
    return $this->load;
  }

  public function setLoad(Google_JobStatistics3 $load) {
    $this->load = $load;
  }

  public function getQuery() {
    return $this->query;
  }

  public function setQuery(Google_JobStatistics2 $query) {
    $this->query = $query;
  }

  public function getStartTime() {
    return $this->startTime;
  }

  public function setStartTime( $startTime) {
    $this->startTime = $startTime;
  }

  public function getTotalBytesProcessed() {
    return $this->totalBytesProcessed;
  }

  public function setTotalBytesProcessed( $totalBytesProcessed) {
    $this->totalBytesProcessed = $totalBytesProcessed;
  }
}

class Google_JobStatistics2 extends Google_Model {
  public $cacheHit;
  public $completionRatio;
  public $totalBytesProcessed;

  public function getCacheHit() {
    return $this->cacheHit;
  }

  public function setCacheHit( $cacheHit) {
    $this->cacheHit = $cacheHit;
  }

  public function getCompletionRatio() {
    return $this->completionRatio;
  }

  public function setCompletionRatio( $completionRatio) {
    $this->completionRatio = $completionRatio;
  }

  public function getTotalBytesProcessed() {
    return $this->totalBytesProcessed;
  }

  public function setTotalBytesProcessed( $totalBytesProcessed) {
    $this->totalBytesProcessed = $totalBytesProcessed;
  }
}

class Google_JobStatistics3 extends Google_Model {
  public $inputFileBytes;
  public $inputFiles;
  public $outputBytes;
  public $outputRows;

  public function getInputFileBytes() {
    return $this->inputFileBytes;
  }

  public function setInputFileBytes( $inputFileBytes) {
    $this->inputFileBytes = $inputFileBytes;
  }

  public function getInputFiles() {
    return $this->inputFiles;
  }

  public function setInputFiles( $inputFiles) {
    $this->inputFiles = $inputFiles;
  }

  public function getOutputBytes() {
    return $this->outputBytes;
  }

  public function setOutputBytes( $outputBytes) {
    $this->outputBytes = $outputBytes;
  }

  public function getOutputRows() {
    return $this->outputRows;
  }

  public function setOutputRows( $outputRows) {
    $this->outputRows = $outputRows;
  }
}

class Google_JobStatus extends Google_Model {
  public $errorResult;
  public $errors;
  public $state;
  protected $__errorResultType = 'Google_ErrorProto';
  protected $__errorResultDataType = '';
  protected $__errorsType = 'Google_ErrorProto';
  protected $__errorsDataType = 'array';

  public function getErrorResult() {
    return $this->errorResult;
  }

  public function setErrorResult(Google_ErrorProto $errorResult) {
    $this->errorResult = $errorResult;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function setErrors(/* array(Google_ErrorProto) */ $errors) {
    $this->assertIsArray($errors, 'Google_ErrorProto', __METHOD__);
    $this->errors = $errors;
  }

  public function getState() {
    return $this->state;
  }

  public function setState( $state) {
    $this->state = $state;
  }
}

class Google_ProjectList extends Google_Model {
  public $etag;
  public $kind;
  public $nextPageToken;
  public $projects;
  public $totalItems;
  protected $__projectsType = 'Google_ProjectListProjects';
  protected $__projectsDataType = 'array';

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

  public function getNextPageToken() {
    return $this->nextPageToken;
  }

  public function setNextPageToken( $nextPageToken) {
    $this->nextPageToken = $nextPageToken;
  }

  public function getProjects() {
    return $this->projects;
  }

  public function setProjects(/* array(Google_ProjectListProjects) */ $projects) {
    $this->assertIsArray($projects, 'Google_ProjectListProjects', __METHOD__);
    $this->projects = $projects;
  }

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}

class Google_ProjectListProjects extends Google_Model {
  public $friendlyName;
  public $id;
  public $kind;
  public $numericId;
  public $projectReference;
  protected $__projectReferenceType = 'Google_ProjectReference';
  protected $__projectReferenceDataType = '';

  public function getFriendlyName() {
    return $this->friendlyName;
  }

  public function setFriendlyName( $friendlyName) {
    $this->friendlyName = $friendlyName;
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

  public function getNumericId() {
    return $this->numericId;
  }

  public function setNumericId( $numericId) {
    $this->numericId = $numericId;
  }

  public function getProjectReference() {
    return $this->projectReference;
  }

  public function setProjectReference(Google_ProjectReference $projectReference) {
    $this->projectReference = $projectReference;
  }
}

class Google_ProjectReference extends Google_Model {
  public $projectId;

  public function getProjectId() {
    return $this->projectId;
  }

  public function setProjectId( $projectId) {
    $this->projectId = $projectId;
  }
}

class Google_QueryRequest extends Google_Model {
  public $defaultDataset;
  public $dryRun;
  public $kind;
  public $maxResults;
  public $minCompletionRatio;
  public $preserveNulls;
  public $query;
  public $timeoutMs;
  public $useQueryCache;
  protected $__defaultDatasetType = 'Google_DatasetReference';
  protected $__defaultDatasetDataType = '';

  public function getDefaultDataset() {
    return $this->defaultDataset;
  }

  public function setDefaultDataset(Google_DatasetReference $defaultDataset) {
    $this->defaultDataset = $defaultDataset;
  }

  public function getDryRun() {
    return $this->dryRun;
  }

  public function setDryRun( $dryRun) {
    $this->dryRun = $dryRun;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getMaxResults() {
    return $this->maxResults;
  }

  public function setMaxResults( $maxResults) {
    $this->maxResults = $maxResults;
  }

  public function getMinCompletionRatio() {
    return $this->minCompletionRatio;
  }

  public function setMinCompletionRatio( $minCompletionRatio) {
    $this->minCompletionRatio = $minCompletionRatio;
  }

  public function getPreserveNulls() {
    return $this->preserveNulls;
  }

  public function setPreserveNulls( $preserveNulls) {
    $this->preserveNulls = $preserveNulls;
  }

  public function getQuery() {
    return $this->query;
  }

  public function setQuery( $query) {
    $this->query = $query;
  }

  public function getTimeoutMs() {
    return $this->timeoutMs;
  }

  public function setTimeoutMs( $timeoutMs) {
    $this->timeoutMs = $timeoutMs;
  }

  public function getUseQueryCache() {
    return $this->useQueryCache;
  }

  public function setUseQueryCache( $useQueryCache) {
    $this->useQueryCache = $useQueryCache;
  }
}

class Google_QueryResponse extends Google_Model {
  public $cacheHit;
  public $jobComplete;
  public $jobReference;
  public $kind;
  public $pageToken;
  public $rows;
  public $schema;
  public $totalBytesProcessed;
  public $totalRows;
  protected $__jobReferenceType = 'Google_JobReference';
  protected $__jobReferenceDataType = '';
  protected $__rowsType = 'Google_TableRow';
  protected $__rowsDataType = 'array';
  protected $__schemaType = 'Google_TableSchema';
  protected $__schemaDataType = '';

  public function getCacheHit() {
    return $this->cacheHit;
  }

  public function setCacheHit( $cacheHit) {
    $this->cacheHit = $cacheHit;
  }

  public function getJobComplete() {
    return $this->jobComplete;
  }

  public function setJobComplete( $jobComplete) {
    $this->jobComplete = $jobComplete;
  }

  public function getJobReference() {
    return $this->jobReference;
  }

  public function setJobReference(Google_JobReference $jobReference) {
    $this->jobReference = $jobReference;
  }

  public function getKind() {
    return $this->kind;
  }

  public function setKind( $kind) {
    $this->kind = $kind;
  }

  public function getPageToken() {
    return $this->pageToken;
  }

  public function setPageToken( $pageToken) {
    $this->pageToken = $pageToken;
  }

  public function getRows() {
    return $this->rows;
  }

  public function setRows(/* array(Google_TableRow) */ $rows) {
    $this->assertIsArray($rows, 'Google_TableRow', __METHOD__);
    $this->rows = $rows;
  }

  public function getSchema() {
    return $this->schema;
  }

  public function setSchema(Google_TableSchema $schema) {
    $this->schema = $schema;
  }

  public function getTotalBytesProcessed() {
    return $this->totalBytesProcessed;
  }

  public function setTotalBytesProcessed( $totalBytesProcessed) {
    $this->totalBytesProcessed = $totalBytesProcessed;
  }

  public function getTotalRows() {
    return $this->totalRows;
  }

  public function setTotalRows( $totalRows) {
    $this->totalRows = $totalRows;
  }
}

class Google_Table extends Google_Model {
  public $creationTime;
  public $description;
  public $etag;
  public $expirationTime;
  public $friendlyName;
  public $id;
  public $kind;
  public $lastModifiedTime;
  public $numBytes;
  public $numRows;
  public $schema;
  public $selfLink;
  public $tableReference;
  protected $__schemaType = 'Google_TableSchema';
  protected $__schemaDataType = '';
  protected $__tableReferenceType = 'Google_TableReference';
  protected $__tableReferenceDataType = '';

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

  public function getEtag() {
    return $this->etag;
  }

  public function setEtag( $etag) {
    $this->etag = $etag;
  }

  public function getExpirationTime() {
    return $this->expirationTime;
  }

  public function setExpirationTime( $expirationTime) {
    $this->expirationTime = $expirationTime;
  }

  public function getFriendlyName() {
    return $this->friendlyName;
  }

  public function setFriendlyName( $friendlyName) {
    $this->friendlyName = $friendlyName;
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

  public function getNumBytes() {
    return $this->numBytes;
  }

  public function setNumBytes( $numBytes) {
    $this->numBytes = $numBytes;
  }

  public function getNumRows() {
    return $this->numRows;
  }

  public function setNumRows( $numRows) {
    $this->numRows = $numRows;
  }

  public function getSchema() {
    return $this->schema;
  }

  public function setSchema(Google_TableSchema $schema) {
    $this->schema = $schema;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getTableReference() {
    return $this->tableReference;
  }

  public function setTableReference(Google_TableReference $tableReference) {
    $this->tableReference = $tableReference;
  }
}

class Google_TableCell extends Google_Model {
  public $v;

  public function getV() {
    return $this->v;
  }

  public function setV( $v) {
    $this->v = $v;
  }
}

class Google_TableDataList extends Google_Model {
  public $etag;
  public $kind;
  public $pageToken;
  public $rows;
  public $totalRows;
  protected $__rowsType = 'Google_TableRow';
  protected $__rowsDataType = 'array';

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

  public function getPageToken() {
    return $this->pageToken;
  }

  public function setPageToken( $pageToken) {
    $this->pageToken = $pageToken;
  }

  public function getRows() {
    return $this->rows;
  }

  public function setRows(/* array(Google_TableRow) */ $rows) {
    $this->assertIsArray($rows, 'Google_TableRow', __METHOD__);
    $this->rows = $rows;
  }

  public function getTotalRows() {
    return $this->totalRows;
  }

  public function setTotalRows( $totalRows) {
    $this->totalRows = $totalRows;
  }
}

class Google_TableFieldSchema extends Google_Model {
  public $fields;
  public $mode;
  public $name;
  public $type;
  protected $__fieldsType = 'Google_TableFieldSchema';
  protected $__fieldsDataType = 'array';

  public function getFields() {
    return $this->fields;
  }

  public function setFields(/* array(Google_TableFieldSchema) */ $fields) {
    $this->assertIsArray($fields, 'Google_TableFieldSchema', __METHOD__);
    $this->fields = $fields;
  }

  public function getMode() {
    return $this->mode;
  }

  public function setMode( $mode) {
    $this->mode = $mode;
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
}

class Google_TableList extends Google_Model {
  public $etag;
  public $kind;
  public $nextPageToken;
  public $tables;
  public $totalItems;
  protected $__tablesType = 'Google_TableListTables';
  protected $__tablesDataType = 'array';

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

  public function getNextPageToken() {
    return $this->nextPageToken;
  }

  public function setNextPageToken( $nextPageToken) {
    $this->nextPageToken = $nextPageToken;
  }

  public function getTables() {
    return $this->tables;
  }

  public function setTables(/* array(Google_TableListTables) */ $tables) {
    $this->assertIsArray($tables, 'Google_TableListTables', __METHOD__);
    $this->tables = $tables;
  }

  public function getTotalItems() {
    return $this->totalItems;
  }

  public function setTotalItems( $totalItems) {
    $this->totalItems = $totalItems;
  }
}

class Google_TableListTables extends Google_Model {
  public $friendlyName;
  public $id;
  public $kind;
  public $tableReference;
  protected $__tableReferenceType = 'Google_TableReference';
  protected $__tableReferenceDataType = '';

  public function getFriendlyName() {
    return $this->friendlyName;
  }

  public function setFriendlyName( $friendlyName) {
    $this->friendlyName = $friendlyName;
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

  public function getTableReference() {
    return $this->tableReference;
  }

  public function setTableReference(Google_TableReference $tableReference) {
    $this->tableReference = $tableReference;
  }
}

class Google_TableReference extends Google_Model {
  public $datasetId;
  public $projectId;
  public $tableId;

  public function getDatasetId() {
    return $this->datasetId;
  }

  public function setDatasetId( $datasetId) {
    $this->datasetId = $datasetId;
  }

  public function getProjectId() {
    return $this->projectId;
  }

  public function setProjectId( $projectId) {
    $this->projectId = $projectId;
  }

  public function getTableId() {
    return $this->tableId;
  }

  public function setTableId( $tableId) {
    $this->tableId = $tableId;
  }
}

class Google_TableRow extends Google_Model {
  public $f;
  protected $__fType = 'Google_TableCell';
  protected $__fDataType = 'array';

  public function getF() {
    return $this->f;
  }

  public function setF(/* array(Google_TableCell) */ $f) {
    $this->assertIsArray($f, 'Google_TableCell', __METHOD__);
    $this->f = $f;
  }
}

class Google_TableSchema extends Google_Model {
  public $fields;
  protected $__fieldsType = 'Google_TableFieldSchema';
  protected $__fieldsDataType = 'array';

  public function getFields() {
    return $this->fields;
  }

  public function setFields(/* array(Google_TableFieldSchema) */ $fields) {
    $this->assertIsArray($fields, 'Google_TableFieldSchema', __METHOD__);
    $this->fields = $fields;
  }
}
