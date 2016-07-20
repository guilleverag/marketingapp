var realtorApp = angular.module('realtorApp', ['ionic', 'ngCordova', 'realtorControllers', 'realtorFactory'])

.run(function($rootScope, realtor, $cordovaGeolocation) {
    //Variables Generales del Realtor
    $rootScope.realtor = {
        'userid': 73
    };
    
    realtor.init(function(r){
        $rootScope.realtor = r;
    },$rootScope.realtor.userid);
    
    console.log('realtor init');
    //Variables Generales del Usuario
    $rootScope.user = {
        'email': null,
        'latitude': 26.1462087631,
        'longitude': -80.4833602905,
        'login': false
    };
    
    console.log('user init');
    
    //init ubication
    var posOptions = {timeout: 10000, enableHighAccuracy: false};
    
    $cordovaGeolocation.getCurrentPosition(posOptions)
    .then(function (position) {
        $rootScope.user.latitude  = position.coords.latitude
        $rootScope.user.longitude = position.coords.longitude
    }, function(err) {
        // error
        console.log('ERROR location', err);
    });
    /*navigator.geolocation.getCurrentPosition(function(pos) {
        $rootScope.user.latitude  = pos.coords.latitude;
        $rootScope.user.longitude = pos.coords.longitude;
    });*/
    
    console.log('position init');

    $rootScope.orderComboResult = [
        {'name':'Address', 'id':'address'},
        {'name':'Mlnumber', 'id':'mlnumber'},
        {'name':'Folio #', 'id':'parcelid'},
        {'name':'Year Built', 'id':'yrbuilt'},
        {'name':'DOM', 'id':'dom'},
        {'name':'Sqft', 'id':'lsqft'},
        {'name':'Beds', 'id':'beds'},
        {'name':'Baths', 'id':'bath'},
        {'name':'City', 'id':'city'},
        {'name':'Zip', 'id':'zip'},
        {'name':'Price', 'id':'price'},
        {'name':'Property Types', 'id':'xcoded'}
    ];

    $rootScope.orderResult = '';
    $rootScope.dirResult = 'ASC';
})

.constant('app_config', {
    appName: 'Realtors App',
    appVersion: 1.0
    //apiUrl: 'http://52.10.82.93/rewards/api/v1/reward_api.php'
})

.config(function($stateProvider, $urlRouterProvider, $ionicConfigProvider) {

  $stateProvider
    .state('app', {
      url: "/app",
      abstract: true,
      templateUrl: "templates/menu.html"
    })
    .state('app.about', {
      url: "/about",
      views: {
        'menuContent' :{
            templateUrl: "templates/about.html"
        }
      }
    })
    .state('app.contact', {
      url: "/contact",
      views: {
        'menuContent' :{
            templateUrl: "templates/contact.html"
        }
      }
    }) 
    .state('app.likes', {
      url: "/saved",
      views: {
        'menuContent' :{
            templateUrl: "templates/likes.html",
            controller: 'LikesCtrl'
        }
      }
    })  
    .state('app.login', {
      url: "/login",
      views: {
        'menuContent' :{
            templateUrl: "templates/login.html"
        }
      }
    })  
    .state('app.home', {
      abstract: true,  
      views: {
        'menuContent' :{
            templateUrl: "templates/home.html",
            controller: 'HomeCtrl'
        }
      }
    })    
   .state('app.home.map', {
      url: "/home/map",
      views: {
        'map-tab': {
          templateUrl: "templates/map.html",
          controller: 'HomeMapCtrl'
        }
      }
    })
    .state('app.home.result', {
      url: "/home/result",
      views: {
        'result-tab': {
          templateUrl: "templates/result.html",
          controller: 'HomeResultCtrl'
        }
      }
    })
    .state('app.overview', {
      url: "/overview/:county/:pid",
      views: {
        'menuContent': {
          templateUrl: "templates/overview.html",
          controller: 'OverviewCtrl'
        }
      }
    })
    
  $urlRouterProvider.otherwise("/app/home/map");
  
  var jsScrolling = (ionic.Platform.isAndroid() ) ? false : true;
  $ionicConfigProvider.scrolling.jsScrolling(jsScrolling);
});