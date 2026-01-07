<?php

class APILogs
{

    //We save in a variable the requested method.
    private $method = '';

    //Variable where i will save the instance of the DAO.
    private $logsDAO = null;

    public function __construct()
    {
        require_once DAOS_PATH . "LogsDAO.php";
        require_once CORE_PATH . "JsonUtils.php";
        
        $this->logsDAO = new LogsDAO();
        $this->method = $_SERVER['REQUEST_METHOD'];
    }


    //We respond according to the method
    public function handleRequest()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        switch ($this->method) {
            case 'GET':
                $this->handleGetRequest();
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }


    private function handleGetRequest()
    {
        //We only implement findAll for logs
        $logs = $this->logsDAO->findAll();
        $logs = JsonUtils::serializeArray($logs);

        echo json_encode([
            'status' => 'Success',
            'data' => $logs
        ]);
        
    }
}
