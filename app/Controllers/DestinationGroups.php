<?php
namespace App\Controllers;


use App\Modules\DestinationGroup;

class DestinationGroups extends Controller
{
    public function add($parent = NULL) {
        $parent = $parent ?: $this->request->post('parent');
        DestinationGroup::insertSet([
            'name' => $this->request->post('name'),
            'parent' => $parent
        ])->query();
        $this->request->redirectByUrl('/destinations');
    }

    public function delete() {
        $id = $this->request->post('id');
        DestinationGroup::deleteById($id);

        $this->request->redirectByUrl('/destinations');
    }

    public function edit($id) {
        DestinationGroup::updateSet([
            'name' => $this->request->post('name'),
            'parent' => $this->request->post('parent'),
        ])->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/destinations');
    }

    public function showAdd() {

    }

    public function showEdit() {

    }
}
