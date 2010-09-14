<?php
class JSONVue{
	private $obj;

	public function __construct($obj){
		$this->obj = $obj;
	}

	public function getJSON($filtre = array()){
		$toEncode = $this->getObjectRepresentation($filtre);
		return json_encode($toEncode);
	}

	public function getObjectRepresentation($filtre){
		$fields = array();
		$class = get_class($this->obj);
		if(!isset($filtre[$class]) || !is_array($filtre[$class])){
			$filtreClass = array();
		}else{
			$filtreClass = $filtre[$class];
		}
		$methods = get_class_methods($this->obj);
		foreach($methods as $method){
			$delta = -1;
			if(substr($method, 0, 3) == "get"){
				$delta = 3;
			}elseif(substr($method, 0, 2) == "is"){
				$delta = 2;
			}
			if($delta != -1){
				$fieldName = substr($method,$delta);
				if(!array_key_exists($fieldName, $filtreClass)){
					continue;
				}
				$refMethod = new ReflectionMethod($class, $method);
				if($refMethod->getParameters() == NULL && !$refMethod->isStatic()){
					$field = $this->obj->$method();
					if(is_object($field)){
						$tmpVue = new JSONVue($field);
						$fieldRepr = $tmpVue->getObjectRepresentation($filtre);
					}else{
						$fieldRepr = $field;
					}
					$fields[$fieldName] = $fieldRepr;
				}
			}
		}
		return array("klass" => $class,"fields" => $fields);
	}
}
?>