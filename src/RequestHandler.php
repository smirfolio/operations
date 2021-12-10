<?php

namespace request;

class RequestHandler{

    private $uri;
    private $method;
    private $isAjax;
    private $bodyRequest;

    public function __construct() {
        //var_dump($server);
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        // A sucure way to only manage AJX call sent by a browser client via javascript that set the HTTP_X_REQUESTED_WITH for the fetch method 
        // Api call not permited
        $this->isAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] && $_SERVER['HTTP_X_REQUESTED_WITH'] === "XMLHttpRequest") ? TRUE : FALSE;
        // A secure way to only use a stric digit values in an array
        $this->digits = array_filter(array_map('intval', explode(' ', \json_decode(file_get_contents('php://input'))->digits)), function ($digit) {
            return !empty($digit);
        });
    }

    /**
     * The request handler that will take care on calling the good method depending on the request parpathameter /add, /concat ...
     */
    public function handler(){
            $callabale = \str_replace('/', '', $this->uri);
        // A Secure way to prevent malicious callable (could be done by haking the javascript)
        if(preg_match("/(add|concat|sortasc|sortdesc)/")){
            return FALSE;
        }
        // check if there is any  operator in the request path 
        if(empty($callabale) && !$this->isAjax){
            return FALSE;
        }
        else{
            // Check is the operator exist as a callable method
            if(method_exists($this, $callabale) && $this->method === "POST" && $this->isAjax) {
                return $this->$callabale($this->digits);
            }
            else{
                // if no operator and it's ajax call repsond to the callar with message error
                if($this->isAjax) {
                   return $this->jsonResponse(['message' => "The operation {$callabale} doesn't exist."], 404);
                }
                // if no operatior and it's not an ajax call render the operation Form 
                else {
                    return FALSE;
                }
                
            }
        }
        
    }

    /**
     * Return the Calculator Form
     */
    public function operationsForm(){
        if(\file_exists('./public/index.html')){
            echo \file_get_contents('./public/index.html');
        }
        else{
            echo "no file";
        }
    }

    /**
     * The Add operator
     */
    private function add($digits){
        return $this->jsonResponse(['result' => array_sum($digits)], 200);
    }

    /**
     * The Concat  operator
     */
    private function concat($digits){
        return $this->jsonResponse(['result' => implode('.', $digits)], 200);
    }

    /**
     * The Sortasc  operator
     */
    private function sortasc($digits){
        sort($digits);
        return $this->jsonResponse(['result' => $digits], 200);
    }

     /**
     * The Sortdesc  operator
     */
    private function sortdesc($digits){
        rsort($digits);
        return $this->jsonResponse(['result' => $digits], 200);
    }

    /**
     * Json reponse wrapper
     */
    private function jsonResponse($response, $statusCode){
        switch ($statusCode){
            case 200:
                header("HTTP/1.1 200 Request Ok");
            break;
            case 404:
                header("HTTP/1.1 404 Not Found");
            break;
            case 500:
                header("HTTP/1.1 500 Server error");
            break;

        }
        print_r(\json_encode($response));
        return TRUE;
    }
}
