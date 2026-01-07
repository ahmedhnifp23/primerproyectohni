<?php

class Logs
{

    private int $log_id;
    private ?int $user_id;
    private string $action;
    private string $table_name;
    private string $done_at;

    public function __construct(int $log_id, ?int $user_id, string $action, string $table_name, string $done_at)
    {
        $this->log_id = $log_id;
        $this->user_id = $user_id;
        $this->action = $action;
        $this->table_name = $table_name;
        $this->done_at = $done_at;
    }

    
    /**
     * Get the value of log_id
     */ 
    public function getLogId()
    {
        return $this->log_id;
    }

    /**
     * Set the value of log_id
     *
     * @return  self
     */ 
    public function setLogId($log_id)
    {
        $this->log_id = $log_id;

        return $this;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of action
     */ 
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the value of action
     *
     * @return  self
     */ 
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the value of table_name
     */ 
    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * Set the value of table_name
     *
     * @return  self
     */ 
    public function setTableName($table_name)
    {
        $this->table_name = $table_name;

        return $this;
    }

    /**
     * Get the value of done_at
     */ 
    public function getDoneAt()
    {
        return $this->done_at;
    }

    /**
     * Set the value of done_at
     *
     * @return  self
     */ 
    public function setDoneAt($done_at)
    {
        $this->done_at = $done_at;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'log_id' => $this->log_id,
            'user_id' => $this->user_id,
            'action' => $this->action,
            'table_name' => $this->table_name,
            'done_at' => $this->done_at
        ];
    }
}
