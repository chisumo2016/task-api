### BUILDING AN REST API WITH LARAVEL

## INSTALL LARAVEL BREEZE
    - Install Laravel Breeze
        composer require laravel/breeze --dev
        php artisan breeze:install api

## INSTALL LARAVEL SANCTUM
        php composer require laravel/sanctum 
        php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

### CREATE MODELS AND MIGRATION
    Laravel Sanctun installed by default.
    Create an Article Model and Migration
        php artisan make:model Article -m  
    User can have many articles
        1: Many
    Articles belongs to a User relationshop
    Setup the article Model
        Two options
        1:  
            protected  $table = 'articles';
        2:
            const  TABLE = 'articles';
            protected  $table = SELF::TABLE;
    Mass assigment on Article Model

### RELATIONS TRAITS
    - Gonna set the relationship btn user and article
    - Create a folder called Traits
            Traits/HasAuthour.php
            Traits/ModelHelpers.php :If an aouthor has written an articles
    Implement the logic to all traits 
    Create a relationship to user model
    Use traits in the article model
             use HasAuthor;
            use ModelHelpers;

### CREATE FACTORIES AND SEEDERS
    - Create factory for Article
         php artisan make:factory ArticleFactory  
    - Create seeder for Articles
        php artisan make:seed ArticlesTableSeeder  
    - Create seeder for Users
         php artisan make:seed UsersTableSeeder
    - Wtite all logics in both factory
    - Wtite all logics in both seeders table
    - Call all seeder in DatabaseSeeder
    - Seed into databasee
        php artisan migrate:fresh --seed    

## CREATE CONTROLLER
    - Create a controllers
        php artisan make:controller API/V1/ArticleController -m Article --api 
    - ArticleController Methods
            Index
            Store
            Show
            Update
            Delete
    - Create AuthorController
        php artisan make:controller API/V1/AuthorController 

## API RESOURCE
    - To generate two api resources
    - Create an article resource
        php artisan make:resource V1/ArticleResource 
        php artisan make:resource V1/AuthorResource  
        php artisan make:resource V1/ArticleCollection -c  
    - Resource extends to JsonResponce
    - Colleccction extends to ResourceCollection
    -To make use of collectionn let open the ArticleController
        retun  new ArticleCollection(Article::all());
    - To send type of data to our client in the ArticleCollection
             'data' => $this->collection,
             with(){}
             withResponse(){}
    - Stucture we send to a front end.
    - In the ArticleRessource we need to defined what we can return to our clients.
            Author can have many articles
            Article belong to User
            Define  type, attributes, relatioships , links 
            add two methods with(){} withResponse(){}

    - Open Author Response Resource, retur certain fields
            Stucture of data we send to our clients

## API ROUTES
    - Add the route in api files
    - First before starting the writing the routes  we should understand the RouteService Provider
        We want to do versioning like V1 http: blog.test/api/V1/articles
        Add the versioning on RouteServiceProvider
            Two options of versioning
                1: In RouteServiceProvider
                        Route::middleware('api.v1')
                            ->prefix('api')
                            ->group(base_path('routes/api_v1.php'));

                2: In api route file
    - Get Articles either by ID or Slug , we can implement this ligic into RouteServiceProvider
            Route::bind('article',function ($value){
            return \App\Models\Article::where('id', $value)
                ->orWhere('slug', $value)->firstOrFail();
        });

### POSTMAN & CUSTOM EXCEPTION
    - Need top create a workplace  Academy
    - Create a collections  call  ArticleApi
                set an authentication on the collections itself ArticleApi
                Everything inside the collection will use that authentiocations
    - Add the request
            Get All Articless
             GET:    http://api-book-project.test/api/v1/articles
                
            Copy all url , under collection itself. 
            Tabs:    Autthorization |  Pre-request Script | Test  | Variables  | Runs
            Click variables to create a variables in postman
                VARIABLE        INITIAL VALUE   CURRENT VALUE 
                APP_URL                         http://api-book-project.test/
                FULL_URL                        http://api-book-project.test/api/v1
            click Save Button
    - GET {{ FULL_URL }}/articles
                {{FULL_URL}}/articles
    - Next thing under the headers - way to receive the data
         accept    : application/jsons
    - Disable middleware
    - Get single article
            GET:    http://api-book-project.test/api/v1/articles/1
                    {{FULL_URL}}/articles/1
    - If article isn't available , we need to send a right exception to a user. NotFoundHttpException
        app/Exceptions/Handler.php  on file you can add the error message .
            
    - Test 
        GET: {{FULL_URL}}/articles/1

### CONTROLLER METHODS
        - Store
        - Update
        - Delete
    - Make a customs request  or validate inside the controller
    - Implemennt all the logic to all methods.
    - In delete logic we have two  ways to implement.
            return  response()->json(null, 204);
            return  response()->setStatusCode(204);

## TEST ENDPOINTS WITH POSTMAN
    - Postman is API client
    - we're going to test all the endpoints of articles.
                    GET:    http://api-book-project.test/api/v1/articles
                    GET:    http://api-book-project.test/api/v1/articles/1
                    POST:   http://api-book-project.test/api/v1/articles
                        HEADER  
                        Accept               application/json
                
                        BODY (Send the data to the server)
                        x-www-form-urlencoded
                            KEY                 VALUE

                    PUT:   http://api-book-project.test/api/v1/articles/11
                        HEADER  
                        Accept               application/json
                
                        BODY (Send the data to the server)
                        x-www-form-urlencoded
                            KEY                 VALUE
                            n/a                  n/a

                    DEL:   http://api-book-project.test/api/v1/articles/11
                        HEADER  
                        Accept               application/json
                
                        BODY (Send the data to the server)
                        x-www-form-urlencoded
                            KEY                 VALUE

### SANCTUM - BEARER TOKEN
    - Add the middleware in api route file.
        'middleware' => 'auth:sanctum',
    - 401 Unautherntocated
    - Create Auth login (create with laravel breeze installation)
            POST :  http://api-book-project.test/api/login
             HEADER  
                Accept                      application/vnd.api+json
                Content-Type              application/vnd.api+json

            BODY (Send the data to the server)
                        form-data
                            KEY                 VALUE
                            email
                            password
    - when we hit the endpoint , return endpoint , with token
        Books API 
            Authorization
                SELECT   Type  : Bearer Token

    - Open the AuthentticatedSessionController 
            $user = Auth::user();// $user = \auth()->user();
            return response()->json([
    
            ]);

            $user = \auth()->user();
        return response()->json([
                'success' => true,
                'data' =>[
                    /**This is the token we gonna use to authenticated the user*/
                    'token'   => $user->createToken($user->name())->plainTextToken,
                    'name'    => $user->name()
                ],
                    'message' => 'User Logged in'
        ]);
    - Afteer that ,go postman and authenticate the user
    ERROR:    "message": "CSRF token mismatch.",

## POSTMAN - "CSRF TOKEN MISMATCH"
    - How to fix the token mismatch
        "message": "CSRF token mismatch."
        SOLUTION:
        Go to verifyCsrfToken and add
              protected $except = [
                "/api/*",
                '/login'
            ];
        To run in localhost server
            php artisan serve
            This were the laravel will store the cookie
                [http://127.0.0.1:8000]. 

    Pre-request Script of Postman
        pm.sendRequest({
         url : 'http://127.0.0.1:8000/sanctum/csrf.cookie',
            method: 'GET'
        }, function(error, response, [cookies]){
        console.log(cookies)
        })
    Set Variable in the collectiion
        pm.sendRequest({
            url : 'http://127.0.0.1:8000/sanctum/csrf.cookie',
            method: 'GET'
        }, function(error, response, [cookies]){
        console.log(response.headers)
        pm.collectionVariables.set('csrf-token',cookies.get('XSRF-TOKEN'))
        })

    POST:  http://127.0.0.1:8000/LOGIN

    X-XSRF-TOKEN        {{csrf-token}}

## AUTH USER , USER RESOURCE AND ROUTE
    - create the invokable controller 
        php artisan make:controller API/v1/UserController -i   
    - Create user Resource


### API RESPONSE TRAIT (Code reuse in single inheritance)
- Create a HttpResponses file app/Traits/HttpResponses.php
- Namespace is the path to this file
- success accept three parameters
    - $data - information we're going to send to a user
    - $message - null
    - $code - 200
- We're going use inside the controller
    - Generate controller
        - php artisan make:controller API/AuthController
- We can access the traits inside the controller use HttpResponses;
  - Create the Task Controller with resource
    - php artisan make:controller API/TasksController -r
    - Create the Task Model and Migration
      - php artisan make:model Task -m 
  - Add the route file for tasks

### REGISTRATION  
 - Register Functionality
     -In the user Model we should have  use HasApiTokens; 
   - Validate the incoming request via register()
       php artisan make:request API/StoreUserRequest   
   - Write a logic into register AuthController

### LOGIN
    Implement the register funcctionality
        Send back an error message
        Validate the user
            php artisan make:request API/LoginUserRequest  
        User should be able to login
        New token will be generated, can be used to restrict the access routes
        Write logic inside the AuthController in login method

## PROTECTING ROUTES
    Two types of routes
        Public Route
        Protected Route
    We're going to use the middleware of auth:sanctum, to protected authennticated routes
    TEST AN API
        ACCEPT 
        CONTENT-TYPE
     GET: http://tasks.test/api/tasks
     401 Unauthorized , we're using laravel sanctum middleware
    
    We need to user Beearer Token  , pass the token into Authorization Tab on postman
    Token must protected  for all cost
        SET AS NEW VARIABLE
            Name: Sanctum_Token
            Value: Token
            Scope: Global
    
        





    

