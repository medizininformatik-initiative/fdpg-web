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
   * The "hostedmodels" collection of methods.
   * Typical usage is:
   *  <code>
   *   $predictionService = new Google_PredictionService(...);
   *   $hostedmodels = $predictionService->hostedmodels;
   *  </code>
   */
  class Google_HostedmodelsServiceResource extends Google_ServiceResource {

    /**
     * Submit input and request an output against a hosted model. (hostedmodels.predict)
     *
     * @param string $project The project associated with the model.
     * @param string $hostedModelName The name of a hosted model.
     * @param Google_Input $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Output
     */
    public function predict($project, $hostedModelName, Google_Input $postBody, $optParams = array()) {
      $params = array('project' => $project, 'hostedModelName' => $hostedModelName, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('predict', array($params));
      if ($this->useObjects()) {
        return new Google_Output($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "trainedmodels" collection of methods.
   * Typical usage is:
   *  <code>
   *   $predictionService = new Google_PredictionService(...);
   *   $trainedmodels = $predictionService->trainedmodels;
   *  </code>
   */
  class Google_TrainedmodelsServiceResource extends Google_ServiceResource {

    /**
     * Get analysis of the model and the data the model was trained on. (trainedmodels.analyze)
     *
     * @param string $project The project associated with the model.
     * @param string $id The unique name for the predictive model.
     * @param array $optParams Optional parameters.
     * @return Google_Analyze
     */
    public function analyze($project, $id, $optParams = array()) {
      $params = array('project' => $project, 'id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('analyze', array($params));
      if ($this->useObjects()) {
        return new Google_Analyze($data);
      } else {
        return $data;
      }
    }
    /**
     * Delete a trained model. (trainedmodels.delete)
     *
     * @param string $project The project associated with the model.
     * @param string $id The unique name for the predictive model.
     * @param array $optParams Optional parameters.
     */
    public function delete($project, $id, $optParams = array()) {
      $params = array('project' => $project, 'id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('delete', array($params));
      return $data;
    }
    /**
     * Check training status of your model. (trainedmodels.get)
     *
     * @param string $project The project associated with the model.
     * @param string $id The unique name for the predictive model.
     * @param array $optParams Optional parameters.
     * @return Google_Insert2
     */
    public function get($project, $id, $optParams = array()) {
      $params = array('project' => $project, 'id' => $id);
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Insert2($data);
      } else {
        return $data;
      }
    }
    /**
     * Train a Prediction API model. (trainedmodels.insert)
     *
     * @param string $project The project associated with the model.
     * @param Google_Insert $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Insert2
     */
    public function insert($project, Google_Insert $postBody, $optParams = array()) {
      $params = array('project' => $project, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('insert', array($params));
      if ($this->useObjects()) {
        return new Google_Insert2($data);
      } else {
        return $data;
      }
    }
    /**
     * List available models. (trainedmodels.list)
     *
     * @param string $project The project associated with the model.
     * @param array $optParams Optional parameters.
     *
     * @opt_param string maxResults Maximum number of results to return.
     * @opt_param string pageToken Pagination token.
     * @return Google_PredictionList
     */
    public function listTrainedmodels($project, $optParams = array()) {
      $params = array('project' => $project);
      $params = array_merge($params, $optParams);
      $data = $this->__call('list', array($params));
      if ($this->useObjects()) {
        return new Google_PredictionList($data);
      } else {
        return $data;
      }
    }
    /**
     * Submit model id and request a prediction. (trainedmodels.predict)
     *
     * @param string $project The project associated with the model.
     * @param string $id The unique name for the predictive model.
     * @param Google_Input $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Output
     */
    public function predict($project, $id, Google_Input $postBody, $optParams = array()) {
      $params = array('project' => $project, 'id' => $id, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('predict', array($params));
      if ($this->useObjects()) {
        return new Google_Output($data);
      } else {
        return $data;
      }
    }
    /**
     * Add new data to a trained model. (trainedmodels.update)
     *
     * @param string $project The project associated with the model.
     * @param string $id The unique name for the predictive model.
     * @param Google_Update $postBody
     * @param array $optParams Optional parameters.
     * @return Google_Insert2
     */
    public function update($project, $id, Google_Update $postBody, $optParams = array()) {
      $params = array('project' => $project, 'id' => $id, 'postBody' => $postBody);
      $params = array_merge($params, $optParams);
      $data = $this->__call('update', array($params));
      if ($this->useObjects()) {
        return new Google_Insert2($data);
      } else {
        return $data;
      }
    }
  }

/**
 * Service definition for Google_Prediction (v1.6).
 *
 * <p>
 * Lets you access a cloud hosted machine learning service that makes it easy to build smart apps
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="https://developers.google.com/prediction/docs/developer-guide" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_PredictionService extends Google_Service {
  public $hostedmodels;
  public $trainedmodels;
  /**
   * Constructs the internal representation of the Prediction service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client) {
    $this->servicePath = 'prediction/v1.6/projects/';
    $this->version = 'v1.6';
    $this->serviceName = 'prediction';

    $client->addService($this->serviceName, $this->version);
    $this->hostedmodels = new Google_HostedmodelsServiceResource($this, $this->serviceName, 'hostedmodels', json_decode('{"methods": {"predict": {"id": "prediction.hostedmodels.predict", "path": "{project}/hostedmodels/{hostedModelName}/predict", "httpMethod": "POST", "parameters": {"hostedModelName": {"type": "string", "required": true, "location": "path"}, "project": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Input"}, "response": {"$ref": "Output"}, "scopes": ["https://www.googleapis.com/auth/prediction"]}}}', true));
    $this->trainedmodels = new Google_TrainedmodelsServiceResource($this, $this->serviceName, 'trainedmodels', json_decode('{"methods": {"analyze": {"id": "prediction.trainedmodels.analyze", "path": "{project}/trainedmodels/{id}/analyze", "httpMethod": "GET", "parameters": {"id": {"type": "string", "required": true, "location": "path"}, "project": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Analyze"}, "scopes": ["https://www.googleapis.com/auth/prediction"]}, "delete": {"id": "prediction.trainedmodels.delete", "path": "{project}/trainedmodels/{id}", "httpMethod": "DELETE", "parameters": {"id": {"type": "string", "required": true, "location": "path"}, "project": {"type": "string", "required": true, "location": "path"}}, "scopes": ["https://www.googleapis.com/auth/prediction"]}, "get": {"id": "prediction.trainedmodels.get", "path": "{project}/trainedmodels/{id}", "httpMethod": "GET", "parameters": {"id": {"type": "string", "required": true, "location": "path"}, "project": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "Insert2"}, "scopes": ["https://www.googleapis.com/auth/prediction"]}, "insert": {"id": "prediction.trainedmodels.insert", "path": "{project}/trainedmodels", "httpMethod": "POST", "parameters": {"project": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Insert"}, "response": {"$ref": "Insert2"}, "scopes": ["https://www.googleapis.com/auth/devstorage.full_control", "https://www.googleapis.com/auth/devstorage.read_only", "https://www.googleapis.com/auth/devstorage.read_write", "https://www.googleapis.com/auth/prediction"]}, "list": {"id": "prediction.trainedmodels.list", "path": "{project}/trainedmodels/list", "httpMethod": "GET", "parameters": {"maxResults": {"type": "integer", "format": "uint32", "minimum": "0", "location": "query"}, "pageToken": {"type": "string", "location": "query"}, "project": {"type": "string", "required": true, "location": "path"}}, "response": {"$ref": "List"}, "scopes": ["https://www.googleapis.com/auth/prediction"]}, "predict": {"id": "prediction.trainedmodels.predict", "path": "{project}/trainedmodels/{id}/predict", "httpMethod": "POST", "parameters": {"id": {"type": "string", "required": true, "location": "path"}, "project": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Input"}, "response": {"$ref": "Output"}, "scopes": ["https://www.googleapis.com/auth/prediction"]}, "update": {"id": "prediction.trainedmodels.update", "path": "{project}/trainedmodels/{id}", "httpMethod": "PUT", "parameters": {"id": {"type": "string", "required": true, "location": "path"}, "project": {"type": "string", "required": true, "location": "path"}}, "request": {"$ref": "Update"}, "response": {"$ref": "Insert2"}, "scopes": ["https://www.googleapis.com/auth/prediction"]}}}', true));

  }
}



class Google_Analyze extends Google_Model {
  public $dataDescription;
  public $errors;
  public $id;
  public $kind;
  public $modelDescription;
  public $selfLink;
  protected $__dataDescriptionType = 'Google_AnalyzeDataDescription';
  protected $__dataDescriptionDataType = '';
  protected $__modelDescriptionType = 'Google_AnalyzeModelDescription';
  protected $__modelDescriptionDataType = '';

  public function getDataDescription() {
    return $this->dataDescription;
  }

  public function setDataDescription(Google_AnalyzeDataDescription $dataDescription) {
    $this->dataDescription = $dataDescription;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function setErrors(/* array(Google_string) */ $errors) {
    $this->assertIsArray($errors, 'Google_string', __METHOD__);
    $this->errors = $errors;
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

  public function getModelDescription() {
    return $this->modelDescription;
  }

  public function setModelDescription(Google_AnalyzeModelDescription $modelDescription) {
    $this->modelDescription = $modelDescription;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }
}

class Google_AnalyzeDataDescription extends Google_Model {
  public $features;
  public $outputFeature;
  protected $__featuresType = 'Google_AnalyzeDataDescriptionFeatures';
  protected $__featuresDataType = 'array';
  protected $__outputFeatureType = 'Google_AnalyzeDataDescriptionOutputFeature';
  protected $__outputFeatureDataType = '';

  public function getFeatures() {
    return $this->features;
  }

  public function setFeatures(/* array(Google_AnalyzeDataDescriptionFeatures) */ $features) {
    $this->assertIsArray($features, 'Google_AnalyzeDataDescriptionFeatures', __METHOD__);
    $this->features = $features;
  }

  public function getOutputFeature() {
    return $this->outputFeature;
  }

  public function setOutputFeature(Google_AnalyzeDataDescriptionOutputFeature $outputFeature) {
    $this->outputFeature = $outputFeature;
  }
}

class Google_AnalyzeDataDescriptionFeatures extends Google_Model {
  public $categorical;
  public $index;
  public $numeric;
  public $text;
  protected $__categoricalType = 'Google_AnalyzeDataDescriptionFeaturesCategorical';
  protected $__categoricalDataType = '';
  protected $__numericType = 'Google_AnalyzeDataDescriptionFeaturesNumeric';
  protected $__numericDataType = '';
  protected $__textType = 'Google_AnalyzeDataDescriptionFeaturesText';
  protected $__textDataType = '';

  public function getCategorical() {
    return $this->categorical;
  }

  public function setCategorical(Google_AnalyzeDataDescriptionFeaturesCategorical $categorical) {
    $this->categorical = $categorical;
  }

  public function getIndex() {
    return $this->index;
  }

  public function setIndex( $index) {
    $this->index = $index;
  }

  public function getNumeric() {
    return $this->numeric;
  }

  public function setNumeric(Google_AnalyzeDataDescriptionFeaturesNumeric $numeric) {
    $this->numeric = $numeric;
  }

  public function getText() {
    return $this->text;
  }

  public function setText(Google_AnalyzeDataDescriptionFeaturesText $text) {
    $this->text = $text;
  }
}

class Google_AnalyzeDataDescriptionFeaturesCategorical extends Google_Model {
  public $count;
  public $values;
  protected $__valuesType = 'Google_AnalyzeDataDescriptionFeaturesCategoricalValues';
  protected $__valuesDataType = 'array';

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }

  public function getValues() {
    return $this->values;
  }

  public function setValues(/* array(Google_AnalyzeDataDescriptionFeaturesCategoricalValues) */ $values) {
    $this->assertIsArray($values, 'Google_AnalyzeDataDescriptionFeaturesCategoricalValues', __METHOD__);
    $this->values = $values;
  }
}

class Google_AnalyzeDataDescriptionFeaturesCategoricalValues extends Google_Model {
  public $count;
  public $value;

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_AnalyzeDataDescriptionFeaturesNumeric extends Google_Model {
  public $count;
  public $mean;
  public $variance;

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }

  public function getMean() {
    return $this->mean;
  }

  public function setMean( $mean) {
    $this->mean = $mean;
  }

  public function getVariance() {
    return $this->variance;
  }

  public function setVariance( $variance) {
    $this->variance = $variance;
  }
}

class Google_AnalyzeDataDescriptionFeaturesText extends Google_Model {
  public $count;

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }
}

class Google_AnalyzeDataDescriptionOutputFeature extends Google_Model {
  public $numeric;
  public $text;
  protected $__numericType = 'Google_AnalyzeDataDescriptionOutputFeatureNumeric';
  protected $__numericDataType = '';
  protected $__textType = 'Google_AnalyzeDataDescriptionOutputFeatureText';
  protected $__textDataType = 'array';

  public function getNumeric() {
    return $this->numeric;
  }

  public function setNumeric(Google_AnalyzeDataDescriptionOutputFeatureNumeric $numeric) {
    $this->numeric = $numeric;
  }

  public function getText() {
    return $this->text;
  }

  public function setText(/* array(Google_AnalyzeDataDescriptionOutputFeatureText) */ $text) {
    $this->assertIsArray($text, 'Google_AnalyzeDataDescriptionOutputFeatureText', __METHOD__);
    $this->text = $text;
  }
}

class Google_AnalyzeDataDescriptionOutputFeatureNumeric extends Google_Model {
  public $count;
  public $mean;
  public $variance;

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }

  public function getMean() {
    return $this->mean;
  }

  public function setMean( $mean) {
    $this->mean = $mean;
  }

  public function getVariance() {
    return $this->variance;
  }

  public function setVariance( $variance) {
    $this->variance = $variance;
  }
}

class Google_AnalyzeDataDescriptionOutputFeatureText extends Google_Model {
  public $count;
  public $value;

  public function getCount() {
    return $this->count;
  }

  public function setCount( $count) {
    $this->count = $count;
  }

  public function getValue() {
    return $this->value;
  }

  public function setValue( $value) {
    $this->value = $value;
  }
}

class Google_AnalyzeModelDescription extends Google_Model {
  public $confusionMatrix;
  public $confusionMatrixRowTotals;
  public $modelinfo;
  protected $__modelinfoType = 'Google_Insert2';
  protected $__modelinfoDataType = '';

  public function getConfusionMatrix() {
    return $this->confusionMatrix;
  }

  public function setConfusionMatrix( $confusionMatrix) {
    $this->confusionMatrix = $confusionMatrix;
  }

  public function getConfusionMatrixRowTotals() {
    return $this->confusionMatrixRowTotals;
  }

  public function setConfusionMatrixRowTotals( $confusionMatrixRowTotals) {
    $this->confusionMatrixRowTotals = $confusionMatrixRowTotals;
  }

  public function getModelinfo() {
    return $this->modelinfo;
  }

  public function setModelinfo(Google_Insert2 $modelinfo) {
    $this->modelinfo = $modelinfo;
  }
}

class Google_Input extends Google_Model {
  public $input;
  protected $__inputType = 'Google_InputInput';
  protected $__inputDataType = '';

  public function getInput() {
    return $this->input;
  }

  public function setInput(Google_InputInput $input) {
    $this->input = $input;
  }
}

class Google_InputInput extends Google_Model {
  public $csvInstance;

  public function getCsvInstance() {
    return $this->csvInstance;
  }

  public function setCsvInstance(/* array(Google_object) */ $csvInstance) {
    $this->assertIsArray($csvInstance, 'Google_object', __METHOD__);
    $this->csvInstance = $csvInstance;
  }
}

class Google_Insert extends Google_Model {
  public $id;
  public $modelType;
  public $sourceModel;
  public $storageDataLocation;
  public $storagePMMLLocation;
  public $storagePMMLModelLocation;
  public $trainingInstances;
  public $utility;
  protected $__trainingInstancesType = 'Google_InsertTrainingInstances';
  protected $__trainingInstancesDataType = 'array';

  public function getId() {
    return $this->id;
  }

  public function setId( $id) {
    $this->id = $id;
  }

  public function getModelType() {
    return $this->modelType;
  }

  public function setModelType( $modelType) {
    $this->modelType = $modelType;
  }

  public function getSourceModel() {
    return $this->sourceModel;
  }

  public function setSourceModel( $sourceModel) {
    $this->sourceModel = $sourceModel;
  }

  public function getStorageDataLocation() {
    return $this->storageDataLocation;
  }

  public function setStorageDataLocation( $storageDataLocation) {
    $this->storageDataLocation = $storageDataLocation;
  }

  public function getStoragePMMLLocation() {
    return $this->storagePMMLLocation;
  }

  public function setStoragePMMLLocation( $storagePMMLLocation) {
    $this->storagePMMLLocation = $storagePMMLLocation;
  }

  public function getStoragePMMLModelLocation() {
    return $this->storagePMMLModelLocation;
  }

  public function setStoragePMMLModelLocation( $storagePMMLModelLocation) {
    $this->storagePMMLModelLocation = $storagePMMLModelLocation;
  }

  public function getTrainingInstances() {
    return $this->trainingInstances;
  }

  public function setTrainingInstances(/* array(Google_InsertTrainingInstances) */ $trainingInstances) {
    $this->assertIsArray($trainingInstances, 'Google_InsertTrainingInstances', __METHOD__);
    $this->trainingInstances = $trainingInstances;
  }

  public function getUtility() {
    return $this->utility;
  }

  public function setUtility(/* array(Google_double) */ $utility) {
    $this->assertIsArray($utility, 'Google_double', __METHOD__);
    $this->utility = $utility;
  }
}

class Google_Insert2 extends Google_Model {
  public $created;
  public $id;
  public $kind;
  public $modelInfo;
  public $modelType;
  public $selfLink;
  public $storageDataLocation;
  public $storagePMMLLocation;
  public $storagePMMLModelLocation;
  public $trainingComplete;
  public $trainingStatus;
  protected $__modelInfoType = 'Google_Insert2ModelInfo';
  protected $__modelInfoDataType = '';

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

  public function getModelInfo() {
    return $this->modelInfo;
  }

  public function setModelInfo(Google_Insert2ModelInfo $modelInfo) {
    $this->modelInfo = $modelInfo;
  }

  public function getModelType() {
    return $this->modelType;
  }

  public function setModelType( $modelType) {
    $this->modelType = $modelType;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }

  public function getStorageDataLocation() {
    return $this->storageDataLocation;
  }

  public function setStorageDataLocation( $storageDataLocation) {
    $this->storageDataLocation = $storageDataLocation;
  }

  public function getStoragePMMLLocation() {
    return $this->storagePMMLLocation;
  }

  public function setStoragePMMLLocation( $storagePMMLLocation) {
    $this->storagePMMLLocation = $storagePMMLLocation;
  }

  public function getStoragePMMLModelLocation() {
    return $this->storagePMMLModelLocation;
  }

  public function setStoragePMMLModelLocation( $storagePMMLModelLocation) {
    $this->storagePMMLModelLocation = $storagePMMLModelLocation;
  }

  public function getTrainingComplete() {
    return $this->trainingComplete;
  }

  public function setTrainingComplete( $trainingComplete) {
    $this->trainingComplete = $trainingComplete;
  }

  public function getTrainingStatus() {
    return $this->trainingStatus;
  }

  public function setTrainingStatus( $trainingStatus) {
    $this->trainingStatus = $trainingStatus;
  }
}

class Google_Insert2ModelInfo extends Google_Model {
  public $classWeightedAccuracy;
  public $classificationAccuracy;
  public $meanSquaredError;
  public $modelType;
  public $numberInstances;
  public $numberLabels;

  public function getClassWeightedAccuracy() {
    return $this->classWeightedAccuracy;
  }

  public function setClassWeightedAccuracy( $classWeightedAccuracy) {
    $this->classWeightedAccuracy = $classWeightedAccuracy;
  }

  public function getClassificationAccuracy() {
    return $this->classificationAccuracy;
  }

  public function setClassificationAccuracy( $classificationAccuracy) {
    $this->classificationAccuracy = $classificationAccuracy;
  }

  public function getMeanSquaredError() {
    return $this->meanSquaredError;
  }

  public function setMeanSquaredError( $meanSquaredError) {
    $this->meanSquaredError = $meanSquaredError;
  }

  public function getModelType() {
    return $this->modelType;
  }

  public function setModelType( $modelType) {
    $this->modelType = $modelType;
  }

  public function getNumberInstances() {
    return $this->numberInstances;
  }

  public function setNumberInstances( $numberInstances) {
    $this->numberInstances = $numberInstances;
  }

  public function getNumberLabels() {
    return $this->numberLabels;
  }

  public function setNumberLabels( $numberLabels) {
    $this->numberLabels = $numberLabels;
  }
}

class Google_InsertTrainingInstances extends Google_Model {
  public $csvInstance;
  public $output;

  public function getCsvInstance() {
    return $this->csvInstance;
  }

  public function setCsvInstance(/* array(Google_object) */ $csvInstance) {
    $this->assertIsArray($csvInstance, 'Google_object', __METHOD__);
    $this->csvInstance = $csvInstance;
  }

  public function getOutput() {
    return $this->output;
  }

  public function setOutput( $output) {
    $this->output = $output;
  }
}

class Google_Output extends Google_Model {
  public $id;
  public $kind;
  public $outputLabel;
  public $outputMulti;
  public $outputValue;
  public $selfLink;
  protected $__outputMultiType = 'Google_OutputOutputMulti';
  protected $__outputMultiDataType = 'array';

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

  public function getOutputLabel() {
    return $this->outputLabel;
  }

  public function setOutputLabel( $outputLabel) {
    $this->outputLabel = $outputLabel;
  }

  public function getOutputMulti() {
    return $this->outputMulti;
  }

  public function setOutputMulti(/* array(Google_OutputOutputMulti) */ $outputMulti) {
    $this->assertIsArray($outputMulti, 'Google_OutputOutputMulti', __METHOD__);
    $this->outputMulti = $outputMulti;
  }

  public function getOutputValue() {
    return $this->outputValue;
  }

  public function setOutputValue( $outputValue) {
    $this->outputValue = $outputValue;
  }

  public function getSelfLink() {
    return $this->selfLink;
  }

  public function setSelfLink( $selfLink) {
    $this->selfLink = $selfLink;
  }
}

class Google_OutputOutputMulti extends Google_Model {
  public $label;
  public $score;

  public function getLabel() {
    return $this->label;
  }

  public function setLabel( $label) {
    $this->label = $label;
  }

  public function getScore() {
    return $this->score;
  }

  public function setScore( $score) {
    $this->score = $score;
  }
}

class Google_PredictionList extends Google_Model {
  public $items;
  public $kind;
  public $nextPageToken;
  public $selfLink;
  protected $__itemsType = 'Google_Insert2';
  protected $__itemsDataType = 'array';

  public function getItems() {
    return $this->items;
  }

  public function setItems(/* array(Google_Insert2) */ $items) {
    $this->assertIsArray($items, 'Google_Insert2', __METHOD__);
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
}

class Google_Update extends Google_Model {
  public $csvInstance;
  public $output;

  public function getCsvInstance() {
    return $this->csvInstance;
  }

  public function setCsvInstance(/* array(Google_object) */ $csvInstance) {
    $this->assertIsArray($csvInstance, 'Google_object', __METHOD__);
    $this->csvInstance = $csvInstance;
  }

  public function getOutput() {
    return $this->output;
  }

  public function setOutput( $output) {
    $this->output = $output;
  }
}
