<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Item::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newItem = new Item;
        $newItem->name = $request->item["name"];
        $newItem->save();
        
        return $newItem;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existingItem = Item::find( $id );

        if ( $existingItem ) {
            $existingItem->completed = $request->item['completed'] ? true : false;
            $existingItem->completed_at = $request->item['completed_at'] ? Carbon::now() : null;
            $existingItem->save();

            return $existingItem;
        }

        return "Item not found";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingItem = Item::find( $id );

        if ( $existingItem ) {
            $existingItem->delete();

            return "Item succesfully deleted";
        }

        return "Item not found";
    }
}
