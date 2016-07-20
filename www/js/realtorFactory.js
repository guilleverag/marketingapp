angular.module('realtorFactory', [])
    .factory('properties', function($http, $httpParamSerializer, $ionicLoading, $rootScope){
        var resultList = null, hasSearch = false, property = null, county = null, pid = null;
        var resOrder = '', resDir = 'ASC';

        var searchBaseParams = {
            'search': '33025',
            'county': '1',
            'tsearch': 'location',
            'proptype': '01',
            'price_low': '',
            'price_hi': '',
            'bed': -1,
            'bath': -1,
            'sqft': -1,
            'pequity': -1,
            'pendes': -1,
            'search_type': 'FS',
            'search_mapa': '-1',
            'occupied': -1
        };
        
        var properties = {};
        
        properties.search = function (callback, params){      
            resultList = null, hasSearch = true;
            
            $http({
                method: 'POST',
                url: 'http://www.reifax.com/properties_coresearch.php',
                data: $httpParamSerializer(params),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(callback);
        }; 
        
        properties.isSearch = function(){
            return hasSearch;
        };

        properties.list = function(callback,limit,order,dir){
            limit = limit ? limit : 75;
            order = order ? order : '';
            dir = dir ? dir : 'ASC';
            
            if(resultList && resOrder==order && resDir == dir){
                callback(resultList);
            }else if(hasSearch){
                $http({
                    method: 'POST',
                    url: 'http://www.reifax.com/coresearch.php?resultType=advance&systemsearch=basic',
                    data: "ResultTemplate=-1&MarketingModule=true&limit="+limit+"&sort="+order+"&dir="+dir,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    cache: true
                }).success(function(result){
                    resultList = result;
                    
                    callback(result);
                });
            }else{
                properties.search(function(){
                    $http({
                        method: 'POST',
                        url: 'http://www.reifax.com/coresearch.php?resultType=advance&systemsearch=basic',
                        data: "ResultTemplate=-1&MarketingModule=true&limit="+limit+"&sort="+order+"&dir="+dir,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        cache: true
                    }).success(function(result){
                        resultList = result;
                        
                        callback(result);
                    });
                }, searchBaseParams);
            }
        };

        properties.updateList = function(tab){
            console.log('onResultTabSelect', tab);
            $rootScope.tabState = tab;

            $ionicLoading.show({
                template: 'Loading properties data...'
            });

            properties.list(function(result){

                $rootScope.propertiesResult = result.records;
                $rootScope.resultPropertiesFound = 'Properties Found: '+(result.total>75 ? 75 : result.total);

                if($rootScope.tabState == 'map' && $rootScope.map && $rootScope.drawMap){
                    console.log('Map draw');
                    var resultTotal = (result.total>75 ? 75 : result.total);
                    var records = result.records;

                    $rootScope.map.borrarTodoMap();

                    for(i=0; i<resultTotal; i++){
                        var status = records[i]['status'];
                        var index = status.split('_');
                        status = index[1];

                        $rootScope.map.addPushpinInfoboxMini(
                            records[i]['parcelid'],
                            records[i]['latitude'],
                            records[i]['longitude'],
                            records[i]['address'],
                            records[i]['lprice'],
                            records[i]['beds'],
                            records[i]['bath'],
                            records[i]['sqft'],
                            lsImgCss[status]['explain'],
                            '#/app/overview/'+(records[i]['county'])+'/'+(records[i]['parcelid']),
                            lsHexCssPoint[status],
                            records[i]['pid'],
                            records[i]['county'],
                            records[i]['imagenxima']
                        );
                    }

                    $rootScope.map.getCenterPins();
                }

                $ionicLoading.hide();

            },75,$rootScope.orderResult, $rootScope.dirResult);
        }
        
        properties.overview = function (callback, county, pid){
            if(properties.county == county && properties.pid == pid && properties.property){
                callback(properties.property);
            }else{
                properties.county = county;
                properties.pid = pid;
                properties.property = null;
                 
                $http({
                    method: 'POST',
                    url: 'http://www.reifax.com/mreifax/properties_overview_json.php',
                    data: "county="+county+"&pid="+pid,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function(result){
                    properties.property = result;
                    callback(result);
                });
            }
        }; 
        
        properties.comparables = function (callback, params){
            var urlComp = 'http://www.reifax.com/mreifax/properties_look4comparables.php';
            if(params.typeComp == 'rental') urlComp = 'http://www.reifax.com/mreifax/properties_look4rental.php';
                
            $http({
                method: 'POST',
                url: urlComp,
                data: $httpParamSerializer(params),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(result){                
                callback(result);
            });
        }; 
        
        properties.like = function (callback, params){
            $http({
                method: 'POST',
                url: 'http://www.reifax.com/marketing/resources/php/properties_like.php',
                data: $httpParamSerializer(params),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(result){
                callback(result);
            });
        }; 
        
        properties.isLike = function (callback, params){
            $http({
                method: 'POST',
                url: 'http://www.reifax.com/marketing/resources/php/properties_like.php',
                data: $httpParamSerializer(params)+'&type=is_like',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(result){
                callback(result.success);
            });
        }; 
        
        properties.resultLike = function (callback, params){
            $http({
                method: 'POST',
                url: 'http://www.reifax.com/marketing/resources/php/properties_like.php',
                data: $httpParamSerializer(params)+'&type=result',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(result){
                callback(result);
            });
        };
                
        return properties;
    })
    
    .factory('realtor', function($http, $httpParamSerializer){        
        var realtorData = null;
        var realtor = {};
        
        realtor.init = function (callback, useridr){                  
            if(realtorData){
                callback(realtorData);
            }else{
                $http({
                    method: 'POST',
                    url: 'http://www.reifax.com/marketing/resources/php/realtors_data.php',
                    data: 'useridr='+useridr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function(result){
                    realtorData = result.profile;
                    console.log(realtorData);
                    callback(realtorData);
                });
            }
        }; 
                        
        return realtor;
    })
    
    .factory('users', function($http, $httpParamSerializer){        
        var usersData = null;
        var users = {};
        
        users.init = function (callback, params){                  
            if(usersData){
                callback(usersData);
            }else{
                $http({
                    method: 'POST',
                    url: 'http://www.reifax.com/marketing/resources/php/users_data.php',
                    data: $httpParamSerializer(params)+'&type=login',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function(result){
                    usersData = result;
                    
                    callback(usersData);
                });
            }
        }; 
                        
        return users;
    }
);