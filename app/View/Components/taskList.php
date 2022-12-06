<?php

namespace App\View\Components;

use Illuminate\View\Component;

class taskList extends Component
{
    public $id;
    public $title;
    public $description;
    public $mem;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id,$title,$description,$mem = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->mem = $mem;
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
