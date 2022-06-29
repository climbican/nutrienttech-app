ledgedogAdmin
    .config(function ($stateProvider, $urlRouterProvider){
        $urlRouterProvider.otherwise("/404");


        $stateProvider
        
            //------------------------------
            // HOME
            //------------------------------

            .state('404',{
                url: '/404',
                templateUrl: '404.html'
            })
            //------------------------------
            // BLOG LIST VIEW
            //------------------------------
            .state('product', {
                url: '/product',
                templateUrl: 'views/common-2.html'
            })

            .state('product.list', {
                url: '/list',
                templateUrl: 'views/product_list.html'
            })

            .state('product.add', {
                url:'/add',
                templateUrl: 'views/blog-add.html',
                resolve: {
                    loadPlugin: function($ocLazyLoad) {
                        return $ocLazyLoad.load ([
                            {
                                name: 'css',
                                insertBefore: '#app-level',
                                files: [
                                    'vendors/bower_components/nouislider/jquery.nouislider.css',
                                    'vendors/farbtastic/farbtastic.css',
                                    'vendors/bower_components/summernote/dist/summernote.css',
                                    'vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
                                    'vendors/bower_components/chosen/chosen.min.css'
                                ]
                            },
                            {
                                name: 'vendors',
                                files: [
                                    'vendors/input-mask/input-mask.min.js',
                                    'vendors/bower_components/nouislider/jquery.nouislider.min.js',
                                    'vendors/bower_components/moment/min/moment.min.js',
                                    'vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                                    'vendors/bower_components/summernote/dist/summernote.min.js',
                                    'vendors/fileinput/fileinput.min.js',
                                    'vendors/bower_components/autosize/dist/autosize.min.js',
                                    'vendors/bower_components/chosen/chosen.jquery.js',
                                    'vendors/bower_components/angular-chosen-localytics/chosen.js',
                                    'vendors/bower_components/angular-farbtastic/angular-farbtastic.js'
                                ]
                            }
                        ])
                    }
                }
            })
            .state('blog.update', {
                url: '/update',
                templateUrl: 'views/blog-update.html'
            })

            //------------------------------
            // PAGES
            //------------------------------
            
            .state ('pages', {
                url: '/pages',
                templateUrl: 'views/common-2.html'
            })
            //Profile

            .state('pages.change-password', {
                url: 'change-password',
                templateUrl: 'views/change-password.html'
            })
        

    });
