<?php
class Comment
{

    private $id;
    private $id_usera;
    private $id_postu;
    private $text;
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->id_usera = "";
        $this->id_postu = "";
        $this->text = "";
        $this->creationDate = "";
    }

    function getId()
    {
        return $this->id;
    }

    function getId_usera()
    {
        return $this->id_usera;
    }

    function getId_post()
    {
        return $this->id_postu;
    }

    function getText()
    {
        return $this->text;
    }


    function getCreationDate()
    {
        return $this->creationDate;
    }

    function setId_usera($id_usera)
    {
        $this->id_usera = $id_usera;
    }

    function setId_postu($id_postu)
    {
        $this->id_postu = $id_postu;
    }

    function setText($text)
    {
        if (is_string($text) && strlen(trim($text)) > 0) {
            $this->text = $text;
        }
    }

    function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function saveToDB(mysqli $connection)
    {

        if (($this->id == -1)) {
            $query = "INSERT INTO Comment(Id_usera,Id_postu,text,creationDate)" .
                "VALUES('$this->id_usera', '$this->id_postu', '$this->text', '$this->creationDate')";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
                echo "komentarz dodany";
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $query = "UPDATE Comment SET Id_usera= '$this->id_usera',Id_postu='$this->id_postu'," .
            "text = '$this->text', creationDate = '$this->creationDate' WHERE id = '$this->id'";
            if ($res = $connection->query($query)) {
                return TRUE;
            } else {
                return false;
            }
        }
    }

    static public function loadCommentsByUserID(mysqli $connection, $userId)
    {

        $query = "SELECT * FROM Comment WHERE Id_usera = " . $connection->real_escape_string($userId) .
            " ORDER BY creationDate desc";
        $comments = [];
        $res = $connection->query($query);
        if ($res) {
            foreach ($res as $row) {
                $comment = new Comment;
                $comment->id = $row['id'];
                $comment->id_postu = $row['id_postu'];
                $comment->id_usera = $row['id_usera'];
                $comment->text = $row['text'];
                $comment->creationDate = $row['creationDate'];
                $comments[] = $comment;
            }
            return $comments;
        } else {
            return NULL;
        }
    }

    static public function loadCommentsByTweetID(mysqli $connection, $tweetID)
    {

        $query = "SELECT * FROM Comment WHERE Id_postu = " . $connection->real_escape_string($tweetID);

        $comments = [];
        $res = $connection->query($query);
        if ($res) {
            foreach ($res as $row) {

                $comment = new Comment;
                $comment->id = $row['id'];
                $comment->id_postu = $row['Id_postu'];
                $comment->id_usera = $row['Id_usera'];
                $comment->text = $row['text'];
                $comment->creationDate = $row['creationDate'];
                $comments[] = $comment;
            }
            return $comments;
        } else {
            return NULL;
        }
    }
}

?>