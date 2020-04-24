<?php

include_once '../shared/utilities.php';

class UserTel
{

    private $_conn;
    private $_table_name = "tel";

    public $id;
    public $user_id;
    public $number;
    public $type;
    public $is_main;

    private $_count;

    public function __construct($db)
    {
        $this->_conn = $db;
    }

    public function read()
    {

        return $this->getTelQuery($this->id);
    }

    public function create()
    {

        if($this->is_main == 1)
        {
            $this->querySetDefault($field = 'is_main', $var = 0);
        }
        
        $query = "
                INSERT INTO {$this->_table_name}
                SET
                    user_id = :user_id,
                    number  = :number,
                    type    = :type,
                    is_main = :is_main,
                    created = :created";

        $stmt = $this->_conn->prepare($query);

        // Delete all "bad" chars
        $this->user_id  = htmlspecialchars(strip_tags($this->user_id));
        $this->number   = htmlspecialchars(strip_tags($this->number));
        $this->type     = htmlspecialchars(strip_tags($this->type));
        $this->is_main  = htmlspecialchars(strip_tags($this->is_main));
        $this->created  = htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":number", $this->number);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":is_main", $this->is_main);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute())
        {
            return true;
        }

        return false;
    }

    public function readOne()
    {
        $this->setOneTelQuery($this->id);
    }

    public function update()
    {

        // Delete all "bad" chars
        $this->number   = htmlspecialchars(strip_tags($this->number));
        $this->type     = htmlspecialchars(strip_tags($this->type));
        $this->is_main  = htmlspecialchars(strip_tags($this->is_main));
        $this->id       = htmlspecialchars(strip_tags($this->id));

        $this->setUserIdByElementId(); // Set user_id

        // If need update not all fields
        $update_variables = array();
        
        if(!empty($this->number))
        {
            $update_variables[] = "number = '{$this->number}'";
        }
        elseif(!empty($this->type))
        {
            $update_variables[] = "type = '{$this->type}'";
        }
        else
        {
            $update_variables[] ="is_main = '{$this->is_main}'";
        }

        $update_variables = implode(', ', $update_variables);

        // If exist - set all is_main status for user_id is false (0)
        if($this->is_main)
        {
            if(!$this->querySetDefault($field = 'is_main', $var = 0))
            {
                Util::sendMessage("Ошибка на строне сервера.");
            }
        }

        $query = "UPDATE {$this->_table_name}
                SET
                    {$update_variables}
                WHERE
                    id = :id";

        $stmt = $this->_conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute())
        {
            return true;
        }

        return false;
    }

    public function delete()
    {

        $query = "DELETE FROM {$this->_table_name} WHERE id = :id";

        $stmt = $this->_conn->prepare($query);

        // Delete "bad" chars
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute())
        {
            return true;
        }

        return false;
    }

    public function generateType($type) // Need realy only for multilanguage
    {
        switch ($type)
        {
            case '0':
                return 'Мобильный';
            case '1':
                return 'Рабочий';
            case '2':
                return 'Домашний';
            default:
                return 'Неопределено';
                break;
        }
    }

    public function getTelQuery($user_id = false) // not private for using outside ( read.php )
    {
        if($user_id)
        {
            $ADD = "WHERE user_id = {$user_id}";
        }
        else
        {
            $ADD = "";
        }

        $query = "
                SELECT
                    id,
                    number,
                    type,
                    is_main
                FROM
                    {$this->_table_name}
                {$ADD}";

        $stmt = $this->_conn->prepare($query);

        $stmt->execute();

        $nums = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $nums[] = [
                "id"        => $row['id'],
                "number"    => $row['number'],
                "type"      => $this->generateType($row['type']),
                "is_main"   => $row['is_main'],
            ];
        }

        return $nums;
    }

    public function setOneTelQuery($id) // not private for using outside ( read.php )
    {
        $query = "
                SELECT
                    id,
                    number,
                    type,
                    is_main
                FROM
                    {$this->_table_name}
                WHERE
                    id = {$id}";

        $stmt = $this->_conn->prepare($query);

        $stmt->execute();

        $num = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->number   = $num['number'];
        $this->type     = $this->generateType($num['type']);
        $this->is_main  = $num['is_main'];
    }
    
    public function setUserIdByElementId()
    {
        $query = "SELECT 
                    user_id
                FROM {$this->_table_name}
                WHERE
                    id = :id";

        $stmt = $this->_conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        $this->user_id = $stmt->fetch(PDO::FETCH_ASSOC)['user_id'];
    }

    public function querySetDefault($field, $var)
    {
        $query = "UPDATE {$this->_table_name}
                SET
                    {$field} = {$var}
                WHERE
                    user_id = :id";
        
        $stmt = $this->_conn->prepare($query);

        $stmt->bindParam(':id', $this->user_id);

        if ($stmt->execute())
        {
            return true;
        }

        return false;
    }

}
?>