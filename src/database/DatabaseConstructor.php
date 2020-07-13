<?php
class DatabaseConstructor extends SQLite3
{
    function __construct($db)
    {
        $this->open($db);
    }

    function closeConnection()
    {
        $this->close();
    }
}
