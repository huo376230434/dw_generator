<?php
// @formatter:off

namespace Encore\Admin\Grid;
use App\Admin\Extensions\Grid;

class Column{
    /**
     * @param Grid $grid
     * @return string
     */
    public function linkDetail(Grid $grid)
    {
        return '';
    }

    /**
     * @param string $method
     * @param int $success_id
     * @param null $danger_id
     * @return string
     */
    public function hmShow($method="status", $success_id=1, $danger_id=null)
    {
        return '';
    }

    /**
     * @param int $max_word
     * @return string
     */
    public function popover($max_word = 25)
    {
        return '';
    }
}
