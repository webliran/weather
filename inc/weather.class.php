<?php 
/**
* Photos
* 
* @author Liran Hecht <liranhecht@gmail.com>
*/

class Weather{
    
    public $weatherInfo;
    public $weatherApiUrl = "https://ims.data.gov.il/sites/default/files/isr_cities.xml";
    public $loactionId;

    function __construct()
    {
      
        // get weather info
        $xmlWeatherInfo = file_get_contents($this->weatherApiUrl);
        
        $this->weatherInfo = simplexml_load_string($xmlWeatherInfo);

        // set defalut location id
        $this->loactionId = 510;

    }


    /*
    setDefaultLocationID
    @param locationId int
    return null
    */
    function setLocationID($locationId){
        $this->loactionId = $locationId;
    }

    /*
    getDateUpdated
    return date string
    */
    public function getDateUpdated(){
        foreach ($this->weatherInfo->children() as $child)
        {
            // get xml node Identification that holds date time info
            if($child->getName() === "Identification")
                return $child->IssueDateTime->__toString();
        }
        
        // error no date found
        return null;
    }

    /*
    getPageTitle
    return title string
    */
    public function getPageTitle(){
        foreach ($this->weatherInfo->children() as $child)
        {
            // get xml node Identification that holds title
            if($child->getName() === "Identification")
                return $child->Title->__toString();
        }
        
        // error no date found
        return null;
    }

    /*
    buildCityArray
    return city id and name array
    */
    public function buildCityArray(){
        $cityArray = [];
        
        foreach ($this->weatherInfo->children() as $child)
        {
            // get xml node Location that holds data about each location
            if($child->getName() === "Location"){

                // check if holds meta data for loaction
                if($metaData = $child->LocationMetaData){
                    $cityArray[$metaData->LocationId->__toString()] = $metaData->LocationNameHeb->__toString();
                }
            }
                
        }      
        return $cityArray;
    }


    /*
    getSelectedLocationNameByLocationId
    return city name string
    */
    public function getSelectedLocationNameByLocationId(){
        $locationName = "";

        foreach ($this->weatherInfo->children() as $child)
        {
            // get xml node Location that holds data about each location
            if($child->getName() === "Location"){

                // check if current location and if node holds meta data for loaction
                $metaData = $child->LocationMetaData;
                if($metaData && $metaData->LocationId->__toString() == $this->loactionId){
                    $locationName = $metaData->LocationNameHeb->__toString();
                }
            }
                
        }      
        return $locationName;
    }

    /*
    getTodayTemp
    @param type string
    return high and low temp array[int] OR high and low temp string
    */
    public function getTodayTemp($type = "array"){

        $lowTemp = "";
        $highTemp = "";

        foreach ($this->weatherInfo->children() as $child)
        {
            // get xml node Location that holds data about each location
            if($child->getName() === "Location"){

                // check if current location
                $metaData = $child->LocationMetaData;
                if($metaData && $metaData->LocationId->__toString() == $this->loactionId){
                    // get location temp only for today
                    $locationData = $child->LocationData->TimeUnitData[0];
                    if($locationData){
                        // loop todays data and get high and low temp
                        foreach ($locationData->children() as $locationDataChild){
                            if($locationDataChild->ElementName->__toString() == "Minimum temperature")   
                                $lowTemp = $locationDataChild->ElementValue->__toString();

                            if($locationDataChild->ElementName->__toString() == "Maximum temperature")   
                                $highTemp = $locationDataChild->ElementValue->__toString();
                            
                            
                        }
                    }

                }
            }
                
        }   
        
        if($type == "html" ){
            $seperate = $highTemp ? "-" : "";
            return "<div class='temp-holder'> <span class='low-temp'>{$lowTemp}</span> {$seperate} <span class='high-temp'>{$highTemp}</span> </div>";
        } else if("array"){
            return [$lowTemp,$highTemp];
        }
        
    }


    /*
    getTempImage
    return image by temp url string
    */
    public function getTempImage(){
        if(!$tempArray = $this->getTodayTemp("array") )
            return;

        $imageUrl = "";
        // get average temp
        $averageTemp = array_sum($tempArray)/count($tempArray);
        
        // check average temp and return image of sun or rain
        if($averageTemp > 12){
            $imageUrl = "/weather/assets/img/sun.jpg";
        } else {
            $imageUrl = "/weather/assets/img/rain.jpg";
        }

        return $imageUrl;
    }


}