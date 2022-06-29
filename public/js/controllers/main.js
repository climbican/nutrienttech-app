ledgedogAdmin
    // =========================================================================
    // Base controller for common functions
    // =========================================================================
    .controller('ledgedogAdminCtrl', function($timeout, $state, $scope, growlService, $http, $window, EnvVars){
        //Welcome Message
        //growlService.growl('Welcome back Mallinda!', 'inverse')

        $scope.urlBase = EnvVars.urlBase;

        $scope.userMessage = function(message, type){
            /**
             * Types info, success, warning, danger, inverse (not sure about the last one, it's black)
             */
            if(type == ''){ type = 'info'}
          growlService.growl(message, type);
            //console.log('growl Message called');
        };

        // Detact Mobile Browser
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
           angular.element('html').addClass('ismobile');
        }

        // By default Sidbars are hidden in boxed layout and in wide layout only the right sidebar is hidden.
        this.sidebarToggle = {
            left: false,
            right: false
        }

        // By default template has a boxed layout
        this.layoutType = 0;

        // For Mainmenu Active Class
        this.$state = $state;

        //Close sidebar on click
        this.sidebarStat = function(event) {
            if (!angular.element(event.target).parent().hasClass('active')) {
                this.sidebarToggle.left = false;
            }
        }

        //Listview Search (Check listview pages)
        this.listviewSearchStat = false;

        this.lvSearch = function() {
            this.listviewSearchStat = true;
        }

        //Listview menu toggle in small screens
        this.lvMenuStat = false;

        //Blog
        this.wallCommenting = [];

        this.wallImage = false;
        this.wallVideo = false;
        this.wallLink = false;

        //Skin Switch
        this.currentSkin = 'lightblue';

        this.skinList = [
            'lightblue',
            'bluegray',
            'cyan',
            'teal',
            'green',
            'orange',
            'blue',
            'purple'
        ]

        this.skinSwitch = function (color) {
            this.currentSkin = color;
        }

        $scope.limit = 0;
        $scope.addElementSet =[];
        $scope.elementSelect = {};
        $scope.cropSelect = {};

        $scope.fetchCrops = function(){
            $http({
                method: 'POST',
                url:  $scope.urlBase + 'admin/crop/list',
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){
                    $scope.cropSelect = response.data;

                });
        };

        $scope.fetchElements = function(){
            $http({
                method: 'POST',
                url:  $scope.urlBase + 'admin/element/list',
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){
                    $scope.elementSelect = response.data;

                });
        };

        $scope.fetchDeficiencyList = function(){
            $http({
                method: 'POST',
                url:  $scope.urlBase + 'admin/deficiency/list',
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){
                   // console.log('deficiencies:: '+angular.toJson(response.data));
                    $scope.deficiencyListAll = response.data;

                });
        };

        $scope.fetchDeficiencyList();

        $scope.fetchElements();
        $scope.fetchCrops();

        $scope.productFetch = function($id){
            $http({
                method: 'POST',
                url:  $scope.urlBase + 'admin/product/elements/lookup/'+$id,
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){

                    var prodDetail = response.data.productDetail;
                    $scope.productName = prodDetail.productName;
                    $scope.subTitle = prodDetail.subTitle;
                    $scope.description = prodDetail.description;
                    $scope.benefits = prodDetail.benefits;
                    $scope.dilution = prodDetail.dilution;
                    $scope.compatibilityType = prodDetail.compatibility;
                    $scope.netContents = prodDetail.netContents;

                    var elements = response.data.elements;
                    var i = 0;
                    angular.forEach(elements, function(v, k){
                        var j = 0;
                        angular.forEach(v, function(v,k){
                           // console.log(k+ ' = '+ v);
                            $scope[k] = v;
                            j++;
                        })
                        i++;
                    });
                });
        };

        $scope.fetchCompatList = function(){
            $http({
                method: 'POST',
                url:  $scope.urlBase + 'admin/compatibility/fetch',
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){
                    //console.log('compat :: '+angular.toJson(response.data.compat));
                    $scope.compatSelect = response.data.compat;

                });
        };

       $scope.fetchCompatList();

        $scope.fetchCompatibility = function($id){

            $http({
                method: 'POST',
                url:  $scope.urlBase + 'admin/compatibility/lookup/'+$id,
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){

                    var prodDetail = response.data.productDetail;
                    $scope.productName = prodDetail.productName;
                    $scope.subTitle = prodDetail.subTitle;
                    $scope.description = prodDetail.description;
                    $scope.benefits = prodDetail.benefits;
                    $scope.dilution = prodDetail.dilution;
                    //doesn't matter because name is being used not ng-model
                    $scope.image1 = prodDetail.image1;
                    $scope.image2 = prodDetail.image2;
                    $scope.image3 = prodDetail.image3;
                    $scope.image4 = prodDetail.image4;

                    var elements = response.data.elements;
                    var i = 0;
                    angular.forEach(elements, function(v, k){
                        var j = 0;
                        angular.forEach(v, function(v,k){
                           // console.log(k+ ' = '+ v);
                            $scope[k] = v;
                            j++;
                        })
                        i++;
                    });
                });
        };

        $scope.fetchDeficiency = function($id){

            $scope.defDescription = '';

            $http({
                method: 'POST',
                url:  $scope.urlBase + 'admin/deficiency/lookup/'+$id,
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){
                    var def = response.data;

                    $scope.nameShort = def.nameShort;
                    $scope.defDescription = def.defDescription;
                    $scope.testContent = def.defDescription;
                    $scope.elementID = def.elementID;
                    $scope.cropID = def.cropID;
                });
        };

        // this is to overcome the issue with the rich text editor set value issue
        $scope.description = '';
        $scope.benefits = '';
        $scope.dilution = '';

        $scope.initializeProdValues = function(descriptionVal, benefitsVal, dilutionVal) {
            $scope.description = descriptionVal;
            $scope.benefits = benefitsVal;
            $scope.dilution = dilutionVal;
        };

        $scope.fetchUser = function(id){
            $http({
                method: 'POST',
                url:  $scope.urlBase + 'admin/profile/fetch/'+id,
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){
                    //console.log('compat :: '+angular.toJson(response.data.compat));
                    var p = response.data.profile;
                    $scope.name = p.name;
                    $scope.email = p.email;
                    $scope.userType = p.userType;

                });
        }; //END FETCH USER

        $scope.removeImage = function(def_id, image_id){
            $http({
                method: 'DELETE',
                url:  $scope.urlBase + 'admin/deficiency/remove/image/' + def_id + '/' + image_id,
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){
                    // console.log('this is the response ' + JSON.stringify(response));
                    //remove the image from the UI ?? innerHTML
                    const frameToRemove = document.getElementById('image_row_'+image_id);
                    frameToRemove.remove();
                });
        };

        $scope.approveImage = function(image_id){
            $http({
                method: 'GET',
                url:  $scope.urlBase + 'admin/deficiency/community_image/approve/' + image_id + '/0',
                timeout: 4000,
                headers: {'Content-Type': 'application/json'}
            })
                .then(function(response){
                    // console.log('this is the response ' + JSON.stringify(response));
                    //remove the image from the UI ?? innerHTML
                    $window.location.reload();
                });
        };
    })

    // =========================================================================
    // Header
    // =========================================================================
    .controller('headerCtrl', function($timeout){ //, messageService
        // Top Search
        this.openSearch = function(){
            angular.element('#header').addClass('search-toggled');
            angular.element('#top-search-wrap').find('input').focus();
        }

        this.closeSearch = function(){
            angular.element('#header').removeClass('search-toggled');
        }
        /** Get messages and notification for header
        this.img = messageService.img;
        this.user = messageService.user;
        this.user = messageService.text;

        this.messageResult = messageService.getMessage(this.img, this.user, this.text);**/

        //Clear Notification
        this.clearNotification = function($event) {
            $event.preventDefault();

            var x = angular.element($event.target).closest('.listview');
            var y = x.find('.lv-item');
            var z = y.size();

            angular.element($event.target).parent().fadeOut();

            x.find('.list-group').prepend('<i class="grid-loading hide-it"></i>');
            x.find('.grid-loading').fadeIn(1500);
            var w = 0;

            y.each(function(){
                var z = $(this);
                $timeout(function(){
                    z.addClass('animated fadeOutRightBig').delay(1000).queue(function(){
                        z.remove();
                    });
                }, w+=150);
            })

            $timeout(function(){
                angular.element('#notifications').addClass('empty');
            }, (z*150)+200);
        }

        // Clear Local Storage
        this.clearLocalStorage = function() {

            //Get confirmation, if confirmed clear the localStorage
            swal({
                title: "Are you sure?",
                text: "All your saved localStorage values will be removed",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#F44336",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function(){
                localStorage.clear();
                swal("Done!", "localStorage is cleared", "success");
            });
        }

        //Fullscreen View
        this.fullScreen = function() {
            //Launch
            function launchIntoFullscreen(element) {
                if(element.requestFullscreen) {
                    element.requestFullscreen();
                } else if(element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if(element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                } else if(element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                }
            }

            //Exit
            function exitFullscreen() {
                if(document.exitFullscreen) {
                    document.exitFullscreen();
                } else if(document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if(document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }

            if (exitFullscreen()) {
                launchIntoFullscreen(document.documentElement);
            }
            else {
                launchIntoFullscreen(document.documentElement);
            }
        }
    })
    //=================================================
    // LOGIN
    //=================================================
    .controller('loginCtrl', function(){
        //Status
        this.login = 1;
        this.register = 0;
        this.forgot = 0;
    })
    // =========================================================================
    // COMMON FORMS
    // =========================================================================
    .controller('formCtrl', function(){
        //Input Slider
        this.nouisliderValue = 4;
        this.nouisliderFrom = 25;
        this.nouisliderTo = 80;
        this.nouisliderRed = 35;
        this.nouisliderBlue = 90;
        this.nouisliderCyan = 20;
        this.nouisliderAmber = 60;
        this.nouisliderGreen = 75;

        //Color Picker
        this.color = '#03A9F4';
        this.color2 = '#8BC34A';
        this.color3 = '#F44336';
        this.color4 = '#FFC107';
    });

