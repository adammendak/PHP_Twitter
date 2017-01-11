<?php

class Message
{

    private $id;
    private $senderId;
    private $receiverId;
    private $massage;
    private $messageRead;
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->senderId = '';
        $this->receiverId = '';
        $this->massage = '';
        $this->messageRead = 0;
        $this->creationDate = '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }

    function getSenderId()
    {
        return $this->senderId;
    }

    function getReceiverId()
    {
        return $this->receiverId;
    }

    function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;
    }

    function getMassage()
    {
        return $this->massage;
    }

    function setMassage($massage)
    {
        $this->massage = $massage;
    }

    function getStatus()
    {
        return $this->status;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    public function setCreationDate($date)
    {
        $this->creationDate = $date;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function saveToDB(mysqli $connection)
    {
        if ($this->id == -1) {
            $query = "INSERT INTO Message (senderId, receiverId, message, messageRead, creationDate)
                    VALUES( '$this->senderId', '$this->receiverId', '$this->massage', '$this->messageRead', 
                    '$this->creationDate')";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
                return true;
            } else {
                return false;
            }
        }
    }

    static public function loadAllMassagesSentByUser(mysqli $connection, $userId)
    {
        $query = "SELECT * FROM Message WHERE senderId= '" . $connection->real_escape_string($userId) . "'
                ORDER BY creationDate DESC";
        $messages = [];
        $res = $connection->query($query);
        if ($res && $res->num_rows > 0) {
            foreach ($res as $row) {
                $message = new Message();
                $message->id = $row['id'];
                $message->senderId = $row['senderId'];
                $message->receiverId = $row['receiverId'];
                $message->message = $row['message'];
                $message->messageRead = $row['messageRead'];
                $message->creationDate = $row['creationDate'];
                $messages[] = $message;
            }
            return $messages;
        } else {
            return NULL;
        }
    }

    static public function loadAllMassagesReceivedByUser(mysqli $connection, $userId)
    {
        $query = "SELECT * FROM Message WHERE receiverId= '" . $connection->real_escape_string($userId) . "'
                ORDER BY creationDate DESC";
        $messages = [];
        $res = $connection->query($query);
        if ($res && $res->num_rows > 0) {
            foreach ($res as $row) {
                $message = new Message();
                $message->id = $row['id'];
                $message->senderId = $row['senderId'];
                $message->receiverId = $row['receiverId'];
                $message->message = $row['message'];
                $message->messageRead = $row['messageRead'];
                $message->creationDate = $row['creationDate'];
                $messages[] = $message;
            }
            return $messages;
        } else {
            return NULL;
        }
    }

    static public function loadAMassageByMassageId(mysqli $connection, $massageId) {
        $query = "SELECT * FROM Message WHERE id = '$massageId'";
        $result = $connection->query($query);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $message = new Message();
            $message->id = $row['id'];
            $message->senderId = $row['senderId'];
            $message->receiverId = $row['receiverId'];
            $message->message = $row['message'];
            $message->messageRead = $row['messageRead'];
            $message->creationDate = $row['creationDate'];
            return $massage;
        }
        return null;
    }

}

?>