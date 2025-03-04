<?php
// filepath: /c:/wamp64/www/gaslight_new/app/View/Components/Avatar.php
namespace App\View\Components;

use Illuminate\View\Component;

class Avatar extends Component
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function render()
    {
        return view('components.avatar');
    }

    public function initials()
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper($word[0]);
        }

        return $initials;
    }
}