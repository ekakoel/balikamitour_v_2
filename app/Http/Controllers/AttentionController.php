<?php

namespace App\Http\Controllers;

use App\Models\Attention;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAttentionRequest;
use App\Http\Requests\UpdateAttentionRequest;

class AttentionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function attentions(Request $request){
        $attentions = Attention::all();
        return view('admin.attentions.attentions',[
            'attentions'=>$attentions,
        ]);
    }

    // FUNCTION ADD ORDER WEDDING INVITATION --------------------------------------------------------------------------------------------------------------------------------------------->
    public function func_update_attention(Request $request,$id){
        $attention = Attention::find($id);
        $name = $request->name;
        $page = $request->page;
        $attention_zh = $request->attention_zh;
        $attention_en = $request->attention_en;
        $attention->update ([
            "name" =>$name,
            "page" =>$page,
            "attention_zh" =>$attention_zh,
            "attention_en" =>$attention_en,
        ]);
        dd($attention);
        return redirect("/attentions")->with('success','Attention has been updated!');
    }
    // FUNCTION ADD ORDER WEDDING INVITATION --------------------------------------------------------------------------------------------------------------------------------------------->
    public function func_delete_attention(Request $request,$id){
        $attention = Attention::find($id);
        $attention->delete();
        return redirect("/attentions")->with('success','Attention has been deleted!');
    }
    
}
