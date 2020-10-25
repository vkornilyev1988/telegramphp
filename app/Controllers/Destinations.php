<?php
namespace App\Controllers;


use App\Modules\Destination;
use App\Modules\DestinationGroup;
use Nekrida\Core\View;

class Destinations extends Controller
{
    public function add() {
        Destination::insertSet([
            'name' => $this->request->post('name'),
            'group_id' => (int)$this->request->post('group') ?: null
        ])->query();

        $this->request->redirectByUrl('/destinations');
    }

    public function delete() {
        $id = $this->request->post('id');
        Destination::deleteById($id);

        return $this->showAll();
    }

    public function edit($id) {
        Destination::updateSet([
            'name' => $this->request->post('name'),
            'group_id' => (int)$this->request->post('group') ?: null
        ])->where('id','=',(int)$id)
            ->query();
        $this->request->redirectByUrl('/destinations');
    }

    public function showAdd() {

    }

    public function showAll() {
        $destinationsSQL = DestinationGroup::select(['id' => 'COALESCE(dg.id,0)','dg.name','dg.parent','dest_id' => 'd.id','dest_name' => 'd.name'])
            ->fullJoin(Destination::class)->onA('d.group_id','=','dg.id')
            ->orderBy(['name'])
            ->query();
        $destinations = [];
        while ($row = $destinationsSQL->fetch(2)) {
            if (!isset($destinations[$row['id']]))
                $destinations[$row['id']] = ['id' => $row['id'],'name'=>$row['name'],'parent' => $row['parent']];
            if ($row['dest_id'])
                $destinations[$row['id']]['items'][] = ['id' => $row['dest_id'], 'name' => $row['dest_name']];
        }
        $destinationsRec = DestinationGroup::buildTree($destinations);

        $destinationGroups = DestinationGroup::selectAll();

        $view = $this->request->cache('user/role') == 2 ? 'telegraphist/destinations' : 'admin/destinations/destinations';

        return View::render($view,[
            'destinations' => $destinationsRec,
            'groups' => $destinationGroups
        ]);
    }

    public function showEdit() {

    }
}
