<?php

  namespace Models;

  global $models;

  use Enum\session;
  use Enum\champ;
  use Enum\path;
  use Enum\model_auth;
  use Enum\EnumerationData;

  $models = [

    EnumerationData::JSON_TO_ARRAY->value => function (string $filepath): array {
      $json = file_get_contents($filepath);
      $tableau = json_decode($json, true);
      return $tableau ?? [];
    },


    EnumerationData::ARRAY_TO_JSON->value => function (array $tableau, string $filepath): void {
      $json = json_encode($tableau, JSON_PRETTY_PRINT);
      file_put_contents($filepath, $json);
    },
  ];

  function getAllModels(){
      global $models;
      return $models;
  }


