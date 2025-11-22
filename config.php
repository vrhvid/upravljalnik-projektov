<?php
    unset($CFG);
    global $CFG;
    $CFG = new stdClass();

    $CFG->mdbserver = getenv("MDB_SERVER");
    $CFG->mdbdatabase = getenv("MDB_DB");
    $CFG->mdbuser = getenv("MDB_USER");
    $CFG->mdbpassword = getenv("MDB_PASSWORD");
?>