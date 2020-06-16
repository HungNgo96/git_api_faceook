<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Page extends Controller
{
    public function createPage (Request $req){
        $data = $req->data;
        // $arrayID=[];
        $dataPage = DB::table('page')->get()->toArray();
        //dd($dataPage);
        // foreach($dataPage as $item){
        //     //dd($item);
        //     array_push($arrayID,$item->PAGE_ID);
        // }
       // dd($arrayID);
       // dd($data);
        foreach($data as $item){
                DB::table('page')->updateOrInsert(
                ['PAGE_ID' => $item['id'] ],
                [ 'PAGE_NAME' => $item['name'],'PAGE_TOKEN' => $item["access_token"],'CREATED_OR_UPUDATED'=>Carbon::now()]
            );
         }

        return response()->json([
          
        ],200);
    }

    public function createPost(Request $req){
        $id =$req->page_post_id;
        $content = $req->content;
        $post_id = $req->post_id;
        DB::table('posts')->updateOrInsert(
            ['PAGE_POST_ID' => $id],
            ['CONTENT' => $content
            ,'POST_ID' => $post_id
            ,'CREATED_OR_UPUDATED'=>Carbon::now()]
        );
        return response()->json([],200);
    }

    public function getInfoPage(Request $req){
        $info_page = DB::table('page')->get()->toArray();

        return response()->json([$info_page],200);
    }
}
