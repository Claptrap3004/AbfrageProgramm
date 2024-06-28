<?php

namespace quiz;

class EditController
{
    public function index()
    {

    }

    public function import(): void
    {
        $importer = new CSVImporter();
        $importer->readCSV('../Files/quiz1.csv');
    }

    public function export()
    {

    }

    public function editQuestion()
    {

    }

    public function createQuestion()
    {

    }

}