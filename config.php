<?php
    unset($CFG);
    global $CFG;
    $CFG = new stdClass();

    $CFG->mdbserver = "MongoDB server name";
    $CFG->mdbdatabase = "MongoDB database name";
    $CFG->mdbuser = "MongoDB username";
    $CFG->mdbpassword = "MongoDb user password";
?>