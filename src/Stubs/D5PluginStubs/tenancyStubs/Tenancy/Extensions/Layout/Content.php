<?php

namespace App\Tenancy\Extensions\Layout;

use Closure;
use Illuminate\Contracts\Support\Renderable;
use Encore\Admin\Layout\Content as ParentContent;

class Content extends ParentContent
{


    /**
     * Render this content.
     *
     * @return string
     */
    public function render()
    {
        $items = [
            'header'      => $this->header,
            'description' => $this->description,
            'breadcrumb'  => $this->breadcrumb,
            'content'     => $this->build(),
        ];

        return view('tenancy.content', $items)->render();
    }
}
