var realtorControllers = angular.module('realtorControllers',[])

.controller('ReiFaxCtrl', function($scope, $rootScope, $ionicSideMenuDelegate, $ionicModal, $ionicPlatform) {
    console.log('reifaxCtrl');
    
    $scope.toggleLeft = function() {
        $ionicSideMenuDelegate.toggleLeft();
    };
    
    $ionicModal.fromTemplateUrl('templates/modal/search.html', {
        scope: $scope,
        animation: 'slide-in-up'
    }).then(function(modal) {
        $scope.modal = modal;
    });
    
    $scope.showSearch = function() {
        $scope.modal.show();
    };
    
    // Cleanup the modal when we're done with it!
    $scope.$on('$destroy', function() {
        $scope.modal.remove();
        $scope.modal2.remove();
        $scope.orderResult.remove();
    });
    
    $ionicModal.fromTemplateUrl('templates/modal/login.html', {
        scope: $scope,
        animation: 'slide-in-up'
    }).then(function(modal) {
        $scope.modal2 = modal;
    });
    
    $scope.showLogin = function() {
        $scope.modal2.show();
    };

    $ionicModal.fromTemplateUrl('templates/popover/order.html', {
        scope: $scope
    }).then(function(modal) {
        $scope.orderResult = modal;
    });

    $scope.showResultOrder = function() {
        console.log('showpopover');
        $scope.orderResult.show();
    };

    $scope.logout = function() {
        $rootScope.user.login = false;
        $rootScope.user.email = '';
    };
    
    $ionicPlatform.registerBackButtonAction(function (event) {
        if($state.current.name=='app.about' || $state.current.name=='app.contact' || $state.current.name=='app.likes' || $state.current.name=='app.login'){
          navigator.app.exitApp();
        }else if($state.current.name=='app.home.map' || $state.current.name=='app.home.result'){
            navigator.app.exitApp();
        }
        else {
          navigator.app.backHistory();
        }
      }, 100);
})

.controller('LoginCtrl', function($scope, $rootScope, $state, users, properties) {
    $scope.login = function(){
        users.init(function(res){
            console.log(res);
            if(res.success){
                $rootScope.user.login = true;
                $rootScope.user.email = res.users;  
                
                properties.resultLike(function(result){
                    $rootScope.like = {
                        'properties': result.likes,
                        'total': result.total
                    };
                    
                    $scope.modal2.hide();
                    
                    $state.go('app.home.map');
                },{
                    'useridr': $rootScope.realtor.userid,
                    'email': $rootScope.user.email
                });
            }
        },{
            'email':    $scope.email,
            'password': $scope.pass,
            'useridr':  $rootScope.realtor.userid
        });
    };
})

.controller('HomeCtrl', function($scope, $rootScope, properties) {
    console.log('HomeCtrl');
    
    $rootScope.drawMap = false;
    
    $scope.onResultTabSelect = function(tab){
        properties.updateList(tab);        
    }

    $scope.$on('$ionicView.enter', function(){
        if($rootScope.tabState == 'map'){
            $rootScope.map.getCenterPins();
        }
    });
    
    $rootScope.tabState = 'map';
    
    $scope.searchParams = {
        'search': '',
        'county': '1',
        'tsearch': 'location',
        'proptype': '',
        'price_low': '',
        'price_hi': '',
        'bed': '-1',
        'bath': '-1',
        'sqft': '-1',
        'pequity': -1,
        'pendes': -1,
        'search_type': 'FS',
        'search_mapa': '-1',
        'occupied': -1
    };
    
    $scope.closeSearch = function() {
        $scope.modal.hide();
        
        properties.search(function(){
            $scope.onResultTabSelect($rootScope.tabState);
        }, $scope.searchParams)
    };
})

.controller('HomeMapCtrl', function($scope, $rootScope, properties) {
    //init map
    console.log('HomeMapCtrl');   
    var containerMapPrincipal = new XimaMap('containerMapPrincipal','containerMapPrincipal_search_latlong','control_mapa_div','_pan','_draw','_poly','_clear','_maxmin','_circle');
    containerMapPrincipal._IniMAP($rootScope.user.latitude, $rootScope.user.longitude);

    $rootScope.map = containerMapPrincipal;
    $rootScope.drawMap = true;
    console.log('HomeMapCtrl - draw');
    properties.updateList($rootScope.tabState);
})

.controller('HomeResultCtrl', function($scope, $rootScope, properties) {
    console.log('HomeResultCtrl');
    $scope.like = function(pid, county) {
        if(!$rootScope.user.login){
            //show login modal
            $scope.showLogin();
        }else{        
            var optLike = {
                'useridr': $rootScope.realtor.userid,
                'email': $rootScope.user.email,
                'pid': pid,
                'county': county
            };
            
            var id='res_like_'+pid;
            
            properties.like(function(res){
                if(res.success){
                    
                    var button = angular.element(document.getElementById(id));
                    button.removeClass('button-assertive');
                    button.addClass('button-balanced');
                    button.html('<i class="icon ion-ios-heart-outline"></i> Save Property');
                    
                    if(res.status == 'L'){ 
                        button.removeClass('button-balanced');
                        button.addClass('button-assertive');
                        button.html('<i class="icon ion-ios-heart"></i> Saved');
                        $rootScope.like.properties[pid]={'pid': pid, 'status': 'L', 'county': county};
                        $rootScope.like.total++;
                    }else{
                        delete $rootScope.like.properties[pid];
                        $rootScope.like.total--;
                    }
                }
            },optLike);
        }
    }
    
    $scope.isLike = function(pid,county) {
        var id='res_like_'+pid;
        var button = angular.element(document.getElementById(id));
        
        if($rootScope.like && $rootScope.like.properties[pid] && $rootScope.like.properties[pid]['status']=='L'){
            button.removeClass('button-balanced');
            button.addClass('button-assertive');
            return '<i class="icon ion-ios-heart"></i> Saved';
        }

        button.removeClass('button-assertive');
        button.addClass('button-balanced');
        return '<i class="icon ion-ios-heart-outline"></i> Save Property';
    }
    
    $scope.getColor = function(status,type){
        var statusesp = status.split('_')[1];
                
        if(type=='Bg'){
            return lsHexCssPoint[statusesp].color;
        }else
            return lsHexCssPoint[statusesp].border;
    }
    
    $scope.getIndex = function(status){
        return status.split('_')[0];
    }

    $scope.orderSearch = function(type){
        console.log(type);
        if($rootScope.orderResult == type)
            $rootScope.dirResult = ($rootScope.dirResult == 'DESC' ? 'ASC' : 'DESC');
        else
            $rootScope.dirResult = 'ASC';

        $rootScope.orderResult = type;

        console.log($rootScope.orderResult, $rootScope.dirResult);

        $scope.orderResult.hide();
        properties.updateList($rootScope.tabState);
    }
})

.controller('LikesCtrl', function($scope, $rootScope, $filter, properties) {
    $scope.like = function(pid, county) {
        var optLike = {
            'useridr': $rootScope.realtor.userid,
            'email': $rootScope.user.email,
            'pid': pid,
            'county': county
        };
        
        var id='res_like_'+pid;
        
        properties.like(function(res){
            if(res.success){
                delete $rootScope.like.properties[pid];
                $rootScope.like.total--;
            }
        },optLike);
    }
    
    $scope.getColor = function(status,type){
        var statusesp = status.split('_')[1];
                
        if(type=='Bg'){
            return lsHexCssPoint[statusesp].color;
        }else
            return lsHexCssPoint[statusesp].border;
    }
    
    $scope.getIndex = function(status){
        return status.split('_')[0];
    }
})

.controller('OverviewCtrl', function($scope, $rootScope, $stateParams, $ionicHistory, $filter, properties) {
    $scope.pid = $stateParams.pid;
    $scope.county = $stateParams.county;
    $scope.imageW = window.innerWidth < 500 ? '100%' : 350;
    $scope.imageH = window.innerWidth < 500 ? 250 : 250;
    $scope.imageShow = true;
    $scope.propertyLike = false;
    
    $scope.myGoBack = function() {
        $ionicHistory.goBack();
    };
    
    $scope.like = function() {
        if(!$rootScope.user.login){
            //show login modal
            $scope.showLogin();
        }else{
            var optLike = {
                'useridr': $rootScope.realtor.userid,
                'email': $rootScope.user.email,
                'pid': $stateParams.pid,
                'county': $stateParams.county
            };
            
            properties.like(function(res){
                if(res.success){
                    $scope.propertyLike = res.status == 'L' ? true : false;
                    
                    var button = angular.element(document.getElementById("like_property_overview"));
                    button.removeClass('button-assertive');
                    button.html('<i class="icon ion-ios-heart-outline"></i>');
                    
                    if($scope.propertyLike){ 
                        button.addClass('button-assertive');
                        button.html('<i class="icon ion-ios-heart"></i>');
                        $rootScope.like.properties[$stateParams.pid]={'pid': $stateParams.pid, 'status': 'L', 'county': $stateParams.county};
                        $rootScope.like.total++;
                    }else{
                        delete $rootScope.like.properties[$stateParams.pid];
                        $rootScope.like.total--;
                    }
                }
            },optLike);
        }
    }
    
    var optLike = {
        'useridr': $rootScope.realtor.userid,
        'email': $rootScope.user.email,
        'pid': $stateParams.pid,
        'county': $stateParams.county
    };
    
    properties.isLike(function(res){
        if(res){
            $scope.propertyLike = true;
            
            var button = angular.element(document.getElementById("like_property_overview"));
            button.addClass('button-assertive');
            button.html('<i class="icon ion-ios-heart"></i>');

        }
    },optLike);
    
    $scope.getColor = function(status,type){
        var statusesp = status.split('_')[1];
                
        if(type=='Bg'){
            return lsHexCssPoint[statusesp].color;
        }else
            return lsHexCssPoint[statusesp].border;
    }
    
    $scope.getIndex = function(status){
        return status.split('_')[0];
    }
    
    $scope.getDateFormat = function(da){
        var y = da.substring(0,4);
        var m = da.substring(4,6);
        var d = da.substring(6,8);
        
        var textDate = y+'-'+m+'-'+d;
        return new Date(textDate);
    }
    
    properties.overview(function(result){
        $scope.overview = result;
        
        if(result.images.length == 0) $scope.imageShow = false;
        
        var optComp = {
            'array_taken': '',
            'bd': result.property.bd,
            'dir': 'ASC',
            'filter': '',
            'id': $stateParams.pid,
            'limit': 50,
            'prop': result.property.xcode,
            'reset': true,
            'sort': 'Distance',	
            'start': 0,
            'status': 'CS,CC',
            'type': 'nobpo',
            'userid': $rootScope.realtor.userid,
            'typeComp': 'comp',
            'jsonapps': true
        };
        
        properties.comparables(function(res){
            $scope.compData = res.records;
        },optComp);
        
        optComp.status = 'A';
        
        properties.comparables(function(res){
            $scope.compActData = res.records;
        },optComp);
        
        optComp.typeComp = 'rental';
        
        properties.comparables(function(res){
            $scope.compRenData = res.records;
        },optComp);
        
        
    }, $scope.county, $scope.pid);
});