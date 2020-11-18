<?php
//Load the model and the view
class Controller
{
    public function model($model)
    {
        //Require model file
        require_once './models/' . $model . '.php';
        //Instantiate model
        return new $model();
    }

    //Load the view (checks for the file)
    public function view($view, array $data = [])
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        if (file_exists('./views/' . $view . '.php')) {
            require_once './views/' . $view . '.php';
        } else {
            die("View does not exists.");
        }
    }
}
