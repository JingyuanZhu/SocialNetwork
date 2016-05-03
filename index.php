<?php 


$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
  __DIR__.'/models/'
))->register();
//connect database
$di = new \Phalcon\DI\FactoryDefault();
$di->set('db',function(){
  return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
    'host'  => 'localhost',
    'username'=> 'root',
    'password'=> 'root',
    'dbname' => 'DatabaseProject'
  ));
});

$app = new \Phalcon\Mvc\Micro($di);

//GET User
$app->get('/api/user',function() use($app){
  $phql = "SELECT * FROM User";
  $users = $app->modelsManager->executeQuery($phql);
  $data = array();
  foreach($users as $user){
    $data[] = array(
    'UserId' => $user->UserId,
    'Age' => $user->Age,
    'Image' => $user->Image,
    'Zipcode' => $user->Zipcode,
    'Email' => $user->Email,
    'Password' => $user->Password,
    'Starttime' => $user->Starttime
    );
  }
  echo json_encode($data);
});



//GET User by Primary Key
$app->get('/api/user/{UserId}',function($UserId) use ($app){

  $phql = "SELECT * FROM User WHERE UserId LIKE :UserId: ORDER BY UserId";
  $users = $app->modelsManager->executeQuery($phql,array(
    'UserId' => '%'. $UserId .'%'
  ));

  $data = array();
  foreach($users as $user){
    $data[] = array(
    'UserId' => $user->UserId,
    'Age' => $user->Age,
    'Image' => $user->Image,
    'Zipcode' => $user->Zipcode,
    'Email' => $user->Email,
    'Password' => $user->Password,
    'Starttime' => $user->Starttime
   );
  }
  echo json_encode($data);

});
//POST User
$app->post('/api/user',function() use ($app){
  $user = $app->request->getJsonRawBody();

  $phql = "INSERT INTO User (UserId, Age, Image, Zipcode, Email, Password, Starttime) VALUES (:UserId:, :Age:, :Image:, :Zipcode:, :Email:, :Password:, :Starttime:)";
  $status = $app->modelsManager->executeQuery($phql,array(
    'UserId' => $user->UserId,
    'Age' => $user->Age,
    'Image' => $user->Image,
    'Zipcode' => $user->Zipcode,
    'Email' => $user->Email,
    'Password' => $user->Password,
    'Starttime' => $user->Starttime
  ));

  $response = new Phalcon\Http\Response();
  if($status->success() == true){
    $response->setStatusCode(201,'Create New User');
    $user->UserId = $status->getModel()->UserId;

    $response->setJsonContent(array('status'=>'ok','data'=>$user));
  }else{
    $response->setStatusCode(409,'Conflict');

    $errors = array();
    foreach($status->getMessages() as $message){
      $errors[] = $message->getMessage();
    }
    $response->setJsonContent(array('status'=>'ERROR','data'=>$errors));
  }
  return $response;

});

//Update User
$app->put('/api/user/{UserId}',function($UserId) use ($app){
  $user = $app->request->getJsonRawBody();
  $phql = "UPDATE User SET UserId = :UserId:, Age = :Age:, Image = :Image:, Zipcode = :Zipcode:, Email = :Email:, Password = :Password:, Starttime = :Starttime: WHERE UserId = :UserId: ";
  $status = $app->modelsManager->executeQuery($phql,array(
    'UserId' => $user->UserId,
    'Age' => $user->Age,
    'Image' => $user->Image,
    'Zipcode' => $user->Zipcode,
    'Email' => $user->Email,
         'Starttime' => $user->Starttime
  ));
// Create a response
    $response = new Response();

    // Check if the insertion was successful
    if ($status->success() == true) {
        $response->setJsonContent(
            array(
                'status' => 'OK'
            )
        );
    } else {

        // Change the HTTP status
        $response->setStatusCode(409, "Conflict");

        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(
            array(
                'status'   => 'ERROR',
                'messages' => $errors
            )
        );
    }

    return $response;

});

//DELETE
$app->delete('/api/users/{UserId}',function($UserId) use ($app){

  $phql = "DELETE FROM User WHERE UserId = :UserId: ";
  $status = $app->modelsManager->executeQuery($phql,array(
      'UserId' => $UserId
    ));

  if($status->success() == true){
    $response = array('status'=>'OK');
  }else{
    $this->response->setStatusCode(500,'Internal Error')->setHeaders();

    $errors = array();
    foreach($status->getMessages() as $message){
      $errors[] = $message->getMessage();
    }
    $response = array('status'=>'Error','data'=>$errors);
  }
    return $response;
});

//GET Post
$app->get('/api/news',function() use($app){

  $phql = "SELECT * FROM News";
  $news = $app->modelsManager->executeQuery($phql);
  $data = array();
  foreach($news as $new){
    $data[] = array(
    'PostId' => $new->PostId,
    'UserId' => $new->UserId,
    'Entry' => $new->Entry,
    'Image' => $new->Image,
    'Video' => $new->Video,
    'Posttime' => $new->Posttime,
    'LocationId' => $new->LocationId,
    'Setting' => $new->Setting,
    'Ilikeit' => $new->Ilikeit
    );
  }
  echo json_encode($data);
});
//search User's Post
$app->get('/api/news/{UserId}',function($UserId) use ($app){

  $phql = "SELECT * FROM News WHERE UserId LIKE :UserId: ORDER BY UserId";
  $news = $app->modelsManager->executeQuery($phql,array(
    'UserId' => '%'. $UserId .'%'
  ));

  $data = array();
  foreach($news as $new){
    $data[] = array(
    'PostId' => $new->PostId,
    'UserId' => $new->UserId,
    'Entry' => $new->Entry,
    'Image' => $new->Image,
    'Video' => $new->Video,
    'Posttime' => $new->Posttime,
    'LocationId' => $new->LocationId,
    'Setting' => $new->Setting,
    'Ilikeit' => $new->Ilikeit
   );
  }
  echo json_encode($data);
});

//Post Post
$app->post('/api/news',function() use ($app){
  $news = $app->request->getJsonRawBody();
  $phql = "INSERT INTO News (PostId, UserId, Entry, Image, Video, Posttime, LocationId, Setting, Ilikeit) VALUES (:PostId:, :UserId:, :Entry:, :Image:, :Video:, :Posttime:, :LocationId:, :Setting:, :Ilikeit:)";
  $status = $app->modelsManager->executeQuery($phql,array(
    'PostId' => $news->PostId,
    'UserId' => $news->UserId,
    'Entry' => $news->Entry,
    'Image' => $news->Image,
    'Video' => $news->Video,
    'Posttime' => $news->Posttime,
    'LocationId' => $news->LocationId,
    'Setting' => $news->Setting,
    'Ilikeit' => $new->Ilikeit
  ));

  $response = new Phalcon\Http\Response();
    if ($status->success() == true) {
        $response->setStatusCode(201, "Created");

        $news->PostId = $status->getModel()->PostId;

        $response->setJsonContent(
            array(
                'status' => 'OK',
                'data'   => $news
            )
        );

    } else {
        $response->setStatusCode(409, "Conflict");
        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(
            array(
                'status'   => 'ERROR',
                'messages' => $errors
            )
        );
    }

    return $response;

});



//GET Location

$app->get('/api/local',function() use($app){
  $phql = "SELECT * FROM Location";
  $locals = $app->modelsManager->executeQuery($phql);
  $data = array();
  foreach($locals as $local){
    $data[] = array(
    'LocationId'=>$local->LocationId,
    'Longtitude'=>$local->Longtitude,
    'Latitude'=>$local->Latitude
    );
  }
  echo json_encode($data);
});

//Post location
$app->post('/api/local',function() use ($app){
  $local = $app->request->getJsonRawBody();
  $phql = "INSERT INTO Location (LocationId, Longtitude, Latitude) VALUES (:LocationId:, :Longtitude:, :Latitude:)";
  $status = $app->modelsManager->executeQuery($phql,array(
    'LocationId' => $local->LocationId,
    'Longtitude' => $local->Longtitude,
    'Latitude' => $local->Latitude
  ));

  $response = new Phalcon\Http\Response();
    if ($status->success() == true) {
        $response->setStatusCode(201, "Created");

        $news->LocationId = $status->getModel()->LocationId;

        $response->setJsonContent(
            array(
                'status' => 'OK',
                'data'   => $news
            )
        );

    } else {
        $response->setStatusCode(409, "Conflict");
        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(
            array(
                'status'   => 'ERROR',
                'messages' => $errors
            )
        );
    }

    return $response;

});



//GET friendship
$app->get('/api/relationship',function() use($app){
  $phql = "SELECT * FROM Relationship";
  $relations = $app->modelsManager->executeQuery($phql);
  $data = array();
  foreach($relations as $relation){
    $data[] = array(
      'UserId' => $relation->UserId,
      'Friend' => $relation->Friend
    );
  }
  echo json_encode($data);
});




$app->handle();


