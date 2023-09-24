<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class MusicController extends BaseController
{

    public function delete($data)
    {
       $main = new MainModel();
       $main->delete($data);
       return redirect()->to('/test');
    }

    public function updates($id)
    {
        $data = [
            'title' => $this ->request->getPost('title'),
            'artist' => $this ->request->getPost('artist'),
            'file_path' => $this ->request->getPost('file_path'),
        ];
        $main = new MainModel();
        $main->set($data)->where('id', $id)-update();
        return redirect()-> to('/test');
    }
    public function update($data)
    {
       $main = new MainModel();
       $data = [
        'd' = $main->where('id', $id)->first(),
       'main' => $main -> findAll(),
        'tt'  => 'update'

       ];
       return view('main', $data);
    }
    public function save()
    {
       $main = new MainModel();
       $ID = $this->request->getPost('id');
       $id = $this->request->getPost('id');

       $data = [
        'title' => $this ->request->getPost('title'),
        'artist' => $this ->request->getPost('artist'),
        'file_path' => $this ->request->getPost('file_path'),
       ];

       if(isset($ID))
       {
         $main->set($data)->where('id',$ID)-update();
       }
       else
       {
         $main->save($data);
       }
       return redirect()->to('/test')
    }
    public function test()
    {
        $main = new MainModel();
        $data['main'] = $main ->findAll();
        return view ('main',$data);
    }
    public function index()
    {
        //
    }
}
