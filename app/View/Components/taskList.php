<?php

namespace App\View\Components;

use Illuminate\View\Component;

class taskList extends Component
{
    public $id;
    public $title;
    public $description;
    public $memToShow;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id,$title,$description,$memToShow = "")
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->memToShow = $memToShow;
        // dd($mem);
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.task-list');
    }
}
