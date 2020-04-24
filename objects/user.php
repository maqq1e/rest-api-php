<?php

class User
{

    private $_conn;
    private $_table_name         = "users";
    private $_dependence_table   = ["tel", "email"];

    public $id;
    public $name;
    public $surname;
    public $middle;
    public $tel_list;
    public $email_list;

    private $_count;

    public function __construct($db)
    {
        $this->_conn = $db;
    }

    public function read()
    {

        $query = "
                SELECT
                    id,
                    name,
                    surname,
                    middle
                FROM
                    {$this->_table_name}";

        $stmt = $this->_conn->prepare($query);

        $stmt->execute();
        // Set count of result
        $this->_count = $stmt->rowCount();

        return $stmt;
    }

    public function create()
    {
        $query = "
                INSERT INTO {$this->_table_name}
                SET
                    name    = :name,
                    surname = :surname,
                    middle  = :middle,
                    created = :created";

        $stmt = $this->_conn->prepare($query);

        // Delete all "bad" chars
        $this->name     = htmlspecialchars(strip_tags($this->name));
        $this->surname  = htmlspecialchars(strip_tags($this->surname));
        $this->middle   = htmlspecialchars(strip_tags($this->middle));
        $this->created  = htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":middle", $this->middle);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute())
        {
            return true;
        }

        return false;
    }

    public function readOne()
    {
        $user   = $this->_getUserQuery($this->id);
        $nums   = $this->getTelQuery($this->id);
        $emails = $this->getEmailList($this->id);

        $this->name         = $user['name'];
        $this->surname      = $user['surname'];
        $this->middle       = $user['middle'];
        $this->tel_list     = $nums;
        $this->email_list   = $emails;
    }

    public function update()
    {

        // Delete all "bad" chars
        $this->name         = htmlspecialchars(strip_tags($this->name));
        $this->surname      = htmlspecialchars(strip_tags($this->surname));
        $this->middle       = htmlspecialchars(strip_tags($this->middle));
        $this->id           = htmlspecialchars(strip_tags($this->id));

        // If need update not all fields
        $update_variables = array();
                
        if(!empty($this->name))
        {
            $update_variables[] = "name = '{$this->name}'";
        }
        elseif(!empty($this->surname))
        {
            $update_variables[] = "surname = '{$this->surname}'";
        }
        else
        {
            $update_variables[] ="middle = '{$this->middle}'";
        }

        $update_variables = implode(', ', $update_variables);

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
        switch ($type) {
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

    private function _getUserQuery($user_id = false)
    {
        if($user_id)
        {
            $ADD = "WHERE id = {$user_id}";
        }
        else
        {
            $ADD = "";
        }

        $query = "
                SELECT
                    id,
                    name,
                    surname,
                    middle
                FROM
                    {$this->_table_name}
                {$ADD}";

        $stmt = $this->_conn->prepare($query);

        $stmt->execute();

        // Set count of result
        $this->_count = $stmt->rowCount();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
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
                    {$this->_dependence_table[0]}
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

    public function getEmailList($user_id = false) // not private for using outside ( read.php )
    {
        if($user_id)
        {
            $ADD = "WHERE user_id = {$user_id}";
        }
        else
        {
            // Without $user_id need for developing
            $ADD = "";
        }

        $query = "
                SELECT
                    id,
                    email,
                    is_main
                FROM
                    {$this->_dependence_table[1]}
                {$ADD}";

        $stmt = $this->_conn->prepare($query);

        $stmt->execute();

        $nums = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $nums[] = [
                "id"        => $row['id'],
                "email"     => $row['email'],
                "is_main"   => $row['is_main'],
            ];
        }

        return $nums;
    }

    public function getCount() // not private for using outside ( read.php )
    {
        return $this->_count;
    }
}
?>