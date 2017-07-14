<?php
	class defaultparamvalue {
		public function getName($empty = '', $firstname = "Michael", $lastname = array("Michael", "Watzer")) {
			return $firstname." ".$lastname;
		}
                public function getAge($age = 22) {
			return $age;
		}
	}
 	$reflection = new ReflectionClass("defaultparamvalue");
 	$methods    = $reflection->getMethods();
        foreach($methods as $method) {
                echo $method->getName()."\n";
		foreach($method->getParameters() as $param) {
			echo "\t".$param->getName();
			if($param->isDefaultValueAvailable())
				if(is_array($param->getDefaultValue())) {
					echo ", default value: array(";
					foreach($param->getDefaultValue() as $val) {
						echo "'".$val."'";
						if($val != end($param->getDefaultValue()))
							echo ", ";
					}
					echo ")";
				}
			 	else
					echo ", default value: '".$param->getDefaultValue()."'";
			echo "\n";
		}
	}
?>
