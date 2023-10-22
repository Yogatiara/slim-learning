<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
  // get
  $app->get('/kucing', function(Request $request, Response $response){
    $db = $this->get(PDO::class);

    $query = $db->query('SELECT * FROM kucing ');
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode ($results));

    return $response->withHeader('Content-Type', 'application/json');

  });

  $app->get('/kucing/{id}', function(Request $request, Response $response, $args){
    $db = $this->get(PDO::class);

    $query = $db->prepare('SELECT * FROM kucing where id_kucing=? ');
    $query->execute([$args['id']]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode ($results[0]));

    return $response->withHeader('Content-Type', 'application/json');

  });

  $app->post('/kucing', function(Request $request, Response $response) {
    $parsedBody = $request->getParsedBody();

    $idKucing = $parsedBody['id_kucing'];
    $idRas = $parsedBody['id_ras'];
    $namaKucing = $parsedBody['nama_kucing'];
    $umur = $parsedBody['umur'];
    $jenisKelamin = $parsedBody['jenis_kelamin'];

    $db = $this->get(PDO::class);
    $query = $db->prepare('INSERT INTO kucing (id_kucing, id_ras, nama_kucing, umur, jenis_kelamin) VALUES (?, ?, ?, ?, ?)');
    $query->execute([$idKucing, $idRas, $namaKucing, $umur, $jenisKelamin]);

    $lastId = $db->lastInsertId();

    $response->getBody()->write(json_encode(
        [
            'message' => 'Data kucing disimpan dengan id ' . $lastId
        ]
    ));

    return $response->withHeader('Content-Type', 'application/json');
  });

  $app->put('/car/{id}', function(Request $request, Response $response, $args) {
    $parsedBody = $request->getParseBody();

    $currentId = $args['id'];
    $countryName = $parsedBody['name'];
    $db = $this->get(PDO::class);

    $response->getBody()->write(json_encode(
      [
          'message' => 'Data kucing dengan id' . $currentId .'telah diupdate dengan nama ' . $countryName
      ]
  ));

  return $response->withHeader('Content-Type', 'application/json');


  });

  $app->delete('/kucing/{id}', function(Request $request, Response $response, $args) {


    $currentId = $args['id'];
    $db = $this->get(PDO::class);

    $query = $db->prepare('DELETE FROM kucing where id_kucing=? ');
    $query->execute([$currentId]);

    $response->getBody()->write(json_encode(
      [
          'message' => 'Data kucing dengan id' . $currentId .'telah dihspus'
      ]
  ));

  return $response->withHeader('Content-Type', 'application/json');



  });



    
};
