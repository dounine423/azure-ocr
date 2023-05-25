<?php

namespace App\Controllers;
require_once '../vendor/pear/http_request2/HTTP/Request2.php';
use HTTP_Request2;


class Home extends BaseController
{ 
    public function index()
    {
        return view('index');
    }

    public function runOCR()
    {
        $mode=$_POST['mode'];
        $data = array();
        $text="";
        $target_url="https://ocr-connexis.cognitiveservices.azure.com/vision/v3.2/read/analyze";
        $request = new Http_Request2($target_url);
        if($mode=='1'){
            $newName = explode('.',$_FILES['file']['name']);
            $ext = end($newName);
            $fileName = 'assets/upload/'.rand().time().'.'.$ext;
            move_uploaded_file($_FILES['file']['tmp_name'], $fileName);
            $result="";
            $headers = array(
                'Host' => 'ocr-connexis.cognitiveservices.azure.com',
                'Content-Type' => 'application/octet-stream',
                'Ocp-Apim-Subscription-Key' => '7ddecaa49a05400495c8e49eb25b1a4d',
            );
            $request->setHeader($headers);
            $request->setBody(fopen($fileName, 'r'));
        }else{            
            $headers = array(
                'Host' => 'ocr-connexis.cognitiveservices.azure.com',
                'Content-Type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => '7ddecaa49a05400495c8e49eb25b1a4d',
            );
            $url=$_POST['url'];
            $body = array (
                "url" => $url
            );
            $request->setHeader($headers);
            $request->setBody(json_encode($body));
        }
        $request->setMethod(HTTP_Request2::METHOD_POST);
        try {
            $response = $request->send();
            $header =  $response->getHeader();
            $operation_location = $header['operation-location'];
            $request = new HTTP_Request2();
            $request->setUrl($operation_location);
            $request->setMethod(HTTP_Request2::METHOD_GET);
            $request->setConfig(array(
                'follow_redirects' => TRUE
            ));
            $request->setHeader(array(
                'Ocp-Apim-Subscription-Key' => '7ddecaa49a05400495c8e49eb25b1a4d'
            ));
            try {
                sleep(3);
                $response = $request->send();
                $result=json_decode($response->getBody(),true);
                $pages=$result['analyzeResult']['readResults'];
                foreach($pages as $page){
                    foreach($page['lines'] as $line){
                        $text=$text.$line['text'].'<br>';
                    }
                    $text=$text.'<br><br><br>';
                }
                $data['result']=$text;
                $data['success']=true;
            }
            catch(HTTP_Request2_Exception $e) {
                $data['message'] =  'Error: ' . $e->getMessage();
                $data['success'] = false;

            } 
        }
        catch (HttpException $ex) {
            $data['message'] =  $ex;
            $data['success'] = false;
        }
        echo json_encode($data);
    }
}
