<?php

namespace App\Admin\Extensions\DummyType\Form;

use App\Admin\Extensions\Form\Field\CommonTrait;
use Encore\Admin\Form\Field;

class DummyForm extends Field
{
    use CommonTrait;

    protected $view="admin.DummyBladeType.form.DummyBladeName";

    public function render()
    {
//        $this->script = <<<EOT
//
//CodeMirror.fromTextArea(document.getElementById("{$this->id}"), {
//    lineNumbers: true,
//    mode: "text/x-php",
//    extraKeys: {
//        "Tab": function(cm){
//            cm.replaceSelection("    " , "end");
//        }
//     }
//});
//
//EOT;
        return parent::render();

    }

}
