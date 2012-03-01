<?php

function absoluteUrl($localPart) {
  return 'http://' . $_SERVER["HTTP_HOST"] . '/' . $localPart;
} 

function error404() {
  header('HTTP/1.0 404 Not Found', true, 404);
  header('Status: 404 Not Found', true, 404);
  echo '404 Not Found';
}

function getFormsDir() {
  return BASEDIR . '/resources/forms';
}

function getFormFilename($formId) {
  return getFormsDir() . '/' . $formId . '.xml';
}

function getFormTitle($formId) {
  $filename = getFormFilename($formId);
  $xml = new SimpleXMLElement(file_get_contents($filename));
  $xml->registerXPathNamespace('f', 'http://www.w3.org/2002/xforms');
  $xml->registerXPathNamespace('h', 'http://www.w3.org/1999/xhtml');
  $xml->registerXPathNamespace('ev', 'http://www.w3.org/2001/xml-events');
  $xml->registerXPathNamespace('xsd', 'http://www.w3.org/2001/XMLSchema');
  $xml->registerXPathNamespace('jr', 'http://openrosa.org/javarosa');
  list($title) = $xml->xpath('/h:html/h:head/h:title');
  return (string) $title;
}

function formList() {
  header('Content-Type: text/xml; charset=utf-8');
  $formsDir = getFormsDir();
  $formFiles = array_diff(scandir($formsDir), array('..', '.', '.gitignore'));
  echo '<' . '?xml version="1.0" encoding="UTF-8"?' . '>';
  echo '<forms>';
  foreach ($formFiles as $fileName) {
    $formId = basename($fileName, '.xml');
    $url = absoluteUrl('formXml?formId=' . $formId);
    $title = getFormTitle($formId);
    echo '<form url="' . $url . '">' . $title . '</form>';
  }
  echo '</forms>';
}

function formXml($formId) {
  $filename = getFormFilename($formId);
  if (file_exists($filename)) {
    $title = getFormTitle($formId);
    header('Content-Type: text/xml; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $title . '.xml";');
    readfile($filename);
  }
  else {
    error404();
  }
}
