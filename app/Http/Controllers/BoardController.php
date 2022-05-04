<?php

namespace App\Http\Controllers;

use App\Models\board;
use App\Models\boardUser;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function boardBoardUser($board_id)
    {
        $boards         = board::orderby('position')->paginate(5);
        $boardMaxs      = board::orderby('position')->get();
        if($boardMaxs->count()>0){
            $boardMaxID     = $boardMaxs->last()->id;
        }
        else{
            $boardMaxID=0;
        }

        $boardName      = board::find($board_id);
        $boardUsers     = boardUser::where('board_id' , $board_id)->orderby('position')->paginate(5);
        $boardUserMaxs  = boardUser::orderby('position')->get();
        if($boardUserMaxs->count()>0){
            $boardUserMaxID = $boardUserMaxs->last()->id;
        }
        else{
            $boardUserMaxID=0;
        }

        return view('admin.boardUser.index')->with(
            [
                'boards'         => $boards,
                'boardMaxID'     => $boardMaxID,
                'boardUsers'     => $boardUsers,
                'boardUserMaxID' => $boardUserMaxID,
                'boardIdSelecte' => $board_id,
                'boardUserName'  => $boardName->postenMaenlich." / ".$boardName->postenWeiblich,
            ]);
    }

    public function index()
    {
      //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(board $board)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit($board_id)
    {
     //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $board_id)
    {
     //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy($board_id)
    {
     //
    }
}
