<?php

define('BASEDIR', dirname(dirname(__FILE__)));

function getRequest() {
  $request = new stdClass();
  $urlParts = parse_url($_SERVER["REQUEST_URI"]);
  $request->path = trim($urlParts['path'], '/');
  $request->query = array();
  parse_str($urlParts['query'], $request->query);
  return $request;
}

function route($request) {
  include_once(BASEDIR . '/app/actions.php');
  switch ($request->path) {
    case 'formList':
      formList();
      break;
    case 'formXml':
      if (isset($request->query['formId'])) {
        formXml($request->query['formId']);
      }
      break;
    default:
      error404();
      break;
  }
}

route(getRequest());
